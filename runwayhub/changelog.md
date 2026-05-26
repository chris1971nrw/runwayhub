# Changelog

Alle Änderungen an RunwayHub werden hier dokumentiert.

## [2.0.0] - 2026-05-26

### Hinzugefügt

- **OpenAIP API Integration** ✅
  - `src/core/OpenAIP/Client.php` - Haupt-Client für OpenAIP API
  - `src/core/OpenAIP/Airport.php` - Flughafen-Daten
  - `src/core/OpenAIP/Waypoint.php` - Wegpunkt-Daten
  - `src/core/OpenAIP/Airway.php` - Luftweg-Daten
  - `src/core/OpenAIP/Navaid.php` - Navigationshilfe-Daten
  - `api/openaip.php` - REST-Endpoints für OpenAIP-Daten
  - `src/artisan/commands/OpenAIPSyncCommand.php` - Synchronisations-Command
  - `database/migrations/20260526000001_create_openaip_tables.sql` - Datenbank-Migration
  - `database/migrations/README.md` - Migration-Dokumentation
  - `docs/openaip.md` - Umfassende Dokumentation
  - `docs/features.md` - Features-Übersicht
  - `docs/roadmap.md` - Entwicklungspfad
  - `docs/tech_notes.md` - Technische Notizen
  - `src/core/OpenAIP/README.md` - OpenAIP-Ordner README
  - `i18n/de/openaip.php` - Deutsche Übersetzungen
  - `i18n/en/openaip.php` - Englische Übersetzungen
  - `runwayhub/.env.example` - Umgebungsvariablen
  - `runwayhub/README.md` - Projektdokumentation
  - `tests/OpenAIP/` - PHPUnit Tests

- **Demo-System** ✅
  - Autonome Demo-Agenten
  - GitHub Issues Watcher
  - Synchronisation mit Haupt-System
  - Feature-Flags (Demo vs Production)
  - Rollback-Plan
  - GitHub Pages Deployment

### REST-Endpunkte (12 Endpunkte)

### REST-Endpunkte (12 Endpunkte)

- `GET /api/openaip/airports` - Alle Flughäfen
- `GET /api/openaip/airports/{id}` - Einzelner Flughafen
- `GET /api/openaip/waypoints` - Alle Wegpunkte
- `GET /api/openaip/waypoints/{id}` - Einzelfwegpunkt
- `GET /api/openaip/airways` - Alle Luftwege
- `GET /api/openaip/airways/{id}` - Einzelflugweg
- `GET /api/openaip/navaids` - Alle Navigationshilfen
- `GET /api/openaip/navaids/{id}` - Einzelnavigationshilfe
- `GET /api/openaip/airspace` - Lufträume (optional)
- `GET /api/openaip/airspace/{id}` - Einzelner Luftraum
- `POST /api/openaip/sync` - Sync ausführen
- `GET /api/openaip/status` - Sync-Status
- `POST /api/openaip/clearcache` - Cache leeren

### Features

- ✅ Automatische Daten-Synchronisation
- ✅ Datenbank-Cache mit Offline-Fallback
- ✅ API-Key Management (sicher in .env)
- ✅ Synchronisations-Command (`php artisan openaip:sync`)
- ✅ Cron-Job-Unterstützung für automatischen Sync
- ✅ Filter- und Limit-Funktionen für API-Aufrufe
- ✅ Cache TTL 5 Minuten
- ✅ Fehler logging bei API-Aufrufen
- ✅ Offline-Fallback auf lokale Datenbank

### Sicherheit

- ✅ API-Key wird aus `.env` geladen
- ✅ Fallback-Value: `4eafd2e1740e235b937c362c0e3074f9`
- ✅ Fehler logging bei API-Aufrufen
- ✅ SSL/TLS Verbindungen
- ✅ Input Validation

### Datenbank

- ✅ Neue Tabellen: `airports_openaip`, `waypoints_openaip`, `airways_openaip`, `navaids_openaip`, `airspace_openaip`
- ✅ Indexe für Performance-Optimierung
- ✅ Timestamps für Synchronisations-Tracking
- ✅ UTF-8 Encoding

### API-Response Format

```json
{
    "success": true,
    "count": 100,
    "data": [
        {
            "id": 1,
            "name": "Munich Airport",
            "iata": "MUC",
            "icao": "EDDM",
            "latitude": 48.3538,
            "longitude": 11.7861
        }
    ]
}
```

## [1.0.0] - 2026-05-01

### Initial Release

- Grundlegende Projektstruktur
- Core-System (Database, Request, Response)
- Entity-Klassen
- Repository-Klassen
- Basis-Controller-Struktur
- i18n-System (DE/EN)
- Tests (PHPUnit)
- README.md
- .gitignore
