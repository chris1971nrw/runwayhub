# RunwayHub - Modernes Virtual Airline Management System

Das komplette System zum Verwalten von Virtual Airlines, Piloten und Flugdaten.

**Version:** 2.0.3  
**Last Updated:** 2026-05-28  
**Status:** Production Ready  
**Autonomy Watchdog:** Active

## 🚀 Features

- **Multi-Airline Support** - Verwalten Sie mehrere Airlines
- **Live-Flugverfolgung** - Via ACARS API
- **Wetter-API** - METAR/TAF, Alerts, PIREP
- **Statistiken & Reports** - Leaderboards, Flugstatistiken
- **ACARS Integration** - Flugdaten-Erfassung (Simulation)
- **Login System** - Callsign/Passwort Authentifizierung
- **VA Management** - Virtual Airlines gründen und anschließen

## 📋 Schnellstart

```bash
cd /home/christoph/.openclaw/workspace-runwayhub/runwayhub

# Server starten
php -S localhost:8000 -t public

# Browser öffnen
# http://localhost:8000
```

## 🌐 Zugängliche Seiten

| URL | Beschreibung |
|-----|-------------|
| `/` | Landing Page |
| `/login.php` | Pilot Login |
| `/dashboard.php` | Hauptdashboard |
| `/va-gruenden.php` | VA gründen |
| `/va-connect.php` | VA anschließen |
| `/va-admin.php` | VA Admin (kommt) |

## 🔐 Demo Accounts

```
Admin: demo_admin / admin123
Pilot: demo_pilot / pilot123  
Guest: demo_guest / guest123
```

## 📡 API Endpoints

### Login
```bash
POST /api/login-pilot.php
{
  "callsign": "demo_pilot",
  "password": "pilot123"
}
```

### VA Erstellen
```bash
POST /api/va-create.php
{
  "name": "Deutsche Airline",
  "airlineName": "Deutsche Airline",
  "website": "https://www.deutsche-airline.de",
  "logo": "logo.png",
  "colors": {
    "primary": "#000000",
    "secondary": "#ffffff"
  }
}
```

### VA Verbinden
```bash
POST /api/va-connect.php
{
  "ownerCredentials": {
    "username": "user123",
    "password": "pass123"
  },
  "website": "https://www.deutsche-airline.de"
}
```

## 🛠️ Tech Stack

- **PHP 8.3+**
- **SQLite / MySQL**
- **OpenAIP API**
- **ACARS API**
- **Weather Services**
- **ACARS (Simulation)**

## 📚 Dokumentation

- [Architecture](docs/architecture.md)
- [Features](docs/features.md)
- [Database](docs/database.md)
- [Deployment](docs/deployment.md)
- [Weather API](docs/weather-api.md)
- [ACARS](docs/ACARS.md)

## 🔧 Development

```bash
# Dependencies
composer install

# Migrationen
php src/cli/migrate.php

# Seed Daten
php src/cli/seed.php

# Tests
composer test
```

## 📖 Lizenz

MIT - Kostenlos und open source.

---

**Built with ❤️ by @chris1971nrw**

**Powered by OpenAIP API & Weather Services**
