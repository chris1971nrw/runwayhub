# Datenbank-Migrationen

Diese Verzeichnis enthält SQL-Migrationen für RunwayHub.

## Migration-Ordnerstruktur

```
database/migrations/
├── README.md                    # Diese Datei
├── 001_create_users.sql         # Benutzer
├── 002_create_aircrafts.sql     # Flugzeuge
├── 003_create_airports.sql      # Flughäfen
├── 004_create_routes.sql        # Routen
├── 005_create_flights.sql       # Flüge
├── 006_create_bookings.sql      # Buchungen
├── 007_create_pilots.sql        # Piloten
├── 008_create_pireps.sql        # PIREPs
└── 009_create_roles.sql         # Rollen & Rechte
```

## Migrationen ausführen

### Installation
```bash
cd /var/www/runwayhub
mysql -u root -p < database/migrations/001_create_users.sql
mysql -u root -p < database/migrations/002_create_aircrafts.sql
# usw.
```

### CLI-Migrationstool
```bash
php runwayhub/cli/migrate.php install
php runwayhub/cli/migrate.php migrate
php runwayhub/cli/migrate.php rollback
```

## Migration-Schema

```sql
-- Migration 001: create_users.sql
-- Benutzer-Verwaltung

-- Migration 002: create_aircrafts.sql
-- Flugzeug-Verwaltung

-- Migration 003: create_airports.sql
-- Flughafen-Daten

-- Migration 004: create_routes.sql
-- Routen-Definition

-- Migration 005: create_flights.sql
-- Flug-Planung

-- Migration 006: create_bookings.sql
-- Buchungssystem

-- Migration 007: create_pilots.sql
-- Piloten-Verwaltung

-- Migration 008: create_pireps.sql
-- PIREP-System

-- Migration 009: create_roles.sql
-- Rollen & Rechte
```

## Rollback

Jede Migration kann mit folgendem Befehl zurückgerollt werden:

```bash
php runwayhub/cli/migrate.php rollback --migration=001
```

## Rollback-Skripte

Für jede Migration gibt es ein entsprechendes Rollback-Skript:

- `rollback-001_create_users.sql`
- `rollback-002_create_aircrafts.sql`
- etc.

## Best Practices

1. **Backup vor Migration**
   ```bash
   mysqldump -u dbuser -p database > backup.sql
   ```

2. **Staging-Umgebung**
   Testen vor Production-Deploy

3. **Migration Log**
   Verwende die migrations-Log-Table

4. **Datenbank-Indexe**
   Nach Migration Indexe optimieren

5. **Transaktionen**
   Verwendung von BEGIN/COMMIT

## Beispiel-Migration

```sql
-- Migration 001: create_users.sql
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('admin', 'staff', 'pilot', 'guest') NOT NULL DEFAULT 'guest',
  `avatar` VARCHAR(255) DEFAULT NULL,
  `last_login` TIMESTAMP NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

## Datenbank-Optimierung

```sql
-- Optimierung nach Migration
ANALYZE TABLE users;
OPTIMIZE TABLE aircrafts;

-- Index-Erstellung
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_aircrafts_status ON aircrafts(status);
```

## Migration-Tools

Empfohlene Tools für Datenbank-Management:
- phpMyAdmin
- mysqlconsole
- Migration CLI Tools

## Support

Bei Fragen:
- GitHub Issues
- Community Forum
- docs@runwayhub.de
