# API Documentation - RunwayHub v2.0.0

**Complete API Reference**  
**Version:** 2.0.0  
**Last Updated:** 2026-05-26

---

## Overview

RunwayHub provides comprehensive aviation data through three integrated APIs:

1. **Weather API** - Real-time weather and forecasts (6 endpoints)
2. **FlightAware API** - Live flight tracking (4 endpoints)
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

## Quick Start

### Example Requests

**Get Current Weather:**
```bash
curl "http://localhost/api/weather.php?airport=DUS"
```

**Get Flight Status:**
```bash
curl "http://localhost/api/flightaware.php?flight=AA123"
```

**Get OpenAIP Aircraft:**
```bash
curl "http://localhost/api/openaip.php?aircraft=ALL"
```

---

## Weather API Endpoints

### 1. Current Weather

**Endpoint:** `GET /current/{airport}`

**Description:** Get current weather conditions for a specific airport.

**Parameters:**
- `airport` (string, required) - Airport ICAO code (e.g., DUS, FRA, KJFK)

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
  },
  "meta": {
    "cache_hit": false,
    "cache_age": 0,
    "request_id": "abc123"
  }
}
```

**Example Response:**
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
    "pressure": 1013
  }
}
```

### 2. Weather Forecast

**Endpoint:** `GET /forecast/{airport}?days={n}`

**Description:** Get weather forecast for multiple days.

**Parameters:**
- `airport` (string, required) - Airport ICAO code
- `days` (integer, optional) - Number of forecast days (1-7, default: 3)

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

**Description:** Get METAR aviation weather observation.

**Parameters:**
- `airport` (string, required) - Airport ICAO code

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

**Description:** Get TAF aviation forecast.

**Parameters:**
- `airport` (string, required) - Airport ICAO code

**Response:**
```json
{
  "success": true,
  "airport": "DUS",
  "taf": "TAF DUS 262320Z 2700/2806 27015KT 9999 SCT025...",
  "parsed": {
    "validFrom": "2700Z",
    "validUntil": "2806Z",
    "wind": "27015KT",
    "visibility": "9999",
    "conditions": "SCT025"
  }
}
```

### 5. Weather Alerts

**Endpoint:** `GET /alerts/{airport}`

**Description:** Get weather alerts for a specific airport.

**Parameters:**
- `airport` (string, required) - Airport ICAO code

**Response:**
```json
{
  "success": true,
  "airport": "DUS",
  "alerts": [
    {
      "type": "wind",
      "severity": "moderate",
      "description": "High winds expected",
      "startTime": "2026-05-27T02:00Z",
      "endTime": "2026-05-27T08:00Z"
    }
  ]
}
```

### 6. API Status

**Endpoint:** `GET /status`

**Description:** Check API health and status.

**Response:**
```json
{
  "status": "ok",
  "cacheHit": false,
  "cacheAge": 0,
  "apiVersion": "2.0.0",
  "uptime": "99.9%"
}
```

---

## FlightAware API Endpoints

### 1. Flight Status

**Endpoint:** `GET /status/{flight}`

**Description:** Get real-time flight status.

**Parameters:**
- `flight` (string, required) - Flight number (e.g., AA123, DL456)

**Response:**
```json
{
  "success": true,
  "flight": {
    "status": "enroute",
    "origin": "KJFK",
    "destination": "KDUS",
    "airline": "AA",
    "flightNumber": "AA123",
    "scheduled": {
      "departure": "2026-05-26T22:00Z",
      "arrival": "2026-05-26T23:30Z"
    },
    "estimated": {
      "departure": "2026-05-26T22:05Z",
      "arrival": "2026-05-26T23:35Z"
    },
    "actual": {
      "departure": "2026-05-26T22:03Z",
      "arrival": null
    },
    "position": {
      "latitude": 40.5,
      "longitude": -75.2,
      "altitude": 35000,
      "speed": 450
    }
  }
}
```

**Status Values:**
- `scheduled` - Scheduled for departure
- `enroute` - Currently in flight
- `landed` - Has arrived at destination
- `delayed` - Delayed from schedule
- `cancelled` - Flight cancelled
- `diverted` - Diverted to alternate

### 2. Flight Position

**Endpoint:** `GET /position/{flight}`

**Description:** Get real-time flight position and altitude.

**Parameters:**
- `flight` (string, required) - Flight number

**Response:**
```json
{
  "success": true,
  "flight": {
    "position": {
      "latitude": 40.5,
      "longitude": -75.2,
      "altitude": 35000,
      "speed": 450,
      "heading": 270,
      "verticalSpeed": 0,
      "updated": "2026-05-26T21:52:00Z"
    }
  }
}
```

### 3. Airline Flights

**Endpoint:** `GET /airline/{airline}`

**Description:** Get all flights for an airline.

**Parameters:**
- `airline` (string, required) - Airline code (e.g., AA, DL, UA)

**Response:**
```json
{
  "success": true,
  "airline": "AA",
  "flights": [
    {
      "flightNumber": "AA123",
      "origin": "KJFK",
      "destination": "KDUS",
      "status": "enroute",
      "scheduled": {
        "departure": "2026-05-26T22:00Z"
      }
    }
  ]
}
```

