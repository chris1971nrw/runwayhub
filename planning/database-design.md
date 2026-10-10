# runwayhub - Flight Simulator Virtual Airline

## 📊 Datenbank-Design (Migrations Blueprint)

### Tabelle: `pilots` (User & Profil-Daten)
```sql
// Migration Blueprint
create_table('pilots') {
    id           : integer      // Primary Key auto_increment
    email        : string       // UNIQUE, not null  
    password     : string       // hashed (bcrypt)
    name         : string       // Vorname/Nachname
    icao_callsign: string       //呼号 "GOL 123", UNIQUE
    
    // Flugzeugqualifikationen
    aircraft_certified:         // JSON Array oder relational separate table? 
        varchar                // ["A320", "B738", "C172"]
                
    // Base & Skills  
    home_airport : string       // ICAO Code (EDDM, EGLL)
    skill_level  : integer      // int(1): Beginner, (2): Intermediate, (3): Expert
    
    // Status Tracking
    last_active   : timestamp
    completed_flights: integer  // total log count
    on_time_rating: decimal     // avg on-time performance %
    
    // Timestamps  
    created_at    : timestamp
    updated_at    : timestamp
}

index('email'), index('icao_callsign'), index('home_airport')
```

### Tabelle: `flight_schedules` (Dispatch-System)
```sql
create_table('flight_schedules') {
    id                       : integer          // PK
    callsign                 : string           // "VH100", "GOL452"
    
    // Routing  
    from_airport             : string           // ICAO (EDDM)
    to_airport               : string           // ICAO (EGLL) 
    distance_nm              : integer          // nautical miles calculation
    fuel_required_liters     : integer
    
    // Aircraft Assignment  
    aircraft_registration    : string           // FK zu aircrafts? Optional
    aircraft_type            : string           // "A320", "B738"
    
    // Temporal Data
    departure_datetime       : datetime         // planned
    arrival_datetime         : datetime         // estimated  + offset
    
    // Flight Status  
    active                   : boolean          // true = pending execution, false=canceled/completed
    weather_conditions       : string           // JSON {"type":"Turbulence","severity":"moderate"}
    
    // Timing Buffers  
    buffer_time_minutes      : integer          // ± delay tolerance
}

index('from_airport', 'to_airport')  // Routing efficiency tracking  
```

### Tabelle: `flight_logs` (Execution Tracking)
```sql
create_table('flight_logs') {
    id                       : integer          // PK, auto_increment
    schedule_id              : integer          // FK flight_schedules
    
    // Piloten Attribution
    pilot_id                 : integer          // FK pilots
    
    // Actual Times vs Estimated
    estimated_departure      : datetime         // from schedules table  
    actual_off_time          : datetime         // real takeoff timestamp?
    actual_arrival           : datetime         // real landing timestamp?
    
    // Performance Metrics
    completed                : boolean          // Flug abgeschlossen?
    on_time_status           : enum('early','on-time','late','canceled')?
    delay_cause              : string           // technische Gründe, Wetter etc.
    
    // Community Content
    screenshots_1            : string           // Path zu storage/app/flights/
    screenshots_2            : string           // optional multiple uploads (json)
    comments                 : text             // Pilot's notes
    
    created_at               : timestamp
} 

index('pilot_id'), index('schedule_id', 'completed')  // Reporting Analytics
```

### Tabelle: `aircrafts` (Flugzeug-Inventar)
```sql  
create_table('aircrafts') {
    id                             : integer      // PK
    registration                   : string       // "D-AIMR", UNIQUE, not null 
    model_type                     : string       // "Airbus A320neo"
    manufacturer                   : string       // "Airbus/Boeing/Cessna"
    
    // Capacity  
    total_seats                    : integer      // passenger capacity  
    flight_hours_total             : decimal(8,1) // maintenance tracking
    
    // Status & Qualifications  
    available                      : boolean      // true=false (maintenance?)  
    pilot_certified                : string json  // ["GOL123", "BAW456"] 
    required_crew_capacity         : integer      // minimum crew members needed
    
    created_at                     : timestamp
}
```

