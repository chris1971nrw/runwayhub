# RunwayHub API Documentation

## Overview

RunwayHub provides a comprehensive API for aviation data access. All endpoints support JSON responses.

### Base URL
```
https://chris1971nrw.github.io/runwayhub/api/
```

### Authentication
Most endpoints are public. Some booking endpoints require authentication via header:
```
Authorization: Bearer <token>
```

---

## Weather Endpoints

### GET /weather/current
Get current weather for airport

**Parameters:**
- `airport` (required): ICAO code (e.g., `EDDM`)

**Response:**
```json
{
  "success": true,
  "data": {
    "temperature": 15,
    "humidity": 65,
    "wind": {
      "direction": 270,
      "speed": 12,
      "gusts": 18
    },
    "visibility": 10000,
    "conditions": "Clear",
    "pressure": 1013
  }
}
```

### GET /weather/latest
Get latest weather report

**Response:** Same as current

---

## Flight Endpoints

### GET /flight/status
Get flight status

**Parameters:**
- `icao24` (required): ICAO hex code (e.g., `3C6647`)
- `flightnumber`: Optional flight number

**Response:**
```json
{
  "success": true,
  "data": {
    "flight": {
      "number": "DLH400",
      "callsign": "DLH400",
      "type": "B738",
      "aircraft": "Boeing 737-800",
      "origin": "EDDM",
      "destination": "EDDF",
      "status": "en-route",
      "altitude": 35000,
      "speed": 450,
      "latitude": 48.5,
      "longitude": 11.2,
      "heading": 270,
      "eta": "2026-05-28T12:00:00Z"
    }
  }
}
```

### GET /flight/check
Check flight availability

---

## Airport Endpoints

### GET /airport/info
Get airport information

**Parameters:**
- `airport` (required): ICAO or IATA code

**Response:**
```json
{
  "success": true,
  "data": {
    "icao": "EDDF",
    "iata": "FRA",
    "name": "Frankfurt Airport",
    "city": "Frankfurt",
    "country": "DE",
    "latitude": 50.0379,
    "longitude": 8.5622,
    "elevation": 364,
    "timezone": "Europe/Berlin",
    "runways": [...],
    "terminals": [...]
  }
}
```

---

## PIREP Endpoints

### GET /pirep
Get PIREP (Pilot Report)

**Parameters:**
- `airport` (required)
- `date` (optional): YYYY-MM-DD

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": "PIREP-001",
      "airport": "EDDM",
      "datetime": "2026-05-28T10:00:00Z",
      "wind": "270/12/18",
      "visibility": "10km",
      "weather": "FEW040",
      "turbulence": "Light",
      "icing": "None",
      "remarks": "Good flying conditions"
    }
  ]
}
```

---

## Statistics Endpoints

### GET /statistics
Get overall statistics

**Response:**
```json
{
  "success": true,
  "data": {
    "totalFlights": 150000,
    "totalAircraft": 8500,
    "totalPilots": 1200,
    "totalBookings": 3500,
    "activeUsers": 850,
    "apiRequestsToday": 15000
  }
}
```

---

## Booking Endpoints

### POST /booking
Create new booking

**Headers:**
```
Authorization: Bearer <token>
Content-Type: application/json
```

**Request:**
```json
{
  "aircraft": "C172",
  "pilot": "John Doe",
  "airport": "EDDM",
  "startTime": "2026-06-01T09:00:00Z",
  "endTime": "2026-06-01T12:00:00Z",
  "purpose": "Training flight"
}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "booking": {
      "id": "BK-2026-001",
      "status": "confirmed",
      "createdAt": "2026-05-28T10:00:00Z"
    }
  }
}
```

---

## Leaderboard Endpoints

### GET /leaderboard
Get pilot rankings

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "rank": 1,
      "pilot": {
        "name": "Max Mustermann",
        "callsign": "GHOST",
        "country": "DE",
        "totalHours": 2500
      },
      "points": 15000
    }
  ]
}
```

---

## Turbulence Endpoints

### GET /turbulence
Get turbulence forecast

**Parameters:**
- `lat`, `lon`: Coordinates
- `range`: Distance in km

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "time": "2026-05-28T12:00:00Z",
      "intensity": "Light",
      "region": {
        "lat": 48.5,
        "lon": 11.2
      }
    }
  ]
}
```

---

## ACARS Endpoints

### GET /acars
Get ACARS message

**Parameters:**
- `messageId`: Message ID

**Response:**
```json
{
  "success": true,
  "data": {
    "messageId": "MSG-001",
    "type": "POSITION",
    "aircraft": "3C6647",
    "position": {
      "latitude": 48.5,
      "longitude": 11.2,
      "altitude": 35000
    },
    "timestamp": "2026-05-28T10:30:00Z"
  }
}
```

---

## Health & Status

### GET /api-status
API health check

**Response:**
```json
{
  "status": "operational",
  "version": "2.0.3",
  "uptime": 86400,
  "services": {
    "weather": "operational",
    "flight": "operational",
    "aircraft": "operational"
  }
}
```

---

## Error Codes

| Code | Description |
|------|-------------|
| 400 | Bad Request - Invalid parameters |
| 401 | Unauthorized - Authentication required |
| 404 | Not Found - Resource doesn't exist |
| 429 | Too Many Requests - Rate limit exceeded |
| 500 | Internal Server Error |
| 503 | Service Unavailable |

---

## Rate Limiting

API requests are limited to:
- 100 requests per minute per IP
- 1000 requests per hour per IP

Headers returned with each response:
```
X-RateLimit-Limit: 100
X-RateLimit-Remaining: 95
X-RateLimit-Reset: 1716923400
```

---

## Changelog

### v2.0.3 (2026-05-28)
- Added ACARS endpoint
- Improved turbulence forecasting
- Enhanced weather data

### v2.0.2 (2026-05-27)
- Added PIREP endpoint
- Improved flight tracking

### v2.0.0 (2026-05-25)
- Initial API release
- Core endpoints implemented

---

*Documentation last updated: 2026-05-28*
