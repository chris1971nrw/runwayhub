# Demo System - Zusammenfassung

## Überblick

Dieses Dokument fasst das Demo-System für RunwayHub zusammen.

## Installede Komponenten

### 1. Demo Airline

| Feld | Wert |
|---|---|
| Name | DemoFly |
| Code | DMO |
| IATA | DM |
| ICAO | DMFLY |
| Rufzeichen | DEMOFLY |
| Land | Deutschland |
| Logo | null |
| Farbe | #0066cc |
| Status | Aktiv |

### 2. Demo Benutzer

| Benutzername | Email | Rolle | Passwort |
|--|-|--|---|--|
| demo_admin | admin@demofly.runwayhub.de | admin | demo123 |
| demo_staff | staff@demofly.runwayhub.de | staff | demo123 |
| demo_pilot | pilot@demofly.runwayhub.de | pilot | demo123 |
| demo_guest | guest@demofly.runwayhub.de | guest | demo123 |

### 3. Demo Flotte

| Kennzeichen | Typ | Modell | Sitzplätze | Status |
|----------|-----|--------|----------|--------|
| DM-FAZ | Boeing | 737-800 | 160 | aktiv |
| DM-FBZ | Airbus | A320-200 | 150 | aktiv |
| DM-C172 | Cessna | 172 | 4 | aktiv |

### 4. Demo Flüge

| Flugnummer | Route | Abflug | Ziel | Distanz (km) | Dauer (min) | Status | Preis Economy |
|----------|-------|--------|------|-------------|------------|--------|-----------|
| DMF001 | München-Frankfurt | MUC | FRA | 530 | 75 | scheduled | 299€ |

### 5. Demo PIREP

| Flug | Höhe | Geschwindigkeit | Wetter | Kommentare |
|------|------|----------------|--------|------------|
| DMF001 | 35000ft | 450kt | Gewitter | Glatter Flug, schöne Sicht |

### 6. Demo Buchungen

| Booking Nr. | Passagier | Klasse | Preis | Status |
|------------|-----------|--------|-------|--------|
| DM001 | Max Mustermann | Economy | 299€ | confirmed |
| DM002 | Erika Musterfrau | Economy | 299€ | confirmed |
| DM003 | Hans Beispielmann | Economy | 299€ | confirmed |

## Datei-Struktur

```
runwayhub/
├── database/
│   └── migrations/
│       ├── 2026_05_26_000001_create_demo_airline.php
│       ├── 2026_05_26_000002_create_demo_users.php
│       ├── 2026_05_26_000003_create_demo_aircraft.php
│       ├── 2026_05_26_000004_create_demo_flights.php
│       ├── 2026_05_26_000005_create_demo_pireps.php
│       └── 2026_05_26_000006_create_demo_bookings.php
├── database/
│   └── seeds/
│       ├── DemoAirline.php
│       ├── DemoUsers.php
│       ├── DemoFleet.php
│       ├── DemoFlights.php
│       ├── DemoPIREPs.php
│       ├── DemoBookings.php
│       └── DatabaseSeeder.php
├── src/
│   ├── api/
│   │   └── Controller/
│   │       └── DemoController.php
│   ├── Command/
│   │   └── DemoInstallCommand.php
│   ├── Console/
│   │   └── Command/
│   │       └── DemoInstallCommand.php
│   ├── Entity/
│   │   ├── DemoAirline.php
│   │   ├── DemoUser.php
│   │   ├── DemoAircraft.php
│   │   ├── DemoFlight.php
│   │   ├── DemoPIREP.php
│   │   └── DemoBooking.php
│   └── Repository/
│       ├── DemoAirlineRepository.php
│       ├── DemoUserRepository.php
│       ├── DemoAircraftRepository.php
│       ├── DemoFlightRepository.php
│       ├── DemoPIREPRepository.php
│       ├── DemoBookingRepository.php
│       └── DemoRepository.php
├── tests/
│   ├── DemoAirlineTest.php
│   ├── DemoUserTest.php
│   ├── DemoAircraftTest.php
│   ├── DemoFlightTest.php
│   ├── DemoPIREPTest.php
│   └── DemoBookingTest.php
└── DEMO_README.md
```

## API Endpoints

| Endpoint | Methode | Beschreibung |
|----------|---------|--------------|
| `/api/demo/airline` | GET | Demo Airline Info |
| `/api/demo/users` | GET | Demo User List |
| `/api/demo/aircraft` | GET | Demo Flotte |
| `/api/demo/flights` | GET | Demo Flüge |
| `/api/demo/pireps` | GET | Demo PIREPs |
| `/api/demo/bookings` | GET | Demo Buchungen |
| `/api/demo/reset` | POST | Reset alle Demo-Daten |

## Installation

### Schritt 1: Migrationen ausführen

```bash
php bin/console doctrine:schema:create
```

### Schritt 2: Demo-Daten installieren

```bash
php bin/console demo:install
```

### Schritt 3: Test ausführen

```bash
php bin/phpunit tests/
```

## Verwendung

### Entwicklung

```bash
# Demo-Daten installieren
php bin/console demo:install

# API Endpoints testen
curl https://localhost:8080/api/demo/airline
curl https://localhost:8080/api/demo/users
curl https://localhost:8080/api/demo/aircraft
```

### Präsentation

```bash
# Demo-Daten im leisen Modus installieren
php bin/console demo:install --quiet

# API für Präsentation
curl -s https://localhost:8080/api/demo/airline | jq .
```

### Reset

```bash
# Demo-Daten zurücksetzen
php bin/console demo:install --reset

# Oder direkt
curl -X POST https://localhost:8080/api/demo/reset
```

## Sicherheit

- **Passwörter**: bcrypt gehasht
- **Demo-Daten**: Nur für Entwicklung
- **Production**: Eigene Daten verwenden
- **DSGVO**: Keine persönlichen Daten in Demo

## Lizenz

MIT License

---

**Generiert am**: 2026-05-26  
**Version**: 1.0.0  
**Status**: Beta
