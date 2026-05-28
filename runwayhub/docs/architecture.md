# RunwayHub Architektur

## 1. Überblick

RunwayHub ist eine modulare Virtual Airline Manager Software, entwickelt in PHP 8+ mit MySQL und umfassenden SEO-Optimierungen.

## 2. Projektstruktur

```
runwayhub/
├── src/                    # Core-Code
│   ├── core/              # Bootstrap, Router, Container
│   │   ├── Bootstrap.php
│   │   ├── Router.php
│   │   ├── Request.php
│   │   ├── Response.php
│   │   ├── Controller.php
│   │   ├── View.php
│   │   ├── Database.php
│   │   └── Middleware/
│   ├── modules/           # Flug-, Flotten-, Buchungsmodule
│   └── api/               # REST-Endpoints
├── public/                # Web-Root
│   ├── index.php          # Entry-Point
│   ├── templates/         # View Templates
│   │   ├── layout.php
│   │   ├── layout-seo.php
│   │   ├── dashboard.php
│   │   ├── about.php
│   │   └── ...
│   ├── assets/
│   │   ├── css/
│   │   │   └── main.css
│   │   ├── js/
│   │   │   └── main.js
│   │   └── images/
├── .jsonld/               # JSON-LD Structured Data
│   ├── manifest.json
│   ├── homepage.jsonld
│   ├── dashboard.jsonld
│   └── api.jsonld
├── i18n/                  # Sprachdateien
│   ├── de/
│   │   └── messages.php
│   └── en/
│       └── messages.php
├── config/                # Konfiguration
│   └── database.php
├── database/              # SQL-Migrations
│   └── migrations/
├── tests/                 # PHPUnit Tests
├── .github/               # GitHub Actions
│   └── workflows/
│       ├── ci.yml
│       ├── deploy.yml
│       └── sitemap.yml
├── sitemap.xml            # XML Sitemap
├── robots.txt             # Search Engine Directives
├── .env.example           # Environment Template
├── composer.json          # Dependency Manager
├── README.md              # Projekt-Dokumentation
├── LICENSE                # MIT License
└── docs/                  # Dokumentation
    ├── architecture.md
    ├── features.md
    ├── roadmap.md
    ├── tech_notes.md
    ├── changelog.md
    ├── security.md
    └── ...
```

## 3. Architekturprinzipien

- **Modularität**: Jede Funktionalität als eigenes Modul
- **Single Responsibility**: Jede Klasse eine Aufgabe
- **Dependency Injection**: Trennung von Daten und Logik
- **PSR-12**: PHP Coding Standards
- **Migrations-First**: Datenbank-Schema evolutionär
- **Internationalisierung**: Trennung von Code und Text
- **SEO-First**: Strukturierte Daten, Meta-Tags, Sitemap
- **Accessibility**: WCAG 2.1 AA Compliance
- **Performance**: Gzip/Br Compression, Browser Caching

## 4. Technology Stack

| Layer | Technologie | Version/Notes
|-------|-------------|---------------|
| Backend | PHP | 8.2+ |
| Database | MySQL / MariaDB | 8.0+ |
| Frontend | HTML5, CSS3, Vanilla JS | Mobile-first |
| API | RESTful JSON | JSON-LD enriched |
| Tests | PHPUnit | 10+ |
| CI/CD | GitHub Actions | YML Workflows |
| Container | Docker | Multi-stage builds |
| SEO | Structured Data | JSON-LD Schema.org |

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
- Ticket-Management

### 5.4 AdminModule
- Benutzer-Management
- Rollen & Rechte
- System-Konfiguration

### 5.5 ReportModule
- Statistiken
- Analytik
- Export-Funktionen

## 6. SEO-Architektur

### 6.1 Structured Data (JSON-LD)
```
✅ SoftwareApplication Schema
✅ WebPage Schema
✅ API Specification Schema
✅ Organization Publisher
✅ CreativeWork für Lizenz
```

### 6.2 Meta-Tags
```
✅ Title Tags
✅ Meta Description
✅ Meta Keywords
✅ Open Graph (Facebook)
✅ Twitter Cards
✅ Canonical URLs
✅ Robots Tags
```

### 6.3 Sitemap & Robots
```
✅ XML Sitemap (alle öffentlichen Seiten)
✅ robots.txt mit crawl-delay
✅ Change Frequency Einstellungen
✅ Priorität für jede Seite
```

### 6.4 Performance
```
✅ Gzip/Brotli Compression
✅ Browser Caching
✅ Lazy Loading für Bilder
✅ Mobile-First Design
✅ Reduced Motion Support
```

## 7. Datenfluss

```
User Request
    ↓
Router (public/index.php)
    ↓
Middleware (Auth, Guest, Admin)
    ↓
Controller (Module + Route)
    ↓
Model (Datenbank-Operationen)
    ↓
View (HTML-Template mit SEO-Meta-Tags)
    ↓
Response (JSON / HTML + JSON-LD)
    ↓
Analytics Tracking
```

## 8. Sicherheit

- **Input Validation**: Auf allen Endpoints
- **SQL Injection Prevention**: Prepared Statements
- **CSRF Protection**: Tokens auf Formularen
- **Password Hashing**: bcrypt (cost=12)
- **Session Management**: Secure Cookies (HttpOnly, Secure, SameSite)
- **CSP Headers**: Content Security Policy
- **HSTS**: HTTP Strict Transport Security
- **X-Frame-Options**: Clickjacking Protection

## 9. Deployment

- **Production**: Nginx + PHP-FPM
- **Staging**: Identische Umgebung
- **Database**: Backups vor jedem Deploy
- **CI/CD**: GitHub Actions für Tests + Deploy
- **Docker**: Containerisierung
- **GitHub Pages**: Hosting Option

## 10. Erweiterbarkeit

Neue Module:
1. Verzeichnis `src/modules/`
2. Controller im passenden Ordner
3. Migration im `database/migrations/`
4. Tests in `tests/`
5. JSON-LD für neue Seiten in `.jsonld/`

Neue API-Endpoints:
1. Route im Router
2. Controller
3. Validierung
4. Tests
5. API-Dokumentation

## 11. Accessibility (A11y)

- **Skip Links**: Für Screen Reader
- **Focus Visible**: Klare Fokus-Indikatoren
- **ARIA Labels**: Screen Reader Support
- **Contrast**: WCAG 2.1 AA Standards
- **Keyboard Navigation**: Vollständige Tastatur-Unterstützung
- **Reduced Motion**: Respektiert Vorlieben

---

_Dokument automatisch gepflegt von DocsAgent._