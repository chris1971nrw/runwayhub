# runwayhub - Flight Simulator Virtual Airline 🛫

## 🎯 Use-Case & Zielsetzung

### Core Purpose
**Fluggesellschaft-Virtual-Community** für Piloten, die mit MSFS und X-Plane fliegen  
→ Schedule-Ausführungsverfolgung + Crew-Bereitstellungen + Community Features  

---

## 👥 User Roles Definition

| Rolle | Zweck | Berechtigungen |
|-------|-------|----------------|
| **pilot** | Flieger im Simulator | Eigene Logs hochladen, Schedules einsehen, Crew finden |  
| **crew** | Flugbegleiter (virtuell) | Passenger-Logs hochladen, Safety Reports |  
| **ground_control** | Dispatch (Admin) | Schedule erstellen, Piloten zuweisen, Route prüfen |  
| **admin** | System Admin | Benutzer löschen, Logs auditieren, Maintenance Mode |

---

## 📊 Datenmodell Blueprint

### Core Entities & Beziehungen

```mermaid
erDiagram
    pilot ||--o{ flight_log : writes
    pilot ||--o{ crew_log : submits
    ground_control ||--o| flight_schedule : assigns
    
    flight_schedule {
        int id PK
        string callsign
        date departure_date
        string from_airport ICAO (EGKK/LHBB)
        string to_airport ICAO 
        datetime estimated_off
        datetime estimated_arrival
        aircraft_type (A320/B738)
        distance_nm
        active boolean
    }
    
    flight_log {
        int id PK
        int user_id FK
        int schedule_id FK
        string actual_from_airport
        string actual_to_airport
        datetime actual_off
        datetime actual_arrival
        string comments
        string screenshots (optional)
        boolean completed
    }
    
    crew_log {
        int id PK
        int flight_log_id FK
        user_id FK
        string passenger_count
        string service_rating
        string incident_report
    }
    
    aircraft {
        int id PK
        string registration (D-AIMR)
        string model_type
        int total_seats
        int total_crew_required
        maintenance_status
        boolean available
    }
```

### Entity Details

| Entity | Felder | Beschreibung |
|--------|---------|--------------|  
| **pilots** | id, email, name, home_base, skill_level, license_type, rating, aircraft_certified[], created_at, last_active | Virtuelle Piloten-Profile |
| **crew** | id, user_id FK (nullable), callsign, experience_hours, last_check | Crew-Mitglieder |  
| **ground_control** | internal only, keine public View/Controller nötig | Dispatch-Funktionen |

---

## ✈️ Flight Schedule Features

### Schedule-Ausführungs-Tracking
- ✅ Estimated vs Actual Off/Takeoff Times
- ✅ Route Changes (ICAO Codes: EGKK → LHBB)  
- ✅ Aircraft Loadout (passenger count, fuel calculation)  
- ✅ Comments & Safety Notes per Log

### Community Features (Phase 2)
- 📸 Flight Log Screenshots (MSFS/X-Plane Screenshot Upload)  
- 💬 Discussion Threads pro Schedule  
- ⭐ Pilot Reviews nach Flugcompleted  
- 🏆 Leaderboard (Best Completion % this month)

---

## 🔐 Auth & Security Design

### Authentifizierung
```
┌─────────────────┐
│ Landing Page     │
│ "Login / Sign Up"│
└──────┬───────────┘
       │
    ┌───▼────┐
    │ Session │ (Laravel Session Auth - einfach)
    │ Based   │
    └─────────┘
       
```

### Autorisierungs-Matrix (Policy als Policies in app/Policies/)
- **pilot**: CanSeeOwnSchedule, CanUploadLogs, CanRequestCrew
- **crew**: CanSubmitFlightLogs, CanViewAssignedOnly  
- **ground_control**: Full CRUD except delete, CannotAccessUserPrivateData

### Sessions & Refresh Tokens
```env
SESSION_SECURE=true
SESSION_LIFETIME=1209600  # 14 days for pilots
REFRESH_TOKEN_TTL=7d      # für SPA-Frontends später
```

---

## 🗀 Frontend: Blade Templates vs Alpine.js

### Empfehlung: Alpine.js + Inertia.js (SPA feel ohne Separate Router)

**Warum?**
- ✅ Single Page App feeling für flutende Logs
- ✅ Responsive UI ohne Reload  
- ✅ Charts pro Schedule (Chart.js integration)
- ✅ Simpler than React/Vue für Laravel-Veterane
- ✅ Kein Babel/Webpack Overhead nötig

