# RunwayHub - Virtual Airline Manager

**Moderne, Open Source, Multi-Airline Fluggesellschaft-Management-Software**

![Version](https://img.shields.io/badge/version-2.0.3-blue.svg)
![PHP](https://img.shields.io/badge/PHP-8.3+-green.svg)
![License](https://img.shields.io/badge/license-MIT-blue.svg)
![Sicherheit](https://img.shields.io/badge/sicherheit-gehärtet-brightgreen.svg)
![SEO](https://img.shields.io/badge/SEO-97.5%25-green.svg)

---

## 📋 Überblick

RunwayHub ist eine **kostenlose, Open Source**-Virtual Airline Manager Software, entwickelt mit modernem PHP 8.3+. Das System bietet umfassende Flugmanagement-Funktionalitäten, Wetterintegration und Virtual Air Traffic Controller (VA)-Verwaltung für FBOs, Flughäfen und Luftfahrtunternehmen.

### Hauptmerkmale

- ✅ **Multi-Airline-Unterstützung** - Kompatibel mit mehreren Airlines und Systemen
- ✅ **Wetter-API** - METAR/TAF-Wetterdaten mit Caching
- ✅ **VA-Management** - Erstellen, verwalten und Verbinden von Virtual Air Traffic Controllern
- ✅ **Statistik & Berichte** - Umfassende Fluganalysen und Reporting
- ✅ **PIREP-System** - Integration von Pilotenwetterberichten
- ✅ **Leaderboards** - Verfolgen der Top-Performer
- ✅ **Sicherheit** - Branchensicherheitsstandards (bcrypt, CSRF, XSS-Prävention)
- ✅ **ACARS-Entwicklung** - Eigene ACARS-Technologie für Echtzeitflugstatus

---

## 🚀 Schnellstart

### Installation und Start

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
- [**Deployment**](runwayhub/docs/deployment.md) - Produktionsaufbau und Deployment
- [**Wetter-API**](runwayhub/docs/weather-api.md) - METAR/TAF-Integration
- [**ACARS**](runwayhub/docs/acars.md) - Eigene ACARS-Entwicklung für Echtzeitflugstatus
- [**Sicherheit**](runwayhub/docs/security.md) - Sicherheits-Härtung und Best Practices
- [**Performance**](runwayhub/docs/performance-guide.md) - Optimierung und Caching

---

## 🎯 Kernfunktionen

### Flight Management

- **Multi-Airline-Unterstützung**
  - Kompatibel mit mehreren Airlines
  - Vereinheitlichte Dateninterface
  
- **Flugüberwachung**
  - Eigene ACARS-Integration für Echtzeitflugstatus
  - Flugverlauf und Historie
  - Ankunfts- und Abflugbretter
  
- **Wetterintegration**
  - METAR-Wetterberichte
  - TAF-Prognosen
  - Wetterwarnungen und Alerts

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
- **32 Controller** - CRUD-Operationen für alle Entitäten
- **Ratenbegrenzung** - Schutz vor Missbrauch und DDoS
- **CORS-Unterstützung** - Cross-Origin Resource Sharing
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
- **CSP:** Content Security Policy Header
- **HSTS:** HTTP Strict Transport Security

Siehe [Sicherheitsdokumentation](runwayhub/docs/security.md) für detaillierte Informationen.

---

## 📊 Technische Daten

- **PHP-Dateien:** 144 (alle syntaktisch gültig)
- **API-Endpoints:** 40+
- **Datenbanktabellen:** 15
- **Dokumentationsdateien:** 54 Dateien
- **Zeilen Code:** ~65.000
- **PHP-Version:** 8.3.6+
- **SQLite:** 15 Tabellen
- **Sicherheit:** Enterprise-grade

---

## 🎓 Lizenz

Dieses Projekt ist unter der MIT-Lizenz veröffentlicht - frei verfügbar und Open Source.

```
Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software.
```

---

## 👥 Community & Support

- **GitHub:** [@chris1971nrw](https://github.com/chris1971nrw)
- **Issues:** [Fehler melden](https://github.com/chris1971nrw/runwayhub/issues)
- **Diskussionen:** [Diskussionen beitreten](https://github.com/chris1971nrw/runwayhub/discussions)
- **Email:** demo@airline.com

---

## 📞 Hilfe & Support

Benötigen Sie Unterstützung?

- **Email:** demo@airline.com
- **GitHub Issues:** [Fehler melden](https://github.com/chris1971nrw/runwayhub/issues)
- **Community:** [Discussions beitreten](https://github.com/chris1971nrw/runwayhub/discussions)

---

## 🚀 Warum Virtual Airline Manager?

- **Kostenlos:** Keine Lizenzgebühren oder versteckte Kosten
- **Open Source:** Vollständiger Quellcode verfügbar
- **Selbstgehostet:** Vollständige Kontrolle über Ihre Daten
- **Datenschutz:** Alle Daten bleiben auf Ihrem Server
- **Multi-Airline:** Funktioniert mit mehreren Airlines gleichzeitig
- **Eigene ACARS-Technologie** - Proprietäre Echtzeitflugstatus-Integration
- **Sicher:** Enterprise-grade Sicherheitsstandards
- **Flexibel:** Vollständige Anpassungsmöglichkeiten

---

## 📰 Blog & Updates

Verfolgen Sie die neuesten Entwicklungen:

- **Flugüberwachungs-Leitfaden**
- **Wetterwarnungen**
- **ACARS-Entwicklungs-Updates**
- **Buchungstutorials**
- **Luftfahrt-News**
- **Nutzer-Tipps**

---

## 🖼️ Screenshots

![Dashboard](https://runwayhub.github.io/assets/screenshots/dashboard.jpg)
![Flight Board](https://runwayhub.github.io/assets/screenshots/flight-board.jpg)

---

**Version:** 2.0.3  
**Build:** 2026-05-28  
**Status:** ✅ Production Ready  
**Lizenz:** MIT

---

![RunwayHub](https://runwayhub.github.io/assets/og-image.jpg)
