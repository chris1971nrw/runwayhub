# OpenAIP Integration

OpenAIP (Open Aviation Instrument Procedures) ist eine Open-Source-Datenbank für Fluginformationen. Diese Integration ermöglicht RunwayHub, Echtzeit-Fluginformationen von OpenAIP zu konsumieren und lokal zu speichern.

## Dateien

- `Client.php` - Haupt-Client für OpenAIP API
- `Airport.php` - Flughafen-Daten
- `Waypoint.php` - Wegpunkt-Daten
- `Airway.php` - Luftweg-Daten
- `Navaid.php` - Navigationshilfe-Daten
- `DatabaseSchema.php` - SQL-Schema Generator
- `README.md` - Diese Datei

## Installation

1. Kopieren Sie die OpenAIP-Klassen in `src/core/OpenAIP/`
2. Führen Sie die Migration aus: `database/migrations/20260526000001_create_openaip_tables.sql`
3. Stellen Sie `OPENAIP_API_KEY` in `.env` ein
4. Führen Sie `php src/artisan openaip:sync --force` aus

## Nutzung

### PHP-Code

```php
use RunwayHub\Core\OpenAIP\Client;

$client = new Client();

// Alle Flughäfen holen
$airports = $client->getAirports('country=DE', 100);

// Einzelnen Flughafen
$airport = $client->getAirport('EDDM');

// Synchronisation
$result = $client->sync();
```

### REST API

```bash
# Alle Flughäfen
curl https://yoursite.com/api/openaip/airports?limit=100

# Einzelner Flughafen
curl https://yoursite.com/api/openaip/airports/EDDM

# Sync ausführen
curl -X POST https://yoursite.com/api/openaip/sync
```

## API Endpunkte

| Endpunkt | Methode | Beschreibung |
|----------|---------|--------------|
| `/api/openaip/airports` | GET | Alle Flughäfen |
| `/api/openaip/airports/{id}` | GET | Einzelner Flughafen |
| `/api/openaip/waypoints` | GET | Alle Wegpunkte |
| `/api/openaip/waypoints/{id}` | GET | Einzelfwegpunkt |
| `/api/openaip/airways` | GET | Alle Luftwege |
| `/api/openaip/airways/{id}` | GET | Einzelflugweg |
| `/api/openaip/navaids` | GET | Alle Navigationshilfen |
| `/api/openaip/navaids/{id}` | GET | Einzelnavigationshilfe |
| `/api/openaip/airspace` | GET | Lufträume (optional) |
| `/api/openaip/sync` | POST | Sync ausführen |
| `/api/openaip/status` | GET | Status |
| `/api/openaip/clearcache` | POST | Cache leeren |

## Synchronisation

### Manuell

```bash
php src/artisan openaip:sync
```

### Optionen

```bash
# Nur Flughäfen
php src/artisan openaip:sync --airports

# Mit Limit
php src/artisan openaip:sync --limit=500

# Erzwungen
php src/artisan openaip:sync --force
```

### Automatischer Sync (Cron)

```bash
# Täglich um 03:00 Uhr
0 3 * * * cd /path/to/runwayhub && php src/artisan openaip:sync
```

## Sicherheit

- API-Key in `.env` speichern
- Keine Hardcoded Keys
- SSL/TLS Verbindungen
- Input Validation

## Lizenz

MIT License
