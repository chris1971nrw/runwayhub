# RunwayHub - Virtual Airline Manager

**Moderne, Open Source, Multi-Airline Fluggesellschaft-Management-Software**

![Version](https://img.shields.io/badge/version-2.0.3-blue.svg)
![PHP](https://img.shields.io/badge/PHP-8.3+-green.svg)
![License](https://img.shields.io/badge/license-MIT-blue.svg)
![Sicherheit](https://img.shields.io/badge/sicherheit-gehärtet-brightgreen.svg)
![SEO](https://img.shields.io/badge/SEO-97.5%25-green.svg)

---

## 📋 Überblick

RunwayHub ist eine **kostenlose, Open Source**-Virtual Airline Manager Software, entwickelt mit modernem PHP 8.3+. Das System bietet umfassende Flugmanagement-Funktionalitäten, Wetterintegration und Virtual Airline Management (VA) für FBOs, Flughäfen und Luftfahrtunternehmen.

### Hauptmerkmale

- ✅ **Multi-Airline-Unterstützung** - Kompatibel mit mehreren Airlines und Systemen
- ✅ **Wetter-API** - METAR/TAF-Wetterdaten mit Caching
- ✅ **VA-Management** - Virtual Airline Management
- ✅ **Statistik & Berichte** - Umfassende Fluganalysen und Reporting
- ✅ **PIREP-System** - Integration von Pilotenwetterberichten
- ✅ **Leaderboards** - Verfolgen der Top-Performer
- ✅ **Sicherheit** - Branchensicherheitsstandards (bcrypt, CSRF, XSS-Prävention)
- ✅ **ACARS-Entwicklung** - Eigene ACARS-Technologie für Echtzeitflugstatus

---

## 🚀 Schnellstart

### Installation

```bash
cd runwayhub
php -S localhost:8000 -t public
```

### Demo-Zugangsdaten

```
Admin:    demo_admin     / admin123
Pilot:    demo_pilot     / pilot123
Guest:    demo_guest     / guest123
```

Besuchen Sie das Live-Demo: <a href="https://runwayhub.github.io">https://runwayhub.github.io</a>

---

## 📖 Dokumentation

- [**Architektur**](runwayhub/docs/architecture.md) - Systemdesign und Struktur
- [**Funktionen**](runwayhub/docs/features.md) - vollständige Feature-Liste
- [**Datenbank**](runwayhub/docs/database.md) - SQLite-Schema-Dokumentation
- [**Deployment**](runwayhub/docs/deployment.md) - Produktionsaufbau
- [**Wetter-API**](runwayhub/docs/weather-api.md) - METAR/TAF-Integration
- [**ACARS**](runwayhub/docs/acars.md) - Eigene ACARS-Entwicklung
- [**Sicherheit**](runwayhub/docs/security.md) - Sicherheits-Härtung
- [**Performance**](runwayhub/docs/performance-guide.md) - Optimierung

---

## 🎯 Kernfunktionen

### Flugmanagement

- **Multi-Airline-Unterstützung**
  - Kompatibel mit mehreren Airlines
  - Vereinheitlichte Dateninterface
  
- **Flugüberwachung**
  - Eigene ACARS-Integration für Echtzeitflugstatus
  - Flugverlauf und Historie
  - Ankunfts- und Abflugbretter
  
- **Wetterintegration**
  - **METAR-Wetterdaten** - METAR-Wetterberichte
  - **TAF-Prognosen** - TAF-Forecasts
  - **Wetterwarnungen** - Wetteralerts und notifications

### Wetter-Datenquellen

- **OpenMeteo** - METAR/TAF-Daten
- **Wetter-APIs** - Integration von Wetter-Diensten
- **ACARS-Integration** - Eigene Wetter-Datenintegration

### Virtual Airline Management (VA)

- **Airline-Management** - Verwaltung Ihrer Fluggesellschaften
- **Flugmanagement** - Flugplanung und Buchung
- **Flottenmanagement** - Flugzeugflotte verwalten
- **Passagiermanagement** - Reservationen und Check-in
- **Statistik & Reports** - Umfassende Analysen
- **Wetterintegration** - METAR/TAF-Daten
- **ACARS-Integration** - Eigene ACARS-Technologie

### API & Integration

- **40+ RESTful Endpoints** - Vollständige API mit JSON-Antworten
- **RESTful Services** - Vollständige CRUD-Operationen
- **Ratenbegrenzung** - Schutz vor Missbrauch und DDoS
- **Dokumentation** - Vollständige API-Dokumentation

---

## 🔧 Technische Stack

- **Backend:** PHP 8.3.6+
- **Datenbank:** SQLite (15 Tabellen)
- **Caching:** TTL-basiertes Caching (5-300 Sekunden)
- **API:** RESTful-Architektur
- **Frontend:** Statische HTML, schnelle Ladezeiten
- **SEO:** Vollständig optimiert
- **Sicherheit:** Enterprise-grade

---

## 🛡️ Sicherheit

RunwayHub beinhaltet branchenübliche Sicherheitsmaßnahmen:

- **Passwort-Hashing:** bcrypt (Kosten-Faktor=12)
- **CSRF-Schutz:** Token-basierte Schutzmechanismen
- **XSS-Prävention:** Automatische Ausgabe-escaping
- **SQL-Injection:** Préparierte Statements
- **Sitzungssicherheit:** HttpOnly, Secure, SameSite-Cookies
- **Ratenbegrenzung:** DDoS-Schutz

Siehe [Sicherheitsdokumentation](runwayhub/docs/security.md) für detaillierte Informationen.

---

## 🚀 Warum Virtual Airline Manager?

- **Kostenlos:** Keine Lizenzgebühren oder versteckte Kosten
- **Open Source:** Vollständiger Quellcode verfügbar
- **Selbstgehostet:** Vollständige Kontrolle über Ihre Daten
- **Datenschutz:** Alle Daten bleiben auf Ihrem Server
- **Multi-Airline:** Funktioniert mit mehreren Airlines gleichzeitig
- **Eigene ACARS-Technologie** - Proprietäre Echtzeitflugstatus-Integration
- **Sicher:** Enterprise-grade Sicherheitsstandards

---

**Version:** 2.0.3  
**Build:** 2026-05-28  
**Status:** ✅ Production Ready  
**Lizenz:** MIT

---

![RunwayHub](https://runwayhub.github.io/assets/og-image.jpg)
