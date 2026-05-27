# User Guide

**RunwayHub User Guide**  
**Version:** 2.0.0  
**Last Updated:** 2026-05-26

---

## Overview

This guide helps you get started with RunwayHub, covering all features, workflows, and best practices.

---

## Quick Start

### 1. Installation

```bash
# Clone repository
git clone https://github.com/chris1971nrw/runwayhub.git
cd runwayhub

# Install dependencies
composer install

# Configure environment
cp .env.example .env
nano .env

# Run migrations
php ./runwayhub/migrate.php

# Set permissions
chmod -R 755 storage/ bootstrap/cache/
```

### 2. First Steps

```bash
# Start the application
php -S localhost:8000 -t public/

# Visit in browser
http://localhost:8000
```

### 3. API Usage

```bash
# Get current weather
curl "http://localhost/api/weather.php?airport=DUS"

# Get flight status
curl "http://localhost/api/flightaware.php?flight=AA123"
```

---

## Features Overview

### Weather Features

- **Current Weather** - Real-time conditions
- **Forecast** - 3-7 day forecasts
- **METAR/TAF** - Aviation weather data
- **Alerts** - Weather warnings

### Flight Tracking

- **Flight Status** - Live flight tracking
- **Flight Position** - Live position updates
- **Airline Listings** - All flights for an airline
- **Flight Search** - Find flights by route

### OpenAIP Features

- **Aircraft Management** - Full aircraft database
- **Airline Management** - Airline operations
- **Flight Management** - Flight planning
- **Booking Management** - Booking operations
- **PIREP Management** - Pilot reports
- **User Management** - User accounts

---

## Web Interface

### Dashboard

The main dashboard shows:

- **Current flights** - Active flights
- **Weather overview** - Local weather
- **Recent activity** - Latest operations
- **Quick actions** - Common tasks

### Flight Tracker

The flight tracker displays:

- **Flight map** - Visual flight paths
- **Flight details** - Full flight info
- **Weather overlay** - Current conditions
- **Status indicators** - Flight status

---

## API Usage Examples

### PHP Example

```php
<?php
require 'vendor/autoload.php';

use RunwayHub\API\Weather;

$weather = new Weather();

// Get current weather for DUS
$current = $weather->getCurrentWeather('DUS');
echo "Temperature: " . $current['temperature'] . "°C\n";
echo "Conditions: " . $current['conditions'] . "\n";
?>
```

### JavaScript Example

```javascript
async function getWeather(airport) {
  const response = await fetch(`http://localhost/api/weather.php?airport=${airport}`);
  const data = await response.json();
  
  if (data.success) {
    console.log(`Weather in ${airport}: ${data.data.temperature}°C`);
    console.log(`Conditions: ${data.data.conditions}`);
  }
}

getWeather('DUS');
```

### Python Example

```python
import requests

def get_weather(airport):
    response = requests.get(f"http://localhost/api/weather.php?airport={airport}")
    data = response.json()
    
    if data['success']:
        print(f"Weather in {airport}: {data['data']['temperature']}°C")
        print(f"Conditions: {data['data']['conditions']}")

get_weather('DUS')
```

---

## Common Tasks

### Task 1: Check Current Weather

```bash
curl "http://localhost/api/weather.php?airport=DUS"
```

### Task 2: Get Flight Status

```bash
curl "http://localhost/api/flightaware.php?flight=AA123"
```

### Task 3: Get Weather Forecast

```bash
curl "http://localhost/api/weather.php?airport=DUS&days=3"
```

### Task 4: Get Airline Flights

```bash
curl "http://localhost/api/flightaware.php?airline=AA"
```

### Task 5: Search Flights

```bash
curl "http://localhost/api/flightaware.php?search=DUS/JFK/2026-05-27"
```

---

## Error Handling

### Handling Errors

All API responses include a `success` field:

```json
{
  "success": true,
  "data": { ... },
  "meta": { ... }
}
```

On error:

```json
{
  "success": false,
  "error": "Invalid airport code",
  "details": "DUS123 is not a valid airport code",
  "code": "INVALID_AIRPORT"
}
```

### Example with Error Handling

```php
try {
    $weather = new Weather();
    $current = $weather->getCurrentWeather('DUS');
    
    if ($current['success']) {
        echo "Temperature: " . $current['data']['temperature'] . "°C\n";
    } else {
        echo "Error: " . $current['error'] . "\n";
    }
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}
```

---

## Performance Tips

### Caching

Enable caching for better performance:

```php
// Cache API responses
$response = $weather->getCurrentWeather('DUS', $cache => true);
```

### Batch Requests

Make multiple requests efficiently:

```php
$airports = ['DUS', 'FRA', 'MUC'];

