# RunwayHub API Documentation

**Version:** 2.0.0  
**Status:** Production Ready  
**Base URL:** `http://localhost/api/`

---

## Overview

RunwayHub provides comprehensive aviation data through three integrated APIs:

1. **Weather API** - Real-time weather and forecasts
2. **FlightAware API** - Live flight tracking
3. **OpenAIP API** - Full OpenAIP client (12 endpoints)

---

## Base URLs

```
Weather API:     http://localhost/api/weather.php
FlightAware API: http://localhost/api/flightaware.php
OpenAIP API:     http://localhost/api/openaip.php
Status API:      http://localhost/api/status.php
```

---

## Authentication

### No Authentication (v2.0)

RunwayHub v2.0 operates without authentication for simplicity.

### Production Authentication (v3.0+)

API keys will be required in future versions:

```php
$headers = [
    'Authorization: Bearer {API_KEY}',
    'Accept: application/json'
];
```

---

## Weather API Endpoints

### 1. Current Weather

**Endpoint:** `GET /current/{airport}`

**Example:**
```bash
curl "http://localhost/api/weather.php?airport=DUS"
```

**Response:**
```json
{
  "success": true,
  "airport": "DUS",
  "data": {
    "temperature": 12.5,
    "humidity": 78,
    "wind_speed": 15,
    "wind_direction": 270,
    "conditions": "partly cloudy",
    "visibility": 10000,
    "pressure": 1013,
    "updated": "2026-05-26T21:52:00Z"
  }
}
```

### 2. Weather Forecast

**Endpoint:** `GET /forecast/{airport}?days={n}`

**Example:**
```bash
curl "http://localhost/api/weather.php?airport=DUS&days=3"
```

**Response:**
```json
{
  "success": true,
  "airport": "DUS",
  "data": {
    "daily": [
      {
        "date": "2026-05-27",
        "temperature_min": 8,
        "temperature_max": 15,
        "precipitation": 0,
        "conditions": "sunny",
        "wind_speed": 10
      }
    ]
  }
}
```

### 3. METAR Observation

**Endpoint:** `GET /metar/{airport}`

**Example:**
```bash
curl "http://localhost/api/weather.php?metar=DUS"
```

**Response:**
```json
{
  "success": true,
  "airport": "DUS",
  "metar": "METAR DUS 262320Z 27015KT 9999 SCT025 12/08 Q1013 NOSIG",
  "parsed": {
    "time": "262320Z",
    "wind": "27015KT",
    "visibility": "9999",
    "conditions": "SCT025",
    "temperature": 12,
    "dewpoint": 8,
    "pressure": 1013
  }
}
```

### 4. TAF Forecast

**Endpoint:** `GET /taf/{airport}`

**Example:**
```bash
curl "http://localhost/api/weather.php?taf=DUS"
```

### 5. Weather Alerts

**Endpoint:** `GET /alerts/{airport}`

**Example:**
```bash
curl "http://localhost/api/weather.php?alerts=DUS"
```

### 6. API Status

**Endpoint:** `GET /status`

**Response:**
```json
{
  "status": "ok",
  "cacheHit": false,
  "cacheAge": 0,
  "apiVersion": "2.0.0"
}
```

---

## FlightAware API Endpoints

### 1. Flight Status

**Endpoint:** `GET /status/{flight_number}`

**Example:**
```bash
curl "http://localhost/api/flightaware.php?flight=AA123"
```

**Response:**
```json
{
  "success": true,
  "flight": {
    "status": "enroute",
    "origin": "KJFK",
    "destination": "KDUS",
    "scheduled": {
      "departure": "2026-05-26T22:00Z",
      "arrival": "2026-05-26T23:30Z"
    },
    "estimated": {
      "departure": "2026-05-26T22:05Z",
      "arrival": "2026-05-26T23:35Z"
    }
  }
}
```

### 2. Flight Position

**Endpoint:** `GET /position/{flight_number}`

**Example:**
```bash
curl "http://localhost/api/flightaware.php?position=AA123"
```

### 3. Airline Flights

**Endpoint:** `GET /airline/{airline_code}`

**Example:**
```bash
curl "http://localhost/api/flightaware.php?airline=AA"
```

### 4. Flight Search

**Endpoint:** `GET /search/{origin}/{destination}/{date}`

**Example:**
```bash
curl "http://localhost/api/flightaware.php?search=DUS/JFK/2026-05-27"
```

---

## OpenAIP API Endpoints

Full OpenAIP implementation with 12 endpoints:

- Aircraft management
- Airline management
- Flight management
- Booking management
- PIREP management
- User management
- And more...

**Example:**
```bash
# Get all aircraft
curl "http://localhost/api/openaip.php?aircraft=ALL"
```

---

## Error Handling

### Error Format

All errors follow this format:

```json
{
  "success": false,
  "error": "error message",
  "details": "optional details",
  "code": "ERROR_CODE"
}
```

### Common Errors

| Error Code | Description |
|------------|-------------|
| INVALID_AIRPORT | Invalid airport code provided |
| INVALID_FLIGHT | Flight number not found |
| RATE_LIMITED | Too many requests |
| API_UNAVAILABLE | External API unavailable |
| CACHE_EXPIRED | Cache expired, refreshing |
| NETWORK_ERROR | Network connection failed |

### Error Examples

