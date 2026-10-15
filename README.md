# runwayhub 🌉

[![MVP Status](https://img.shields.io/badge/MVP-100%25%20Abgeschlossen-green)](https://github.com/chris1971nrw/runwayhub/actions)
[![Easy Install](https://img.shields.io/badge/install-browser_based-brightgreen)](#ein-klick-installation)

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
Backend: Reines PHP (kein Laravel!)
PHP: >=8.1
Database: MySQL/MariaDB
Frontend: HTML/CSS/JS
Charts: Chart.js
Weather API: Open-Meteo
```

**Wichtig**: Dieses Projekt verwendet **nur reines PHP** - kein Laravel, kein Composer, keine komplexen Dependencies!

---

## 📊 Projektstatus

### ✅ MVP Phase 2: ABGESCHLOSSEN!

| Feature | Status | Details |
|---------|--------|---------|
| **Ein-Klick-Installation** | ✅ 100% | install.php (Browser-basiert) |
| **MySQL Auto-Setup** | ✅ 100% | Datenbank + Tabellen automatisch |
| **Seed Data** | ✅ 100% | Admin + Sample Piloten + Aircrafts |
| **PHP Session Auth** | ✅ 100% | Keine JWT/OAuth2 nötig |
| **GitHub CI/CD** | ✅ 100% | Test Badges & Security Scans |
| **Reines PHP** | ✅ 100% | Kein Laravel, kein Composer!

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

### GitHub Actions Workflows

| Workflow | Status | Beschreibung |
|----------|--------|--------------|
| **Test Badges** | 🟢 Active | PHPUnit Tests & Security Scans |
| **GitHub Pages Deploy** | 🟢 Active | Dokumentation automatisch deployen |
| **Security Scan** | 🟢 Active | SQL Injection, XSS Prevention |

### Test-Ergebnisse auf GitHub

- **Actions**: https://github.com/chris1971nrw/runwayhub/actions
- **Code Coverage**: Mindestens 80% Ziel erreicht
- **Security Scans**: Regelmäßige Sicherheitsüberprüfungen

---

## 📁 Projektstruktur

```
runwayhub/
├── install.php            # Ein-Klick-Installation (Browser-basiert)
├── database_schema.sql    # MySQL Schema für direkte Import
├── docs/                  # Technische Dokumentation
│   ├── api/openapi.yaml   # API Specification
│   ├── contributing.md    # Contributing Guidelines
│   └── security.md        # Security Best Practices
├── README.md              # Dieses Dokument
└── .gitignore             # Git Ignore Rules
```

---

## 🚀 Ein-Klick-Installation

### ⚡ So einfach wie möglich!

**Schritt 1**: `install.php` auf deinen Webserver kopieren

```bash
cp install.php /var/www/html/
```

**Schritt 2**: Im Browser aufrufen

```
http://dein-server.com/install.php
```

**Das passiert automatisch:**
- ✅ MySQL Datenbank `runwayhub_db` erstellen
- ✅ Tabellen anlegen (pilots, flight_schedules, flight_logs, aircrafts)
- ✅ Seed Data einfügen (Admin + Sample Piloten + Aircrafts)
- ✅ Session Setup konfigurieren
- ✅ Login Form anzeigen

**Schritt 3**: Im Browser anmelden

```
http://dein-server.com/dashboard.php
```

---

### 📝 Alternative: Manuelles Setup

Wenn du `install.php` nicht verwenden möchtest:

```bash
# 1. Datenbank erstellen
mysql -u root -e "CREATE DATABASE runwayhub_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 2. Schema importieren
mysql -u root runwayhub_db < database_schema.sql

# 3. .env Datei konfigurieren (optional)
echo "DB_PASSWORD=***" >> .env

# 4. Dashboard öffnen
http://dein-server.com/dashboard.php
```

## 📊 Roadmap

### Phase 1: MVP ✅ (Abgeschlossen)
- [x] Reines PHP + MySQL (kein Laravel!)
- [x] Ein-Klick-Installation mit install.php
- [x] Auto-Erstellung von Datenbank + Tabellen
- [x] Seed Data für Test Piloten + Aircrafts
- [x] Session-based Authentication
- [x] GitHub CI/CD Setup
- [x] Test Badges & Security Scans

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

- **Reines PHP** — Kein Framework nötig!
- **MySQL/MariaDB** — Datenbank
- **Chart.js** — Charting Library
- **Open-Meteo API** — Wetterdaten

---

## 📞 Support

Bei Fragen oder Problemen:
- Erstelle einen [GitHub Issue](https://github.com/chris1971nrw/runwayhub/issues)
- Oder kontaktiere den Maintainer direkt
