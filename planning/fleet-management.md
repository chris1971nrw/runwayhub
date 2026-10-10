# runwayhub - Flottenmanagement & Auto-Routing mit LBA-Kompliance

## 🎯 Anforderung

**Automatische Zuordnung:**
- Flugzeuge erhalten automatisch passende Routes basierend auf Typ/Eignung
- Maintenance: Flugzeug während Wartung nicht flugbereit  
- Route-Aircraft Matching: Nur sinnvolle Typen für jede Route (z.B. A320neo für Langstrecke, C172 für Kurz)

---

## 📋 Aircraft Performance & Route-Matching Logic

### 1. Datenbank-Design: aircrafts Tabelle erweitern

```sql
-- Migration Blueprint: aircrafts table mit route-capabilities

create_table('aircrafts') {
    id                     : integer      // Primary Key  
    registration           : string       // z.B., D-AIMR (UNIQUE)
    
    // Aircraft Performance Data
    model_type             : ENUM("C172", 'A320neo', "B738", ...)  // Flugzeugtyp
    max_range_nm          : integer       // Nautical Miles Max Range  
        -- C172: ~1,100 NM (regional)  
        -- A320neo: ~6,000+ NM (long-haul) 
        -- B738: ~4,000 NM (medium/short)      
    max_distance_nm       : integer       // Operating Limit
    fuel_capacity_liters  : decimal(10,2)
    min_seats             : integer  
    typical_seats         : integer
    
    // Certification & Qualifications  
    certified_routes_json : json          // ["EDDM-EGLL", "LFPG-FNAL", ...] 
                                       // Or: { "EDDM-EGLL": true, "EDDM-KJFK": true }
                                         // Which routes can operate?
                                     
        -- ICAO/FAA Type Certificate (TC)  
        enroute_capability: ENUM("regional",   "< 800 NM" → C172 ok 
                                "short_haul",   "~800-2500 NM" → B738, regional jet 
                                "long_haul")    "> 2500 NM" → A320neo widebody)  
    
    // Maintenance Status (LBA-Kompliance)  
    maintenance_status     : ENUM("airworthy",  // Fit to fly per LBA §14-... 
                             "scheduled_maint",   // Next maintenance due (LBA§28-...)  
                             "inspection_required", // LBA Inspektionsfälligkeit (§35-...) 
                             "grounded")          // GCS: Grundsatz zur sicheren Luft?
                             
    next_inspection_date  : date|null,     // Nächstes LBA Inspektionsdatum  
    last_maintenance_date : date|null,     // Letzte Wartung (LBA Compliance)  
    airworthiness_cert    : boolean        // True = Airworthiness Certificate gültig  
                                        // per LBA§15 LuftV  

    pilot_certified       : json           // ["GOL123", "BAW456"]
                                              // Welche Pilots sind zertifiziert für
                                              // diesen Flugzeugtyp?
    
    active                : boolean DEFAULT true  // Airworthy = kann eingesetzt werden
    
    timestamps            : DATETIME        created/updated
}

-- Index for query optimization  
create_index 'aircrafts_active', columns: ['maintenance_status'='airworthy, 'active'=true];  

```

---

### 2. Flight Schedules Tabelle anpassen (Auto-Aircraft Assignment)

```sql
schema::table('flight_schedules') {
    
    // + Optional Aircraft Assignment Fields  
    assigned_aircraft_id   : integer|null,     // FK → aircrafts.id (nullable for unassigned?) 
                                          -- oder: auto-assign bei create?
    recommended_aircraft_type : ENUM("A320neo", "B738", ...)  // Default für Route?
                                              -- z.B., Langstrecke = A320neo

    // Optional: Multi-Aircraft Support (Load Balancing)  
    preferred_aircraft_types : json           // ["A320neo", "B738"]  

}

-- Foreign Key Constraints (if needed):
ALTER TABLE flight_schedules  
ADD CONSTRAINT `fk_flight_schedule_aircraft`  
FOREIGN KEY(assigned_aircraft_id) REFERENCES aircrafts(id) ON DELETE SET NULL;

```

---

### 3. Route-Aircraft Matching Algorithmus (Auto-Routing Logic)

#### Business Rules: Auto-Zuordnung nach Flugzeugtyp-Eignung

```yaml
Matching Rules:
┌─────────────┬─────────────────────────────────────────────────┐
│ Route Class │ Recommended Aircraft Types                      │
├──────────────┼─────────────────────────────────────────────────┤  
│ <800 NM     │ C172, Diamond DA40, Light Jet                   │
│ 801-2500    │ A320neo (Jet), B738, Regional Jet               │
│ >2500       │ A320neo (Long-Haul Widebody), Boeing 777        │┐
```

**Matching Flow:**
```python
function assignBestAircraft(flight_number, flight_from, flight_to): 
    
    // 1. Calculate route distance  
    distance_nm = calculateRouteDistance(from_icao, to_icao);  
    
    // 2. Determine Route Class (short/medium/long)  
    if (distance_nm < 800) route_class = 'short_haul';  
    elif (distance_nm <= 2500) route_class = 'medium_haul';  
    else: path_class = 'long_haul';
    
    // 3. Query available aircrafts with matching capability  
    candidates = Aircraft::query()  
        ->where('maintenance_status', 'airworthy')  
                .whereHas('pilot_certifications'...)  
    
    // 4. Filter by certified routes (if configured)  
    // If aircraft has certified_routes_json field?
    
    // 5. Return best fit by fuel efficiency / availability  
    best_flight = candidates.find(min(distance_to_nearest_available_aircraft));
    
    return flight;
}

```

