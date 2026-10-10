# runwayhub 🌉

## Flight Simulator Virtual Airline Community

Eine Web-Plattform für MSFS und X-Plane Piloten zur Flugplanung, Schedule-Ausführungsverfolgung und Crew-Bereitstellungen.

---

## 🎯 Zielsetzung

**runwayhub** ist eine Virtual Airline Community für Flugsimulator-Piloten mit folgenden Features:

- ✅ **Schedule Management** — Flugpläne erstellen & verwalten
- ✅ **Flight Tracking** — Flugprotokolle hochladen & tracken
- ✅ **ACARS Integration** — MSFS/X-Plane Log Parsing
- ✅ **Community Features** — Reviews, Leaderboards, Weather Widget
- ✅ **Admin Dashboard** — Finanzberichte & Fleet Management

---

## 🛠️ Tech Stack

```yaml
Backend: Laravel 10.x
PHP: >=8.1
Database: MySQL/MariaDB
Frontend: Alpine.js + Blade Templates
Charts: Chart.js
Weather API: Open-Meteo
```

---

## 📋 Voraussetzungen

- PHP 8.1+ mit folgenden Extensions:
  - `mbstring`, `xml`, `zip`, `gd`, `curl`
  - `pdo_mysql`, `sqlite3` (für Testing)
- Composer
- MySQL/MariaDB oder SQLite (Development)
- Node.js 18+ (optional, für Vite Builds)

---

## 🚀 Installation

### 1. Projekt Clone & Setup

```bash
cd /data/.openclaw/workspace/runwayhub
composer install
cp .env.example .env
php artisan key:generate
```

### 2. Datenbank Konfiguration

Editiere `.env` mit deinen DB-Credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=runwayhub_db
DB_USERNAME=root
DB_PASSWORD=dein_passwort
```

### 3. Migrations ausführen

```bash
php artisan migrate --seed
```

### 4. Server starten (Development)

```bash
php artisan serve
```

Besuche `http://localhost:8000` im Browser.

---

## 📁 Projektstruktur

```
runwayhub/
├── app/
│   ├── Console/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/
│   │   │   │   └── FlightBookingController.php
│   │   │   ├── AdminController.php
│   │   │   ├── GroundControlController.php
│   │   │   └── UserReportController.php
│   │   └── Controllers/
│   ├── Jobs/
│   │   └── ProcessFlightLogJob.php
│   └── Models/
│       ├── Pilot.php
│       ├── FlightSchedule.php
│       ├── FlightLog.php
│       └── Aircraft.php
├── database/
│   ├── factories/
│   ├── migrations/
│   │   ├── 2026_10_10_000000_create_pilots_table.php
│   │   ├── 2026_10_10_000001_create_flight_schedules_table.php
│   │   ├── 2026_10_10_000002_create_flight_logs_table.php
│   │   └── 2026_10_10_000003_create_aircrafts_table.php
│   └── seeders/
│       └── DatabaseSeeder.php
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   ├── pilots/
│   │   ├── ground_control/
│   │   ├── admin/
│   │   └── auth/
│   └── components/
│       └── weather-widget.blade.php
├── routes/
│   └── web.php
├── tests/
│   └── Feature/
│       └── FlightBookingTest.php
├── .env.example
├── .gitignore
├── composer.json
└── README.md
```

---

## 🎨 Features

### Pilot Dashboard
- Zugeteigte Flüge Übersicht
- Wetter Widget (Open-Meteo API)
- Flight Statistics & On-Time Performance
- Community Reviews ⭐

### Dispatch Dashboard (Ground Control)
- Flugpläne erstellen & verwalten
- Piloten-Zuweisung mit Aircraft Certification Checks
- Aktivierung/Deaktivierung von Schedules
- Bulk Assignment für mehrere Flüge

### Admin Dashboard
- Finanzberichte (Revenue vs Cost Charts)
- Break-even Analyse (LBA §15 Compliance)
- User Management
- Fleet Management

### ACARS Integration
- MSFS/X-Plane Log Parsing
- Telemetry Data Extraction
- Screenshot Upload Processing
- Laravel Queue Worker für asynchrone Verarbeitung

---

## 🔐 Sicherheit

- **Session-based Authentication** (kein JWT, kein OAuth2 im MVP)
- **Role-based Authorization**: pilot, crew, ground_control, admin
- **Admin-only Update Policy** via GitHub CI/CD
- **Password Hashing** mit Bcrypt
- **CSRF Protection** in allen Forms

---

## 🧪 Testing

```bash
# PHPUnit Tests ausführen
php artisan test

# Coverage Report (optional)
composer install --dev
phpunit --coverage-html=coverage
```

---

## 📊 Roadmap

### Phase 1: MVP ✅ (Abgeschlossen)
- [x] Laravel Skeleton + Migrations
- [x] Eloquent Models mit Relationships
- [x] UI Templates (Pilot, Admin, Ground Control)
- [x] Flight Booking Controller mit 24h Time-Lock
- [x] ACARS Queue Worker Job
- [x] GitHub CI/CD Setup
- [x] PHPUnit Tests

### Phase 2: Community Features (Zukünftig)
- [ ] Screenshot Upload & Gallery
- [ ] Discussion Threads pro Route
- [ ] Leaderboard mit Performance Stats
- [ ] OAuth2 Integration (Google/GitHub Login)
- [ ] WebSocket Notifications

### Phase 3: Advanced Analytics (Post-MVP)
- [ ] Cargo Freight System
- [ ] Route Optimization Engine
- [ ] 3D Route Viewer (Mapbox/Leaflet)
- [ ] Advanced Weather Forecasts

---

## 🤝 Contributing

1. Fork das Repository
2. Erstelle einen Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit deine Änderungen (`git commit -m 'Add some AmazingFeature'`)
4. Push zum Branch (`git push origin feature/AmazingFeature`)
5. Öffne einen Pull Request

---

## 📄 Lizenz

Dieses Projekt steht unter der MIT License. Siehe die [LICENSE](LICENSE) Datei für Details.

---

## 🙏 Credits

- **Laravel** — PHP Web Framework
- **Alpine.js** — Lightweight JavaScript Framework
- **Chart.js** — Charting Library
- **Open-Meteo API** — Wetterdaten

---

## 📞 Support

Bei Fragen oder Problemen:
- Erstelle einen [GitHub Issue](https://github.com/chris1971nrw/runwayhub/issues)
- Oder kontaktiere den Maintainer direkt

---

**🛫 Happy Flying! — runwayhub Team**

<!-- project: github.com/chris1971nrw/runwayhub -->
# runwayhub