### Tabelle: `crew_logs` (Passenger Logs + Safety Reporting)
```sql
create_table('crew_logs') {
    id                       : integer          // PK, auto_increment  
    flight_log_id            : integer          // FK zu flight_logs
    
    // Log Data
    passenger_count          : integer          // how many passengers counted?
    service_rating           : string           // "Exemplary", "Good" etc.
    
    // Incidents Reporting  
    incident_report          : text             // null = no incidents
    safety_recommendations   : text             // optional
    
  created_at                 : timestamp
}

index('flight_log_id')
```

---

## 🗺️ Routen-Konzept (routes/web.php Blueprint only)

### Public Facing Routes:
```php
use App\Http\Controllers\Auth\LoginController;
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/forgot-password', [...])->name('password.request');

// Register Pilot (Self-Service)
Route::get('/register', [...])->middleware('guest');
Route::post('/register', [...]);
```

### Piloten Portal:
```php
Route::middleware(['auth'])->group(function(){
    // Dashboard Overview  
    Route::get('/dashboard', [PilotDashboardController::class, 'index']);
    
    // Assigned Schedules Liste (meine Flüge) 
    Route::get('/flights/assigned', [PilotFlightsController::class, 'assigned']);
    Route::get('/flights/{schedule}', [ScheduleDetailController::class, 'show']);
    Route::post('/flights/{schedule}/log-upload', [FlightLogUploadController::class, 'store']);
    
    // User Logs & Community Discussion  
    Route::get('/logs', [MyLogsController::class, 'index']);
    Route::get('/logs/{id}', [...])->name('my-log.show');
    Route::delete('/logs/{id}', [...])->name('my-log.delete');
});
```

### Ground Control Dispatch:
```php 
Route::middleware(['auth', 'permission:dispatch'])->group(function(){
    // Schedule Creation  
    Route::get('/schedules/new', [NewScheduleController::class, 'showForm']);
    Route::post('/schedules', [NewScheduleController::class, 'store']);
    
    // Assignment Management  
    Route::get('/schedules/{id}/assign-pilot', [...]);
    Route::put('/schedules/{schedule}/pilot/{pilot}', [...]);
});
```

---

## 📝 Wireframes (Text-basiert)

### Landing Page:
```
┌─────────────────────────────────────┐
│  ✈️ runwayhub - Flight Sim Airline   │  
│                                      │
│  [Home Login]                        │ 
│  Sign up → Free Virtual Pilot        │  
└─────────────────────────────────────┘
```

### Pilot Dashboard:
```
┌─────────────────────────────────────┐
│ Dashboard                            │  
│ ┌─ Schedule Assignment ─┐           │  
│ │ Flight VH100          │ ✅ Pending│
│ │ EDDM → EGLL           │ 2h30pm    │  
│ └───────────────────────┘           │
│ ┌─ Completed Flights ──┐            │ 
│ │ On-Time: 94%          │ 📊 charts │  
│ │ Total: 47             │            │  
│ └───────────────────────┘            │
│ [Log Flight Upload]                  │
└─────────────────────────────────────┘
```

### Ground Control Schedule Management:
```
┌─────────────────────────────────────┐
│ ✈️ Dispatch Dashboard - Ground       │  
│                                      │  
│  New Schedule                        │  
│  From: [EDDM] To: [EGLL]            │  
│  Departures: [14:30] Aircraft: [A320]| 
│                                      │  
│  Assigned Pilot:                   │  
│  ┌─────────────────┐                │ 
│  | GOL 123 (Beginner)  | ✅ Selected   │
│  └─────────────────┘                │  
│                                      │  
│  [Create Schedule]                  │  
└─────────────────────────────────────┘
```

