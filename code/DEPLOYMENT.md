# RunwayHub - Deployment-Guide

## 🚀 Installation

### 1. Standard-Installation

```bash
# ZIP-Datei extrahieren
unzip runwayhub.zip

# Installation ausführen
php install.php

# Zur Hauptseite
http://dein-domain.de/install.php
```

### 2. Docker-Installation

```bash
# Kopiere Umgebungsvariablen
cp .env.example .env

# Docker-Container starten
docker-compose up -d --build

# Zur Hauptseite
http://dein-domain.de:8080/install.php
```

### 3. Manuelle Installation

```bash
# 1. ZIP extrahieren
unzip runwayhub.zip

# 2. Datenbank initialisieren
php database/init.php

# 3. Standard-Daten
php database/seed.php

# 4. Zur Hauptseite
http://dein-domain.de/index.php
```

## 📦 Deployment auf Server

### Schritte:

1. **ZIP-Datei herunterladen**
   ```bash
   cd /home/christoph/.openclaw/workspace-runwayhub/runwayhub
   tar -czvf runwayhub.tar.gz --exclude="*.git*" --exclude="*.env*" .
   ```

2. **Auf Server übertragen**
   ```bash
   scp runwayhub.tar.gz user@server:/var/www
   tar -xzf runwayhub.tar.gz -C /var/www
   ```

3. **Berechtigungen setzen**
   ```bash
   cd /var/www
   chown -R www-data:www-data .
   chmod -R 755 logs uploads
   ```

4. **Zur Hauptseite**
   ```bash
   http://dein-domain.de/install.php
   ```

## 🐳 Docker Deployment

### Build-Container:

```bash
# Dockerfile erstellen
# docker-compose.yml konfigurieren

# Build
docker-compose build

# Starten
docker-compose up -d
```

### Container-Management:

```bash
# Starten
docker-compose up -d

# Stoppen
docker-compose down

# Logs
docker-compose logs -f

# Backup
docker exec runwayhub_app sqlite3 database.sqlite "SELECT * FROM bookings" > backup.sql
```

## 🔧 Konfiguration

### config/config.php

```php
define('APP_NAME', 'RunwayHub');
define('APP_VERSION', '1.0.0');
define('DB_PATH', __DIR__ . '/database.sqlite');
define('SMTP_HOST', 'smtp.example.com');
define('ADMIN_EMAIL', 'admin@example.com');
```

## 📊 Datenbank

### Backup:

```bash
sqlite3 database.sqlite ".dump" > backup.sql
```

### Wiederherstellen:

```bash
sqlite3 database.sqlite < backup.sql
```

## 🔒 Sicherheit

### Produzptions-Modus:

```php
// config/config.php
define('ALLOW_REGISTRATION', 'false');
define('ALLOW_BOOKING', 'false');
define('ALLOW_ADMIN', 'false');
define('APP_ENV', 'production');
```

## 📝 Next Steps

- ✅ **install.php** erstellt
- ✅ **Docker**-Configuration erstellt
- ✅ **tar.gz** erstellt
- ⚠️ **.env** Beispiel vorhanden
- ⚠️ **Deployment**-Guide erstellt
- ⚠️ **Documentation** zu aktualisieren

## 🎯 Next Steps

**A** - Documentation verbessern
**B** - .gitignore mit .env Regeln
**C** - GitHub release erstellen
**D** - Weitere Deployments
**E** - Eigene Vorlage

Das **System** ist **bereit** für **Deployment**! 🚀
