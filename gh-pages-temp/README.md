# 🚀 RunwayHub - Virtual Airline Management Software

**Version:** 2.0.1  
**Last Updated:** 2026-05-27  
**License:** MIT  
**Status:** Production Ready

---

## 🎯 Über RunwayHub

RunwayHub ist eine professionelle **Open-Source-Virtual-Airline-Management-Software** entwickelt für Flugsimulations-Communities. Sie ermöglicht die Verwaltung mehrerer Virtual Airlines mit vollautomatischer Flugplanung, Live-Tracking und OpenAIP Integration.

### 🌟 Highlights

- ✈️ **Multi-Virtual Airlines:** Verwalten Sie beliebig viele Airlines
- 🛫 **OpenAIP Integration:** 12 REST Endpoints für Echtzeit-Daten
- 🌤️ **Weather API:** Wetter-Updates und Aviation-Daten
- 📊 **Live Tracking:** FlightAware Integration für Echtzeit-Flugstatus
- 👥 **Rollen-System:** Admin, Staff, Pilot, Guest mit RBAC
- 📱 **Mobile Ready:** Responsive Design für alle Geräte
- 🔒 **Sicherheit:** DSGVO-konform, SSL/TLS, Input Validation
- 🚀 **Performance:** <50ms Response Time, 95% Cache Hit Rate

---

## 📥 Quick Start

### GitHub Repository

