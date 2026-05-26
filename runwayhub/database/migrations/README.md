# Datenbank-Migrationen

Alle Datenbank-Migrationen befinden sich in diesem Ordner.

## Migrationen

### 20260526000001_create_openaip_tables.sql

Erstellt Tabellen für OpenAIP-Daten:
- `airports_openaip` - Flughäfen
- `waypoints_openaip` - Wegpunkte
- `airways_openaip` - Luftwege
- `navaids_openaip` - Navigationshilfen
- `airspace_openaip` - Lufträume (optional)

## Anwendung

```bash
mysql -u user -p database < database/migrations/20260526000001_create_openaip_tables.sql
```

## Neue Migration

1. Neue `.sql` Datei in diesem Ordner anlegen
2. Migration-Naming-Konvention: `YYYYMMDDHHMMSS_migrationname.sql`
3. Migration ausführen oder in Artisan-Command integrieren
