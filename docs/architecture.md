# RunwayHub Architektur

## 1. Überblick

RunwayHub ist eine modulare Virtual Airline Manager Software, entwickelt in PHP 8+ mit MySQL.

## 2. Projektstruktur

```
runwayhub/
├── src/               # Core‑Code
│   ├── core/         # Bootstrap, Router, Container
│   ├── modules/      # Flug‑, Flotten‑, Buchungsmodule
│   ├── api/          # REST‑Endpoints
│   └── cli/          # Command‑Line Tools
├── public/           # Web‑Root
│   ├── index.php     # Entry‑Point
│   ├── assets/       # CSS, JS, Bilder
│   └── css/, js/     # Stylesheets, Scripts
├── i18n/             # Sprachdateien
│   ├── de/
│   └── en/
├── config/           # Konfiguration
│   └── env.php       # Umgebungsvariablen
├── tests/            # PHPUnit Tests
│   ├── Unit/
│   ├── Integration/
│   └── Fixtures/
├── docs/             # Dokumentation
├── database/         # SQL‑Migrations
│   └── migrations/
└── public_html/      # Deployment‑Target (optional)
```

## 3. Architekturprinzipien

- **Modularität**: Jede Funktionalität als eigenes Modul
- **Single Responsibility**: Jede Klasse eine Aufgabe
- **Dependency Injection**: Trennung von Daten und Logik
- **PSR‑12**: PHP Coding Standards
- **Migrations‑First**: Datenbank‑Schema evolutionär
- **Internationalisierung**: Trennung von Code und Text

## 4. Technology Stack

| Layer | Technologie |
|-------|-------------|
| Backend | PHP 8.2+ |
| Database | MySQL 8.0 / MariaDB 10.6+ |
| Frontend | HTML5, CSS3, Vanilla JS |
| API | RESTful JSON |
| Tests | PHPUnit 10+ |
| Config | PHP Config / .env |

## 5. Module

### 5.1 FlightModule
- Flugpläne, Routen, Umläufe
- Pilotenverwaltung
- Fluglogs, PIREPs

### 5.2 FleetModule
- Flottenmanagement
- Flugzeuge, Wartung
- Flugzeugtypen

### 5.3 BookingModule
- Buchungssystem
- Preise, Verfügbarkeiten
- Ticket‑Management

### 5.4 AdminModule
- Benutzer‑Management
- Rollen & Rechte
- System‑Konfiguration

### 5.5 ReportModule
- Statistiken
- Analytik
- Export‑Funktionen

## 6. Datenfluss

```
User Request
    ↓
Router (public/index.php)
    ↓
Controller (Module + Route)
    ↓
Model (Datenbank‑Operationen)
    ↓
View (HTML‑Template)
    ↓
Response (JSON / HTML)
```

## 7. Sicherheit

- **Input Validation**: Auf allen Endpoints
- **SQL Injection Prevention**: Prepared Statements
- **CSRF Protection**: Tokens auf Formularen
- **Password Hashing**: `password_hash()`
- **Session Management**: Secure Cookies

## 8. Deployment

- **Production**: Nginx + PHP‑FPM
- **Staging**: Identische Umgebung
- **Database**: Backups vor jedem Deploy
- **CI/CD**: GitHub Actions für Tests + Deploy

## 9. Erweiterbarkeit

Neue Module:
1. Verzeichnis `src/modules/`
2. Controller im passenden Ordner
3. Migration im `database/migrations/`
4. Tests in `tests/`

Neue API‑Endpoints:
1. Route im Router
2. Controller
3. Validierung
4. Tests

---

_Dokument automatisch gepflegt von DocsAgent._