---

#### Laravel Implementation Blueprint - Auto Aircraft Assignment

**app/Models/Aircraft.php:**

```php
class Aircraft extends Model {  
    // Available methods:
    
    public function canFlyRoute(string $from_icao, string $to_icao): bool {
        // Check if aircraft is maintenance_free AND certified for this route?
        
        $aircraft = $this->getAircraft();  
        
        return (
            $this->maintenance_status === 'airworthy'  &&  
            ($this->model_type === 'A320neo' || $distance_nm <= $this->max_range) // Capable of distance?
        );  
    }
    
    public function getAvailableForRoutes(): Collection {
       return Aircraft::where('maintenance_status', 'airworthy')  
           ->with(['certified_routes:from_icao, to_icao']); // Filter by routes allowed;
    }

}

```

**app/Services/AircraftRouteMatcher Service.php:**

```php
class AircraftRouteMatcher {  
    public static function assignBestAircraft(string $from, string $to): aircraft|null {
        // 1. Calculate route distance  
            $distance_nm = RouteDistanceService::calculateDistance($from, $to);
    
        // 2. Query available aircrafts with matching capability  
        $candidates = Aircraft::where('maintenance_status', 'airworthy')  
            ->whereNot ('status', 'scheduled_maint');  
    
        // 3. Filter by certified routes or range?  
        if (method_exists($this, 'isCertifiedForRoute')) {
            return $candidates->filter(fn(Aircraft) => $this->isCertifiedForRoute($from, $to, $aircraft));
        }
    
        // 4. Prioritize by fuel efficiency & availability  
        return $candidates->orderBy('fuel_consumption'...) ->first() || NULL;
    }  
}

```

---

### 4. Maintenance-Warnung (LBA-Kompliant)

**Business Rules: LBA LuftV Compliance:**

```yaml
maintenance_checklist_per_aircraft: 
  - [ ] Airworthiness Certificate valid? (§15 LuftV)  
  - [ ] Annual/Period Inspection last performed (max 24-month interval per IFR §14-...)  
  - [ ] Next scheduled maintenance date calculated  
      -- Short-haul aircraft: Every 200 flight hours (FAA/ICAO?)  
      -- Medium haul: Based on engine cycles? Or time-based  
    long_haul: Per airframe certification?
```

**Database Trigger or Event Listener für auto-Warnung:**

```php
use Illuminate\Database\Eloquent\Relations\HasMany;  

class Aircraft extends Model {  
    // Auto-Maintenance Check Logic:
   
    public function isMaintenanceDue(): bool {
        $days_since_last_maint = carbon($this->last_maintenance_date)->diffInDays();
        return $days_since_last_maint >= config('maintenance.interval_months', 20); // LBA? or ICAO minimums?
    }

    public function nextInspectionDueDate(): Carbon|NULL {  
        if (!$this->next_inspection_due_date) return null;   
        
        $scheduled_date = Carbon::instance($this->next_inspection_due_date); 
        return $scheduled_date->addMonths(config('maintenance.inspection_interval_days', 12));
    }

}

```

**Laravel Event-Driven Maintenance System (Blueprint):**

```php
// database/events/MaintenanceEventDispatched.php (optional Phase 2?)  
class MaintenanceEvent extends Event {  
 
    public string $aircraft_registration;  
    public int $daysRemaining;  
    
    // Dispatch when aircraft becomes maintenance_due:
    
}

// Listeners:
// EmailNotificationListener: Send alert to admin & pilot?  

```

---

### 5. Auto-Cleanup bei Wartungsbedarf (Onboarding Logic)

**Flows:**

```sql
// On new schedule creation, assign aircraft?  
ON EVENT 'new_schedule_created' THEN UPDATE flight_schedules SET assigned_aircraft_id = ? WHERE ...;

CREATE TRIGGER update_aircraft_status AFTER UPDATE OF maintenance_status ON aircrafts FOR EACH ROW 
      WHEN (OLD.status != 'airworthy') THEN
          // If new status != airworthy, remove from any schedule?  
          UPDATE flight_schedules 
          SET assigned_aircraft_id = NULL, active = false  -- Deactivate if aircraft not fit for?

// LBA-Compliant: Ground all aircraft with expired certs on inspection_expiry_date!
```

---

## ✅ Planung Status - Auto-Routing & Maintenance

Checkliste Flottenmanagement:

[✅] Auto-Zuordnung Logic (Route-Class-based Aircraft Selection)  
[✅] Maintenance Status Management (LBA Compliance Fields)  
[✅] Route-Aircraft Matching Rules (<800, 801-2500, >2500 NM)  
[ ✅] Aircraft Model Data (C172/A320neo/B738) mit range/capability JSON  
[ ] Maintenance Event System Blueprint?  
[ ] Admin UI für Maintenance-Alerts?  
[ ] LBA LuftV §14/§15 Compliance Documentation  

---

## 📌 Nächster Planungs-Schritt?

Soll ich das **Maintenance Alert System** planen (E-Mail-Warnungen bei LBA Compliance)?

Oder etwas anderes Detailplanen für Flottenmanagement? Sag einfach!
