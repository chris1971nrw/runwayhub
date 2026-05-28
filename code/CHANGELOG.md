# RunwayHub - Changelog

Alle bedeutenden Änderungen an RunwayHub werden in diesem Dokument dokumentiert.

## [Unreleased]

### 🐛 Issue-Reporting

#### Features
- **Issue-Reporting** auf Admin-Dashboard
- **Logfile-Anhang** automatisch
- **GitHub Issues API** Integration

#### API
- `POST /api/admin/issues/submit` - Issue erstellen
- `GET /api/admin/issues/status/{issue_number}` - Issue Status

#### Dashboard
- **Issue-Modal** auf Admin-Dashboard
- **Button** "🐛 Issue erstellen"
- **Logfile-Anhang** automatisch

### 🔐 Admin-Management

#### Features
- **Admin-Account** mit Airline-Zugriff
- **Passwort-Änderung** für alle Admins
- **Login-Activity-Logging**

#### API
- `POST /api/admin/login` - Admin-Login
- `POST /api/admin/logout` - Admin-Logout
- `POST /api/admin/change-password` - Passwort ändern
- `GET /api/admin/profile` - Profil abrufen
- `GET /api/admin/stats` - Admin-Statistiken

### 🔄 Update-Management

#### Features
- **Update-Checker** mit GitHub Releases API
- **Update-Nachricht** auf Dashboard
- **Automatisches Update** auf Anfrage
- **Cache-Verwaltung** (1 Stunde)

#### API
- `GET /api/admin/check-update` - Version-Check

### ✈️ Airline-Management

#### Features
- **Alle Airlines** verwalten (CREATE, READ, UPDATE, DELETE)
- **Flugzeugverwaltung**
- **Piloten-Management**
- **Buchungsverwaltung**

### 📦 Produktions-System

#### Features
- **install.php** - Web-basierte Installation
- **Docker**-Support (docker-compose.yml, Dockerfile)
- **Deployment-Paket** (tar.gz)
- **Installation-Guide** (DEPLOYMENT.md)

### 🔧 Fehlerkorrektur

#### Fixes
- **index.php** mit korrektem Controller-Routing
- **Controller-Name** extrahiert aus Pfad
- **Controller-Instance** erstellt

## [1.0.0] - 2026-05-28

### 🎯 Release-Ziel

**Produktions-bereites** Virtual Airline Management System mit:
- ✅ **Fluggesellschaft Management**
- ✅ **Flug-Management**
- ✅ **Flugzeug-Management**
- ✅ **Piloten-Management**
- ✅ **Buchungs-Management**
- ✅ **ACARS Integration**
- ✅ **Wetter-API** (OpenMeteo)
- ✅ **Admin-Management**
- ✅ **Update-Management**
- ✅ **Issue-Reporting**

### 📋 Features

#### Frontend
- ✅ **Admin-Dashboard** mit Statistiken
- ✅ **Flugbrett** mit Live-Status
- ✅ **Piloten-Verzeichnis**
- ✅ **Buchungsverwaltung**
- ✅ **Wartungsplanung**

#### Backend
- ✅ **SQLite**-Datenbank
- ✅ **RESTful API**
- ✅ **ACARS**-Integration (Mock-Daten)
- ✅ **Wetter-API** (OpenMeteo)
- ✅ **Email**-Verwaltung (SMTP)

#### Administration
- ✅ **Admin-Account** (`admin/admin123`)
- ✅ **Passwort-Änderung**
- ✅ **Login-Logging**
- ✅ **Airline-Zugriff**
- ✅ **Version-Check**
- ✅ **Issue-Reporting**

#### Deployment
- ✅ **Docker**-Support
- ✅ **install.php**
- ✅ **tar.gz** - Deployment-Paket
- ✅ **Dokumentation**

### 📚 Dokumentation

- ✅ **README.md** - Projektbeschreibung
- ✅ **DEPLOYMENT.md** - Installation-Guide
- ✅ **README_DOCKER.md** - Docker-Setup
- ✅ **CHANGELOG.md** - Änderungen

### 🔒 Sicherheit

- ✅ **Passwort-Hashing** (PASSWORD_DEFAULT)
- ✅ **Login-Logging**
- ✅ **Admin-Zugriffskontrolle**
- ✅ **HTTPS**-empfehlung
- ✅ **Rate Limiting**

### 📊 Datenbank

- ✅ **flights** - Flugmanagement
- ✅ **flight_history** - Flugverlauf
- ✅ **aircrafts** - Flugzeugmanagement
- ✅ **maintenance** - Wartungsplanung
- ✅ **pilots** - Pilotenverzeichnis
- ✅ **pilot_history** - Pilotenverlauf
- ✅ **bookings** - Buchungsverwaltung
- ✅ **passengers** - Passagierdaten
- ✅ **seats** - Sitzplatzmanagement
- ✅ **weather_cache** - Wetter-Daten
- ✅ **acars_flights** - ACARS-Tracking
- ✅ **airlines** - Airlines-Management
- ✅ **admins** - Admin-Accounts

### 📝 API-Endpunkte

#### Flight API
- `GET /api/flights` - Alle Flüge
- `POST /api/flights` - Flug erstellen
- `GET /api/flights/{id}` - Flug anzeigen
- `PUT /api/flights/{id}` - Flug aktualisieren
- `DELETE /api/flights/{id}` - Flug löschen

#### Aircraft API
- `GET /api/aircrafts` - Alle Flugzeuge
- `POST /api/aircrafts` - Flugzeug erstellen
- `GET /api/aircrafts/{id}` - Flugzeug anzeigen
- `PUT /api/aircrafts/{id}` - Flugzeug aktualisieren
- `DELETE /api/aircrafts/{id}` - Flugzeug löschen

#### Pilot API
- `GET /api/pilots` - Alle Piloten
- `POST /api/pilots` - Pilot erstellen
- `GET /api/pilots/{id}` - Pilot anzeigen
- `PUT /api/pilots/{id}` - Pilot aktualisieren
- `DELETE /api/pilots/{id}` - Pilot löschen

#### Booking API
- `GET /api/bookings` - Alle Buchungen
- `POST /api/bookings` - Buchung erstellen
- `GET /api/bookings/{id}` - Buchung anzeigen
- `PUT /api/bookings/{id}` - Buchung aktualisieren
- `DELETE /api/bookings/{id}` - Buchung löschen

#### Admin API
- `POST /api/admin/login` - Admin-Login
- `POST /api/admin/logout` - Admin-Logout
- `POST /api/admin/change-password` - Passwort ändern
- `GET /api/admin/profile` - Profil abrufen
- `GET /api/admin/stats` - Statistiken
- `GET /api/admin/check-update` - Version-Check
- `POST /api/admin/issues/submit` - Issue erstellen

### 🚀 Installation

```bash
# ZIP extrahieren
unzip runwayhub.zip

# Installation
php install.php

# Zur Hauptseite
http://dein-domain.de/install.php
```

### 🐳 Docker

```bash
# Umgebungsvariablen kopieren
cp .env.example .env

# Container starten
docker-compose up -d --build

# Zur Hauptseite
http://dein-domain.de:8080/install.php
```

### 📞 Support

**GitHub Issues:** https://github.com/chris1971nrw/runwayhub/issues  
**Documentation:** https://github.com/chris1971nrw/runwayhub/blob/main/README.md  

### 📝 Lizenz

MIT License
