# RunwayHub v1.0.0

## 🎉 Release Notes

Wir freuen uns, die erste Version von **RunwayHub** zu präsentieren!

## ✨ Highlights

### ✈️ Flugmanagement
- ✅ **CREATE, READ, UPDATE, DELETE** Flüge
- ✅ **ACARS-Integration** für Echtzeit-Status
- ✅ **Flugverlauf** (Flight History)
- ✅ **Verspätungen** und **Stornierungen**

### ✈️ Flottenmanagement
- ✅ **Flugzeug-Verwaltung**
- ✅ **Wartungsplanung**
- ✅ **Flugzeug-Status**
- ✅ **Inspektionen**

### 👨‍✈️ Pilotenmanagement
- ✅ **Piloten-Verzeichnis**
- ✅ **Zertifizierungen**
- ✅ **Deaktivierung** (verhindern)
- ✅ **Zuflug-Zuweisung**

### 🎫 Buchungsverwaltung
- ✅ **Buchungen** erstellen
- ✅ **Verfügbarkeit** prüfen
- ✅ **Passagier-Daten**
- ✅ **Sitzplatz-Management**

### 📡 ACARS-Integration
- ✅ **Echtzeit-Flugstatus**
- ✅ **Wetter-Daten** (OpenMeteo)
- ✅ **Metar/TAF-Daten**
- ✅ **Caching** (1 Stunde)

### 🌤️ Wetter-API
- ✅ **OpenMeteo Integration**
- ✅ **Wetter-Vorhersage**
- ✅ **METAR/TAF-Daten**
- ✅ **Wetter-Cache**

### 🔐 Admin-Management
- ✅ **Admin-Account** (`admin/admin123`)
- ✅ **Passwort-Änderung**
- ✅ **Login-Logging**
- ✅ **Airline-Zugriff**
- ✅ **Update-Checker**
- ✅ **Issue-Reporting**

### 🔄 Update-Management
- ✅ **GitHub Releases** Integration
- ✅ **Update-Nachricht** auf Dashboard
- ✅ **Automatisches Update** auf Anfrage
- ✅ **Cache-Verwaltung** (1 Stunde)

### 🐛 Issue-Reporting
- ✅ **Dashboard-Modal**
- ✅ **Logfile-Anhang** automatisch
- ✅ **GitHub Issues API**
- ✅ **Fehlerdiagnose**

### 🐳 Docker-Support
- ✅ **docker-compose.yml**
- ✅ **Dockerfile**
- ✅ **Environment-Variables**
- ✅ **Container-Deployment**

### 🌐 Web-Installation
- ✅ **install.php**
- ✅ **Datenbank-Initialisierung**
- ✅ **Admin-Account**
- ✅ **HTTPS-Empfehlung**

### 📚 Vollständige Dokumentation
- ✅ **README.md**
- ✅ **CHANGELOG.md**
- ✅ **INSTALLATION.md**
- ✅ **USER_HANDBUCH.md**
- ✅ **DEPLOYMENT.md**
- ✅ **README_DOCKER.md**

### 🔒 Sicherheit
- ✅ **Passwort-Hashing**
- ✅ **Login-Logging**
- ✅ **Admin-Zugriffskontrolle**
- ✅ **HTTPS-Empfehlung**
- ✅ **Rate Limiting**
- ✅ **CORS-Konfiguration**

### 🧪 Tests & Security
- ✅ **GitHub Actions** Workflow
- ✅ **PHP Syntax Check**
- ✅ **API Health Check**
- ✅ **ACARS Tests**
- ✅ **Weather Tests**
- ✅ **Security Audit**
- ✅ **Dependency Audit**

## 📋 System-Anforderungen

- PHP 8.3.6+
- Composer 2.0+
- SQLite 3.39+
- Git 2.30+
- Webserver (Apache, Nginx)
- Docker (optional)

## 📦 Installation

### Web-basiert

