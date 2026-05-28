# RunwayHub - Benutzerhandbuch

Dieses Handbuch erklärt alle Funktionen von RunwayHub.

## 🎯 Was ist RunwayHub?

RunwayHub ist ein **Virtual Airline Management System** zur Verwaltung von:
- **Flugzeugen**
- **Flügen**
- **Piloten**
- **Buchungen**
- **Wartung**
- **ACARS-Tracking**
- **Wetter-API**

## 📚 Inhalt

1. [Dashboard](#dashboard)
2. [Flugverwaltung](#flugverwaltung)
3. [Flottenverwaltung](#flottenverwaltung)
4. [Pilotenverwaltung](#pilotenverwaltung)
5. [Buchungsverwaltung](#buchungsverwaltung)
6. [Wartungsplanung](#wartungsplanung)
7. [ACARS-Tracking](#acars-tracking)
8. [Wetter-API](#wetter-api)
9. [Admin-Funktionen](#admin-funktionen)
10. [Issue-Reporting](#issue-reporting)

---

## Dashboard

### Statistiken

- **Aktive Flüge**: Anzahl heute
- **Flotte**: Gesamtzahl Flugzeuge
- **Piloten**: Aktive Piloten
- **Buchungen**: Aktuelle Buchungen

### Navigation

- **Flüge verwalten**: Flugplan erstellen
- **Flotte verwalten**: Flugzeug-Verwaltung
- **Piloten verwalten**: Piloten-Verzeichnis
- **Buchungen verwalten**: Buchungs-Management
- **Wartung verwalten**: Wartungsplanung
- **Einstellungen**: System-Konfiguration

---

## Flugverwaltung

### Flüge ansehen

- **Alle Flüge**: `GET /api/flights`
- **Flug Status**: `GET /api/flights/{id}`
- **Flug Details**: Flugnummer, Route, Status

### Flug erstellen

```bash
curl -X POST http://dein-domain.de/api/flights \
  -H "Content-Type: application/json" \
  -d '{
    "airline_id": 1,
    "flight_number": "LH456",
    "origin": "FRA",
    "destination": "JFK",
    "departure_time": "2026-05-28 14:30:00",
    "aircraft_id": 1
  }'
```

### Flug aktualisieren

```bash
curl -X PUT http://dein-domain.de/api/flights/1 \
  -H "Content-Type: application/json" \
  -d '{
    "status": "in-flight",
    "aircraft_id": 2
  }'
```

---

## Flottenverwaltung

### Flugzeuge ansehen

- **Alle Flugzeuge**: `GET /api/aircrafts`
- **Flugzeug Details**: `GET /api/aircrafts/{id}`

### Flugzeug erstellen

```bash
curl -X POST http://dein-domain.de/api/aircrafts \
  -H "Content-Type: application/json" \
  -d '{
    "registration": "D-AIMA",
    "manufacturer": "Boeing",
    "model": "737-800",
    "capacity": 162,
    "status": "active"
  }'
```

### Wartung planen

```bash
curl -X POST http://dein-domain.de/api/aircrafts/{id}/maintenance \
  -H "Content-Type: application/json" \
  -d '{
    "due_date": "2026-06-01",
    "description": "Ölwechsel und Inspektion"
  }'
```

---

## Pilotenverwaltung

### Piloten ansehen

- **Alle Piloten**: `GET /api/pilots`
- **Pilot Details**: `GET /api/pilots/{id}`

### Pilot erstellen

```bash
curl -X POST http://dein-domain.de/api/pilots \
  -H "Content-Type: application/json" \
  -d '{
    "first_name": "Max",
    "last_name": "Mustermann",
    "license": "B1",
    "credentials": "P-123456",
    "status": "active"
  }'
```

### Pilot deaktivieren

```bash
curl -X PATCH http://dein-domain.de/api/pilots/{id} \
  -H "Content-Type: application/json" \
  -d '{
    "status": "inactive"
  }'
```

### Pilot zu Flug zuweisen

```bash
curl -X PUT http://dein-domain.de/api/flights/{id}/pilot \
  -H "Content-Type: application/json" \
  -d '{
    "pilot_id": 1
  }'
```

---

## Buchungsverwaltung

### Buchungen ansehen

- **Alle Buchungen**: `GET /api/bookings`
- **Buchung Details**: `GET /api/bookings/{id}`

### Buchung erstellen

```bash
curl -X POST http://dein-domain.de/api/bookings \
  -H "Content-Type: application/json" \
  -d '{
    "flight_id": 1,
    "passenger_name": "Mustermann Max",
    "passenger_email": "max@example.com",
    "seat": "12A",
    "price": 150.00
  }'
```

### Verfügbarkeit prüfen

```bash
curl -X GET http://dein-domain.de/api/bookings/availability?flight_id=1&seat=12A
```

---

## Wartungsplanung

### Wartungsintervalle

- **Flugzeuge**: Automatische Wartungstermine
- **Inspektionen**: Jährliche Kontrollen
- **Ölwechsel**: Nach Flugstunden

### Wartungstermin

```bash
curl -X POST http://dein-domain.de/api/aircrafts/{id}/maintenance \
  -H "Content-Type: application/json" \
  -d '{
    "due_date": "2026-06-01",
    "description": "Jährliche Inspektion",
    "status": "scheduled"
  }'
```

---

## ACARS-Tracking

### Flight Status API

```bash
curl -X GET http://dein-domain.de/api/acars/flights?flight_number=LH456
```

**Response:**

```json
{
  "flight_number": "LH456",
  "origin": "FRA",
  "destination": "JFK",
  "departure_time": "2026-05-28 14:30:00",
  "status": "in-flight",
  "aircraft": "D-AIMA",
  "updated": "2026-05-28 16:30:00"
}
```

### Status-Typen

- `scheduled` - Geplant
- `active` - Aktiv
- `board` - Boarding
- `in-flight` - Im Flug
- `landed` - Gelandet
- `delayed` - Verspätet
- `cancelled` - Storniert

---

## Wetter-API

### Wetter für Flugstrecke

```bash
curl -X GET "http://dein-domain.de/api/weather?origin=FRA&destination=JFK"
```

### API-Response

```json
{
  "origin": {
    "city": "Frankfurt",
    "temperature": 22,
    "weather": "partly_cloudy"
  },
  "destination": {
    "city": "New York",
    "temperature": 25,
    "weather": "clear"
  },
  "updated": "2026-05-28 16:30:00"
}
```

---

## Admin-Funktionen

### Login

```bash
curl -X POST http://dein-domain.de/api/admin/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@example.com",
    "password": "admin123"
  }'
```

### Profil abrufen

```bash
curl -X GET http://dein-domain.de/api/admin/profile
```

### Profil aktualisieren

```bash
curl -X PUT http://dein-domain.de/api/admin/profile \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Neuer Name",
    "email": "neue@email.com"
  }'
```

### Passwort ändern

```bash
curl -X PUT http://dein-domain.de/api/admin/change-password \
  -H "Content-Type: application/json" \
  -d '{
    "password": "neues-passwort"
  }'
```

### Statistiken

```bash
curl -X GET http://dein-domain.de/api/admin/stats
```

### Version-Check

```bash
curl -X GET http://dein-domain.de/api/admin/check-update
```

### Update durchführen

```bash
curl -X POST http://dein-domain.de/api/admin/update \
  -H "Content-Type: application/json" \
  -d '{}'
```

---

## Issue-Reporting

### Issue erstellen

Auf dem Dashboard klicke auf **"🐛 Issue erstellen"** und beschreibe das Problem. Der Logfile wird automatisch angehängt.

### API Endpoint

```bash
curl -X POST http://dein-domain.de/api/admin/issues/submit \
  -H "Content-Type: application/json" \
  -d '{
    "description": "Beschreibe das Problem...",
    "admin_email": "admin@example.com",
    "admin_name": "Administrator"
  }'
```

### Issue Status

```bash
curl -X GET "http://dein-domain.de/api/admin/issues/status?issue_number=1"
```

---

## 📞 Support

**GitHub Issues:** https://github.com/chris1971nrw/runwayhub/issues  
**Dokumentation:** https://github.com/chris1971nrw/runwayhub/blob/main/README.md  

**Email:** support@runwayhub.de

---

## 📝 Lizenz

MIT License