$weather = new Weather();
foreach ($airports as $airport) {
    $weather->getCurrentWeather($airport);
}
```

### Rate Limiting

Respect rate limits (100 requests/minute):

```php
// Wait if rate limited
if ($response['rate_limited']) {
    $wait = $response['retry_after'];
    sleep($wait);
    $response = $weather->getCurrentWeather('DUS');
}
```

---

## Best Practices

### API Usage

1. **Cache responses** - Don't request same data repeatedly
2. **Handle errors** - Always check `success` field
3. **Respect rate limits** - Wait for retry_after
4. **Use appropriate TTL** - Weather: 5 min, Flights: 2 min

### Error Handling

1. **Always check success** - Verify API response
2. **Handle exceptions** - Use try/catch blocks
3. **Log errors** - Track issues for debugging
4. **Retry on failure** - Implement exponential backoff

### Code Quality

1. **Type hinting** - Use PHP 8+ features
2. **Documentation** - Add PHPDoc comments
3. **Testing** - Write unit tests
4. **Linting** - Run PHPStan/PSALM

---

## Troubleshooting

### Problem: Invalid Airport Code

**Error:**
```json
{
  "success": false,
  "error": "Invalid airport code",
  "code": "INVALID_AIRPORT"
}
```

**Solution:**
- Use valid ICAO codes (e.g., DUS, FRA, MUC)
- Or IATA codes (e.g., DUS, FRA, MUC)
- Don't include spaces or hyphens

### Problem: Rate Limit Exceeded

**Error:**
```json
{
  "success": false,
  "error": "Rate limit exceeded",
  "code": "RATE_LIMITED"
}
```

**Solution:**
- Wait for rate limit window to expire
- Implement exponential backoff
- Use caching to reduce requests

### Problem: API Unavailable

**Error:**
```json
{
  "success": false,
  "error": "API unavailable",
  "code": "API_UNAVAILABLE"
}
```

**Solution:**
- Check external API status
- Verify network connectivity
- Retry after delay

---

## Support & Resources

### Documentation

- [API Documentation](./docs/api.md)
- [Weather API](./docs/weather-api.md)
- [FlightAware](./flightaware.md)
- [Deployment Guide](./docs/deployment.md)
- [Security Guide](./docs/security.md)

### Getting Help

- **GitHub Issues:** https://github.com/chris1971nrw/runwayhub/issues
- **Documentation:** /docs/
- **Forum:** Flugsimulationsforum.de
- **Email:** support@runwayhub.local

### Community

- Join the GitHub discussion
- Report bugs
- Suggest features
- Contribute code

---

## Getting Started Checklist

- [ ] Install dependencies
- [ ] Configure .env file
- [ ] Run migrations
- [ ] Test API endpoints
- [ ] Review documentation
- [ ] Set up monitoring
- [ ] Enable HTTPS (production)
- [ ] Configure rate limiting
- [ ] Set up logging
- [ ] Test error handling

---

## Next Steps

After getting started:

1. **Read the API docs** - Learn all endpoints
2. **Review examples** - See usage patterns
3. **Configure caching** - Optimize performance
4. **Set up monitoring** - Track usage
5. **Implement logging** - Debug issues
6. **Join the community** - Get support

---

**Version:** 2.0.0  
**Last Updated:** 2026-05-26  
**Status:** Production Ready  
**License:** MIT