```bash
unzip runwayhub.zip
cd runwayhub
cp .env.example .env
php scripts/init-database.php
php install.php
```

### Docker

```bash
cp .env.example .env
docker-compose up -d --build
```

Zur Hauptseite:

```bash
http://dein-domain.de/install.php
```

## 🔐 Admin-Account

**Benutzer:** `admin`  
**Passwort:** `admin123`  

> ⚠️ **Wichtig:** Passwort nach dem ersten Login ändern!

## 🔌 API-Endpunkte

### Flight API
- `GET /api/flights` - Alle Flüge
- `POST /api/flights` - Flug erstellen
- `GET /api/flights/{id}` - Flug anzeigen
- `PUT /api/flights/{id}` - Flug aktualisieren
- `DELETE /api/flights/{id}` - Flug löschen

### Aircraft API
- `GET /api/aircrafts` - Alle Flugzeuge
- `POST /api/aircrafts` - Flugzeug erstellen
- `GET /api/aircrafts/{id}` - Flugzeug anzeigen
- `PUT /api/aircrafts/{id}` - Flugzeug aktualisieren
- `DELETE /api/aircrafts/{id}` - Flugzeug löschen

### Pilot API
- `GET /api/pilots` - Alle Piloten
- `POST /api/pilots` - Pilot erstellen
- `GET /api/pilots/{id}` - Pilot anzeigen
- `PUT /api/pilots/{id}` - Pilot aktualisieren
- `DELETE /api/pilots/{id}` - Pilot löschen

### Booking API
- `GET /api/bookings` - Alle Buchungen
- `POST /api/bookings` - Buchung erstellen
- `GET /api/bookings/{id}` - Buchung anzeigen
- `PUT /api/bookings/{id}` - Buchung aktualisieren
- `DELETE /api/bookings/{id}` - Buchung löschen

### Admin API
- `POST /api/admin/login` - Admin-Login
- `POST /api/admin/logout` - Admin-Logout
- `POST /api/admin/change-password` - Passwort ändern
- `GET /api/admin/profile` - Profil abrufen
- `GET /api/admin/stats` - Statistiken
- `GET /api/admin/check-update` - Version-Check
- `POST /api/admin/issues/submit` - Issue erstellen

## 📡 ACARS-Tracking

### Flight Status API

```bash
curl -X GET http://dein-domain.de/api/acars/flights?flight_number=LH456
```

### Wetter API

```bash
curl -X GET "http://dein-domain.de/api/weather?origin=FRA&destination=JFK"
```

## 🔒 Sicherheit

- ✅ Passwort-Hashing (PASSWORD_DEFAULT)
- ✅ Login-Logging
- ✅ Admin-Zugriffskontrolle
- ✅ HTTPS-Empfehlung
- ✅ Rate Limiting
- ✅ CORS-Konfiguration

### Rate Limiting

```env
RATE_LIMIT_ENABLED=true
RATE_LIMIT_REQUESTS=60
RATE_LIMIT_DURATION=60
```

### CORS

```env
CORS_ORIGIN=http://dein-domain.de
```

## 📊 Datenbank

**SQLite** (Standard):

```env
DB_CONNECTION=sqlite
DB_DATABASE=/app/database.sqlite
```

**MySQL/MariaDB**:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=runwayhub
DB_USERNAME=runwayhub
DB_PASSWORD=***
```

**PostgreSQL**:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=runwayhub
DB_USERNAME=runwayhub
DB_PASSWORD=***
```

## 📝 Datenbank-Tabellen

- `flights` - Flugmanagement
- `flight_history` - Flugverlauf
- `aircrafts` - Flugzeugmanagement
- `maintenance` - Wartungsplanung
- `pilots` - Pilotenverzeichnis
- `pilot_history` - Pilotenverlauf
- `bookings` - Buchungsverwaltung
- `passengers` - Passagierdaten
- `seats` - Sitzplatzmanagement
- `weather_cache` - Wetter-Daten
- `acars_flights` - ACARS-Tracking
- `airlines` - Airlines-Management
- `admins` - Admin-Accounts

