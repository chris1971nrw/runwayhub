# RunwayHub Architektur

## Übersicht

RunwayHub ist eine modulare Virtual Airline Manager Software für PHP 8+.

```
runwayhub/
├── public/              # Web-Root
│   ├── index.php
│   └── assets/
│       ├── css/
│       └── js/
├── src/                 # PHP Code
│   ├── core/           # Core-System
│   │   ├── Database.php
│   │   ├── Request.php
│   │   ├── Response.php
│   │   ├── Router.php
│   │   └── Config.php
│   ├── modules/        # Module
│   │   ├── OpenAIP/
│   │   ├── Demo/
│   │   └── ...
│   ├── api/            # API Endpoints
│   ├── cli/            # CLI Commands
│   └── models/         # Entity-Klassen
├── demo/               # Demo-System
│   ├── agents/        # Autonome Agenten
│   ├── scripts/       # Automatisierung
│   └── deployment/    # Deployment-Docs
├── i18n/              # Internationalisierung
│   ├── de/
│   └── en/
├── tests/             # PHPUnit Tests
├── docs/              # Dokumentation
├── database/          # Migrationen
└── config/            # Konfiguration
```

## Architekturprinzipien

### 1. Modularität

- Jede Funktion als eigenständiges Modul
- Module sind austauschbar
- Module kommunizieren über definierte Interfaces

### 2. Trennung der Besorgnisse

```
┌─────────────────────────────────────────────────┐
│                  Presentation Layer              │
│              (HTML, CSS, JavaScript)            │
└─────────────────────────────────────────────────┘
                        ↓
┌─────────────────────────────────────────────────┐
│                  API Layer                       │
│            (REST-Endpunkte, JSON)               │
└─────────────────────────────────────────────────┘
                        ↓
┌─────────────────────────────────────────────────┐
│                    Service Layer                 │
│              (Business Logic, Services)         │
└─────────────────────────────────────────────────┘
                        ↓
┌─────────────────────────────────────────────────┐
│                    Repository Layer              │
│              (Data Access, Queries)             │
└─────────────────────────────────────────────────┘
                        ↓
┌─────────────────────────────────────────────────┐
│                    Database Layer                │
│              (MySQL/MariaDB)                    │
└─────────────────────────────────────────────────┘
```

### 3. Dependency Injection

```php
// Beispiel: Dependency Injection
class BookingService
{
    public function __construct(
        private Database $database,
        private BookingRepository $bookingRepo,
        private PirepRepository $pirepRepo,
    ) {
    }
}
```

### 4. Datenfluss

```
┌──────────┐     ┌──────────┐     ┌──────────┐     ┌──────────┐
│  Client  │────▶│  Router  │────▶│   API    │────▶│  Service  │
└──────────┘     └──────────┘     └──────────┘     └──────────┘
                                                │
                                                │ Response
                                                │
                                                ▼
┌──────────┐     ┌──────────┐     ┌──────────┐     ┌──────────┐
│   API    │◀────│  Service │◀────│Repository │◀────│ Database │
└──────────┘     └──────────┘     └──────────┘     └──────────┘
```

### 5. Security

- SQL Injection Prevention (Prepared Statements)
- XSS Protection (htmlspecialchars)
- CSRF Token (Session-basiert)
- API-Key Management
- SSL/TLS Verbindungen
- Input Validation

### 6. Internationalisierung

```php
// Internationalisierung
__('flights_total', ['count' => $flightsCount]);
__('welcome_message', ['airline' => config('airline.name')]);
```

## Datenbank-Architektur

### Schema Design

