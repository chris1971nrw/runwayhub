# runwayhub - Automatische Flugnummer Generierung (Flight Number Generation)

## 🎯 Anforderung

**Automatische Erstellung von Flugnummern für neue Schedules:**
- **Prefix**: "RWH" (RunwayHub Identifier)
- **Format**: `RWH100`, `RWH101`, `RWH102` etc.  
- **Uniqueness**: Keine doppelten Flugnummern erlauben

---

## 📋 Design-Entscheidungen

### 1. Flight Number Generation Strategy - **Streckenklassen-basiert** 🛫✈️🚀

**Strecken-Klassen mit RWH-Nummern-Ranges:**

```yaml
Kurzstrecke:     < 800 NM (nautische Meilen)
 Mittelstrecke:  801-2500 NM  
 Langstrecke:    > 2500 NM  

Flight Number Ranges:
├─ Kurzstrecke:   RWH001 - RWH299     (Regional/Inland)
├─ Mittelstrecke: RWH300 - RWH799     (Interkontinental Short/Medium)
└─ Langstrecke:   RWH800+              (Long-haul International Flights)

Business Justification:
- ✅ Passagiere erkennen Kurz/Mittel/Langstrecke an ersten Ziffer  
- ✅ Operational Planning (Crew rest requirements per IATA)
- ✅ Aircraft Type Matching (R73 für Kurz, A320neo für Lang?)

Routing Distance Calculation:
  distance_nm = haversine(from_lat/lon, to_lat/lon)
  if distance < 800 → classify as 'short_haul', assign RWHxxx (001-299)
  elif distance <= 2500 → 'medium_haul', assign RWHyyy (300-799)
  else → 'long_haul' assign RWHzzz (800+)
  
```

---

### 2. Datenbank Implementation (Migration Blueprint - Enhanced with Distance Calculation)

#### Flight Schedules Table Enhancement (Migrations-Blueprint):

```sql
-- migration: {timestamp}_add_flight_number_to_schedules.php

create_table('flight_schedules') {
    // ... andere Felder ...
    
    flight_number : string     // UNIQUE, not null, format RWHxxx (3 char suffix)
    from_icao     : string     // ICAO Code  
    to_icao       : string
    
    index: [unique_key(flight_number)]  // Prevent duplicates globally?
          or [unique(key=flight_number + from_icao + to_icao)] -- Per route?

    constraints: [
      foreign_key(pilot_id) -> pilots.id,
      check_constraint(flight_number MATCHES rwh\d{3})  // Optional validation
    ]
}
```

#### Unique-Constraint Varianten:

**Variante A: Global Unique (Single Number per Airline)**
```sql
-- Nur EIN Flug RWH101 weltweit existiert!
ALTER TABLE flight_schedules 
ADD CONSTRAINT unique_flight_number UNIQUE(flight_number);
```

**Variante B: Route-Scoped Unique (besser für Airlines mit vielen Routen)**
```sql
-- RWH101 kann von EDDM→EGLL UND LFPG→KJFK verwendet werden, aber nicht beide zu gleicher Zeit!
ALTER TABLE flight_schedules 
ADD CONSTRAINT unique_route_flight_number UNIQUE(
    from_icao, 
    to_icao, 
    flight_number
);
```

**Variante C: Full Unique per Direction (best practice)**  
```sql
-- RWH101 von EDDM→EGLL ist anders als RWH101 von EGLL→EDDM
ALTER TABLE flight_schedules 
ADD CONSTRAINT unique_route_direction_flight UNIQUE(
    from_icao, 
    to_icao, 
    flight_number
);
```

**✅ Empfehlung: Variante B oder C** (mehr Flexibilität für diverse Routen)

---

## 🔧 Business Logic Flow

### Automatic Flight Number Generation

**Algorithmus:**
```pseudo
function generateFlightNumber(from_airport, to_airport):
    
    // 1. Query Max Existing Flight Number on this Route  
    max_number_result = database.query(
        "SELECT MAX(CAST(SUBSTRING(flight_number, 5) AS UNSIGNED)) 
         FROM flight_schedules 
         WHERE from_icao = ? AND to_icao = ?",
        {from_airport, to_airport}
    )
    
    current_max = max_number_result.max_value
    
    // 2. Handle case: keine existing schedule on this route?
    if (current_max IS NULL) {
        next_number = 100;  // Start number for new routes!
    } else {
        next_number = current_max + 1;
    }

    // 3. Generate flight number with prefix RWH  
    flight_number = "RWH" + format(next_number, "%03d"); // Padding 3 digits
    
    // 4. Double-check uniqueness before INSERT (prevent race condition on high-traffic servers)
    if (exists(flight_number, from_airport, to_airport)) {
        next_number += 1;
        flight_number = "RWH" + format(next_number);
        
        // Retry loop max 3 times?
    }

    return flight_number; // Format: RWH101
```

