# API Endpoints für Demo-Daten

Dieses Dokument beschreibt alle verfügbaren API-Endpunkte für Demo-Daten.

## Basis-Informationen

- **Base URL**: `/api/demo/`
- **Authentication**: Optional (Demo-Daten sind öffentlich zugänglich)
- **Content-Type**: `application/json`
- **Accept**: `application/json`

## Available Endpoints

### 1. Demo Airline Info

```
GET /api/demo/airline
```

**Beschreibung**: Liefert Informationen über die Demo Airline.

**Response**:
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "DemoFly",
    "code": "DMO",
    "iata": "DM",
    "icao": "DMFLY",
    "callsign": "DEMOFLY",
    "country": "DE",
    "logo": null,
    "color": "#0066cc",
    "is_active": true
  },
  "timestamp": 1650000000
}
```

### 2. Demo User List

```
GET /api/demo/users
```

**Beschreibung**: Liefert eine Liste aller Demo Benutzer.

**Response**:
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "username": "demo_admin",
      "email": "admin@demofly.runwayhub.de",
      "full_name": "Demo Administrator",
      "role": "admin",
      "is_active": true,
      "airline": {
        "id": 1,
        "name": "DemoFly",
        "code": "DMO"
      },
      "type_ratings": null,
      "callsign": null
    },
    ...
  ],
  "count": 4,
  "timestamp": 1650000000
}
```

### 3. Demo Flotte

```
GET /api/demo/aircraft
```

**Beschreibung**: Liefert eine Liste aller Flugzeuge in der Demo Flotte.

**Response**:
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "type": "Boeing",
      "model": "737-800",
      "registration": "DM-FAZ",
      "manufacturer": "Boeing",
      "year": 2023,
      "seats": 160,
      "engine": "CFM56-7B",
      "fuel": "Jet A",
      "range": 3115,
      "max_altitude": 41000,
      "status": "active",
      "airline": {
        "id": 1,
        "name": "DemoFly",
        "code": "DMO"
      }
    },
    ...
  ],
  "count": 3,
  "timestamp": 1650000000
}
```

### 4. Demo Flüge

```
GET /api/demo/flights
```

**Beschreibung**: Liefert eine Liste aller Demo Flüge.

**Response**:
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "flight_number": "DMF001",
      "aircraft": {
        "id": 1,
        "registration": "DM-FAZ",
        "model": "737-800"
      },
      "pilot": {
        "id": 3,
        "username": "demo_pilot",
        "callsign": "DMF123"
      },
      "airline": {
        "id": 1,
        "name": "DemoFly",
        "code": "DMO"
      },
      "airport_from": "MUC",
      "airport_to": "FRA",
      "route": "München-Frankfurt",
      "distance": 530,
      "duration": 75,
      "scheduled_date": "2026-06-02",
      "scheduled_time": "08:00:00",
      "status": "scheduled",
      "price_economy": 299.00,
      "price_business": 599.00,
      "price_first": 1299.00,
      "available_seats": 150,
      "sold_seats": 0
    },
    ...
  ],
  "count": 1,
  "timestamp": 1650000000
}
```

### 5. Demo PIREPs

```
GET /api/demo/pireps
```

**Beschreibung**: Liefert eine Liste aller Demo PIREPs (Pilot Reports).

**Response**:
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "flight_number": "DMF001",
      "aircraft": {
        "registration": "DM-FAZ",
        "model": "737-800"
      },
      "pilot": {
        "id": 3,
        "username": "demo_pilot",
        "callsign": "DMF123"
      },
      "altitude": 35000,
      "speed": 450,
      "fuel": "75%",
      "temperature": -45.0,
      "weather_conditions": "Gewitter",
      "visibility": "5nm",
      "wind_speed": "45kt",
      "wind_direction": "SW",
      "turbulence": "Moderate",
      "icing": "None",
      "comments": "Glatter Flug, schöne Sicht.",
      "is_public": true,
      "status": "submitted",
      "created_at": "2026-05-26 16:00:00"
    },
    ...
  ],
  "count": 1,
  "timestamp": 1650000000
}
```

### 6. Demo Buchungen

```
GET /api/demo/bookings
```

**Beschreibung**: Liefert eine Liste aller Demo Buchungen.

**Response**:
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "booking_number": "DM001",
      "flight": {
        "flight_number": "DMF001",
        "airport_from": "MUC",
        "airport_to": "FRA"
      },
      "passenger": {
        "name": "Max Mustermann",
        "email": "max@example.com",
        "type": "adult"
      },
      "class": "economy",
      "price": 299.00,
      "tax": 15.00,
      "total": 314.00,
      "payment_method": "credit_card",
      "payment_status": "paid",
      "status": "confirmed",
      "created_at": "2026-05-26 16:00:00"
    },
    ...
  ],
  "count": 3,
  "timestamp": 1650000000
}
```

### 7. Reset Demo-Daten

```
POST /api/demo/reset
```

**Beschreibung**: Löscht alle Demo-Daten und installiert sie neu.

**Request Body**: Optional (leer)

**Response**:
```json
{
  "success": true,
  "message": "Demo-Daten wurden erfolgreich zurückgesetzt.",
  "timestamp": 1650000000
}
```

## Fehlerbehandlung

Die API verwendet standardisierte Fehlercodes:

| HTTP Status | Beschreibung |
|-------|------|
| 200 OK | Erfolgreiche Anfrage |
| 400 Bad Request | Ungültige Anfrage-Parameter |
| 401 Unauthorized | Authentifizierung erforderlich |
| 404 Not Found | Ressource nicht gefunden |
| 500 Internal Server Error | Interner Serverfehler |

## Beispiele

Sie können die API Endpoints über Ihren Webbrowser oder mit curl testen:

```bash
# Demo Airline Info
curl https://yoursite.com/api/demo/airline

# Demo User List
curl https://yoursite.com/api/demo/users

# Demo Flotte
curl https://yoursite.com/api/demo/aircraft

# Demo Flüge
curl https://yoursite.com/api/demo/flights

# Demo PIREPs
curl https://yoursite.com/api/demo/pireps

# Demo Buchungen
curl https://yoursite.com/api/demo/bookings

# Reset Demo-Daten
curl -X POST https://yoursite.com/api/demo/reset
```

## Sicherheitshinweise

- Die Demo-Daten sind für Entwicklungszwecke gedacht.
- In Produktion sollten Sie die Demo-Daten durch echte Daten ersetzen.
- Beachten Sie die DSGVO bei der Verwendung persönlicher Daten.
- Stellen Sie sicher, dass die API Endpoints für öffentliche Zugriffe geschützt sind, wenn sie personenbezogene Daten enthalten.

---
