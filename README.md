# RunwayHub - Kostenloser Visualer Fluglotsen-Software

**Modernes, Open Source, Multi-Airline Fluggesellschaft-Management-System**

![Version](https://img.shields.io/badge/version-2.0.3-blue.svg)
![PHP](https://img.shields.io/badge/PHP-8.3+-green.svg)
![License](https://img.shields.io/badge/license-MIT-blue.svg)
![Sicherheit](https://img.shields.io/badge/sicherheit-gehärtet-brightgreen.svg)
![SEO](https://img.shields.io/badge/SEO-97.5%25-green.svg)

---

## 📋 Überblick

RunwayHub ist eine **kostenlose, Open Source**-Fluglotsen-Station für FBOs, Flughäfen und Luftfahrtprofessionals. Entwickelt mit modernem PHP 8.3+, bietet es umfassende Flugmanagement, Wetterintegration und Visual Air Traffic Controller (VA)-Verwaltung.

### Hauptmerkmale

- ✅ **Multi-Airline-Unterstützung** - Kompatibel mit mehreren Airlines und Systemen
- ✅ **Live-Flugüberwachung** - Echtzeitflugstatus via FlightAware API
- ✅ **Wetter-API** - METAR/TAF-Wetterdaten mit Caching
- ✅ **VA-Management** - Erstellen, verwalten und Verbinden von Fluglotsen
- ✅ **Statistik & Berichte** - Umfassende Fluganalysen
- ✅ **PIREP-System** - Integration von Pilotenwetterberichten
- ✅ **Leaderboards** - Verfolgen der Top-Performer
- ✅ **Sicher** - Branchensicherheitsstandards (bcrypt, CSRF, XSS-Prävention)

---

## 🚀 Schnellstart

### Installation

```bash
cd runwayhub
php -S localhost:8000 -t public
```

### Demo-Zugriff

```
Admin:    demo_admin     / admin123
Pilot:    demo_pilot     / pilot123
Guest:    demo_guest     / guest123
```

Besuchen Sie <a href="https://runwayhub.github.io">https://runwayhub.github.io</a>

---

## 📖 Dokumentation

- [**Architektur**](runwayhub/docs/architecture.md) - Systemdesign
- [**Funktionen**](runwayhub/docs/features.md) - vollständige Feature-Liste
- [**Datenbank**](runwayhub/docs/database.md) - Schema-Dokumentation
- [**Deployment**](runwayhub/docs/deployment.md) - Produktionsaufbau
- [**Wetter-API**](runwayhub/docs/weather-api.md) - METAR/TAF-Integration
- [**FlightAware**](runwayhub/docs/flightaware.md) - Flugüberwachung
- [**Sicherheit**](runwayhub/docs/security.md) - Sicherheits-Härtung
- [**Performance**](runwayhub/docs/performance-guide.md) - Optimierung

---

## 🎯 Funktionen

### Kern

- **Multi-Airline-Unterstützung**
  - Kompatibel mit mehreren Airlines
  - Vereinheitlichte Schnittstelle
  
- **Live-Flugüberwachung**
  - Echtzeitflugstatus
  - Flugverlauf
  - Ankunfts- und Abflugbretter
  
- **Wetterintegration**
  - METAR-Wetterberichte
  - TAF-Prognosen
  - Wetterwarnungen

### VA-Management

- **VA-Erstellung** - Neue Fluglotsen erstellen
- **Verbindungs-Management** - Verbinden Ihrer VAs mit dem System
- **Admin-Panel** - Volle Verwaltungsinterface
- **Sichere Sitzungen** - HttpOnly, Sicher, SameSite-Cookies

### API

- **40+ Endpoints** - RESTful API
- **32 Controller** - Volle CRUD-Operationen
- **Ratenbegrenzung** - Schutz vor Missbrauch
- **Dokumentation** - Vollständige API-Docs

---

## 🔧 Technische Stack

- **PHP:** 8.3.6+
- **Datenbank:** SQLite (15 Tabellen)
- **Sicherheit:** bcrypt (Kosten=12), CSRF, XSS-Prävention
- **Caching:** TTL-basiertes Caching (5-300s)
- **API:** RESTful-Architektur
- **Statische HTML:** Schnelle Ladezeiten, SEO-optimiert

---

## 🛡️ Sicherheit

RunwayHub beinhaltet enterprise-grade Sicherheit:

- **Passwort-Hashing:** bcrypt (Kosten=12)
- **CSRF-Schutz:** Token-basiert
- **XSS-Prävention:** Ausgabe-escaping
- **SQL-Injection:** Préparierte Statements
- **Sitzungssicherheit:** HttpOnly, Secure, SameSite-Cookies
- **Ratenbegrenzung:** DDoS-Schutz
- **CSP:** Content Security Policy Header

Siehe [Sicherheitsdokumentation](runwayhub/docs/security.md) für Details.

---

## 📊 Statistik

- **PHP-Dateien:** 144 (alle syntaktisch gültig)
- **API-Endpoints:** 40+
- **Datenbanktabellen:** 15
- **Dokumentation:** 54 Dateien
- **Zeilen Code:** ~65.000

---

## 🎓 Lizenz

MIT License - Kostenlos und Open Source.

```
Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software.
```

---

## 👥 Community

- **GitHub:** [@chris1971nrw](https://github.com/chris1971nrw)
- **Issues:** [Meldung von Fehlern](https://github.com/chris1971nrw/runwayhub/issues)
- **Diskussionen:** [Starten einer Diskussion](https://github.com/chris1971nrw/runwayhub/discussions)
- **Email:** demo@airline.com

---

## 📞 Support

Benötigen Sie Hilfe?

- **Email:** demo@airline.com
- **GitHub Issues:** [Fehler melden](https://github.com/chris1971nrw/runwayhub/issues)
- **Diskussionen:** [Diskussionen beitreten](https://github.com/chris1971nrw/runwayhub/discussions)

---

## 🚀 Warum RunwayHub?

- **Kostenlos:** Keine Lizenzgebühren
- **Open Source:** Vollständiger Quellcode verfügbar
- **Selbstgehostet:** Vollständige Kontrolle über Ihre Daten
- **Datenschutz:** Daten bleiben auf Ihrem Server
- **Multi-Airline:** Funktioniert mit mehreren Airlines
- **Live-Daten:** Echtzeitflugüberwachung
- **Sicher:** Enterprise-grade Sicherheit

---

**Version:** 2.0.3  
**Build:** 2026-05-28  
**Status:** ✅ Production Ready

---

![RunwayHub](https://runwayhub.github.io/assets/og-image.jpg)
