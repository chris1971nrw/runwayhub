# RunwayHub

Virtual Airline Manager Software für virtuelle Airlines.

[![PHP Version](https://img.shields.io/badge/php-%3E%3D8.2-blue)](https://php.net)
[![License](https://img.shields.io/badge/license-Apache%202-green)](LICENSE)
[![OpenAIP](https://img.shields.io/badge/OpenAIP-Integrated-green)](https://www.openaip.net)
[![Demo](https://img.shields.io/badge/Demo-System-blue)](demo/)

## 🚀 Features

### Core

- ✅ **Flottenmanagement** - Flugzeugverwaltung
- ✅ **Pilotenverwaltung** - Lizenz und Stunden
- ✅ **Routenplanung** - Luftwege und Flughäfen
- ✅ **Buchungssystem** - Online-Buchungen
- ✅ **PIREPs** - Pilot Reports
- ✅ **Statistiken** - Flugdaten

### OpenAIP Integration

- ✅ **OpenAIP API** - Live-Fluginformationen
- ✅ **Flughäfen** - IATA/ICAO Codes
- ✅ **Wegpunkte** - VOR, NDB, VORTAC, ILS
- ✅ **Luftwege** - Airways
- ✅ **Navigationshilfen** - Navaids

### Demo-System

- ✅ **Autonome Agents** - Fluggenerierung, PIREPs, Buchungen
- ✅ **GitHub Issues Watcher** - Auto-Monitoring
- ✅ **Synchronisation** - Demo vs Production
- ✅ **Feature Flags** - Flexible Konfiguration
- ✅ **Rollback Plan** - Fehlerbehandlung

### i18n

- ✅ **Deutsch/Englisch** - Vollständige Übersetzung
- ✅ **Pluralformen** - Anzahl-sensitive Texte
- ✅ **Caching** - Performance-optimiert

### Sicherheit

- ✅ **API Keys** - Sichere Authentifizierung
- ✅ **CSRF Protection** - Token-basiert
- ✅ **XSS Prevention** - htmlspecialchars()
- ✅ **SQL Injection** - Prepared Statements

## 📖 Dokumentation

- [README_DEMO.md](README_DEMO.md) - Demo-System
- [Demo/README.md](demo/README.md) - Demo Dokumentation
- [DEMO_SYSTEM.md](demo/deployment/DEMO_SYSTEM.md) - Systemübersicht
- [API_REFERENCE.md](demo/deployment/API_REFERENCE.md) - API-Dokumentation
- [USAGE_GUIDE.md](demo/deployment/USAGE_GUIDE.md) - Nutzungsanleitung
- [Features](docs/features.md) - Features-Übersicht
- [Roadmap](docs/roadmap.md) - Entwicklungspfad
- [Tech Notes](docs/tech_notes.md) - Technische Details
- [Changelog](changelog.md) - Änderungshistorie

## 🚀 Quick Start

### Demo-System

```bash
# 1. Clone Repository
git clone https://github.com/chris1971nrw/runwayhub.git
cd runwayhub

# 2. Demo-Modus aktivieren
export RUNWAYHUB_DEMO_MODE=true

# 3. Demo-Daten generieren
php demo/scripts/generate_demo_data.php

# 4. Autonomes System starten
./demo/scripts/autonomous_demo.sh

# 5. Status prüfen
./demo/scripts/health_check.sh

# 6. Metriken anzeigen
./demo/scripts/metrics.sh
```

### Production

```bash
# 1. Environment konfigurieren
cp .env.example .env

# 2. Datenbank-Migration
php src/artisan migrate

# 3. OpenAIP Synchronisation
php src/artisan openaip:sync

# 4. Starten
php -S localhost:8080 -t public
```

## 🤖 Demo-System

Das autonome Demo-System simuliert alle RunwayHub-Funktionen:

### Autonome Agents

| Agent | Aufgabe | Intervall |
|-------|---------|-----------|
| FlightAutomator | Flüge generieren | 5 Min |
| PIREPGenerator | PIREPs simulieren | 5 Min |
| BookingGenerator | Buchungen erstellen | 10 Min |
| FleetStatusUpdater | Flotten-Status | 15 Min |
| IssuesWatcher | GitHub Issues | 10 Min |
| DemoSystemSync | Sync mit Production | 30 Min |

### Features

- ✅ Automatische Fluggenerierung
- ✅ PIREP-Simulation
- ✅ Buchungs-Erstellung
- ✅ Flotten-Management
- ✅ GitHub Issues Watcher
- ✅ Screenshot-Analyse
- ✅ Reproduktionstest Vorschlag
- ✅ Fehler-Propagation
- ✅ Rollback-Plan
- ✅ Feature-Flags
- ✅ Daten-Isolierung

### Scripts

```bash
# Autonomes System
./demo/scripts/autonomous_demo.sh

# Daten generieren
php demo/scripts/generate_demo_data.php

# Issues überwachen
php demo/scripts/watch_issues.php

# Sync mit Production
php demo/scripts/demo_sync.php

# Issues forwarden
php demo/scripts/forward_issues.php

# Deployment
php demo/scripts/deploy_pages.sh

# Health Check
./demo/scripts/health_check.sh

# Metriken
./demo/scripts/metrics.sh

# Feature Flags
php demo/scripts/feature_flags.sh
```

### Konfiguration

```bash
# .env Datei
RUNWAYHUB_DEMO_MODE=true
GITHUB_TOKEN=ghp_...
FLIGHT_AUTOMATION_ENABLED=true
PIREP_SIMULATION_ENABLED=true
BOOKING_GENERATION_ENABLED=true
ISSUES_WATCHER_ENABLED=true
DEMO_LOG_LEVEL=info
```

### Logs

- `/runwayhub/docs/demo-issues.log` - Issues-Log
- `/runwayhub/docs/demo-sync.log` - Sync-Log

### GitHub Integration

- Issues alle 10 Minuten prüfen
- `demo` Label erkennen
- Issues an Main-Agent weiterleiten
- Screenshot-Analyse
- Reproduktionstest vorschlagen

### Synchronisation

- Code-Share: Gleicher Codebase
- Feature-Flags: Demo vs Production
- Daten-Sync: Demo-Daten separat
- Rollback-Plan: Bei Haupt-System-Fehlern

### Fehlerbehandlung

- Demo-Fehler → Gleicher Fehler im Haupt-System
- Demo-Fehler fixen → Haupt-System-Fix
- Tags: `demo`, `bug` vs `production`, `bug`

## 📞 Support

Für Support und Fragen:

- **Email:** dev@runwayhub.de
- **GitHub Issues:** https://github.com/chris1971nrw/runwayhub/issues
- **Dokumentation:** https://github.com/chris1971nrw/runwayhub/wiki

## 📜 Lizenz

Apache License 2.0