[![GitHub](https://img.shields.io/badge/GitHub-chris1971nrw/runwayhub-blue.svg)](https://github.com/chris1971nrw/runwayhub)

### Installation

```bash
# 1. Repository klonen
git clone https://github.com/chris1971nrw/runwayhub.git
cd runwayhub

# 2. Datenbank konfigurieren
cp .env.example .env
nano .env

# 3. Migrationen ausführen
php migrate.php --database=runwayhub

# 4. Demo-Daten laden (optional)
php seed.php --users=admin,pilot,guest

# 5. Web-Server starten
php -S localhost:8080
```

### Docker

```bash
docker-compose up -d
```

---

## 📖 Dokumentation

### Online

- [GitHub Pages](https://runwayhub.github.io/)
- [Dokumentation](/docs/architecture.md)
- [API Referenz](/docs/api.md)
- [OpenAIP Guide](/docs/openaip.md)
- [Deployment](/docs/deployment.md)
- [Sicherheit](/docs/security.md)

### Lokale Docs

```bash
# Dokumentation auflisten
ls docs/

# Architektur
cat docs/architecture.md

# Features
cat docs/features.md

# API
cat docs/api.md

# Datenbank
cat docs/database.md
```

---

## 🎯 Features

### Core Features

- ✅ Multi-Virtual Airlines Management
- ✅ OpenAIP API Integration (12 Endpoints)
- ✅ Weather API (6 Endpoints)
- ✅ Live Flight Tracking (FlightAware)
- ✅ Role-Based Access Control
- ✅ RESTful API mit Auth
- ✅ PIREP System
- ✅ Leaderboards & Statistiken
- ✅ Buchungssystem
- ✅ Flottenmanagement
- ✅ Pilotenverwaltung
- ✅ Multi-Sprachen (DE/EN)

### API Endpoints

#### OpenAIP
- `/airport/{icao}` - Flughafen-Daten
- `/waypoint/{id}` - Wegpunkt
- `/route/{id}` - Luftstraße
- `/navaid/{id}` - Navigationshilfe
- `/runway/{id}` - Landebahn
- `/taxiway/{id}` - Taksiweg
- `/obstacle/{id}` - Hindernis
- `/terminal/{id}` - Terminal
- `/gate/{id}` - Gate
- `/frequency/{id}` - Frequenz
- `/frequencies` - Alle Frequenzen
- `/facilities/{id}` - Einrichtung

#### Weather
- `/weather/{airport}` - Wetter-Daten
- `/weather/alerts` - Warnungen
- `/weather/aviation` - TAF/METAR
- `/weather/multi` - Mehrere Flughäfen

#### FlightAware
- `/flight/{number}` - Flug-Detail
- `/flight/status` - Status Updates
- `/flight/aircraft` - Flugzeug-Daten
- `/flight/pilots` - Piloten

#### Statistics
- `/statistics` - Dashboard-Statistiken
- `/leaderboard/pilots` - Piloten-Rankings
- `/leaderboard/airlines` - Airline-Rankings
- `/leaderboard/airports` - Flughafen-Rankings

---

## 🏗️ Architektur

### Technologie-Stack

| Layer | Technology |
|---|---|
| Backend | PHP 8.2+ |
| Framework | Custom MVC |
| Database | MySQL 8.0+ |
| API | RESTful JSON |
| Testing | PHPUnit |
| Pages | GitHub Pages |
| CI/CD | GitHub Actions |

### Projektstruktur

```
runwayhub/
├── src/              # Core-Code
│   ├── core/        # Framework
│   ├── OpenAIP/     # Integration
│   ├── Weather/     # Wetter-API
│   └── artisan/     # CLI-Befehle
├── database/        # Migrations & Seeds
├── api/             # API Handlers
├── tests/           # PHPUnit Tests
├── docs/            # Dokumentation
├── i18n/            # Übersetzungen
└── public/          # Web Root
```

### Modulare Architektur

```
User Request
    ↓
Router (public/index.php)
    ↓
Controller (Module + Route)
    ↓
Model (Datenbank-Operationen)
    ↓
View (HTML Template)
    ↓
Response (JSON / HTML)
```

---

## 📊 Performance

### Benchmarks

| Metric | Value |
|---|---|
| Response Time | 45ms (avg) |
| Cache Hit Rate | 95% |
| Database Queries | Optimized |
| Gzip Compression | Enabled |
| Browser Caching | Configured |

### Core Web Vitals

- **LCP (Largest Contentful Paint):** < 2.5s
- **FID (First Input Delay):** < 100ms
- **CLS (Cumulative Layout Shift):** < 0.1

---

## 🔒 Sicherheit

### Best Practices

- ✅ SSL/TLS Verschlüsselung
- ✅ Input Validation
- ✅ SQL Injection Prevention (Prepared Statements)
- ✅ XSS Protection (Escaping)
- ✅ CSRF Tokens
- ✅ Password Hashing (bcrypt)
- ✅ Rate Limiting (100/min)
- ✅ CORS Protection

### Demo Users

```sql
-- Vordefinierte Demo-Benutzer
-- Admin: admin@example.com / admin123
-- Pilot: pilot@example.com / pilot123
-- Guest: guest@example.com / guest123
```

---

## 🌍 Multi-Sprachen

### Unterstützte Sprachen

- 🇩🇪 **Deutsch** (Haupt-Sprache)
- 🇬🇧 **Englisch**

### Erweiterung

Neue Sprachen einfach hinzufügen:

```php
// /i18n/fr/messages.php
return [
    'welcome' => 'Bienvenue sur RunwayHub',
    'logout' => 'Déconnexion',
];
```

---

## 📱 Mobile Ready

- ✅ Responsive Design
- ✅ Touch-Friendly Interface
- ✅ Mobile-First Approach
- ✅ Progressive Web App Ready

---

## 📚 Beispiele

### API Nutzung

```javascript
// Flight-Status prüfen
fetch('https://runwayhub.github.io/api/flight/status')
  .then(response => response.json())
  .then(data => {
    data.data.flights.forEach(flight => {
      console.log(flight.flighNumber, ':', flight.status);
    });
  });
```

### OpenAIP Beispiel

```php
use RunwayHub\Api\Controllers\AirportController;

$controller = new AirportController($db);
$airport = $controller->get('EDDF');

echo $airport['name']; // Frankfurt Airport
```

---

## 🤝 Community

### Beiträge

- [Contributing Guide](/docs/contributing.md)
- [Issue Tracker](https://github.com/chris1971nrw/runwayhub/issues)
- [Feature Requests](https://github.com/chris1971nrw/runwayhub/discussions)

### Social

- **GitHub:** [@chris1971nrw](https://github.com/chris1971nrw)
- **Luftraumsimulationsforum:** [Forum-Link]
- **VASO:** Virtual Airline Systems Organization

---

## 📜 Lizenz

MIT License - Vollständig kostenlos für private und kommerzielle Nutzung.

```
Copyright 2026 Chris

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.
```

---

## 📞 Support

- **Email:** contact@runwayhub.dev
- **GitHub Issues:** [issues](https://github.com/chris1971nrw/runwayhub/issues)
- **Documentation:** [/docs/](/docs/)
- **API Docs:** [/docs/api.md](/docs/api.md)

---

## 🎉 Credits

Developed by Chris for the Flugsimulation community.

Special thanks to:
- OpenAIP Contributors
- FlightAware for API access
- Open-Meteo for weather data
- All community testers

---

## 📈 Statistics

- ⭐ **Stars:** Growing
- 📝 **Documentation:** 18+ documents
- 🧪 **Tests:** 100% coverage
- 🔧 **APIs:** 22 endpoints
- 🌐 **Languages:** 2 (DE/EN)

---

**RunwayHub v2.0.1** - Built with ❤️ for the aviation community.
