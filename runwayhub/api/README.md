# RunwayHub API

REST API für RunwayHub - Virtual Airline Manager.

## Basisinformationen

**Base URL:** `https://runwayhub.de/api/`

**Authentifizierung:** API Keys (Bereits implementiert)

**Format:** JSON

**Version:** 1.0.0

---

## Übersicht

### Implementierte Endpunkte

- ✅ OpenAIP: 12 Endpunkte
- ✅ Wetter-API: 6 Endpunkte
- ✅ FlightAware: 4 Endpunkte
- ✅ Gesamt: 22+ Endpunkte

### Geschriebene Endpunkte

- ✅ `GET /airport/{airport}` - Flughafeninfos
- ✅ `GET /weather/{airport}` - Wetterdaten
- ✅ `GET /flightaware/{flight}` - Flugverfolgung
- ✅ `GET /aircraft/{aircraft}` - Flugzeuginfos
- ✅ `GET /pilots/{callsign}` - Pilotendaten
- ✅ `GET /airlines` - Airline-Liste
- ✅ `GET /routes` - Routen
- ✅ `GET /flights/{flightId}` - Flugdaten
- ✅ `POST /bookings` - Buchung erstellen
- ✅ `GET /bookings/{bookingId}` - Buchungsdetails
- ✅ `GET /pireps` - PIREP-Liste
- ✅ `GET /statistics` - Statistiken
- ✅ `GET /leaderboard` - Leaderboards

### API Key

Erhalten Sie Ihren API Key aus dem Admin-Panel unter `/admin/api-keys`.

---

## Endpunkte

### OpenAIP

- `GET /openaip/airport/{airport}` - Flughafeninformationen
- `GET /openaip/weather/current` - Aktuelle Wetterdaten
- `GET /openaip/weather/forecast` - Wettervorhersage
- `GET /openaip/flights` - Aktive Flüge
- `GET /openaip/asterads` - ASTERADS Daten
- `GET /openaip/notams` - NOTAM Informationen
- `GET /openaip/pireps` - PIREP-Liste
- `GET /openaip/almanac` - Almanak Daten
- `GET /openaip/navaids` - Navaids Informationen
- `GET /openaip/airlines` - Airline-Liste
- `GET /openaip/aircraft` - Flugzeugtypen
- `GET /openaip/facilities` - Bodenfacilities

### Wetter-API

- `GET /weather/{airport}` - Wetter für Flughafen
- `GET /weather/current` - Aktuelles Wetter
- `GET /weather/forecast` - Wettervorhersage
- `GET /weather/alerts` - Wetterwarnungen
- `GET /weather/turbulence` - Turbulenzdaten
- `GET /weather/visibility` - Sichtbarkeitsdaten

### FlightAware

- `GET /flightaware/flights` - Flugverfolgung
- `GET /flightaware/{flight}` - Einzelflug-Details
- `GET /flightaware/airports` - Flughafen-Liste
- `GET /flightaware/delays` - Verspätungsstatistiken

### Status

- `GET /status` - API Status
- `GET /health` - Health Check
- `GET /version` - API Version

---

## Dokumentation

Vollständige API-Dokumentation verfügbar unter: [/api/docs](/api/docs)

**Swagger/OpenAPI:** `/api/docs/swagger.json`

**ReDoc:** `/api/docs/redoc`

**Postman Collection:** `/api/docs/postman.json`

---

## Rate Limits

| Endpoint | Rate Limit |
|----------|------------|
| OpenAIP | 100 requests/min |
| Wetter-API | 60 requests/min |
| FlightAware | 10 requests/min |
| Status | Unlimited |

---

## Beispiel

```bash
curl https://runwayhub.de/api/airport/EDDF
```

Response:
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
    "timezone": "Europe/Berlin"
  }
}
```

---

**Version:** 1.0.0  
**Last Updated:** 2026-05-27
