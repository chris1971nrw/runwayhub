# runwayhub - Eigens ACARS System Blueprint

## 🎯 Purpose

**ACARS (Aircraft Communications Addressing and Reporting System):**
- **Zweck:** Flugsimulator Logs → Datenbank Pipeline  
- **MSFS/X-Plane:** Screenshot Upload, Flight Paths, Performance Data  
- **Auto-Parsing:** Flugstart/Endzeit, Route, Weather Conditions  
- **LBA Compliance:** Maintenance Records via ACARS Reports  

---

## 📋 Core Integration Flow

**Pilot Flow (MSFS/X-Plane):**
```mermaid
graph TD
    A[Pilot startet Simflug] --> B[Log-File generiert MSFS.log/xplane.simulation/]  
    C[Lande erfolgt + Screenshot] --> D[Schnittstelle uploadet: /storage/app/flights/{schedule_id}]/  
    E[Cron-Job prüft Upload-Pfad] --> F[Fluggeschehen im ACARS Queue]  
    F --> G[Flugdaten extrahiert & in flight_schedules gespeichert]  
    G --> H[Pilot erhält "Flight Completed" Notification + LBA-Compliance-Record]
```

---

## 📁 Datenbank Design: ACARS Schema Blueprint

### 1. Flight_Logs Tabelle (Laravel Model)

```sql
/** ACARS Log Storage - Laravel migration **/
create_table('flight_logs') {  
    id                        : integer          // Primary Key, auto-increment  
    
    schedule_id               : integer          // FK → flight_schedules.id  
                                      // Links to assigned route
  
    aircraft_registration     : string           // z.B., D-AIMR (UNIQUE)  
      -- Auto-filled from aircrafts table assignment
    
    sim_platform              : ENUM("msfs_2020", "xplane12") // Simulator Type  
    
    flight_number             : string           // e.g., "RWH100" (from schedule)
    actual_takeoff_date       : datetime         // Extracted from MSFS log timestamp  
                                        // Format: [2026-10-15 14:28:03]  
    
    actual_arrival_date       : datetime         // Real landing time  
                          -- Derived from flight_end_timestamp
    
    flight_duration_minutes    : integer          // Calculated: arrival - takeoff
    distance_covered_nm       : decimal(10,2)    // Actual route NM flown (if recorded)
    
    // Weather Conditions at Departure (if logged by simulator)  
    departure_visibility_km   : decimal|null
    departure_wind_knots      : integer|null
    departure_temp_celsius    : decimal|null  -- For performance analysis
  
    // Aircraft Performance Data (Extracted from MSFS/X-Plane telemetry)  
    max_altitude_ft           : integer          // Peak altitude reached  
    fuel_consumption_liters   : decimal(8,2)     // Real fuel used vs. planned  
                                        -- Useful for optimization
    
    turbulence_events_count    : integer|null     // If logged (minor/turmoil warnings?)
    
    screenshots_directory     : string           // e.g., "/storage/app/flights/2026-10-15/RWH100_Landing.jpg"
      -- Absolute path to MSFS screenshot  
       (stored per flight for review/certification)
      
        -- Pilot Uploads Screenshots: Takeoff, Enroute/Landscapes, Arrival Photos  
      // File size limit: 20MB max per image (simulated photo realism)  

    // LBA Maintenance Records (via ACARS Report Integration)  
    engine_cycles_count       : integer|null     // If simulator telemetry provided  
                                      -- Incremented upon each flight hour
          next_maintenance_date_calculated : date|null  // Next inspection? Based on cycles
    
    is_flight_verified        : boolean DEFAULT false  // Admin flag for LBA certification
    lba_compliance_status     : ENUM("compliant", "inspection_required", "grounded")

    timestamps                : datetime created/updated
  
}

-- Indexes for query optimization:  
create_index 'flight_logs_by_schedule', columns: ['schedule_id'];  
create_index 'flight_logs_by_aircraft_reg', columns: ['aircraft_registration'];  
create_index 'flight_logs_timestamp', columns: ['actual_takeoff_date'];  

/** Foreign Key Constraints **/  
ALTER TABLE flight_logs ADD CONSTRAINT fk_flight_log_schedule  
  FOREIGN KEY(schedule_id) REFERENCES flight_schedules(id) ON DELETE CASCADE;  

/** Trigger for Flight Verification **/  
CREATE TRIGGER update_flight_verification_status AFTER UPDATE OF is_verified ON flight_logs
  FOR EACH ROW BEGIN IF NEW.is_verified THEN UPDATE flight_schedules SET verified_flights = verified_flights +1 WHERE id = OLD.id END; END;

```

---

### 2. ACARS Queue Job Blueprint (Laravel Worker)

**app/Jobs/AcArsLogProcessing.php:**

