# runwayhub - Automatic Round-Trip Flight Creation Planning

## 🎯 Anforderung & Business Logic

**Bei Erstellung eines neuen Fluges (z.B. EDDM → EGLL):**
- **Option A**: Automatisch Rückflug erstellen (EGLL → EDDM)
- **Option B**: Nur Hinflug ohne automatische Rückflug-Creation  
- **Option C**: Manuell Rückflug angeben (wenn nicht automatisch gewünscht)

---

## 📋 Design-Entscheidungen

### 1. Datenbank-Schema Extension (Flight Schedules Table)

**Add fields für Round-Trip Configuration:**

```sql
-- In flight_schedules table:

schema::table('flight_schedules'):
    id                     : integer      // Primary Key  
    flight_number          : string       // RWH100 format, unique per route
    
    from_icao              : string       // Origin airport (EDDM)
    to_icao                : string       // Destination (EGLL)    
    
    departure_datetime     : datetime     // Hinflug abflugzeit
    return_datetime        : datetime     // Rückflug (nur wenn available=true)  
                                        // nullable oder same route reversed?
    
    aircraft_type          : enum("A320neo", "B738","C172")
    active                 : boolean      // true = flight is scheduled
    
    round_trip             : boolean      // Is this part of a round-trip pair?
                                       // true = Hinflug/return_flight paired
  
    -- Optional: Reference to return flight (if auto-created)  
    return_flight_id       : integer      // FK nullable → self-reference or null  

    index(['from_icao', 'to_icao, 'round_trip']);
```

---

### 2. Round-Trip Logic Flow

#### Option A: Automatic Return Flight Creation

**Flow:**
```
Step 1: User erstellt neuen Flug EDDM → EGLL  
        ↓  
Step 2: System prüft Konfiguration (automatically_create_return=true) 
        ↓  
Step 3: Reverse Route berechnen  
      - To ICAO becomes From ICAO  
      - From ICAO becomes To ICAO  
      - Departure Date = Original + offset days? (optional config)  
      ↓    
Step 4: Call FlightNumberGenerator() für Rückflug  
      - Same route reversed? Or different flight_number prefix?
        e.g. RWH100 Hin, RWH200 Rück? Or same number? 
        → Different flight numbers for outbound/return!
        ↓  
Step 5: Create return_flight record (auto-generated)  
Step 6: Set round_trip=true on both records  
      - Link Hinflug.id with ReturnFlight.id via FK?

Response: [Hin + Rückflug Pair erstellt]
```

#### Option B: Manual Return Flight Creation

**Flow:**
```
Step 1: User erstellt Hinflug (EDDM → EGLL) 
        ↓  
Step 2: User klickt "[Optional: Create Return Flight]" Checkbox
        ↓  
Step 3a: [Checkbox unchecked] → Nur Hinflug erstellt  
       Step 4: continue ohne return_flight

Step 3b: [Checkbox checked] → Show Datepicker Dialog?
      - User wählt Rückflug abflugsdatum (EGLL → EDDM)? 
        or System suggests next available date + offset?
        ↓  
Step 4: Call FlightNumberGenerator for reverse route  
Step 5: Create return_flight only on confirmation  
```

#### Option C: Hybrid Approach (Recommended)

**Configuration per Schedule:**

```yaml
CreateFlightController::store(Request $request):
    // Input Form Fields:
    - from_icao: "EDDM" (required)
    - to_icao:     "EGLL" (required) 
    - departure_datetime: DateTime(required)
    - auto_create_return: boolean (default=true) ← NEW FIELD!
    
    business logic:
      if (!auto_create_return) {
          // Only create outbound flight
          create_flight_schedule() → return_response(only_outbound);
      } else {
          // Auto-create round trip?
          
          $outbound = schedule::create($request->input);
          
          // Generate return flight
          $return = [
            'from_icao'  => 'EGLL',  // Reversed airports
            'to_icao'    => 'EDDM',  
            'departure_datetime': $request->departure_datetime 
                      ->addDays($CONFIG_ROUND_TRIP_OFFSET_DAYS),  // Default +14 days?
            'flight_number' => generate_next_return($outbound->from, $outbound->to)
          ];
          
          schedule::create($return);
          
          // Link both via round_trip=true or direct FK relation?
    }
```

---

