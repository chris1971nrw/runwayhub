# API Endpoints - RunwayHub

## Übersicht

Dieses Dokument listet alle API-Endpunkte mit Beschreibung und Beispielen auf.

---

## OpenAIP Endpoints

### GET /openaip/airport/{airport}

Flughafeninformationen abrufen.

**Parameters:**
- `airport` (required) - ICAO Code (z.B. EDDF)

**Response:**
```json
{
  "success": true,
  "data": {
    "airport": "EDDF",
    "name": "München Franz Josef Strauss",
    "iata": "EDDF",
    "icao": "EDDM",
    "latitude": 48.3537,
    "longitude": 11.7860,
    "elevation": 470,
    "timezone": "Europe/Berlin"
  }
}
```

### GET /openaip/weather/current

Aktuelle Wetterdaten für Flughafen.

**Parameters:**
- `airport` (required)

**Response:**
```json
{
  "success": true,
  "data": {
    "metar": "EDDF 271100Z 27010KT 9999 FEW035 20/10 Q1020 NOSIG",
    "taf": "TAF EDDF 271100Z...",
    "wind": {"direction": 270, "speed": 10, "gusts": null},
    "visibility": 9999,
    "weather": [],
    "clouds": [{"coverage": "FEW", "base": 3500}],
    "temperature": 20,
    "dewpoint": 10
  }
}
```

### GET /openaip/weather/forecast

Wettervorhersage.

**Response:**
```json
{
  "success": true,
  "data": {
    "forecasts": [
      {"hour": 0, "temperature": 20, "precipitation": 0, "wind_speed": 10},
      {"hour": 6, "temperature": 22, "precipitation": 0, "wind_speed": 12}
    ]
  }
}
```

### GET /openaip/flights

