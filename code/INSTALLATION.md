# RunwayHub - Installationsanleitung

Dieses Dokument erklärt die Installation von RunwayHub auf Ihrer Maschine.

## 📋 Voraussetzungen

### System-Anforderungen
- **PHP** 8.3.6+
- **Composer** 2.0+
- **SQLite** 3.39+
- **Git** 2.30+
- **Webserver** (Apache, Nginx)
- **Docker** (optional)

### Systemerweiterungen
- `mbstring`
- `PDO`
- `GD`
- `OpenSSL`
- `curl`
- `mbstring`

## 🚀 Web-basierte Installation

### Schritt 1: ZIP extrahieren

```bash
unzip runwayhub.zip
cd runwayhub
```

### Schritt 2: Umgebungsvariablen kopieren

```bash
cp .env.example .env
nano .env
```

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=http://dein-domain.de
DB_CONNECTION=sqlite
DB_DATABASE=/app/database.sqlite
GITHUB_TOKEN=github_pat_xxxxx
SMTP_HOST=smtp.dein-domain.de
SMTP_PORT=587
SMTP_USERNAME=info@dein-domain.de
SMTP_PASSWORD=your-password
```

### Schritt 3: Datenbank initialisieren

```bash
php scripts/init-database.php
```

### Schritt 4: Zur Installation

```bash
# Zur Hauptseite
http://dein-domain.de/install.php
```

### Schritt 5: Admin-Account

**Benutzer:** `admin`  
**Passwort:** `admin123`

> **Wichtig:** Passwort nach dem ersten Login ändern!

### Schritt 6: Fertig

```bash
# Zur Hauptseite
http://dein-domain.de/dashboard.php
```

## 🐳 Docker-Installation

### Schritt 1: Umgebungsvariablen kopieren

```bash
cp .env.example .env
```

### Schritt 2: Container erstellen

```bash
docker-compose up -d --build
```

### Schritt 3: Zur Installation

```bash
http://dein-domain.de:8080/install.php
```

### Schritt 4: Zur Hauptseite

```bash
http://dein-domain.de:8080/dashboard.php
```

## 📊 Datenbankanbindung

### SQLite (Standard)

```env
DB_CONNECTION=sqlite
DB_DATABASE=/app/database.sqlite
```

### MySQL / MariaDB

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=runwayhub
DB_USERNAME=runwayhub
DB_PASSWORD=secret
```

### PostgreSQL

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=runwayhub
DB_USERNAME=runwayhub
DB_PASSWORD=secret
```

## 🔧 Troubleshooting

### Fehler: Datenbank nicht gefunden

```bash
# Datenbank erstellen
php scripts/init-database.php
```

### Fehler: PHP-Fehler

```bash
# PHP-Version prüfen
php -v

# Fehlende Erweiterungen
php -m
```

### Fehler: SQLite nicht gefunden

```bash
# SQLite installieren
sudo apt-get install sqlite3
```

### Fehler: Docker nicht gefunden

```bash
# Docker installieren
curl -fsSL https://get.docker.com | bash -s docker
```

## 🔐 Sicherheit

### HTTPS erzwingen

1. **SSL-Zertifikat** konfigurieren
2. **HTTPS** in `.env` setzen
3. **HTTP-Redirect** aktivieren

### Rate Limiting

```env
RATE_LIMIT_ENABLED=true
RATE_LIMIT_REQUESTS=60
RATE_LIMIT_DURATION=60
```

### CORS-Header

```env
CORS_ORIGIN=http://dein-domain.de
```

## 📝 Next Steps

- ✅ **Installation** abgeschlossen
- ✅ **Datenbank** initialisiert
- ✅ **Admin-Account** erstellt
- ✅ **Dashboard** erreichbar

**Fertig!** 🚀

**Nächste Schritte:**
- Admin-Dashboard besuchen
- Airlines verwalten
- Flüge planen
- Piloten zuweisen
- Buchungen erstellen

**Support:** https://github.com/chris1971nrw/runwayhub/issues  
**Dokumentation:** https://github.com/chris1971nrw/runwayhub/blob/main/README.md  
