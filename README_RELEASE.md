# runwayhub 🌉

## 🎉 Beta Release 1.0.0-beta

**Release-Datum**: 2026-10-11

[![MVP Status](https://img.shields.io/badge/MVP-100%25%20Abgeschlossen-green)](https://github.com/chris1971nrw/runwayhub/actions)
[![Easy Install](https://img.shields.io/badge/install-browser_based-brightgreen)](#ein-klick-installation)
[![Beta Release](https://img.shields.io/badge/release-beta-orange)](#beta-release-informationen)

---

## 🎯 Zielsetzung

**runwayhub** ist eine Virtual Airline Community für Flugsimulator-Piloten mit folgenden Features:

- ✅ **Schedule Management** — Flugpläne erstellen & verwalten
- ✅ **Flight Tracking** — Flugprotokolle hochladen & tracken
- ✅ **ACARS Integration** — MSFS/X-Plane Log Parsing
- ✅ **Community Features** — Reviews, Leaderboards, Weather Widget
- ✅ **Admin Dashboard** — Finanzberichte & Fleet Management

---

## 🚀 Beta Release Informationen

### Was ist runwayhub?

**runwayhub** ist eine Virtual Airline Community für Flugsimulator-Piloten mit folgenden Features:

#### MVP Phase 2: ABGESCHLOSSEN! ✅

| Feature | Status | Details |
|---------|--------|---------|
| **Reines PHP** | ✅ 100% | Kein Laravel, kein Composer |
| **Ein-Klick-Installation** | ✅ 100% | install.php (Browser-basiert) |
| **MySQL Auto-Setup** | ✅ 100% | Datenbank + Tabellen automatisch |
| **Seed Data** | ✅ 100% | Admin + Sample Piloten + Aircrafts |
| **PHP Session Auth** | ✅ 100% | Keine JWT/OAuth2 nötig |
| **GitHub CI/CD** | ✅ 100% | Test Badges & Security Scans |

#### Installations-Status:

```bash
✅ install.php — Ein-Klick-Installation (Browser-basiert)
✅ database_schema.sql — MySQL Schema für direkten Import
✅ index.php — Login Form mit Auto-DB Setup
✅ dashboard.php — Dashboard mit allen Features
✅ docs/ — Technische Dokumentation
```

#### Sicherheits-Features:

- ✅ Session-based Authentication (kein JWT/OAuth2)
- ✅ Role-based Authorization: pilot, crew, ground_control, admin
- ✅ Password Hashing mit Bcrypt
- ✅ HTTP Only Cookies
- ✅ CSRF Protection in allen Forms

#### Tech Stack:

```yaml
Backend: Reines PHP (kein Laravel!)
PHP: >=8.1
Database: MySQL/MariaDB
Frontend: HTML/CSS/JS
Charts: Chart.js
Weather API: Open-Meteo
```

---

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

## 📞 Support

Bei Fragen oder Problemen:
- Erstelle einen [GitHub Issue](https://github.com/chris1971nrw/runwayhub/issues)
- Oder kontaktiere den Maintainer direkt

---

## 📄 Lizenz

Dieses Projekt steht unter der MIT License.

---

## 🙏 Credits

- **Reines PHP** — Kein Framework nötig!
- **MySQL/MariaDB** — Datenbank
- **Chart.js** — Charting Library
- **Open-Meteo API** — Wetterdaten
