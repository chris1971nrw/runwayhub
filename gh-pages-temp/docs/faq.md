# FAQ - Frequently Asked Questions

**RunwayHub FAQ**  
**Version:** 2.0.0  
**Last Updated:** 2026-05-26

---

## General Questions

### Q: What is RunwayHub?

**A:** RunwayHub is a comprehensive flight management platform built with PHP and Laravel. It provides:
- Weather API integration
- Flight tracking via FlightAware
- OpenAIP client implementation
- Multi-airline management
- Role-based access control
- RESTful API with documentation

### Q: What programming languages does RunwayHub support?

**A:** RunwayHub is primarily PHP, but the API supports multiple languages:
- PHP (native)
- JavaScript/Node.js
- Python
- Ruby
- Go
- And more...

### Q: Is RunwayHub free to use?

**A:** Yes, RunwayHub is released under the MIT License and is free to use for both personal and commercial projects.

### Q: What are the system requirements?

**A:**
- **PHP:** 8.2+
- **Database:** MySQL 8.0+ or PostgreSQL 15+
- **Memory:** 256MB+
- **Disk:** 1GB+ (plus database space)
- **OS:** Linux, Windows, macOS

---

## API Questions

### Q: Do I need an API key?

**A:** Currently no, but API keys will be required in future versions for rate limiting and access control.

### Q: What is the API rate limit?

**A:** Default is 100 requests per minute per API endpoint. You can configure this in the .env file.

### Q: How long does the API cache data?

**A:**
- **Weather data:** 5 minutes
- **Flight data:** 2 minutes (live data)
- **OpenAIP data:** 1 hour
- **Status data:** No cache

### Q: Can I use the API without authentication?

**A:** Yes, the current version operates without authentication. Future versions will require API keys.

### Q: What endpoints are available?

**A:**
- **Weather API:** 6 endpoints (current, forecast, METAR, TAF, alerts, status)
- **FlightAware API:** 4 endpoints (status, position, airline, search)
- **OpenAIP API:** 12 endpoints (full OpenAIP implementation)
- **Status API:** 1 endpoint (health check)

### Q: How do I handle rate limiting?

**A:** Respect the X-RateLimit-Remaining header and implement exponential backoff. The API returns a 429 status code when rate limited.

---

## Weather API Questions

### Q: Which airports does the Weather API support?

**A:** The Weather API supports all airports with Open-Meteo coverage, including:
- European airports (DUS, FRA, MUC, etc.)
- North American airports (KJFK, KLAX, etc.)
- Worldwide airports with latitude/longitude

### Q: How accurate is the weather data?

**A:** Weather data is sourced from Open-Meteo and aviation weather stations. Accuracy is typical for aviation meteorology:
- Temperature: ±1-2°C
- Wind: ±5-10 knots
- Conditions: Generally accurate

### Q: What format is METAR data in?

**A:** METAR data is provided as both raw text and parsed structured data for easy parsing.

### Q: Can I get historical weather data?

**A:** Not yet in v2.0. This is planned for v3.0+.

---

## FlightAware Questions

### Q: Do I need a FlightAware account?

**A:** Yes, you need a FlightAware AeroAPI account to get an API key for production use.

### Q: How do I get a FlightAware API key?

**A:**
1. Visit https://flightaware.com/
2. Register for an AeroAPI account
3. Generate an API key
4. Configure in your .env file

### Q: What flight information is available?

**A:**
- Flight status (scheduled, enroute, landed, etc.)
- Current position (lat/long, altitude)
- Airline information
- Scheduled times
- Estimated times
- Actual times

### Q: How frequently is flight data updated?

**A:** Flight data is updated every 2 minutes via cache refresh. Real-time updates may have slight delays.

### Q: Can I track historical flights?

**A:** Not yet in v2.0. This is planned for future releases.

---

## OpenAIP Questions

### Q: What is OpenAIP?

**A:** OpenAIP (Open Aviation Information Portal) is a specification for managing flight simulation data. RunwayHub implements 12 OpenAIP endpoints.

### Q: What does OpenAIP manage?

**A:** OpenAIP manages:
- Aircraft
- Airlines
- Flights
- Bookings
- PIREPs
- Users
- And more...

### Q: Is OpenAIP data stored locally?

**A:** Yes, all OpenAIP data is stored in the database and can be queried via the API.

---

## Installation Questions

### Q: How do I install RunwayHub?

**A:**
```bash
git clone https://github.com/chris1971nrw/runwayhub.git
cd runwayhub
composer install
cp .env.example .env
nano .env
php ./runwayhub/migrate.php
```

### Q: How do I configure the .env file?

**A:** See the .env.example file for required variables:
```bash
APP_NAME="RunwayHub"
APP_ENV="production"
APP_DEBUG="false"
APP_URL="https://your-domain.com"

DB_CONNECTION="mysql"
DB_HOST="localhost"
DB_DATABASE="runwayhub"
DB_USERNAME="user"
DB_PASSWORD="password"
```

