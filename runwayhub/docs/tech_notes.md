# RunwayHub Technische Notizen

Technische Details, Entscheidungen und Best Practices für RunwayHub.

## Tech Stack

### Backend

- **PHP 8.2+**: Modernes PHP mit Typisierung und Namespaces
- **MySQL 8.0**: relationale Datenbank mit prepared statements
- **PDO**: Datenbank-Abstraktionsschicht
- **Composer**: Dependency Management

### Frontend

- **HTML5**: Modernes Markup
- **CSS3**: Styling mit Responsive Design
- **JavaScript**: Vanilla JS für Frontend-Funktionen

### Entwicklungswerkzeuge

- **Git**: Versionskontrolle
- **GitHub**: Code-Hosting
- **PHPUnit**: Unit-Testing
- **GitHub Actions**: CI/CD

### Deployment

- **Apache/Nginx**: Webserver
- **PHP-FPM**: PHP-Processor
- **Redis/Memcached**: Caching (optional)
- **MariaDB**: Alternative zu MySQL

## Architekturentscheidungen

### 1. Modularer Aufbau

```
┌─────────────────────────────────────┐
│              Core System            │
│   Database, Request, Response       │
└─────────────────────────────────────┘
              ↓
┌─────────────────────────────────────┐
│              API Layer              │
│           REST Endpoints            │
└─────────────────────────────────────┘
              ↓
┌─────────────────────────────────────┐
│            Service Layer            │
│        Business Logic              │
└─────────────────────────────────────┘
              ↓
┌─────────────────────────────────────┐
│            Repository Layer         │
│        Data Access Layer           │
└─────────────────────────────────────┘
              ↓
┌─────────────────────────────────────┐
│            Database Layer           │
│           MySQL/MariaDB            │
└─────────────────────────────────────┘
```

**Begründung:**
- Entkopplung der Schichten
- Einfache Wartbarkeit
- Testbarkeit
- Erweiterbarkeit

### 2. Dependency Injection

```php
class BookingService
{
    public function __construct(
        private Database $database,
        private BookingRepository $bookingRepo,
        private PirepRepository $pirepRepo,
    ) {
    }
}
```

**Begründung:**
- Testbarkeit
- Wartbarkeit
- Flexibilität

### 3. RESTful API Design

- **Resources**: nouns in plural (flights, airports)
- **HTTP Methods**: GET, POST, PUT, DELETE
- **Status Codes**: 200, 201, 400, 404, 500
- **Content Type**: application/json
- **Versioning**: URL-based (/api/v1/)

**Beispiel:**

```json
GET /api/v1/flights
{
    "success": true,
    "count": 100,
    "data": [...]
}
```

### 4. Datenbank-Design

**Prinzipien:**
- Normalisierung (3NF)
- Indexe für Performance
- Foreign Keys für Integrität
- Timestamps für Audit-Logs

**Beispiel:**

```sql
CREATE TABLE flights (
    id INT PRIMARY KEY AUTO_INCREMENT,
    flight_number VARCHAR(10) NOT NULL,
    origin_id INT NOT NULL,
    destination_id INT NOT NULL,
    scheduled_departure DATETIME,
    status ENUM('scheduled', 'in_air', 'arrived'),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### 5. Internationaleisierung

```php
// Helper-Funktion
function __(string $key, array $params = [], string $locale = null): string
{
    // Locale-Erkennung
    // Fallback auf DE
    // Caching
}

// Verwendung
__('flights_total', ['count' => $flightsCount]);
__('welcome_message', ['airline' => config('airline.name')]);
```

**Struktur:**

```
i18n/
├── de/
│   └── messages.php
└── en/
    └── messages.php
```

### 6. Sicherheit

**Schichten:**

1. **Input Validation**
   - Type Checking
   - Sanitization
   - Whitespace Removal

2. **SQL Injection Prevention**
   - Prepared Statements
   - Parameter Binding

3. **XSS Protection**
   - htmlspecialchars()
   - Content Security Policy

4. **CSRF Protection**
   - Token in Session
   - Validation bei Form-Submission

5. **API Security**
   - API-Key Authentication
   - Rate Limiting
   - CORS Configuration

### 7. Caching

```php
// Caching-Strategie
$pireps = Cache::get('pireps', function() {
    return Pirep::all();
});

// TTL: 300 Sekunden
Cache::put('pireps', $pireps, 300);
```

**Cache-Levels:**

1. **Application Cache** (Redis/Memcached)
2. **Database Cache** (Query Result Cache)
3. **Browser Cache** (Static Assets)

### 8. Error Handling

```php
try {
    $booking = Booking::create($data);
} catch (\Exception $e) {
    Log::error('Booking Error', [
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
    ]);
    
    throw new AppException(
        __('error_booking_failed'),
        500
    );
}
```

**Error-Codes:**

- **4xx**: Client Errors
- **5xx**: Server Errors
- **0**: Success

### 9. Logging

```php
Log::info('Flight generated', [
    'flight_id' => $flight->id,
    'aircraft_id' => $aircraft->id,
]);