```php
namespace App\Jobs;

use Illuminate\queue\Job; // Base Laravel Job Class  
use Illuminate\Contracts\Queue\ShouldQueue; // Must implement interface!  

class AcarsLogProcessing extends Job implements ShouldQueue {  
    protected $schedule_id;  
    protected $sim_file_path;  
    protected $aircraft_registration;
    
     public function handle(): void {
         Log::info('ACARS Queue running for schedule ' . $this->schedule_id);

         // 1. Extract telemetry from MSFS/X-Plane log file?  
         $telemetry = new TelemetryParser($this→sim_file_path);  

         // 2. Parse flight data (takeoff/arrival, weather)  
             $flight_data = $telemetry->getFlightData();

         // 3. Store in flight_logs table via Eloquent  
                $log = Flight_Log::create([
                    'schedule_id': $this->schedule_id,  
                    'aircraft_registration' => $this→aircraft_registration,  
                    'actual_takeoff_date': $flight_data->takeoff_timestamp,  
                        'actual_arrival_date': $flight_data→landing_timestamp,  
                        'flight_duration_minutes': $flight_data->duration_minutes,  
                      'screenshots_directory' => '/storage/app/flights/' . hash($this→sim_file_path),  
                       // Weather conditions (if logged in simulator?)  
                        'departure_visibility_km': $flight_data->visibilityKm,  
                        'flight_performance': [
                            'max_altitude_ft': $flight_data->peak_altitude,  
                              'fuel_consumption_liters': $flight_data.fuelUsed  
                    ]  
                 ]);

         // 4. Send Email to Pilot if verified? (optional notification)  
         if ($log→is_verified) {  
             Mail::to($pilot→email)->send('Flight Completed', new FlightCompletion);
         }

         // Mark job as completed  
         App::handleAcarsProcessing(); 
     }  

     /*** Parse MSFS Log File for Flight Data ***/  
    private function parseMSFsLog(string $log_path): array {  
        
       $content = file_get_contents($log_path);  // Read entire log file?
      
        return preg_match_all('/\[([0-9]{4}-[0\-9]+\-[0-9]+ [0-9:]+)/', $content, $matches)[1] + ' UTC');  
        
    }

}

/** Queue Worker Command (Console Artifacts **/
php artisan queue:work acars  --queue=acars,scheduled;  

// .env config for QUEUE_CONNECTION = `redis` or `database`;  

```

---

### 3. Laravel Queue Worker Setup (File Watching)

**Setup für ACARS System:**

```yaml
Laravel Job Configuration:
┌─────────────────────────────────┐
│ Queue Worker (ACARS Pipeline): │  
├─────────────────────────────────┤
│ 1. File Watcher: /storage/app/ │  
│    - Monitor folder for new     │  
│      MSFS_log files uploaded?  
│       
│ 2. Cron-Job daily check?:      
├─────────────────────────────────┤
│ 3. Extract flight data from log │  
│    - Takeoff timestamp (UTC)   │  
│    - Landing timestamp          │  
│    - Weather conditions         │  
│    - Aircraft telemetry (if avail)?  
│       
│ 4. Store in flight_logs table: │  
│    - Schedule ID (FK link)     │ → Route details?  
│    - Flight logs (duration, performance?)  
│       
│ 5. Email notification to Pilot: │  
│    - "Flight completed" +      │  
│      LBA Compliance Record     │
└─────────────────────────────────┘

File path config (.env.example):
APP_FLIGHT_LOGS_PATH=/storage/app/flights  
APP_ACARS_QUEUE_CONNECTION=redis  // DB or Redis for Laravel queue worker!  

// Cron entry: * * * * * php artisan acars:process (每小时检查航班日志)  
```

---

### 4. Pilot Workflow für Screenshots Upload  

**Upload Guide Integration in ACARS:**

```markdown
# Screenshots-Upload Instructions (MSFS/X-Plane):

1. Flight ends → Sim logs saved locally (e.g., /C:/Simulation/Logs/MSFS2020.log)  
2. Navigate to runwayhub.de > My Flights > {schedule_id}  
3. Click "Upload Screenshot" button  
4. Select image file (.jpg/.png max 5MB? or higher?)  
5. Confirm upload → Stored in /storage/app/flights/{schedule_id}/  
6. ACARS job processes flight data & email notification sent  

LBA Compliance Records via ACARS:
- ✅ Engine cycles count (if simulator provides telemetry)  
- ✅ Fuel consumption vs planned  
- ✅ Turbulence events logged for future reference?  

```

---

### 5. MSFS/X-Plane Log Parsing Blueprint

**app/Services/TelemetryParser.php:**

