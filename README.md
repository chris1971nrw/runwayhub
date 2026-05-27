# RunwayHub - Virtual Airline Manager

[![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://www.php.net/)
[![License](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![Version](https://img.shields.io/badge/Version-2.0.0-green.svg)](docs/changelog.md)
[![Tests](https://github.com/chris1971nrw/runwayhub/actions/workflows/tests.yml/badge.svg)](https://github.com/chris1971nrw/runwayhub/actions)
[![GitHub Pages](https://github.com/chris1971nrw/runwayhub/actions/workflows/pages.yml/badge.svg)](https://runwayhub.github.io)

> **RunwayHub** - Modernes Virtual Airline Management System für Flugsimulation.

## ✈️ Features

- 📋 **Multi-Airline Support** - Verwalte mehrere virtuelle Airlines
- 🛫 **Live-Flugverfolgung** - Echtzeit-Flugstatus via FlightAware
- 🌤️ **Wetter-API** - METAR/TAF, Alerts, Vorhersagen (Open-Meteo)
- 📊 **Statistiken & Reports** - PIREP, Flugdaten, Leaderboards
- 👨‍✈️ **Pilotenverwaltung** - Pilotendatenbank mit Callsigns, Flugzeugen
- 🗺️ **Routenplanung** - Flugpläne, Umkreise, Wegpunkte
- 🔐 **Rollen & Rechte** - Admin, Staff, Pilot, Gast
- 🌍 **Mehrsprachig** - Deutsch & Englisch
- 🚀 **Autonome Entwicklung** - GitHub Actions CI/CD

## 🚀 Quick Start

### **Lokal testen**

```bash
cd /runwayhub
php -S localhost:8000 -t public
```

**Browser:** `http://localhost:8000/dashboard-public.php?airline=SW`

### **Demo-Account**

```
E-Mail: demo@airline.com
Passwort: password
```

### **Pilotenregistrierung**

```
http://localhost:8000/pilot-register.php?airline=SW
```

## 📖 Airline-Auswahl

Klicke auf eine Airline:

- ✈️ **SW** - SkyWings
- 🛫 **EU** - EuroFly
- 🌊 **OC** - OceanAero
- ⛰️ **AL** - AlpineAir

## 📚 Documentation

| Dok | Link |
|-----|------|
| **Architecture** | [docs/architecture.md](docs/architecture.md) |
| **Features** | [docs/features.md](docs/features.md) |
| **Database** | [docs/database.md](docs/database.md) |
| **Security** | [docs/security.md](docs/security.md) |
| **Deployment** | [docs/deployment.md](docs/deployment.md) |
| **Weather API** | [docs/weather-api.md](docs/weather-api.md) |
| **FlightAware** | [flightaware.md](flightaware.md) |
| **Changelog** | [docs/changelog.md](docs/changelog.md) |

## 🔌 API Endpoints

### **OpenAIP API** (12 Endpoints)
- `GET /api/airport/{identifier}` - Flughafen
- `GET /api/waypoint/{identifier}` - Wegpunkt
- `GET /api/airway/{identifier}` - Luftstraße
- `GET /api/navaid/{identifier}` - Navigationshilfe

### **Weather API** (6 Endpoints)
- `GET /api/weather/current/{airport}` - Aktuelle Wetterdaten
- `GET /api/weather/forecast/{airport}` - Vorhersage
- `GET /api/weather/metar/{airport}` - METAR Beobachtung
- `GET /api/weather/taf/{airport}` - TAF Vorhersage
- `GET /api/weather/alerts/{airport}` - Warnungen
- `GET /api/weather/status` - Status

### **FlightAware API** (4 Endpoints)
- `GET /api/flightaware/flights/{flightNumber}` - Live-Flugstatus
- `GET /api/flightaware/airline/{airline}/flights` - Airline-Flüge
- `GET /api/flightaware/flights` - Alle aktiven Flüge
- `GET /api/flightaware/search` - Suche

## 🔐 Security

- ✅ **bcrypt** (cost=12)
- ✅ **HttpOnly, Secure, SameSite**
- ✅ **CSP, HSTS, X-Frame-Options**
- ✅ **Session Regeneration**
- ✅ **Input Validation**
- ✅ **Rate Limiting**
- ✅ **SQL Injection Schutz**

## 🧪 Testing

```bash
cd runwayhub
composer install
composer test
```

## 📊 Demo Users

- **Admin**: `demo_admin` / `admin123`
- **Pilot**: `demo_pilot` / `pilot123`
- **Guest**: `demo_guest` / `guest123`

## 🤝 Contributing

1. Fork & Clone
2. `git checkout -b feature/name`
3. Commit mit `git commit -m 'feat: ...'`
4. Pull Request
5. Tests durchlaufen (PHPUnit)

### **Issue Reporting**

- 🐛 **Bugs** → [Issues](https://github.com/chris1971nrw/runwayhub/issues)
- 💡 **Features** → [Discussions](https://github.com/chris1971nrw/runwayhub/discussions)
- 📋 **Roadmap** → [docs/roadmap.md](docs/roadmap.md)

## 📄 License

[MIT](LICENSE) - Kostenlos und open source.

## 👥 Credits

- [@chris1971nrw](https://github.com/chris1971nrw)
- OpenAIP API
- Laravel Framework
- PHP Community

---

**RunwayHub** ✈️ - *Fly your virtual airline!*

[GitHub Repository](https://github.com/chris1971nrw/runwayhub)
[GitHub Actions](https://github.com/chris1971nrw/runwayhub/actions)
[GitHub Pages](https://runwayhub.github.io)