Log::error('Flight Generation Failed', [
    'error' => $e->getMessage(),
    'trace' => $e->getTraceAsString(),
]);
```

**Log-Levels:**

- **DEBUG**: Debugging Informationen
- **INFO**: Allgemeine Informationen
- **WARNING**: Auffälligkeiten
- **ERROR**: Fehler
- **CRITICAL**: Kritische Fehler

### 10. Migrationen

```php
// Migration-Klasse
class CreateFlightsTable extends Migration
{
    public function up(): void
    {
        $this->create('flights', function (Blueprint $table) {
            $table->id();
            $table->string('flight_number');
            // ...
        });
    }

    public function down(): void
    {
        $this->dropIfExists('flights');
    }
}
```

**Ausführung:**

```bash
php artisan migrate
php artisan migrate:fresh --seed
```

### 11. Tests

```php
// TestCase
class FlightTest extends TestCase
{
    public function test_flight_can_be_created(): void
    {
        $flight = Flight::create([
            'flight_number' => 'DH001',
            'origin_id' => 1,
            'destination_id' => 2,
        ]);

        $this->assertEquals('DH001', $flight->flight_number);
    }
}
```

**Test-Strategie:**

- **Unit Tests**: Einzelne Funktionen
- **Integration Tests**: Module-Interaktionen
- **E2E Tests**: Gesamtsystem

### 12. Performance

**Optimierungen:**

1. **Database**
   - Indexes
   - Query Optimization
   - Connection Pooling

2. **Caching**
   - Redis/Memcached
   - Browser Caching
   - GZIP Compression

3. **Code**
   - Early Return
   - Avoid Unnecessary Calculations
   - Use Generics

4. **Architecture**
   - Microservices (future)
   - Load Balancing
   - CDN

### 13. Monitoring

**Metriken:**

- API Response Time
- Error Rate
- Database Queries
- Cache Hit Rate
- Memory Usage

**Tools:**

- **Sentry**: Error Tracking
- **New Relic**: APM (optional)
- **Prometheus**: Metrics (optional)
- **Grafana**: Dashboards (optional)

### 14. Deployment

**Pipeline:**

```bash
1. Pull Code von GitHub
2. Run Tests
3. Run Linters
4. Build Application
5. Deploy to Staging
6. Run Integration Tests
7. Deploy to Production
8. Smoke Tests
```

**CI/CD:**

```yaml
# GitHub Actions
name: CI/CD

on:
  push:
    branches: [main, dev]
  pull_request:
    branches: [main]

jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      - name: Install Dependencies
        run: composer install
      - name: Run Tests
        run: php vendor/bin/phpunit
  deploy:
    needs: test
    runs-on: ubuntu-latest
    steps:
      - name: Deploy
        run: ./deploy.sh
```

### 15. Backup Strategy

**Regel:**

- **3-2-1 Regel**:
  - 3 Kopien
  - 2 verschiedene Medien
  - 1 externe Kopie

**Automatische Backups:**

```bash
# MySQL Backup
mysqldump -u root -p database > backup.sql

# Datei-Backup
tar -czf backup.tar.gz /runwayhub/public
```

**Schedule:**

- **Stündlich**: Inkrementell
- **Täglich**: Vollbackup
- **Wöchentlich**: Vollbackup + Archive

### 16. Security Checklist

- [ ] API Keys im .env
- [ ] SSL/TLS aktiv
- [ ] CSRF Tokens
- [ ] XSS Protection
- [ ] SQL Injection Prevention
- [ ] Rate Limiting
- [ ] CORS Configuration
- [ ] Input Validation
- [ ] Password Hashing (bcrypt)
- [ ] Session Security

### 17. Best Practices

1. **Code Organization**
   - PSR-12 Standard
   - Clear Naming
   - Comments für komplexe Logik

2. **Testing**
   - Tests vor Code
   - Coverage Reports
   - Automated Testing

3. **Documentation**
   - README.md
   - API Dokumentation
   - Code Comments

4. **Version Control**
   - Git Commits nach Konvention
   - Branch Naming
   - Pull Requests

5. **Performance**
   - Lazy Loading
   - Caching
   - Database Indexes

---

## Known Issues

### 1. Demo-Daten

**Problem:**
- Demo-Daten überschreiben keine echten Daten

**Lösung:**
- Daten-Isolierung
- Feature-Flags

### 2. GitHub Issues

**Problem:**
- Rate Limiting bei GitHub API

**Lösung:**
- Token Refresh
- Caching von Issues

### 3. PIREPs

**Problem:**
- Simulation vs Real Data

**Lösung:**
- Demo-Flag
- Separate Data Store

---

## TODO

- [ ] APM Integration
- [ ] Error Tracking
- [ ] Load Testing
- [ ] Security Audit
- [ ] Performance Profiling
- [ ] User Documentation
- [ ] Video Tutorials

---

## Related

- [Architecture](architecture.md)
- [Features](features.md)
- [Roadmap](roadmap.md)

---

## Support

- **Email:** dev@runwayhub.de
- **GitHub Issues:** https://github.com/chris1971nrw/runwayhub/issues

---

## License

Apache License 2.0