## ✨ Features

### Dashboard

- ✅ Statistiken
- ✅ Navigation
- ✅ Update-Nachricht
- ✅ Issue-Reporting

### Flugmanagement

- ✅ Alle Flüge
- ✅ Flug Status
- ✅ Flug Details
- ✅ Flug erstellen
- ✅ Flug aktualisieren
- ✅ Flug löschen

### Flottenmanagement

- ✅ Alle Flugzeuge
- ✅ Flugzeug Details
- ✅ Flugzeug erstellen
- ✅ Flugzeug aktualisieren
- ✅ Wartung planen
- ✅ Wartungstermine

### Pilotenmanagement

- ✅ Alle Piloten
- ✅ Pilot Details
- ✅ Pilot erstellen
- ✅ Pilot deaktivieren
- ✅ Zu Flug zuweisen
- ✅ Pilotenverlauf

### Buchungsverwaltung

- ✅ Alle Buchungen
- ✅ Buchung Details
- ✅ Verfügbarkeit prüfen
- ✅ Buchung erstellen
- ✅ Buchung aktualisieren
- ✅ Buchung löschen

### ACARS-Integration

- ✅ Flight Status API
- ✅ Status-Typen
- ✅ Update-Nachrichten
- ✅ Caching (1 Stunde)

### Wetter-API

- ✅ OpenMeteo Integration
- ✅ Wetterdaten
- ✅ METAR-TAF-Daten
- ✅ Wetter-Cache

### Admin-Funktionen

- ✅ Login/Logout
- ✅ Passwort-Änderung
- ✅ Profil-Verwaltung
- ✅ Statistiken
- ✅ Version-Check
- ✅ Update durchzuführen
- ✅ Issue-Reporting

### Deployment

- ✅ install.php
- ✅ Docker-Support
- ✅ Deployment-Paket (tar.gz)
- ✅ Installation-Guide
- ✅ Docker-Support

## 🔐 Sicherheit

- ✅ Passwort-Hashing
- ✅ Login-Logging
- ✅ Admin-Zugriffskontrolle
- ✅ HTTPS-Empfehlung
- ✅ Rate Limiting
- ✅ CORS-Konfiguration

## 🔧 Troubleshooting

### Fehler: Datenbank nicht gefunden

```bash
php scripts/init-database.php
```

### Fehler: PHP-Fehler

```bash
php -v
php -m
```

### Fehler: SQLite nicht gefunden

```bash
sudo apt-get install sqlite3
```

### Fehler: Docker nicht gefunden

```bash
curl -fsSL https://get.docker.com | bash -s docker
```

## 📞 Support

**GitHub Issues:** https://github.com/chris1971nrw/runwayhub/issues  
**Documentation:** https://github.com/chris1971nrw/runwayhub/blob/main/README.md  
**Email:** support@runwayhub.de

## 📦 Release v1.0.0

- ✅ Flugmanagement
- ✅ Flottenmanagement
- ✅ Pilotenmanagement
- ✅ Buchungsverwaltung
- ✅ ACARS-Integration
- ✅ Wetter-API
- ✅ Admin-Management
- ✅ Update-Checker
- ✅ Issue-Reporting
- ✅ Docker-Support
- ✅ Web-Installation
- ✅ Produktions-ready

### Changelog

Siehe [CHANGELOG.md](./CHANGELOG.md)

### Lizenz

MIT License

## 📝 Related

- [GitHub Repository](https://github.com/chris1971nrw/runwayhub)
- [GitHub Issues](https://github.com/chris1971nrw/runwayhub/issues)
- [API-Documentation](./USER_HANDBUCH.md)
- [Deployment-Guide](./DEPLOYMENT.md)
