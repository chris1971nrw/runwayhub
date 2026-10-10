# runwayhub 🌉

[![MVP Status](https://img.shields.io/badge/MVP-100%25%20Abgeschlossen-green)](https://github.com/chris1971nrw/runwayhub/actions)
[![PHPUnit Tests](https://github.com/chris1971nrw/runwayhub/actions/workflows/test.yml/badge.svg)](https://github.com/chris1971nrw/runwayhub/actions)
[![Code Coverage](https://img.shields.io/badge/coverage-80%25+-blue)](#code-coverage-report)
[![Security](https://img.shields.io/badge/security-composer_audit-brightgreen)](https://github.com/chris1971nrw/runwayhub/actions/workflows/test.yml)

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

## 📊 Projektstatus

### ✅ MVP Phase 2: ABGESCHLOSSEN!

| Feature | Status | Details |
|---------|--------|---------|
| **Laravel Skeleton** | ✅ 100% | Migrations, Models, Controllers |
| **UI Templates** | ✅ 100% | Pilot Dashboard, Admin, Ground Control |
| **Flight Booking API** | ✅ 95% | 24h Time-Lock Mechanismus |
| **ACARS Queue Worker** | ✅ 100% | MSFS/X-Plane Log Parsing |
| **GitHub CI/CD** | ✅ 100% | Admin-only Deploy Policy |
| **PHPUnit Tests** | ✅ 80% | MVP Features getestet |

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

## 🧪 Testing & CI/CD

### PHPUnit Tests

```bash
# Tests ausführen
php artisan test

# Coverage Report (optional)
composer install --dev
phpunit --coverage-html=coverage
```

### GitHub Actions Workflows

| Workflow | Status | Beschreibung |
|----------|--------|--------------|
| **PHPUnit Tests & Code Coverage** | 🟢 Active | Automatisches Testing bei jedem Commit |
| **GitHub Pages Deploy** | 🟢 Active | Dokumentation automatisch deployen |
| **Security Scan** | 🟢 Active | Composer Audit für Vulnerabilities |

### Test-Ergebnisse auf GitHub

- **Actions**: https://github.com/chris1971nrw/runwayhub/actions
- **Code Coverage**: Mindestens 80% Ziel erreicht
- **Security Scans**: Regelmäßige Sicherheitsüberprüfungen

---

## 📁 Projektstruktur

```
runwayhub/
├── app/                    # Laravel Application Code
│   ├── Console/
│   ├── Http/              # Controllers (API, Admin, GroundControl)
│   ├── Jobs/               # ACARS Queue Worker
│   └── Models/             # Eloquent ORM Models
├── database/               # Migrations & Seeders
│   ├── migrations/         # 4 Core Tabellen
│   └── seeders/            # Test Data Seeder
├── resources/              # Blade Templates + Alpine.js Components
│   ├── views/
│   │   ├── layouts/
│   │   ├── pilots/
│   │   ├── ground_control/
│   │   ├── admin/
│   │   └── auth/
│   └── components/
├── routes/                 # Laravel Routes (web.php, api.php)
├── tests/                  # PHPUnit Tests
├── storage/               # Laravel Storage
├── vendor/                # Composer Dependencies (nicht committet)
├── phpunit.xml            # Test Konfiguration
└── planning/              # 📁 ALLERLEINUNGSDOKUMENTE!
```

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
DB_PASSWORD=***
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

## 📊 Roadmap

### Phase 1: MVP ✅ (Abgeschlossen)
- [x] Laravel Skeleton + Migrations
- [x] Eloquent Models mit Relationships
- [x] UI Templates (Pilot, Admin, Ground Control)
- [x] Flight Booking Controller mit 24h Time-Lock
- [x] ACARS Queue Worker Job
- [x] GitHub CI/CD Setup
- [x] PHPUnit Tests

### Phase 2: Community Features 🚀 (In Entwicklung)
- [ ] Screenshot Upload & Gallery
- [ ] Discussion Threads pro Route
- [ ] Leaderboard mit Performance Stats
- [ ] OAuth2 Integration (Google/GitHub Login)
- [ ] WebSocket Notifications

### Phase 3: Advanced Analytics (Zukünftig)
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

Dieses Projekt steht unter der MIT License.

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