### 3. Datenbank-Konzept-Design (Migrations Blueprint)

#### Migration Schema: flight_schedules table

```sql
CREATE TABLE `flight_schedules` (  
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,  
  flight_number VARCHAR(20) NOT NULL,    -- RWH100 format
  from_icao VARCHAR(4) NOT NULL,           -- Origin airport ICAO  
  to_icao VARCHAR(4) NOT NULL,            -- Destination  
  departure_datetime DATETIME NOT NULL,      -- Outbound flight time
  return_datetime DATETIME NULL,             -- Return flight (nullable unless round_trip=true?)  
  active BOOLEAN DEFAULT TRUE,                 -- Is this scheduled?  
  aircraft_type ENUM('A320neo', 'B738', 'C172') NOT NULL
  
  // Round-Trip Configuration Fields
  round_trip TINYINT(1) DEFAULT FALSE,        -- Is paired schedule? (outbound/return)
                                    -- true = Hinflug+Rückflug existieren beide  
                                      // und linked via FK or same logical route pair  
  return_flight_id BIGINT UNSIGNED NULL,      -- FK nullable → Self-Reference if auto-created  
                                              // to_icao from self-reference  

  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP

) ENGINE=InnoDB;

-- Unique Constraints
ALTER TABLE `flight_schedules`  
ADD CONSTRAINT `unique_flight_number_route` UNIQUE(
    from_icao, 
    to_icao, 
    flight_number
); -- Per-route uniqueness

-- Foreign Key Constraint for return-flight (if auto-created)
ALTER TABLE `flight_schedules`  
ADD CONSTRAINT `fk_return_flight_id`  
FOREIGN KEY(`return_flight_id`) REFERENCES `flight_schedules` (`id`) ON DELETE CASCADE;
```

**Constraints & Indexes:**

```sql
-- Add Round-Trip Pairing Constraint (optional for MVP):
ALTER TABLE flight_schedules 
ADD UNIQUE(`round_trip`, `from_icao`, `to_icao`);
  
-- Enable return_datetime check constraint?  
ALTER TABLE flight_schedules  
ADD CONSTRAINT `check_return_before_outbound_datetime` CHECK(
    round_trip=0 OR return_datetime > departure_datetime
);

-- Index for query efficiency:  
CREATE INDEX `idx_from_to_active` ON flight_schedules (`from_icao`, `to_icao`, `active`);
```

---

### 4. Laravel Implementation (Blueprint-only, kein Code schreiben!)

#### app/Models/FlightSchedule.php (Eloquent Model Planning)

```php
class FlightSchedule extends Model {
    
    protected $fillable = [
        'flight_number',
        'from_icao', 
        'to_icao',
        'departure_datetime',
        'return_datetime',  
        'active',
        'aircraft_type',
        'round_trip',
        'return_flight_id'  // FK Self-Reference? Or nullable if manual-only return?
    ];

    protected $hidden = ['password']; 
    
    /*** Round-Trip Relationship *** */
    
    // Return flight (if exists)
    public function returnFlight(): BelongsTo {
        return $this->belongsTo(self::class, 'return_flight_id', 'id');
    }
    
    // Outbound flights paired to this return flight  
    public function outboundFlights(): HasMany {
        return $this->hasMany(self::class, 'return_flight_id', 'id');
    }

    /*** Helper methods (Planned, ohne Implementierung) *** */
   
    public function createPairFlight(int $days_offset=14): self {
        // If round_trip=true? Auto-create reverse flight! 
       return [ReverseRouteLogic, FlightNumberGenerator)->generate($days_offset)];
    }

    /*** Round-Trip Helper: Generate Return Route (Planned) *** */  
    public function getReturnSuggestion(): array|null {
        if (!$this->round_trip && !$this->return_datetime) return NULL;
        
     return [
       'from_icao' => $this->to_icao,   // Reverse airports? Or same as original?
       'to_icao' => $this->from_icao,
       'departure_datetime': $this->departure_datetime + Config('roundtrip.offset_days'),
       'flight_number': FlightNumberGenerator::generateNext($this->from, $this->to)
     ]; 
    }

    // Check if paired return flight exists  
    public function pairExists(): bool {
        if ($this->round_trip || ($this->return_flight_id)) return true;
        return false;  // Only outbound scheduled, no return yet?
    }
    
    /*** Helper: Is round-trip outbound or inbound leg?** **/
   public function isOutboundLeg(): bool {
       return $this->from_icao !== 'EGLL'; // If this has origin EDDM, it's outbound?
                                      // Need to track which route direction in business logic?
    }
}
```