```sql
-- Airlines
CREATE TABLE airlines (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    iata_code VARCHAR(3) UNIQUE,
    icao_code VARCHAR(4) UNIQUE,
    founded_year YEAR
);

-- Fluggesellschaften
CREATE TABLE airlines (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    website VARCHAR(255),
    logo_url VARCHAR(255)
);

-- Flughäfen
CREATE TABLE airports (
    id INT PRIMARY KEY AUTO_INCREMENT,
    iata_code VARCHAR(5) UNIQUE NOT NULL,
    icao_code VARCHAR(5) UNIQUE,
    name VARCHAR(255),
    city VARCHAR(100),
    country VARCHAR(100),
    latitude DECIMAL(9,6),
    longitude DECIMAL(9,6),
    timezone VARCHAR(50)
);

-- Flugzeuge
CREATE TABLE fleet (
    id INT PRIMARY KEY AUTO_INCREMENT,
    registration VARCHAR(10) UNIQUE NOT NULL,
    aircraft_type VARCHAR(100),
    manufacturer VARCHAR(100),
    model VARCHAR(100),
    serial_number VARCHAR(50),
    status ENUM('operational', 'maintenance', 'retired'),
    purchase_date DATE,
    maintenance_until DATETIME,
    last_maintenance_at DATETIME
);

-- Flüge
CREATE TABLE flights (
    id INT PRIMARY KEY AUTO_INCREMENT,
    flight_number VARCHAR(10) NOT NULL,
    origin_id INT NOT NULL,
    destination_id INT NOT NULL,
    scheduled_departure DATETIME,
    scheduled_arrival DATETIME,
    actual_departure DATETIME,
    actual_arrival DATETIME,
    status ENUM('scheduled', 'boarding', 'in_air', 'arrived', 'cancelled'),
    aircraft_id INT,
    FOREIGN KEY (origin_id) REFERENCES airports(id),
    FOREIGN KEY (destination_id) REFERENCES airports(id),
    FOREIGN KEY (aircraft_id) REFERENCES fleet(id)
);

-- PIREPs
CREATE TABLE pireps (
    id INT PRIMARY KEY AUTO_INCREMENT,
    flight_id INT NOT NULL,
    altitude INT,
    wind_direction INT,
    wind_speed INT,
    temperature_c INT,
    visibility_km DECIMAL(5,2),
    weather_condition VARCHAR(50),
    remarks TEXT,
    report_time DATETIME,
    FOREIGN KEY (flight_id) REFERENCES flights(id)
);

-- Buchungen
CREATE TABLE bookings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    flight_id INT NOT NULL,
    passenger_name VARCHAR(255),
    passenger_email VARCHAR(255),
    passenger_type ENUM('business', 'economy', 'premium_economy'),
    price DECIMAL(10,2),
    booking_code VARCHAR(10) UNIQUE,
    status ENUM('confirmed', 'cancelled', 'pending'),
    booking_time DATETIME,
    FOREIGN KEY (flight_id) REFERENCES flights(id)
);
```

## API-Architektur

### REST Endpoints

```
GET  /api/v1/flights              - Alle Flüge
GET  /api/v1/flights/{id}         - Einzelner Flug
GET  /api/v1/airports             - Alle Flughäfen
GET  /api/v1/airports/{id}        - Einzelner Flughafen
GET  /api/v1/pireps               - Alle PIREPs
GET  /api/v1/pireps/{id}          - Einzelner PIREP
GET  /api/v1/bookings             - Alle Buchungen
GET  /api/v1/bookings/{id}        - Einzelne Buchung
GET  /api/v1/fleet                - Flotte
GET  /api/v1/fleet/{id}           - Einzelnes Flugzeug
GET  /api/v1/statistics           - Statistiken
GET  /api/v1/status               - System-Status
```

### API Response Format

```json
{
    "success": true,
    "data": {
        "id": 1,
        "flight_number": "DH001",
        "origin": "FRA",
        "destination": "MUC"
    },
    "meta": {
        "timestamp": "2026-05-26T16:46:00+02:00",
        "version": "1.0.0"
    }
}
```

### Error Response

```json
{
    "success": false,
    "error": {
        "code": 404,
        "message": "Ressource nicht gefunden",
        "details": "Flug mit ID=9999 nicht gefunden"
    },
    "meta": {
        "timestamp": "2026-05-26T16:46:00+02:00",
        "version": "1.0.0"
    }
}
```

## Demo-Architektur

### Autonomous Agents

```
┌─────────────────────────────────────────────────┐
│              Demo System Orchestrator           │
│              (autonomous_demo.sh)               │
└─────────────────────────────────────────────────┘
         │              │              │
         ▼              ▼              ▼
┌─────────────────┐ ┌─────────────────┐ ┌─────────────────┐
│  FlightAutomator│ │   PIREPGenerator │ │ BookingGenerator│
└─────────────────┘ └─────────────────┘ └─────────────────┘
         │              │              │
         ▼              ▼              ▼
┌─────────────────┐ ┌─────────────────┐ ┌─────────────────┐
│  FleetUpdater   │ │     IssuesWatch │ │ DemoSystemSync  │
└─────────────────┘ └─────────────────┘ └─────────────────┘
         │              │              │
         ▼              ▼              ▼
┌─────────────────────────────────────────────────┐
│              Database (Isolated)                 │
└─────────────────────────────────────────────────┘
```

### Feature Flags

```bash
# Demo-Modus
DEMO_MODE=true
FLIGHT_AUTOMATION_ENABLED=true
PIREP_SIMULATION_ENABLED=true

# Production-Modus
DEMO_MODE=false
FLIGHT_AUTOMATION_ENABLED=false
PIREP_SIMULATION_ENABLED=false
```

### Rollback Strategie

```bash
# Feature-Flag setzen
DEMO_MODE=false

# Produktion aktivieren
# Fehler debuggen
DEMO_MODE=true
```

## Next Steps

1. **API Endpoints** - Vollständige REST-API implementieren
2. **Frontend-Views** - Admin, Staff, Pilot, Guest Views
3. **Tests** - PHPUnit-Tests für alle Module
4. **Monitoring** - APM, Logging, Metrics
5. **Security** - Security Audit, Penetration Testing