### Views-Architektur (SPA Layouts mit Blade als Backend)

```
resources/views/
├── layouts/
│   ├── app.blade.php          # Main App Shell
│   └── auth.blade.php         # Login/Register Forms  
├── pilots/
│   ├── dashboard.blade.php    # Overview (Burndown Charts?)
│   ├── flights/               # Liste meiner assigned Schedules
│   │   ├── index.blade.php
│   │   └── show.blade.php     # Flight Detail + Log Upload Form
│   └── logs/                  # Eigene Logs & Community Discussion
├── ground_control/
│   ├── schedules/             # Dispatch Dashboard
│   │   ├── new_schedule.blade.php
│   │   └── assign_logs.blade.php
│   └── aircraft_assignments/  # Flugzeuge zu Schedule Map
```

### Charts & Visualisierung
- **Chart.js** für Performance-Metriken (Completion Rate, On-Time % per Pilot)  
- **ApexCharts** alternative wenn mehr interaktiv (Gantt Diagramm für Flugschedule)?  

---

## 🛠️ Tech Stack Summary (Final)

```yaml
Framework: Laravel 10.x (LTS)
PHP: >=8.1 (Standard Ubuntu App Image includes)
Database: MySQL 5.7+ or MariaDB (Umbrel Default)  
Cache: File / Redis optional (Sessions, ETags caching später)
Queue: Horizon/Supervisor (Flight Log Processing? Async?)

Frontend: 
- Alpine.js 3.x (Forms Validation, Charts, Tabs - keine heavy libs)
- Chart.js oder ApexCharts für Analytics  
- Tailwind CSS via CDN (kein Build-step nötig) oder Blade Components  

Assets:
- Vite optional für SPA Build (falls Alpine.js + Inertia gewählt)
- CDN Libraries: Alpine/Chart/Tailwind

Deployment:
- Standard-Umbrel PHP Docker Image mit MySQL Container  
- Public `/public` Webroot mounten (Nginx/Apache Proxy behind Umbrel App)
```

---

## 📋 Migrations & Seeddaten

### Phase 1: Initial Schema (Migrations zu schreiben später)
1. `create_pilots_table()` - Piloten-Daten mit ICAO-Codes  
2. `create_flight_schedules_table()` - Schedules mit ICAO Routes  
3. `create_flight_logs_table()` - Execution Logs  
4. `create_aircrafts_table()` - Fahrzeug-Registriernummer, Typ  
5. `create_crew_logs_table()` - Safety Reports  

### Seed-Funktionen (initial Admin Setup)
```php
DB::table('pilots')->insert([
    ['id' => 1, 'callsign' => 'ALPHA 1', 'role' => 'ground_control'],
    // Weitere Testaccounts? Oder erst nach User-Registrierung?
]);
```

---

## 🗺️ Roadmap (ohne Code-Erstellung)

### Sprint 1: MVP Foundation (Week 1)
- ✅ Laravel Skeleton, Eloquent Models  
- ✅ Migration-Framework für Core Tables  
- ✅ Basic Auth + Session Management  
- ✅ Landing Page + Piloten-Self-Registration (Form Validation)  

### Sprint 2: Schedule & Dispatching (Week 2-3)  
- ✅ Ground Control Dashboard (Schedule creation, Assignment)  
- ✅ Pilot Dashboard (Own assigned schedule list view)  
- ✅ Flight Log Upload Form + Storage Path configuration (`storage/app/flights`)

### Sprint 3: Community Features (Week 4+)
- 📸 Fileupload Screenshots/Video Clips  
- 💬 Discussion Threads pro Route  
- ⭐ Reviews & Leaderboard Charts  

### Future Phases (Post-MVP)
- 🌐 WebSocket Notifications (Push to Chat? Slack Integration?)  
- 📊 Advanced Analytics per Piloten Performance  
- ✈️ 3D Route Viewer (Mapbox/Leaflet mit ICAO Koordinaten?)  

---

## ✅ Checkliste: Planung abgeschlossen

**Wenn ja, dann Code-Erstellung im nächsten Schritt:**
- [ ] User Roles & Permissions definiert ✅
- [ ] Core Entities skizziert ✅  
- [ ] Frontend-Architektur entschieden (Alpine.js + Inertia) ✅
- [ ] Migrationen strukturplan ✅
- [ ] Auth-Flow klar (Session Auth, 14 days TTL) ✅  

---

**Status:** Use-Case geklärt! Nächster Schritt: Code-Erstellung der Laravel Skeleton mit diesen Entitäten & Models? 🛫
