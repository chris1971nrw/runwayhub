# OpenAIP Integration

OpenAIP (Open Aviation Instrument Procedures) ist eine Open-Source-Datenbank für Fluginformationen. Diese Integration ermöglicht RunwayHub, Echtzeit-Fluginformationen von OpenAIP zu konsumieren und lokal zu speichern.

## API-Informationen

- **API-Endpunkt**: `https://www.openaip.net`
- **API-Key**: `4eafd2e1740e235b937c362c0e3074f9` (speichern Sie diesen sicher in `.env`)
- **Dokumentation**: https://github.com/dokken/openaip

## Installierte Klassen

### `/src/core/OpenAIP/Client.php`
Haupt-Client-Klasse für OpenAIP API. Koordiniert alle API-Operationen.

### `/src/core/OpenAIP/Airport.php`
Verwaltet Flughafen-Daten. Unterstützt:
- `list()` - Liste alle Flughäfen
- `get($id)` - Einzelne Flughafen-Details
- `sync()` - Synchronisiere alle Flughäfen
- `clearCache()` - Cache leeren

### `/src/core/OpenAIP/Waypoint.php`
Verwaltet Wegpunkt-Daten. Unterstützt:
- `list()` - Liste alle Wegpunkte
- `get($id)` - Einzelne Wegpunkt-Details
- `sync()` - Synchronisiere alle Wegpunkte
- `clearCache()` - Cache leeren

### `/src/core/OpenAIP/Airway.php`
Verwaltet Luftweg-Daten. Unterstützt:
- `list()` - Liste alle Luftwege
- `get($id)` - Einzelne Luftweg-Details
- `sync()` - Synchronisiere alle Luftwege
- `clearCache()` - Cache leeren

### `/src/core/OpenAIP/Navaid.php`
Verwaltet Navigationshilfe-Daten (VOR, NDB, VORTAC, ILS, etc.). Unterstützt:
- `list()` - Liste alle Navigationshilfen
- `get($id)` - Einzelnavigationshilfe-Details
- `sync()` - Synchronisiere alle Navigationshilfen
- `clearCache()` - Cache leeren

## REST-Endpunkte

### `/api/openaip/airports`
- **GET**: Liste alle Flughäfen
- **GET `/api/openaip/airports/{id}`**: Einzelner Flughafen
- **GET `/api/openaip/airports?filter=country=DE&limit=100`**: Gefilterte Liste

### `/api/openaip/waypoints`
- **GET**: Liste alle Wegpunkte
- **GET `/api/openaip/waypoints/{id}`**: Einzelfwegpunkt

### `/api/openaip/airways`
- **GET**: Liste alle Luftwege
- **GET `/api/openaip/airways/{id}`**: Einzelflugweg

### `/api/openaip/navaids`
- **GET**: Liste alle Navigationshilfen
- **GET `/api/openaip/navaids/{id}`**: Einzelnavigationshilfe

### `/api/openaip/airspace`
- **GET**: Liste Lufträume (falls verfügbar)
- **GET `/api/openaip/airspace/{id}`**: Einzelner Luftraum

### `/api/openaip/sync`
- **POST**: Führe Synchronisation aus

### `/api/openaip/status`
- **GET**: Hole Sync-Status

### `/api/openaip/clearcache`
- **POST**: Leere Cache

## Datenbank-Schema

Tabellen befinden sich in `/database/migrations/20260526000001_create_openaip_tables.sql`:

- `airports_openaip` - Flughäfen
- `waypoints_openaip` - Wegpunkte
- `airways_openaip` - Luftwege
- `navaids_openaip` - Navigationshilfen
- `airspace_openaip` - Lufträume (optional)

## Synchronisation

### Manuelle Synchronisation

```bash
php src/artisan openaip:sync
```

### Optionen

```bash
# Nur Flughäfen
php src/artisan openaip:sync --airports

# Nur mit Limit
php src/artisan openaip:sync --limit=500

# Erzwingen
php src/artisan openaip:sync --force
```

### Automatischer Sync (Cron)

Füge diesen Eintrag in deine Crontab hinzu:

```bash
# Täglich um 03:00 Uhr
0 3 * * * cd /path/to/runwayhub && php src/artisan openaip:sync
```

## Cache-Strategie

- **TTL**: 300 Sekunden (5 Minuten) für API-Cache
- **Datenbank-Cache**: Daten werden nach jedem Sync in Datenbank gespeichert
- **Offline-Fallback**: Falls OpenAIP nicht erreichbar, werden lokale Datenbank-Daten verwendet

## API-Sicherheit

- API-Key wird aus `.env` geladen oder auf `4eafd2e1740e235b937c362c0e3074f9` fallen zurück
- API-Key sollte in `.env` gespeichert werden:
  ```
  OPENAIP_API_KEY=4eafd2e1740e235b937c362c0e3074f9
  ```
- `.env` sollte in `.gitignore` enthalten

## Datenmodelle

### Flughafen (Airport)

```php
[
    'id' => 1,
    'name' => 'Munich Airport',
    'city' => 'Munich',
    'country' => 'Germany',
    'iata' => 'MUC',
    'icao' => 'EDDM',
    'latitude' => 48.3538,
    'longitude' => 11.7861,
    'elevation' => 455,
    'timezone' => 'Europe/Berlin',
    'weather_station' => 'EDDM',
    'type' => 'airfield',
]
```

### Wegpunkt (Waypoint)

```php
[
    'id' => 1,
    'name' => 'KRENN',
    'type' => 'VORTAC',
    'latitude' => 33.6058,
    'longitude' => -116.8844,
    'country' => 'USA',
    'description' => 'VORTAC',
    'frequency' => '117.9',
]
```

### Luftweg (Airway)

```php
[
    'id' => 1,
    'name' => 'JO515',
    'type' => 'J',
    'latitude' => 33.6058,
    'longitude' => -116.8844,
    'description' => 'J515',
    'airport1' => 'LAX',
    'airport2' => 'KJFK',
]
```

### Navigationshilfe (Navaid)

```php
[
    'id' => 1,
    'name' => 'LAX',
    'type' => 'VORTAC',
    'latitude' => 33.9425,
    'longitude' => -118.4081,
    'country' => 'USA',
    'frequency' => '113.2',
    'power' => '50',
    'range' => '139',
]
```

## API-Aufrufe

### Beispiel: Alle Flughäfen holen

```php
use RunwayHub\Core\OpenAIP\Client;

$client = new Client();
$airports = $client->getAirports('country=DE&iata=MUC', 100);
print_r($airports);
```

### Beispiel: Einzelnen Flughafen holen

```php
$airport = $client->getAirport('EDDM');
print_r($airport);
```

### Beispiel: Synchronisation

```php
$result = $client->sync();
print_r($result);
```

## Fehlerbehandlung

Alle API-Klassen implementieren try-catch-Blöcke und loggen Fehler. Bei fehlgeschlagenen API-Aufrufen wird automatisch auf lokale Datenbank-Daten zurückgegriffen (Offline-Fallback).

Fehler werden im `error_log()` protokolliert.

## Nächste Schritte

1. **Datenbank-Migration ausführen**:
   ```bash
   mysql -u user -p database < database/migrations/20260526000001_create_openaip_tables.sql
   ```

2. **Erste Synchronisation**:
   ```bash
   php src/artisan openaip:sync --force
   ```

3. **API-Endpunkte testen**:
   ```bash
   curl https://yoursite.com/api/openaip/airports?limit=10
   ```

4. **Automatischer Sync einrichten** (Cron Job)
