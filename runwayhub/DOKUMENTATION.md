# RunwayHub - Vollständige Dokumentation

**Version:** 1.0.0  
**Datum:** 2026-05-28  
**Status:** Released

---

## 📖 Inhaltsverzeichnis

1. [Einleitung](#einleitung)
2. [Installation](#installation)
3. [Konfiguration](#konfiguration)
4. [API Endpunkte](#api-endpunkte)
5. [Sicherheit](#sicherheit)
6. [Fehlerbehebung](#fehlerbehebung)
7. [Deployment](#deployment)
8. [Best Practices](#best-practices)

---

## Einleitung

RunwayHub ist ein modernes Virtual Airline Management System für Flugsimulation. Es bietet Multi-Airline Support, Live-Flugverfolgung, Wetter-API Integration und VA Management.

### 🌟 Features

- **Multi-Airline Support** - Verwalten Sie mehrere Virtual Airlines
- **Live-Flugverfolgung** - Integration mit FlightAware API
- **Wetter-API** - METAR/TAF, Alerts, PIREP
- **VA Management** - Erstellen & Verbinden von Virtual Airlines
- **Login System** - Callsign/Passwort Authentifizierung
- **ACARS Client** - Flugdaten-Erfassung

### 📊 System Requirements

- **PHP:** 8.3+
- **Database:** SQLite oder MySQL
- **RAM:** 256MB+
- **OS:** Linux/macOS/Windows

---

## Installation

### 1. Download & Extrahieren

```bash
wget https://github.com/chris1971nrw/runwayhub/releases/download/v1.0.0/runwayhub-v1.0.0.tar.gz
tar -xzf runwayhub-v1.0.0.tar.gz
cd runwayhub
```

### 2. Dependencies installieren

```bash
composer install
```

### 3. Server starten

```bash
php -S localhost:8000 -t public
```

### 4. Browser öffnen

```
http://localhost:8000
```

---

## Konfiguration

### Database Config

```bash
# SQLite (empfohlen)
# Keine weitere Konfiguration nötig

# MySQL (optional)
cp .env.example .env
nano .env
```

### .env Variablen

```bash
# Database
DB_DRIVER=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=runwayhub
DB_USERNAME=root
DB_PASSWORD=***
```

---

## API Endpunkte

### Login

```bash
POST /api/login-pilot.php
Content-Type: application/json

{
  "callsign": "demo_pilot",
  "password": "***"
}
```

**Response:**
```json
{
  "success": true,
  "user": {
    "id": "pilot-001",
    "callsign": "demo_pilot",
    "airline": "Lufthansa"
  },
  "sessionToken": "uuid-generated-token",
  "sessionId": "uuid-session-id",
  "groups": ["pilots"]
}
```

### VA Erstellen

```bash
POST /api/va-create.php
Content-Type: application/json

{
  "name": "Deutsche Airline",
  "airlineName": "Deutsche Airline",
  "website": "https://www.deutsche-airline.de",
  "logo": "logo.png",
  "colors": {
    "primary": "#000000",
    "secondary": "#ffffff"
  }
}
```

### VA Verbinden

```bash
POST /api/va-connect.php
Content-Type: application/json

{
  "ownerCredentials": {
    "username": "user123",
    "password": "***"
  },
  "website": "https://www.deutsche-airline.de"
}
```

### VA Liste

```bash
GET /api/va/list
```

---

## Sicherheit

### Implementierte Schutzmechanismen

- ✅ **bcrypt Passwörter** (cost=12)
- ✅ **HttpOnly Cookies**
- ✅ **Secure Flags**
- ✅ **SameSite Cookies**
- ✅ **SQL Injection Schutz**
- ✅ **XSS Schutz**
- ✅ **CSP Headers**
- ✅ **HSTS**
- ✅ **X-Frame-Options**
- ✅ **Content Security Policy**

### Best Practices

1. **Passwörter niemals im Code speichern**
2. **Immer HTTPS verwenden (Production)**
3. **Session Tokens regelmäßig rotieren**
4. **CORS nur für vertrauenswürdige Domains aktivieren**
5. **Regelmäßige Sicherheitsupdates**

---

## Fehlerbehebung

### Datenbankfehler

```bash
# SQLite Datei prüfen
ls -la runwayhub.sqlite

# Datenbank neu erstellen
rm runwayhub.sqlite
php src/cli/migrate.php
```

### API nicht erreichbar

```bash
# Server prüfen
ps aux | grep php

# Server neu starten
php -S localhost:8000 -t public
```

---

## Deployment

### Production Deployment

```bash
# 1. Environment vorbereiten
cp .env.example .env
nano .env
# Production settings setzen

# 2. Security Headers
# .htaccess für Apache

# 3. SSL/TLS
certbot --nginx -d yourdomain.com

# 4. Database Backup
mysqldump -u root -p runwayhub > backup.sql

# 5. Deploy
rsync -avz /path/to/runwayhub/ user@server:/var/www/runwayhub/
```

---

## Best Practices

### Development

1. **Git Hooks nutzen**
2. **Code Quality Tools**
3. **Unit Tests schreiben**
4. **Dokumentation aktuell halten**

### Testing

```bash
# Unit Tests
composer test

# Code Quality
composer lint
composer stan
```

---

## Contributors

- **Developer:** Chris 1971 NRW
- **Email:** chris1971nrw@ab.de
- **GitHub:** [@chris1971nrw](https://github.com/chris1971nrw)

---

**Built with ❤️ by @chris1971nrw**

**Licensed under MIT**

**© 2026 RunwayHub**