---

## 🔐 Middleware & Policies Blueprint  

### Auth Flow:
```plaintext
Guest → Login Form → Session Created → Redirect→ Dashboard  
Logged out → /login → "Welcome back" → Session restored (if cookie valid)
```

### Authorization Policies (app/Policies/):
```php
// pilot.php
class PilotPolicy implements PolicyContract {
    public function viewFlightSchedule(User $user, FlightSchedule $schedule): bool {
        return $user->role === 'pilot' && $user->flight_logs->contains($schedule->id);
    }
    
    // ... mehr Policies für CRUD Operations
}

// ground_control.php
class GroundControlPolicy implements PolicyContract {
    public function manageSchedule(User $user, FlightSchedule $schedule): bool {
        return in_array($user->role, ['admin', 'ground_control']);
    }
}
```

### Middleware (app/Http/Middleware/):
- `Authenticate` – Standard Laravel Session Check  
- `PilotOnly` – Nur für Piloten sichtbaren Views  
- `DispatchRole` – Grundkontrollzugriff nur  
- `LogUpload` – Only authenticated users mit Flugzeugqualifikation  

---

## 📦 Asset Management & File Uploads

### Storage Configuration:
```env
STORAGE_FLIGHT_LOGS=storage/app/flights/screenshots/
MAX_FILE_SIZE=4096   // 4MB screenshots  
ALLOWED_EXTENSIONS=["jpg","png","webp"]
```

### File Upload Flow:
1. Pilot wählt Schedule → [Choose Screenshot] Button erscheint  
2. `FlightLogUploadController@store` verarbeitet Datei  
3. Speichert als unique hash in `storage/app/flights/{user_id}/{log_id}/`  
4. Datenbank speichert relative Path (nicht absolute URL)  

---

## 🧩 Blade Templates Struktur Planung

### Layouts:
```resources/views/layouts/
├── app.blade.php         # Main App Shell mit Alpine.js init
└── auth.blade.php        # Login/register forms only
```

### Pilot Views:
```resources/views/pilots/
├── dashboard.blade.php   // Schedule + Stats Overview  
│   ┌─────────┐            │  
│   │ Charts  │            │ ← Alpine Tabs für Sections  
│   └─────────┘            │
├── assigned_schedules    │  
└── flight_logs           │  
```

### Ground Control Views:
```resources/views/ground_control/
├── schedules_new.blade.php     // Schedule creation form  
├── assignment_manage.blade.php // Piloten zuweisen  
└── aircraft_assignments.blade.php  // Flugzeuge tracken  
```

---

## 📈 Analytics & Reporting (Phase 2 Planning)

### Dashboard Metrics:
```yaml
Pilot Performance Charts:
- Completion Rate by Month (Chart.js Line Chart)
- On-Time Percentage per Aircraft (Pie Chart)  
- Average Delay Duration (Bar Chart)  

Ground Control Analytics:  
- Schedule Completion vs Cancelled Ratio  
- Weather Impact analysis  
- Airport efficiency heatmaps  
```

---

## 🧹 Cleanup & Maintenance Tasks

### Background Queue Processing (optional Phase 3?):
```
Laravel Queues für asynchrone Jobs:
- FlightStatsAnalyticsJob → nightly batch processing?  
- PilotBadgesAwardedJob → celebrate Milestone after 50 flights  
- AircraftStatusSyncJob → maintenance notifications
```

---

## ✅ Planungsabschnitt abgeschlossen

### Nächste Phase (nur weiter planen):
[ ] Schema Migrations vollständig dokumentieren (mit Beispiel-Daten)  
[ ] Seeder-Dateien strukturieren (Testdaten für MVP Demo?)  
[ ] API Endpoints planen? (falls später REST Integration nötig)  

Soll ich jetzt die **Schema-Migrationen als Doc** zusammenfassen, bevor der Code kommt? 🛫