```php
class TelemetryParser {  
    public function parseMSFSLog(string $log_path): FlightData {  
        // Example MSFS log line: [2026-10-15 14:28:03] Takeoff initiated at EDDM  
        $takeoff_time = preg_match('/Takeoff\+initiated|Start flight sequence', $content)[0];
        
      // Landing timestamp: [2026-10-15 15:42:37] Landing completed at EGLL  
         $landing_time = preg_match('/Landing\.completed/', $content)[0];  
    
        return new FlightData($takeoff_time, $landing_time→flight_duration_minutes);  

    }

}
```

---

### 6. X-Plane Log Parsing Blueprint

**app/Services/XPlaneLogParser.php:**

```php
class XplaneLogParser extends TelemetryParser {  
     protected function parseXplaneLog(string $log_path): FlightData {  
        
           // X-Plane format differs? Extract similar data from .simulations file?  
           
         return FlightData::fromXPlaneLog($log_path);
}  

    public function getFlightDataFromXPlane(): FlightData {  
        // Parse .xplane12 file for flight records  
        $content = (string) file_get_contents($this→log_path);  
    
          if (!preg_match('/[0-9]{4}-[0-9]+\-[0-9]+\s+UTC/', $content)) return null;
    
        // Extract UTC timestamp from first flight record:  
         [$takeoff, $landing] = []; 
    
        return new FlightData(...);
    }

}

```

---

### 7. ACARS System - Pilot Certification Flow

**Flows:**

```yaml
Flight Verification Process (LBA §15):
┌───────────────────────────────────────┐
│ 1. Pilot initiates flight in MSFS      │  
│ 2. Landscapes captured, screenshots uploaded  
│    → Stored per schedule ID  
│     
│ 3. ACARS Cron Job checks for new files daily?  │  
│    → Extracts data from log file?  
│    
│ 4. Store in DB: flight_logs table      │ (FK to schedules)  
│       
│ 5. Pilot receives E-Mail notification   │  
│    - "Flight completed" +              │  
│      performance metrics               │  
│       
│ 6. Admin verifies flight (optional?)   
│    → Sets is_verified = true?          │  
│       Trigger: Update verified_flights count;  
└───────────────────────────────────────┘

LBA Compliance Tracking:
- ✅ Airworthiness Certificate valid? (from aircrafts table)  
- ✅ Flight duration within planned range?  
- ✅ Fuel consumption recorded for analysis  

```

---

## 🧭 LBA-Kompliance Integration  

### 8. Maintenance Records via ACARS

**ACARS System - LBA §15 Compliance Logic:**

```yaml
Maintenance Status (via Telemetry):
┌───────────────────────┐  
│ Engine Cycles Count:  │ ← Increment per flight hour (if telemetry logged);  
│ Fuel Consumption Log: │ → Used for efficiency analysis;  
│ Turbulence Events:    │ → Warning log? (Safety monitoring)  
└───────────────────────┘  

LBA Requirements:
[ ] Airworthiness Certificate valid? (§15 LuftV)  
[✓] Annual/Period Inspection last performed (§14 ICAO minimums)  
  -- Incremented upon each flight cycle?  
[ ✓] Next Scheduled Maintenance calculated (engine cycles-based?)  
```

---

## ✅ ACARS System Planungs-Checkliste  

| Aufgabe | Status | Anmerkungen |
|---------|--------|-----------|
| **Flight_Logs Tabelle Design** | ✅ Geplant FK + Telemetry Fields | MSFS/X-Plane Unique constraints? |
| **ACARS Queue Job Blueprint** | ✅ PHP Job Class + Laravel Worker Setup | File Watching für Uploads |
| **Telemetry Parser (MSFS)** | ✅ Regex Extraction Timestamps | Flight duration calculation |
| **X-Plane Parser Integration** | ✅ Override/Extend TelemetryParser | .simulations file parsing? |
| **Schnittstelle Upload-Pfad** | ✅ /storage/app/flights/{schedule_id}/ | Screenshots + Telemetry Files |
| **Pilot Workflow Guide** | ✅ Upload Instructions for Flight Logs | Screenshot max size limit |
| **Maintenance Records Integration** | ✅ Engine Cycles via ACARS Data | LBA Compliance Tracking |
| **Admin Verification Endpoint** | ⏳ TODO? (Optional Phase 2?) | Admin dashboard flight review |
| **Email Notification Service** | ✅ Mail:to($pilot→email) Laravel Queue | "Flight Completed" Template |

---

## 📌 Nächster Planungs-Schritt?

Soll ich den **Maintenance Alert System mit Weather-Warnungen** planen (LBA §14/§15 Compliance Alerts)?

Oder noch **ACARS API Endpoints** für Entwickler-Dokumentation schreiben?  

Sag einfach, was als nächstes zu planen ist!