---

### 5. Form-Layout Planning (resources/views/ground_control/schedules_new.blade.php Blueprint)

**Field Layout für New Schedule Creation:**

```html
<!-- resources/views/ground_control/schedules_new.blade.php -->

<div class="schedule-creation-form">  

  // Left Panel: Flight Configuration  
  ┌────────────────────────────────┐
  │ ✈️ Create New Route            │  
  ├────────────────────────────────┤
  │                                 │  
  │ From Airport [EDDM ▼]         │ ← Alpine Dropdown (ICAO Codes)  
  │ To     Airport [EGLL▼]        │  
  │                             ────│  
  │ Departure Date [Date Picker]  │ ← Calendar date  
  │ Time                 [14:30]  │ ← Alpine Time Select Component  
  │                             ────│  
  │ Aircraft Type    [A320neo ▼] │  
  │ Buffer Minutes   [±15 ▼]      │  
  │                             ────│  
  │                                │
  │ ⚙️ Round-Trip Configuration:  │  
  ├──────────────────────────────┤
  │                               │
  │ ☐ Create Return Flight Automatically? ← Alpine Checkbox!  
  │                               │
  │ If checked → Auto-Create       ▲  
  │   Offset Days [14 ▼]          │  
  │   Returns on: [Auto-Calculated Date Picker] │
  │                               │
  └──────────────────────────────┘  
  

  // Right Panel: Schedule Details Preview
  ┌────────────────────────────────┐
  │ Preview:                       │  
  ├────────────────────────────────┤
  │ Outbound Flight                │  
  │ EDDM → EGLL                   │  
  │ RWH100                        │ ← Auto-generated number  
  │                                      
  │ Return Flight (Auto)           │  
  │ EGLL → EDDM                  │  ← Preview only if ☐ checked?
  │ RWH200                        │   (Generate different number!)
  └────────────────────────────────┘  
          
  Action Buttons:  
    [Save & Create Outbound Only]  (uncheck return)  
    [Create Full Round Trip]       (checked, auto-return = true)  

</div>

<!-- Alpine X-Data Binding for Form State -->
<script defer setup>
import { reactive } from "alpine"

const scheduleForm = reactive({
  from_airoport: 'EDDM',
  to_airport: 'EGLL', 
  departure_datetime: '', // DateTime input
  auto_create_return: false,
  
  methods: {
    
    validate() {
      return {
        required_fields: this.from_icao && this.to_icao && this.departure_datetime,
        airport_validations: validICAO(this.from_icao),
      };
    },

    createReturnSuggestion(): boolean | null {
           return auto_create_return ? {
                 'from_icao': 'EGLL',
                 'to_icao': 'EDDM', 
                 'departure_datetime': this.departure_datetime.addDays(14), // default offset?
               } : null;
           }
       },
  
      init() {
        // Load preview of return flight only if auto_create_return checked?
      }
    }
  }));  
</script>
```

---

### 6. Business Logic: Return Flight Auto-Creation (Service Blueprint)

#### app/Services/ScheduleCreationService.php (Blueprint)