```json
{
  "success": false,
  "error": "Invalid airport code",
  "details": "DUS123 is not a valid airport code",
  "code": "INVALID_AIRPORT"
}
```

---

## Rate Limiting

### Default Limits

- **Weather API:** 100 requests/minute
- **FlightAware API:** 100 requests/minute
- **OpenAIP API:** 50 requests/minute
- **Status API:** Unlimited

### Rate Limit Headers

```
X-RateLimit-Limit: 100
X-RateLimit-Remaining: 85
X-RateLimit-Reset: 1685198400
```

### Handling Rate Limits

On 429 Too Many Requests:

```bash
# Exponential backoff
wait=$(( (429-1) * 1 + 60 ))  # Start with 1s, add 60s
sleep $wait

# Retry request
curl "http://localhost/api/weather.php?airport=DUS"
```

---

## Caching Strategy

### Weather Data

- **TTL:** 5 minutes
- **Storage:** In-memory cache
- **Benefits:** Reduced API calls

### Flight Data

- **TTL:** 2 minutes (live data)
- **Storage:** In-memory cache
- **Benefits:** Recent position data

### Status Data

- **TTL:** 1 hour
- **Storage:** In-memory cache
- **Benefits:** Consistent API info

---

## Query Parameters

### Common Parameters

| Parameter | Type | Description |
|-----------|------|-------------|
| airport | string | Airport code (ICAO/IATA) |
| flight | string | Flight number (e.g., AA123) |
| days | integer | Number of forecast days (1-7) |
| airline | string | Airline code (e.g., AA, DL) |
| date | string | Date for search (YYYY-MM-DD) |
| format | string | JSON (default) or XML |

### Examples

```bash
# Get 5-day forecast
curl "http://localhost/api/weather.php?airport=DUS&days=5"

# Get airline flights
curl "http://localhost/api/flightaware.php?airline=AA&format=JSON"

# Get METAR for multiple airports
curl "http://localhost/api/weather.php?metar=DUS&metar=FRA&metar=MUC"
```

---

## Response Formats

### JSON (Default)

```json
{
  "success": true,
  "data": { ... },
  "meta": {
    "request_id": "abc123",
    "timestamp": 1685198400,
    "cache_hit": false
  }
}
```

### Pretty Print

Enable pretty-printed JSON:

```bash
curl "http://localhost/api/weather.php?airport=DUS&pretty=1"
```

---

## SDKs & Libraries

### cURL Examples

```bash
# Weather API
curl "http://localhost/api/weather.php?airport=DUS"

# FlightAware API
curl "http://localhost/api/flightaware.php?flight=AA123"
```

### PHP

```php
use RunwayHub\API\Weather;
use RunwayHub\API\FlightAware;

$weather = new Weather();
$current = $weather->getCurrentWeather('DUS');

$flight = new FlightAware();
$status = $flight->getFlightStatus('AA123');
```

### JavaScript (fetch)

```javascript
const response = await fetch('http://localhost/api/weather.php?airport=DUS');
const data = await response.json();
console.log(data);
```

---

## Code Examples

### PHP Weather Example

```php
<?php
require 'vendor/autoload.php';

use RunwayHub\API\Weather;

$weather = new Weather();

// Get current weather
$current = $weather->getCurrentWeather('DUS');
echo "Temperature: " . $current['temperature'] . "°C\n";

// Get forecast
$forecast = $weather->getForecast('DUS', 3);
echo "3-day forecast retrieved\n";
?>
```

### JavaScript Weather Example

```javascript
async function getWeather(airport) {
  const response = await fetch(`http://localhost/api/weather.php?airport=${airport}`);
  const data = await response.json();
  
  if (data.success) {
    console.log(`Weather in ${airport}: ${data.data.temperature}°C`);
  }
}

getWeather('DUS');
```

### Python Weather Example

```python
import requests

def get_weather(airport):
    response = requests.get(f"http://localhost/api/weather.php?airport={airport}")
    data = response.json()
    
    if data['success']:
        print(f"Weather in {airport}: {data['data']['temperature']}°C")

get_weather('DUS')
```

---

## Webhooks

### Available Endpoints

Future versions will support:

- Flight status change webhooks
- Weather alert notifications
- Booking confirmation webhooks

---

## Testing

### Test Suite

RunwayHub includes comprehensive test suite:

```bash
cd tests
php run-tests.php
```

### Test Endpoints

```bash
# Weather API test
curl "http://localhost/api/weather.php?airport=DUS"

# FlightAware API test
curl "http://localhost/api/flightaware.php?flight=AA123"

# Status check
curl "http://localhost/api/status.php"
```

---

## Monitoring

### Health Checks

```bash
# API health
curl http://localhost/api/status.php

# Database health
mysql -u user -p database -e "SHOW PROCESSLIST;"

# Cache health
php artisan cache:clear
```

### Metrics

- API response times
- Cache hit rates
- Error rates
- Request counts

---

## Support

### Getting Help

- **Documentation:** /docs/
- **API Reference:** /docs/api.md
- **GitHub Issues:** https://github.com/chris1971nrw/runwayhub/issues
- **Forum:** Flugsimulationsforum.de

### Community

- Join the discussion on GitHub
- Report bugs
- Suggest features
- Contribute code

---

**Version:** 2.0.0  
**Last Updated:** 2026-05-26  
**Base URL:** http://localhost/api/  
**License:** MIT
