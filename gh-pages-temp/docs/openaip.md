# OpenAIP Integration Documentation

Complete integration guide for OpenAIP (Open Aviation Instrument Procedures) database in RunwayHub.

## 📖 What is OpenAIP?

OpenAIP is a comprehensive, free, Open-Source database for aviation information. It provides:
- **Airports** - ICAO/IATA codes, coordinates, elevation, timezone
- **Waypoints** - VORs, NDBs, fixes, intersection points
- **Airways** - Victor airways (V), Jet routes (J)
- **Navigation Aids** - VOR, NDB, ILS, DME, etc.
- **Airspace** - Class A-E airspace information

## 🚀 Installation

### Prerequisites

- PHP 8.2+
- Composer
- OpenAIP API access (API Key required)
- MySQL/MariaDB 8.0+

### Setup Steps

1. **Install OpenAIP Classes**
   ```bash
   # Place classes in src/core/OpenAIP/
   # Client.php, Airport.php, Waypoint.php, Airway.php, Navaid.php
   ```

2. **Create Migration**
   ```bash
   php artisan migrate
   # Runs: database/migrations/20260526000001_create_openaip_tables.sql
   ```

3. **Configure Environment**
   ```env
   OPENAIP_API_KEY=your_api_key_here
   OPENAIP_API_URL=https://openaip.io/api
   OPENAIP_CACHE_TTL=300
   OPENAIP_FORCE_UPDATE=false
   ```

4. **Run Initial Sync**
   ```bash
   php src/artisan openaip:sync --force
   ```

## 📚 API Usage

### PHP Examples

```php
use RunwayHub\Core\OpenAIP\Client;

$client = new Client();

// Get all airports
$airports = $client->getAirports('country=DE', 100);

// Get specific airport
$airport = $client->getAirport('EDDM');

// Sync database
$result = $client->sync();

// Get sync status
$status = $client->getSyncStatus();

// Clear cache
$client->clearCache();
```

### REST API Examples

```bash
# Get all airports
curl "https://yoursite.com/api/openaip/airports?limit=100"

# Get specific airport
curl "https://yoursite.com/api/openaip/airports/EDDM"

# Sync database
curl -X POST https://yoursite.com/api/openaip/sync

# Get status
curl https://yoursite.com/api/openaip/status

# Clear cache
curl -X POST https://yoursite.com/api/openaip/clearcache
```

## 🗄️ Database Schema

### OpenAIP Tables

#### airports_openaip
```sql
CREATE TABLE airports_openaip (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    iata VARCHAR(10) DEFAULT NULL,
    icao VARCHAR(10) NOT NULL,
    latitude DECIMAL(10, 8) NOT NULL,
    longitude DECIMAL(10, 8) NOT NULL,
    elevation INT NOT NULL,
    timezone VARCHAR(50) NOT NULL,
    continent VARCHAR(2) NOT NULL,
    country VARCHAR(2) NOT NULL,
    region VARCHAR(5) NOT NULL,
    municipality VARCHAR(100) DEFAULT NULL,
    scheduled_service VARCHAR(1) DEFAULT 'Y',
    gps_code VARCHAR(10) NOT NULL,
    website VARCHAR(255) DEFAULT NULL,
    phone VARCHAR(50) DEFAULT NULL,
    fax VARCHAR(50) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_icao (icao),
    INDEX idx_iata (iata),
    INDEX idx_country (country),
    INDEX idx_region (region)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### waypoints_openaip, airways_openaip, navaids_openaip, airspace_openaip

Similar structure with respective fields.

## 🔧 CLI Commands

### Manual Sync

```bash
# Full sync (recommended: run during low-traffic periods)
php src/artisan openaip:sync --force

# Sync with limit
php src/artisan openaip:sync --limit=500