### Q: How do I update RunwayHub?

**A:**
```bash
git pull origin main
composer update
php ./runwayhub/migrate.php
```

### Q: How do I backup the database?

**A:**
```bash
mysqldump -u user -p database > backup.sql
```

### Q: How do I restore from backup?

**A:**
```bash
mysql -u user -p database < backup.sql
```

---

## Security Questions

### Q: Is RunwayHub secure?

**A:** Yes, RunwayHub follows security best practices:
- Role-based access control
- Input validation
- SQL injection prevention
- XSS protection
- CSRF tokens
- Rate limiting
- Secure dependencies

### Q: Do I need HTTPS?

**A:** Yes, for production use. RunwayHub works best with HTTPS and the security headers are most effective with HTTPS.

### Q: How do I secure the API?

**A:**
1. Use HTTPS
2. Enable rate limiting
3. Implement API keys (v3.0+)
4. Configure CORS
5. Review logs regularly
6. Keep dependencies updated

### Q: How do I rotate credentials?

**A:**
1. Create new API keys
2. Update .env file
3. Clear application cache
4. Update client applications

---

## Performance Questions

### Q: How fast is the API?

**A:**
- **Weather API:** ~200ms (cache hit)
- **Flight API:** ~500ms (with API auth)
- **OpenAIP API:** ~300ms
- **Average:** <500ms for cache hits

### Q: How can I improve performance?

**A:**
1. Enable caching (already configured)
2. Use Redis for better performance
3. Implement CDN for static assets
4. Optimize database queries
5. Use connection pooling

### Q: How much memory does RunwayHub use?

**A:**
- **Minimum:** 256MB
- **Recommended:** 512MB
- **With Redis:** 1GB

### Q: Can I scale RunwayHub?

**A:** Yes, RunwayHub can be scaled:
- **Horizontal:** Multiple instances behind load balancer
- **Database:** Read replicas
- **Cache:** Shared Redis
- **CDN:** Static assets

---

## Development Questions

### Q: Can I contribute to RunwayHub?

**A:** Yes! Here's how to contribute:
1. Fork the repository
2. Create a feature branch
3. Make changes
4. Write tests
5. Submit a pull request

### Q: What is the development workflow?

**A:**
1. Create feature branch
2. Write code
3. Run tests
4. Document changes
5. Submit PR
6. Review and merge

### Q: What testing tools are available?

**A:**
- PHPUnit (unit tests)
- PHPStan (static analysis)
- CodeQL (security scanning)
- Benchmark tests
- Integration tests

### Q: How do I write tests?

**A:** See the tests/ directory for examples:
```php
class MyServiceTest extends PHPUnit\Framework\TestCase
{
    public function testSomething(): void
    {
        $service = new MyService();
        $result = $service->doSomething();
        $this->assertIsArray($result);
    }
}
```

---

## Community Questions

### Q: Where can I get help?

**A:**
- **GitHub Issues:** https://github.com/chris1971nrw/runwayhub/issues
- **Documentation:** /docs/
- **Forum:** Flugsimulationsforum.de
- **Email:** support@runwayhub.local

### Q: How do I report a bug?

**A:**
1. Go to GitHub Issues
2. Choose "Report a bug"
3. Provide steps to reproduce
4. Include error messages
5. Submit issue

### Q: How do I request a feature?

**A:**
1. Go to GitHub Issues
2. Choose "Request a feature"
3. Describe the feature
4. Explain the use case
5. Submit issue

### Q: How do I join the community?

**A:**
1. Star the repository
2. Fork the repository
3. Follow the development
4. Join the discussion
5. Contribute code

---

## Miscellaneous Questions

### Q: What is the license?

**A:** RunwayHub is released under the MIT License. You can use it for any purpose.

### Q: Where can I download the source code?

**A:** The source code is available on GitHub: https://github.com/chris1971nrw/runwayhub

### Q: Is there a mobile app?

**A:** Not yet in v2.0. Mobile app development is planned for future releases.

### Q: What browsers are supported?

**A:** All modern browsers:
- Chrome (latest 2 versions)
- Firefox (latest 2 versions)
- Safari (latest 2 versions)
- Edge (latest 2 versions)

---

## Getting Started Quick Start

### 1. Install
```bash
git clone https://github.com/chris1971nrw/runwayhub.git
cd runwayhub
composer install
```

### 2. Configure
```bash
cp .env.example .env
nano .env
```

### 3. Test
```bash
php ./runwayhub/migrate.php
php -S localhost:8000 -t public/
```

### 4. Explore
Visit http://localhost:8000

### 5. Learn
Read the documentation in /docs/

---

**Version:** 2.0.0  
**Last Updated:** 2026-05-26  
**Status:** Production Ready  
**License:** MIT