```php
class ScheduleCreationService {
    
    // Create new schedule with optional auto-return generation  
    public static function createWithOptionalReturn(Request $request): array|FlightSchedule {
        
       // 1. Create Outbound Flight  
        $outbound = FlightSchedule::create([
            'flight_number' => FlightNumberGenerator::generateNext($from, $to), 
            'from_icao'    => $request->input('from_icao'),
            'to_icao'      => $request->input('to_icao'),
            'departure_datetime' => $request->input('departure_datetime'),
            'aircraft_type'  => $request->input('aircraft_type'),
            'active'         => true,
            
            // Return Configuration  
            'auto_create_return' => $request->boolean('auto_create_return') || false,
        ]);

        if ($outbound->{isAutoCreated return && !$outbound->returnFlightId) {
                // Auto-Create Return Flight? 
                
                returnSchedule = self::createReturnFlight($from_icao, $to_icao, departure_datetime);
                self::linkOutboundToReturn($outbound, $return_flight);
            }
        }

       return [$outbound, ...]; or throw DuplicateReturnFlightException;
    }

    public static function createReturnFlight(FromAirport : string $from,
                                               ToAirpoit : string 
                                                  DepartureDatetime datetime): FlightSchedule {
    
        // 1. Calculate Reverse Route Configuration  
            $reversed_from = $request->input('to_icao');   // EGLL → EDDM
            $reversed_to = $request->input('from_icao');
            
        // 2. Generate Return Date (Offset Days)? or user input?  
          if ($offset_days) {      
             $return_datetime = $departure_datetime + days($offset_days);
              // Calculate next available flight date based on aircraft maintenance schedule!
          } else {
            // Use departure_datetime as return flight too? unusual but valid for special cases like ferry flights?
    }

        // 3. Generate unique flight number for return (different from outbound!) 
            $return_flight_number = FlightNumberGenerator::generateNext($from, $to);
        
        // 4. Create return flight schedule  
           return FlightSchedule::create([
               'flight_number' => $return_flight_number, 
               'from_icao'    => $request->input('to_icao'),   // EGLL
               'to_icao'      => $request->input('from_icao'),  // EDDM  
                'departure_datetime' => $return_datetime,  // offset date from outbound
               'round_trip'     => true,  // Link to outbound pair? 
                'active'         => false,  // Default inactive till activated by dispatch?  
           ]);
    
       return [new FlightSchedule($return);
    }

    /*** Helper: Offset Days Config for Return Flight Creation** */ 
    public static function get_return_date_offset(): int {
        return config('roundtrip.default_offset_days', 14); // Default 2 weeks? or 30 days?
    }

    /*** Helper: Check if return flight already exists before creation (race condition prevention)** /  
    private static function checkDuplicateReturnFlight($flight_number, $from, $to): bool { 
        $duplicate = FlightSchedule::where('flight_number', $flight_number)
              ->exists();
              
        if ($duplicate) throw new DuplicateFlightNumberException; // Retry with incremented number!
}

/** Method: Link outgoing return flight (round_trip=true)** /  
private static function linkOutboundToReturn(OutboundFlight outbound, 
                                                   ReturnFlight $return_flight): void {
  $outbound->update([
      'return_flight_id' => $return->id(),
      'round_trip' => true,  
  ]);
}

/** Helper: Generate return flight number for reverse route (unique constraint check)** */  
private static function generateNextReturnFlightNumber($from_icao, $to_icao): string { 
    // Should be same prefix RWH but different suffix than outbound? 
    // e.g., OUTBOUND: RWH100 → RETURN: RWH200?  
    // Or just next available number after current max on reverse route?  
    return FlightNumberGenerator::generateNext($reversed_from, $reversed_to);  
}  

// Business Rules & Config:
return_flight_active_default = false;  // Pending admin activation?  
allow_auto_return_creation = config('system.auto_create_returns.enabled', true);  
default_offset_days = config('system.auto_create_returns.offset_days', 14);

}
```

---

### 7. Database Constraints & Business Rules

**Constraints Checklist:**

```sql
[✅] Flight Number Unique per Route (from_icao + to_icao + flight_number)  
[✅] Round-Trip FK Constraint (self-reference if auto-created)  
[✅] Return DateTime must be > Departure DateTime? (Optional Check: return_datetime >= departure_datetime?)  
    → Allow same-day flights for ferry operations? Or always later?
```

---

## 📝 Summary & Next Planning Steps

**Current Planning Status:**

Checklist - Round Trip Feature:

[✅] Auto-Create Return Flight Logic (Business Rules)  
[✅] Optional Manual Return Configuration (Checkbox UI)  
[✅] Database Schema Enhancement (return_flight_id FK, round_trip bool field)  
[✅] Flight Number Generation for Reverse Route (Unique constraints on both legs)  
[ ] Migrations Documentation? 
[ ] API Endpoint Spec for Create Schedule with Round-Trip Option?  

---

## 🎯 Nächster Planungs-Schritt?

Soll ich jetzt die **Migrations-Dokumentation** schreiben oder den **API-Endpoint für Create Schedule mit Auto Return Logic**?

Oder möchtest du was Spezifisches noch dazutragen, das ich detailliert planen soll?