### 4. Flight Search

**Endpoint:** `GET /search/{origin}/{destination}/{date}`

**Description:** Search for flights by route and date.

**Parameters:**
- `origin` (string, required) - Origin airport code
- `destination` (string, required) - Destination airport code
- `date` (string, required) - Date in YYYY-MM-DD format

**Response:**
```json
{
  "success": true,
  "search": {
    "origin": "DUS",
    "destination": "JFK",
    "date": "2026-05-27",
    "flights": [
      {
        "flightNumber": "UA456",
        "airline": "UA",
        "scheduled": {
          "departure": "2026-05-27T06:00Z",
          "arrival": "2026-05-27T09:00Z"
        },
        "status": "scheduled"
      }
    ]
  }
}
```

---

## OpenAIP API Endpoints

**Full OpenAIP implementation with 12 endpoints:**

### 1. Aircraft List

**Endpoint:** `GET /aircraft/{list}`

**Parameters:**
- `list` (string) - "ALL" for all aircraft, or specific aircraft ID

### 2. Airline List

**Endpoint:** `GET /airlines/{list}`

**Parameters:**
- `list` (string) - "ALL" for all airlines

### 3. Flight List

**Endpoint:** `GET /flights/{list}`

**Parameters:**
- `list` (string) - Flight numbers to retrieve

### 4. Booking List

**Endpoint:** `GET /bookings/{list}`

**Parameters:**
- `list` (string) - Booking IDs to retrieve

### 5. PIREP List

**Endpoint:** `GET /pireps/{list}`

**Parameters:**
- `list` (string) - PIREP IDs to retrieve

### 6-12. Additional Endpoints

- User management
- Aircraft details
- Airline details
- Flight details
- Booking details
- PIREP details
- And more...

**See Full OpenAIP Documentation for all endpoints.**

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

### Common Error Codes

| Error Code | Description |
|------------|-------------|
| `INVALID_AIRPORT` | Invalid airport code provided |
| `INVALID_FLIGHT` | Flight number not found |
| `RATE_LIMITED` | Too many requests |
| `API_UNAVAILABLE` | External API unavailable |
| `CACHE_EXPIRED` | Cache expired, refreshing |
| `NETWORK_ERROR` | Network connection failed |

### Example Error Response

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

```php
// Exponential backoff
$wait = ($attempt - 1) * 1 + 60; // Start with 1s, add 60s
sleep($wait);

// Retry request
$response = $weather->getCurrentWeather('DUS');
```

---

## Caching Strategy

### Cache TTL

- **Weather Data:** 5 minutes
- **Flight Data:** 2 minutes (live data)
- **OpenAIP Data:** 1 hour
- **Status Data:** No cache

### Cache Headers

```
X-Cache-Hit: true
X-Cache-Age: 300
```

---

## Usage Examples

### PHP Example

```php
<?php
require 'vendor/autoload.php';

use RunwayHub\API\Weather;
use RunwayHub\API\FlightAware;

$weather = new Weather();
$flight = new FlightAware();

// Get current weather
$current = $weather->getCurrentWeather('DUS');
echo "Temperature: " . $current['temperature'] . "°C\n";

// Get flight status
$status = $flight->getFlightStatus('AA123');
echo "Flight: " . $status['status'] . "\n";
?>
```

### JavaScript Example

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

### Python Example

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

## SDKs & Libraries

### cURL Examples

```bash
# Weather API
curl "http://localhost/api/weather.php?airport=DUS"

# FlightAware API
curl "http://localhost/api/flightaware.php?flight=AA123"
```

### REST Client Examples

**Get Current Weather:**
```
GET /api/weather.php
Parameters:
  airport = DUS
```

**Get Flight Status:**
```
GET /api/flightaware.php
Parameters:
  flight = AA123
```

---

## Widget Examples

RunwayHub includes pre-built widgets:

- **Weather Widget:** `gh-pages/examples/weather-widget.html`
- **Flight Tracker:** `gh-pages/examples/flight-tracker.html`

**Usage:**
1. Download the HTML file
2. Serve via web server
3. Customize as needed

---

## Documentation Resources

- **API Documentation:** /docs/api.md
- **Weather Guide:** /docs/weather-api.md
- **FlightAware Guide:** /flightaware.md
- **Deployment Guide:** /docs/deployment.md
- **Security Guide:** /docs/security.md
- **Roadmap:** /docs/roadmap.md
- **User Guide:** /docs/user-guide.md
- **FAQ:** /docs/faq.md
- **Changelog:** /changelog.md

---

## Support

### Getting Help

- **GitHub Issues:** https://github.com/chris1971nrw/runwayhub/issues
- **Documentation:** /docs/
- **Forum:** Flugsimulationsforum.de
- **Email:** support@runwayhub.local

### Community

- Star the repository
- Fork and contribute
- Report bugs
- Request features

---

## Version Information

- **Version:** 2.0.0
- **Release Date:** 2026-05-26
- **License:** MIT
- **Base URL:** http://localhost/api/

---

**Last Updated:** 2026-05-26 21:52 Europe/Berlin  
**Version:** 2.0.0  
**Status:** Production Ready  
**License:** MIT