Aktive Flüge (Flugverfolgung).

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "flight_number": "LH1234",
      "callsign": "DLH1234",
      "aircraft": "A320",
      "origin": "EDDF",
      "destination": "EDDL",
      "status": "en-route",
      "latitude": 50.0,
      "longitude": 8.5,
      "altitude": 35000,
      "eta": "2026-05-27T14:00:00Z"
    }
  ]
}
```

### GET /openaip/asterads

ASTERADS Daten (Aviation Area Safety and Reporting).

### GET /openaip/notams

NOTAM (Notice to Air Missions) Informationen.

### GET /openaip/pireps

PIREP (Pilot Reports) Liste.

### GET /openaip/almanac

Almanak Daten (Navigationsdaten).

### GET /openaip/navaids

Navaids (Navigationshilfen) Informationen.

### GET /openaip/airlines

Liste aller Airline.

### GET /openaip/aircraft

Liste aller Flugzeugtypen.

### GET /openaip/facilities

Bodenfacilities Informationen.

---

## Wetter-API Endpoints

### GET /weather/{airport}

Wetterdaten für spezifischen Flughafen.

### GET /weather/current

Aktuelle Wetterdaten (global).

### GET /weather/forecast

Wettervorhersage für mehrere Standorte.

### GET /weather/alerts

Aktuelle Wetterwarnungen.

### GET /weather/turbulence

Turbulenzvorhersagen.

### GET /weather/visibility

Sichtbarkeitsdaten.

---

## FlightAware Endpoints

### GET /flightaware/flights

Flugverfolgung via FlightAware API.

### GET /flightaware/{flight}

Details für spezifischen Flug.

### GET /flightaware/airports

Liste aller Flughäfen.

### GET /flightaware/delays

Verspätungsstatistiken.

---

## Login System Endpoints

### POST /api/login-pilot.php

Pilotauthentifizierung mit Callsign/Passwort.

**Parameters:**
- `callsign` (required) - Piloten Callsign
- `password` (required) - Benutzerpasswort

**Response:**
```json
{
  "success": true,
  "user": {
    "id": "pilot-001",
    "callsign": "demo_pilot",
    "airline": "Lufthansa"
  },
  "sessionToken": "uuid-generated-token",
  "sessionId": "uuid-session-id",
  "groups": ["pilots"]
}
```

### POST /api/pilot-register.php

Neuen Piloten registrieren.

**Parameters:**
- `callsign` (required)
- `password` (required)
- `email` (required)
- `airline` (optional)
- `airline_url` (optional)

**Response:**
```json
{
  "success": true,
  "user": {
    "id": "pilot-002",
    "callsign": "demo_guest",
    "email": "demo@guest.ab.de"
  },
  "message": "Account created successfully"
}
```

### GET /api/pilot/verify/{callsign}

Verifiziert Callsign ohne Passwort.

**Parameters:**
- `callsign` (required)

**Response:**
```json
{
  "valid": true,
  "callsign": "demo_pilot",
  "airline": "Lufthansa"
}
```

### POST /api/logout.php

Logout mit Session-Token.

**Parameters:**
- `sessionToken` (required)

### GET /api/pilot/profile/{token}

Profilinformationen.

**Parameters:**
- `token` (required)

---

## Virtual Airline Endpoints

### POST /api/va-create.php

Neue Virtual Airline erstellen.

**Parameters:**
- `name` (required) - VA Name
- `logo` (required) - Logo URL
- `colors` (required) - {"primary": "#ff0000", "secondary": "#ffffff"}
- `airlineName` (required)
- `website` (required)

**Response:**
```json
{
  "success": true,
  "va": {
    "id": "va-001",
    "name": "Lufthansa Virtual",
    "airline_name": "Lufthansa",
    "airline_website": "https://www.lufthansa.com",
    "logo": "logo.png",
    "colors": {"primary": "#000000", "secondary": "#ff0000"}
  },
  "owner_credentials": {
    "username": "lhvirtual20260527",
    "password": "hashed-uuid",
    "expires": "+365 days"
  },
  "group_id": "group-001"
}
```

### POST /api/va-connect.php

Bestehende VA anschließen.

**Parameters:**
- `ownerCredentials.username` (required)
- `ownerCredentials.password` (required)
- `website` (optional)

**Response:**
```json
{
  "success": true,
  "va": {
    "id": "va-001",
    "name": "Lufthansa Virtual",
    "airline_name": "Lufthansa",
    "airline_website": "https://www.lufthansa.com"
  },
  "group_id": "group-001",
  "message": "Successfully connected to virtual airline"
}
```

### GET /api/va/list

Alle Virtual Airlines auflisten.

**Response:**
```json
{
  "va": [
    {
      "id": "va-001",
      "name": "Lufthansa Virtual",
      "airline_name": "Lufthansa",
      "airline_website": "https://www.lufthansa.com",
      "logo": "logo.png",
      "colors": {"primary": "#000000", "secondary": "#ff0000"}
    }
  ]
}
```

### GET /api/va/{vaId}

VA nach ID abrufen.

### PUT /api/va/{vaId}

VA aktualisieren.

### DELETE /api/va/{vaId}

VA löschen.

---

## Core Endpoints

### GET /status

API Status überprüfen.

**Response:**
```json
{
  "success": true,
  "data": {
    "status": "operational",
    "version": "1.0.0",
    "uptime": 86400,
    "requests": {
      "total": 12345,
      "today": 567,
      "rate_limit": 100
    }
  }
}
```

### GET /health

Health Check.

### GET /version

API Version.

---

## Admin Endpoints

### POST /admin/api-keys

Neuen API Key erstellen.

### GET /admin/api-keys

API Keys auflisten.

### DELETE /admin/api-keys/{id}

API Key löschen.

---

## User Endpoints

### POST /user/register

Neues Benutzerkonto erstellen.

### POST /user/login

Benutzeranmeldung.

### GET /user/profile

Profilinformationen.

### PUT /user/profile

Profil aktualisieren.

### POST /user/logout

Ausloggen.

---

## Error Handling

### Fehlerantworten

- `400 Bad Request` - Ungültige Anfrage
- `401 Unauthorized` - Ungültige Authentifizierung
- `403 Forbidden` - Fehlende Berechtigungen
- `404 Not Found` - Ungültiger Endpunkt
- `429 Too Many Requests` - Rate Limit erreicht
- `500 Internal Server Error` - Serverfehler

### Fehlerformat

```json
{
  "success": false,
  "error": true,
  "message": "Fehlermeldung",
  "code": "ERROR_CODE",
  "details": {}
}
```

---

## Rate Limits

| Endpoint | Limit | Window |
|----------|-------|--------|
| OpenAIP | 100 requests | 60s |
| Wetter-API | 60 requests | 60s |
| FlightAware | 10 requests | 60s |
| Status | Unlimited | - |
| Admin | 10 requests | 60s |

---

## Authentication

API Keys verwenden.

Header: `Authorization: Bearer {API_KEY}`

Oder Query Parameter: `api_key={API_KEY}`

---

## Best Practices

1. **Rate Limits einhalten** - Vermeiden Sie zu viele requests
2. **Cache responses** - Speichern Sie nicht-dynamische Daten
3. **Error Handling** - Behandeln Sie Fehlergracefully
4. **CORS** - Nur vertrauenswürdige Domains
5. **HTTPS** - Immer HTTPS verwenden

---

## Rate Limit Bumping

Bei Überschreitung: `Retry-After` Header enthält Sekunden bis zur nächsten request.

---

**Version:** 1.0.0  
**Last Updated:** 2026-05-27