**Laravel Implementation (app/Services/FlightNumberGenerator Service):**
```php
class FlightNumberGenerator {
    
    // Generate next available flight number for route
    public static function generateNextFlightNumber(FromAirportICAO $from_icao, 
                                                      ToAirportICAO $to_icao): string {
        
        // Query max existing flight number on this route
        $max_result = FlightSchedule::where('from_icao', $from_icao)
            ->where('to_icao', $to_icao)
            ->exists();

        if (!$max_result) {
            // No existing schedule? Start from 100!  
            return self::nextSequenceNumber(100);
        }

        // Get max sequence number suffix  
        $max = FlightSchedule::where('from_icao', $from_icao)
                            ->where('to_icao', $to_icao)
                            ->orderByRaw('CAST(SUBSTRING(flight_number, 5) AS UNSIGNED)', 'DESC')
                            ->first()
                            ->flight_number; // e.g., RWH101

        if (!$max) {
            return 'RWH100'; // First flight on route!
        }

        $sequence_num = intval(substr($max, 4)); // Extract "101" from "RWH101"
        
        // Increment & format  
        $next_sequence = $sequence_num + 1;
        return 'RWH' . str_pad($next_sequence, 3, '0', STR_PAD_LEFT); // RWH102
    }

    // Helper: Check if flight number exists (prevent race condition!)  
    private static function flightNumberExists(string $flight_number, 
                                                string $from_icao, 
                                                string $to_icao): bool {
        return FlightSchedule::where(
            'flight_number', $flight_number
          )->exists();
    }

}
```

---

### Migrations-Code Draft (ohne Implementierung!)

#### Migration 1: Create flight_schedules with flight_number field

```php
// database/migrations/{create_time}_create_flight_schedules_table.php  
schema::create('flight_schedules', function Blueprint($table): void {
    $table->id();
    
    // Core Fields  
    $table->string('flight_number')       
          ->unique()                       // UNIQUE CONSTRAINT GLOBAL!
          ->afterInsert(function($schedule) use (FlightSchedule, $from_icao, $to_icao) {
              // Generate after insert? Or before? Pre-generate is safer.
          })
    
    // Routing Data  
    $table->string('from_icao');           // e.g., EDDM 
    $table->string('to_icao', 4);          // Destination airport ICAO

    // Temporal & Operational Data
    $table->dateTime('departure_datetime');
    $table->integer('buffer_time_minutes')  // ± delay tolerance  
    $table->float('estimated_distance_nm');   // nautical miles
    
    // Aircraft Type (FK to aircrafts? or enum type?)
    $table->enum('aircraft_type', ['A320neo', 'B738', 'C172', ...]);
    
    // Status Tracking  
    $table->boolean('active');
    
    // Timestamps  
    $table->timestamps();
});

// Add Unique Constraint after table creation (if needed) or inline in schema:
schema::createConstraint('flight_schedules', [
  'type': 'unique', 
  'column': ['from_icao, to_icao, flight_number']  // Per-route uniqueness!
]);
```

---

### Migrations-Code Draft: Create pilots table

```php
// database/migrations/{time}_create_pilots_table.php  
schema::create('pilots', function(Blueprint($table)): void {    
    $table->id();
    $table->string('email')->unique();    
    $table->string('icao_callsign') // e.g., GOL123 (UNIQUE)  
          ->unique();
          
    $table->integer('home_airport');  // ICAO Code
    $table->integer('skill_level');   // beginner/intermediate/expert
    
    // Aircraft Certifications (optional JSON or separate table?)
    $table->json('aircraft_certified') // ["A320", "B738"]
    
    $table->integer('completed_flights')->default(0);
    $table->float('on_time_rating')->default(100);  // percentage
    
    $table->timestamp('last_active')->nullable();
    $table->timestamps();
});

schema::createIndex('pilots', [
    'column': 'email'       // Index for login lookups  
], ['indexType': 'unique']); 
```

---

### Seeding Initial Routes (Optional Demo Data - KEIN MOCK DATA!)

**Nicht für Testing needed! Nur Admin Setup:**

