# Demo System für RunwayHub

Dieses Demo-System dient zur Demonstration und Entwicklung der RunwayHub Software.

## Installation

### Schritt 1: Migrationen ausführen

```bash
php bin/console doctrine:schema:create
php bin/console doctrine:migrations:create demo_install
php bin/console doctrine:migrations:migrate
```

### Schritt 2: Demo-Daten installieren

```bash
php bin/console demo:install
```

Oder alternativ mit den Seed Files:

```bash
php bin/console doctrine:fixture:load DatabaseFixtures
```

## Demo Airline

**Name**: DemoFly  
**Code**: DMO  
**IATA**: DM  
**ICAO**: DMFLY  
**Land**: Deutschland  
**Farbe**: Blau (#0066cc)  
**Status**: Aktiv

## Demo Benutzer

| Benutzername | Email | Rolle | Passwort |
|-------------|-------|-------|----------|
| demo_admin | admin@demofly.runwayhub.de | admin | demo123 |
| demo_staff | staff@demofly.runwayhub.de | staff | demo123 |
| demo_pilot | pilot@demofly.runwayhub.de | pilot | demo123 |
| demo_guest | guest@demofly.runwayhub.de | guest | demo123 |

## Demo Flotte

| Kennzeichen | Typ | Modell | Hersteller | Sitzplätze | Status |
|------------|-----|--------|------------|-----------|--------|
| DM-FAZ | Boeing | 737-800 | Boeing | 160 | aktiv |
| DM-FBZ | Airbus | A320-200 | Airbus | 150 | aktiv |
| DM-C172 | Cessna | 172 | Cessna | 4 | aktiv |

## Demo Flüge

| Flugnummer | Route | Abflug | Ziel | Distanz (km) | Dauer (min) | Status | Preis (Economy) |
|-----------|-------|--------|------|-------------|------------|--------|----------------|
| DMF001 | München-Frankfurt | MUC | FRA | 530 | 75 | scheduled | 299€ |

## Demo PIREP

| Flug | Höhe | Geschwindigkeit | Wetter | Kommentare |
|------|------|----------------|--------|------------|
| DMF001 | 35000ft | 450kt | Gewitter | Glatter Flug, schöne Sicht |

## Demo Buchungen

| Booking Nr. | Passagier | Klasse | Preis | Status |
|------------|-----------|--------|-------|--------|
| DM001 | Max Mustermann | Economy | 299€ | confirmed |
| DM002 | Erika Musterfrau | Economy | 299€ | confirmed |
| DM003 | Hans Beispielmann | Economy | 299€ | confirmed |

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

## Verwendung

Das Demo-System kann für folgende Zwecke verwendet werden:

1. **Entwicklung**: Testen neuer Features mit Demo-Daten
2. **Präsentation**: Demonstration der Software-Funktionalität
3. **Dokumentation**: Erstellung von Use Cases
4. **Training**: Schulung von Nutzern

## Bereinigung

Um alle Demo-Daten zu löschen, führen Sie aus:

```bash
php bin/console demo:install --reset
```

Oder manuell:

```bash
php bin/console doctrine:database:drop --if-empty
php bin/console doctrine:schema:create
php bin/console demo:install
```

## Hinweise

- Alle Demo-Daten sind mit `password_hash` für das Passwort 'demo123' gehasht
- Demo-Daten sollten nur für Entwicklung und Demo-Zwecke verwendet werden
- Für Produktionsumgebungen sollten eigene Daten verwendet werden
- Beachten Sie die DSGVO bei der Verwendung persönlicher Daten

## Lizenz

Dieses Demo-System ist unter der MIT-Lizenz veröffentlicht.

---
