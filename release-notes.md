# RunwayHub - Release Notes

## Version 1.0.0 (2026-05-28) - Initial Release

### 🎉 Überblick

Der erste Release von RunwayHub - ein komplettes Virtual Airline Management System für Flugsimulation.

### ✨ Neuartig in dieser Version

#### Core Features
- **Multi-Airline Support** - Verwalten Sie mehrere Virtual Airlines
- **Live-Flugverfolgung** - Integration mit FlightAware API
- **Wetter-API** - METAR/TAF, Alerts, PIREP, Turbulenz
- **VA Management** - Virtual Airlines gründen und anschließen
- **Login System** - Callsign/Passwort Authentifizierung
- **ACARS Client** - Flugdaten-Erfassung (Simulation)

#### Frontend
- **Landing Page** - Modernes Design mit Flight Board
- **Login Page** - Pilot Login mit Demo-Accounts
- **Dashboard** - Hauptübersicht mit Statistiken
- **VA Forms** - VA gründen und anschließen
- **Weather Widget** - Live-Wetter-Updates

#### Backend API
- `/api/login-pilot.php` - Pilot Login
- `/api/va-create.php` - Neue VA erstellen
- `/api/va-connect.php` - Existierende VA verbinden
- `/api/va/list` - Alle VA auflisten
- `/api/openaip/*` - OpenAIP Endpunkte
- `/api/weather/*` - Wetter Endpunkte
- `/api/flightaware/*` - FlightAware Integration

### 📦 Installation

```bash
cd /home/christoph/.openclaw/workspace-runwayhub/runwayhub

# Dependencies
composer install

# Server starten
php -S localhost:8000 -t public

# Browser öffnen
http://localhost:8000
```

### 🔐 Demo Accounts

| Callsign | Passwort | Rolle |
|----------|----------|-------|
| `demo_admin` | `admin123` | Admin |
| `demo_pilot` | `pilot123` | Pilot |
| `demo_guest` | `guest123` | Guest |

### 🛠️ Technische Details

- **PHP:** 8.3+
- **Database:** SQLite / MySQL
- **APIs:** OpenAIP, FlightAware, Weather Services
- **MQTT:** Optional für ACARS

### 🔒 Sicherheit

- bcrypt Passwörter (cost=12)
- HttpOnly, Secure, SameSite Cookies
- SQL Injection Schutz
- XSS Schutz

### 📚 Dokumentation

- [Architecture](docs/architecture.md)
- [Features](docs/features.md)
- [Database](docs/database.md)
- [Deployment](docs/deployment.md)
- [Weather API](docs/weather-api.md)
- [FlightAware](docs/flightaware.md)
- [Setup Guide](SETUP.md)

### 🐛 Bekannte Issues

- ACARS benötigt echten MQTT-Broker (Simulation im Demo)
- SQLite-PDO-Connector optional (falls nicht verfügbar: Dateistorage)

### ⏳ Roadmap für 2.0

- [ ] MQTT-Broker Integration
- [ ] Echtzeit ACARS Flugdaten
- [ ] OTA Integration (AMADEUS)
- [ ] SMTP E-Mail
- [ ] Leaderboards
- [ ] Mobile App Integration

---

**Built with ❤️ by @chris1971nrw**

**Licensed under MIT**
