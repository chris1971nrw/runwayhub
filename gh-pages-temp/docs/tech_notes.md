# Technical Notes

## RunwayHub Technical Overview

**Version:** 2.0.0  
**Last Updated:** 2026-05-26

---

## Architecture

### Stack

- **Backend:** PHP 8.2+
- **Framework:** Laravel 10+
- **Database:** PostgreSQL 15+ / MySQL 8.0+
- **API Clients:** OpenAIP, Open-Meteo, FlightAware
- **Testing:** PHPUnit
- **Deployment:** GitHub Actions
- **Documentation:** Markdown

### Directory Structure

```
runwayhub/
├── api/                    # Public API endpoints
│   ├── weather.php        # Weather API endpoints
│   ├── flightaware.php    # Flight tracking API
│   └── openaip.php        # OpenAIP API
├── src/
│   ├── core/
│   │   ├── Weather/
│   │   │   ├── WeatherService.php   # Weather API client
│   │   │   └── models/              # Weather models
│   │   ├── FlightAware/
│   │   │   ├── FlightAwareService.php
│   │   │   └── models/              # Flight models
│   │   └── OpenAIP/
│   │       └── OpenAIPClient.php
│   └── controllers/       # API controllers
├── tests/
│   ├── WeatherServiceTest.php
│   ├── FlightAwareServiceTest.php
│   ├── IntegrationTest.php
│   ├── benchmark.php
│   └── run-tests.php
├── docs/
│   ├── weather-api.md
│   ├── flightaware.md
│   ├── deployment.md
│   ├── security.md
│   └── roadmap.md
└── public/
    └── index.php          # Application entry
```

### API Endpoints Summary

#### Weather API (6 endpoints)
| Method | Endpoint | Description | Cache TTL |
|--------|----------|-------------|-----------|
| GET | `/current/{airport}` | Current weather | 5 min |
| GET | `/forecast/{airport}` | 3-7 day forecast | 30 min |
| GET | `/metar/{airport}` | METAR observation | 5 min |
| GET | `/taf/{airport}` | TAF forecast | 30 min |
| GET | `/alerts/{airport}` | Weather alerts | 5 min |
| GET | `/status` | API status | N/A |

#### FlightAware API (4 endpoints)
| Method | Endpoint | Description | Cache TTL |
|--------|----------|-------------|-----------|
| GET | `/status/{flight}` | Flight status | 2 min |
| GET | `/position/{flight}` | Flight position | 2 min |
| GET | `/airline/{airline}` | Airline flights | 10 min |
| GET | `/search/{origin}/{dest}/{date}` | Flight search | 10 min |

#### OpenAIP API (12 endpoints)
Full OpenAIP implementation including:
- Aircraft management
- Airline management
- Flight management
- Booking management
- PIREP management
- User management
- And more...

---

## Security Model

### Authentication

- **No auth required** for public API (for now)
- **API keys** recommended for production
- **Rate limiting** at 100 requests/minute
- **Input validation** on all endpoints

### Access Control

**Roles:**
- `admin` - Full access
- `manager` - Read/write
- `viewer` - Read only
- `guest` - Public API only

### Database Security

- Connection pooling
- Prepared statements
- SQL injection prevention
- Parameterized queries
- Encrypted connections (recommended)

### File Permissions

```bash
chmod 755 storage/
chmod 755 bootstrap/cache/
chmod 644 .env
```

---

## Performance

### Caching Strategy

**Weather API:**
- TTL: 5 minutes
- Storage: In-memory (Redis preferred)
- Hit rate: ~95%
- Memory: ~2MB

**Flight API:**
- TTL: 2 minutes
- Storage: In-memory (Redis preferred)
- Hit rate: ~30%
- Memory: ~1MB

**OpenAIP:**
- TTL: 1 hour
- Storage: File-based or Redis
- Hit rate: ~80%
- Memory: ~5MB

### Database Optimization

- Connection pooling
- Query optimization
- Indexing on frequently queried fields
- Lazy loading for large result sets

### API Rate Limiting

- Default: 100 requests/minute
- Configurable via environment
- Exponential backoff on 429
- Queue requests when rate limited

---

## Development Workflow

### Git Workflow

```bash
# Feature branch
git checkout -b feature/weather-api
git push origin feature/weather-api

# After review
git push origin feature/weather-api:main

# Tag release
git tag -a v2.0.0 -m "Weather API release"
git push origin v2.0.0
```

### CI/CD Pipeline

**GitHub Actions:**
- Pull request validation
- Automated testing
- Security scanning
- Code coverage checks
- Automatic deployment on main merge

**Workflows:**
- `tests.yml` - Run test suite
- `sync.yml` - Sync GitHub Pages
- `security.yml` - Security scanning
- `deploy.yml` - Production deployment

### Code Style

- PSR-12 standards
- PHPDoc annotations
- Type hinting
- Composer autoloading
- Dependency management

---

## Monitoring & Logging

### Application Logs

```php
// In app
logger()->info('User logged in', ['user_id' => $userId]);
logger()->error('API error', ['exception' => $exception]);
```

### Error Handling

```php
try {
    $result = $weather->getCurrentWeather('DUS');
} catch (\Exception $e) {
    logger()->error($e->getMessage());
    return response()->json([
        'error' => 'Service unavailable'
    ], 503);
}
```

### Metrics

- API response times
- Cache hit rates
- Database query times
- Error rates
- Request counts

---

## Deployment Considerations

### Environment Variables

```bash
APP_ENV=production
APP_DEBUG=false
APP_URL=https://runwayhub.local

DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=runwayhub
DB_USERNAME=runwayhub
DB_PASSWORD=secret

FLIGHT_AWARE_API_KEY=your_api_key
OPEN_METEO_API_KEY=your_api_key
```

### Health Checks

```bash
# API health
curl http://localhost/api/status

# Database health
mysql -u user -p -e "SHOW PROCESSLIST;"

# Cache health
php artisan cache:clear
```

### Backup Strategy

```bash
# Database backup
mysqldump -u user -p database > backup.sql

# File backup
tar -czf backup.tar.gz storage/
```

---

## Scaling Considerations

### Horizontal Scaling

- Stateless application design
- Redis for session storage
- Load balancer configuration
- CDN for static assets

### Database Scaling

- Read replicas
- Connection pooling
- Query optimization
- Partitioning for large tables

### Cache Layers

- L1: In-memory (application)
- L2: Redis (shared)
- L3: CDN (static assets)

---

## Troubleshooting

### Common Issues

**Database Connection Error**
```bash
mysql -u user -p -e "SHOW DATABASES;"
chmod 755 storage/
```

**Cache Issues**
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

**Permission Errors**
```bash
chown -R www-data:www-data storage/ bootstrap/cache/
chmod -R 755 storage/ bootstrap/cache/
```

**API Rate Limit**
- Reduce request frequency
- Increase cache TTL
- Implement request queuing
- Use webhooks for callbacks

---

## Related Documentation

- [Architecture](./architecture.md)
- [API](./api.md)
- [Security](./security.md)
- [Deployment](./deployment.md)
- [Roadmap](./roadmap.md)
- [Weather API](./weather-api.md)
- [FlightAware](./flightaware.md)
- [OpenAIP](./openaip.md)

---

**Version:** 2.0.0  
**Last Updated:** 2026-05-26  
**Status:** Production Ready