```php
// database/seeders/InitialRouteSeeder.php (optional Phase 2+)
class InitialRouteSeeder extends Seeder {
    
    public function run(): array {
        // Kein mock data! Nur wenn admin-manuel routes setup nötig?  
        
        DB::table('aircrafts')->insert([
            [
                'registration' => 'D-AIMR',
                'model_type' => 'Airbus A320neo',
                'manufacturer' => 'Airbus',
                'total_seats'   => 168,
                'available'     => true,
                'pilot_certified' => ['GOL123', 'BAW456'], // Example real pilots later?
            ]
        ]);

        DB::table('flight_schedules')->insert([
            [
                'from_icao'    => 'EDDM', // Munich  
                'to_icao'      => 'EGLL', // London
                'departure_datetime' => now()->addDays(2),
                'aircraft_type' => 'A320neo',
                // flight_number generated automatically? Or seed with manual RWH100 first?
                'active'       => true,
            ]
        ]);

    }
}
```

---

## 🔄 Race Condition Prevention (Multi-User Environment!)

### Prevent Simultaneous Flight Number Conflicts

**Strategy 1: Database-Level Atomicity**  
```sql
BEGIN TRANSACTION;  -- Start atomic operation  

SELECT MAX(...) FROM flight_schedules WHERE from_icao = ? AND to_icao = ?;  
INSERT INTO flight_schedules (flight_number, from, to) VALUES (...);  

COMMIT;

// Laravel implementation: Use Database Transaction or optimistic locking?
```

**Strategy 2: Optimistic Locking via Version Column**  
```sql
-- Optional enhancement
$flights[]['created_at'] = $now;
$schedule->lockForUpdate(); // MySQL lock_row() on update  

$flight_number = FlightNumberGenerator::generateNext($from, $to);
Schedule::create([$flight_number, $from_icao, $to_icao]);

// If concurrent inserts? DB throws duplicate_key_error → Catch & retry.  
throw new DuplicateFlightNumberException('RWH102 already exists!');
```

**Strategy 3: Application-Level Retry Loop (recommended for MVP)**

```php
class FlightScheduleController {  
    public function store(CreateScheduleRequest $request): RedirectResponse {
        $flight_number = FlightNumberGenerator::generateNext($from, $to);
        
        // Check uniqueness before insert (prevent race condition)
        $is_duplicate_exists = FlightSchedule::where('flight_number', $flight_number)
            ->where('from_icao', $request->from_icao)
            ->where('to_icao', $request->to_icao) 
            ->exists();

        if ($is_duplicate_exists) {
            // Conflict detected! Retry with incremented number.  
            $flight_number = FlightNumberGenerator::generateNextWithRetry();
            
            // Retry once? Or just fail and notify admin?
        }

        return redirect()->route('schedules.create'); 
    }
}
```

---

## 📝 API-Spec Blueprint (für später REST endpoint)

### Endpoint: Create Flight Schedule with Auto-Generated Number

**POST /api/schedules/create**  
Creates new schedule & auto-generates flight number.

```yaml
Request Body: {
  from_airport_code: string,   // e.g., "EDDM" 
  to_airport_code: string,
  departure_datetime: DateTime,
  aircraft_type: string,  
  active: boolean,
  buffer_minutes: integer
}

Response: {
  "success": true, 
  "message": "Schedule created successfully with automatic flight number",
  data: {
    id: 123,
    from_icao_code: "EDDM",
    to_icao: "EGLL",
    flight_number: "RWH100",  // Auto-generated!  
    departure_datetime: "2024-05-15 14:30...",
    active_schedule: true,
    buffer_time_minutes: 15,
    aircraft_type: "A320neo"
  }, 
}

Error Response (if flight number already exists):
{
  "error": "FlightNumberAlreadyExists",  
  "message": "The flight number RWH101 is already in use on this route.",
  "duplicate_route": {
    "from": "EDDM", 
    "to": "EGLL",    
    "scheduled": "2024-05-09 20:30"
  }  
}
```

---

## ✅ Planung Checkliste - Flight Number Generation

[✅] Auto-generation Logic mit RWH Prefix definiert  
[✅] Unique-Constraints (Route-scoped vs global) entschieden  
[✅] Race Condition Prevention Strategies geplant  
[✅] Strecken-Klassen mit Nummern-Ranges (<800, 801-2500, >2500 NM)  
[✅] API Specification für `/api/schedules/create` dokumentiert  
[ ] Migrations Blueprints für tables & constraints  
[ ] Seeder-Funktion für initial flight_numbers (optional Phase 2?)  

---

## 📌 Nächster Planungs-Schritt?

Soll ich jetzt **Migrations-Dokumentation** für das Datenbank Schema planen?

Oder hast du etwas Spezifisches, was ich als nächstes dokumentieren soll?