# Sync specific data
php src/artisan openaip:sync --airports
php src/artisan openaip:sync --waypoints
php src/artisan openaip:sync --airways
php src/artisan openaip:sync --navaids
php src/artisan openaip:sync --airspace
```

### Options

| Option | Description |
|--------|-------------|
| `--force` | Ignore cache, fetch fresh data |
| `--limit=N` | Limit records to N (0 = unlimited) |
| `--airports` | Sync airports only |
| `--waypoints` | Sync waypoints only |
| `--airways` | Sync airways only |
| `--navaids` | Sync navigation aids only |
| `--airspace` | Sync airspace data |

### Scheduled Sync (Cron)

```bash
# Add to crontab - daily at 2:00 UTC
0 2 * * * cd /path/to/runwayhub && php src/artisan openaip:sync

# With logging
0 2 * * * cd /path/to/runwayhub && php src/artisan openaip:sync --log
```

## 📊 Cache Strategy

### Cache Structure

```
cache/
├── airports.json (5 min TTL)
├── waypoints.json (5 min TTL)
├── airways.json (5 min TTL)
├── navaids.json (5 min TTL)
└── airsaces.json (5 min TTL)
```

### Cache Behavior

- **Default TTL:** 300 seconds (5 minutes)
- **Fresh fetch:** If `OPENAIP_FORCE_UPDATE=true`
- **Offline fallback:** Return last cached data if API unavailable
- **Sync detection:** Last sync timestamp tracked

## 🛡️ Security Best Practices

### API Key Management

```env
# Never commit .env to Git
OPENAIP_API_KEY=your_secure_api_key_here
```

### Recommendations

1. **Use environment variables** - Never hardcode API keys
2. **Rate limiting** - Monitor API usage limits
3. **HTTPS only** - Always use SSL/TLS connections
4. **Input validation** - Sanitize all user inputs
5. **Caching** - Reduce API calls with proper caching

### Example .env Template

```env
APP_NAME=RunwayHub
APP_ENV=local
APP_DEBUG=true
APP_URL=https://runwayhub.github.io

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=runwayhub
DB_USERNAME=runwayhub
DB_PASSWORD=secret

# OpenAIP
OPENAIP_API_KEY=your_api_key_here
OPENAIP_API_URL=https://openaip.io/api
OPENAIP_CACHE_TTL=300
OPENAIP_FORCE_UPDATE=false
```

## 🧪 Testing

```bash
# Run OpenAIP tests
vendor/bin/phpunit tests/OpenAIP/

# Test specific client
vendor/bin/phpunit tests/OpenAIP/ClientTest.php

# With coverage
vendor/bin/phpunit --coverage-text tests/OpenAIP/
```

## 📈 Monitoring

### Health Check Endpoint

```bash
curl https://yoursite.com/api/openaip/status
```

**Response:**
```json
{
    "success": true,
    "status": {
        "last_sync": "2026-05-26T18:00:00+02:00",
        "next_sync": "2026-05-27T02:00:00+02:00",
        "cache_valid": true,
        "cache_expires": "2026-05-26T18:05:00+02:00"
    }
}
```

### Metrics to Monitor

- **Cache hit ratio** - Should be >95%
- **Sync duration** - Should be <60 seconds
- **API errors** - Monitor for rate limiting
- **Database size** - Check for growth

## 🐛 Troubleshooting

### Common Issues

**Issue:** "Cache expired, syncing..."
**Solution:** This is normal. Cache refreshes every 5 minutes.

**Issue:** "API rate limit exceeded"
**Solution:** Implement retry logic with exponential backoff.

**Issue:** "Database table doesn't exist"
**Solution:** Run `php artisan migrate` first.

**Issue:** "API key invalid"
**Solution:** Check OPENAIP_API_KEY in .env.

## 🔗 Resources

- [OpenAIP Official](https://openaip.io/)
- [OpenAIP GitHub](https://github.com/openaip)
- [Aviation Database](https://www.aviationdatabase.com/)

## 📝 Changelog

See [runwayhub/changelog.md](../changelog.md) for OpenAIP integration updates.

## 🤝 Contributing

See [CONTRIBUTING.md](../CONTRIBUTING.md) for contribution guidelines.

---

**Last Updated:** 2026-05-26
**Version:** 2.0.0
**Status:** Stable
