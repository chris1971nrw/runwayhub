# RunwayHub - Docker Deployment

## Schnellstart

### 1. Umgebung konfigurieren

```bash
cp .env.example .env
nano .env  # Konfiguration anpassen
```

### 2. Docker starten

```bash
docker-compose up -d --build
```

### 3. Zugreifen

```bash
http://localhost:8080/install.php
```

## Container-Verwaltung

```bash
# Starten
docker-compose up -d --build

# Stoppen
docker-compose down

# Logs anzeigen
docker-compose logs -f

# Container stoppen
docker-compose stop

# Container neu starten
docker-compose restart
```

## Datenbank-Backup

```bash
# SQLite Backup
docker exec runwayhub_app sqlite3 database.sqlite "SELECT * FROM bookings" > backup.sql

# Backup wiederherstellen
docker exec -i runwayhub_app sqlite3 database.sqlite < backup.sql
```

## Umgebungsvariablen

- `APP_NAME` = App-Name
- `APP_PORT` = Port (Standard: 8080)
- `DB_PATH` = Datenbank-Pfad
- `SMTP_HOST` = SMTP-Server
- `MAILER_DOMAIN` = Mail-Domain
- `ALLOW_REGISTRATION` = Registrierung erlauben
- `ALLOW_BOOKING` = Buchung erlauben

## Sicherheit

**⚠️ Produktion:**

- `.env` anpassen
- `ALLOW_REGISTRATION=false`
- `ALLOW_BOOKING=false`
- `ALLOW_ADMIN=false`
- HTTPS aktivieren
