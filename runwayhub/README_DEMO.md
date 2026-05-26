# Demo System - RunwayHub

Dieses Demo-System dient zur Demonstration und Entwicklung der RunwayHub Software.

## 🚀 Schnelleinstieg

### 1. Projekt initialisieren

```bash
git clone https://github.com/chris1971nrw/runwayhub.git
cd runwayhub
composer install
```

### 2. Datenbank vorbereiten

```bash
# Datenbank erstellen
mysql -u root -p
CREATE DATABASE runwayhub CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

### 3. Demo-Daten installieren

```bash
php bin/console demo:install
```

### 4. API Endpoints testen

```bash
curl https://yoursite.com/api/demo/airline
curl https://yoursite.com/api/demo/users
curl https://yoursite.com/api/demo/aircraft
curl https://yoursite.com/api/demo/flights
```

## 📋 Demodaten

### Demo Airline

| Feld | Wert |
|---|---|
| Name | DemoFly |
| Code | DMO |
| IATA | DM |
| ICAO | DMFLY |
| Land | Deutschland |
| Farbe | #0066cc |

### Demo Benutzer

| Benutzername | Email | Rolle | Passwort |
|-------------|-------|-------|----------|
| demo_admin | admin@demofly.runwayhub.de | admin | demo123 |
| demo_staff | staff@demofly.runwayhub.de | staff | demo123 |
| demo_pilot | pilot@demofly.runwayhub.de | pilot | demo123 |
| demo_guest | guest@demofly.runwayhub.de | guest | demo123 |

### Demo Flotte

| Kennzeichen | Typ | Modell | Sitzplätze |
|----------|-----|--------|-----------|
| DM-FAZ | Boeing | 737-800 | 160 |
| DM-FBZ | Airbus | A320-200 | 150 |
| DM-C172 | Cessna | 172 | 4 |

### Demo Flug

- Flugnummer: DMF001
- Route: München (MUC) -> Frankfurt (FRA)
- Distanz: 530 km
- Dauer: 75 Minuten
- Preis Economy: 299€

### Demo PIREP

- Flug: DMF001
- Höhe: 35000ft
- Geschwindigkeit: 450kt
- Wetter: Gewitter
- Kommentare: "Glatter Flug, schöne Sicht"

### Demo Buchungen

| Booking Nr. | Passagier | Klasse | Preis |
|------------|-----------|--------|-------|
| DM001 | Max Mustermann | Economy | 299€ |
| DM002 | Erika Musterfrau | Economy | 299€ |
| DM003 | Hans Beispielmann | Economy | 299€ |

## 🔧 Installation

### Schritt 1: Migrationen ausführen

```bash
php bin/console doctrine:schema:create
```

### Schritt 2: Demo-Daten installieren

```bash
php bin/console demo:install
```

Oder mit Reset:

```bash
php bin/console demo:install --reset
```

Im leisen Modus:

```bash
php bin/console demo:install --quiet
```

## 📡 API Endpoints

| Endpoint | Methode | Beschreibung |
|----------|---------|--------------|
| `/api/demo/airline` | GET | Demo Airline Info |
| `/api/demo/users` | GET | Demo User List |
| `/api/demo/aircraft` | GET | Demo Flotte |
| `/api/demo/flights` | GET | Demo Flüge |
| `/api/demo/pireps` | GET | Demo PIREPs |
| `/api/demo/bookings` | GET | Demo Buchungen |
| `/api/demo/reset` | POST | Reset Demo-Daten |

### Beispiele

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

## 🧪 Testing

Tests ausführen:

```bash
php bin/phpunit tests/
```

## 📖 Dokumentation

- [DEMO_README.md](DEMO_README.md) - Detailles README
- [DEMO_SUMMARY.md](DEMO_SUMMARY.md) - Zusammenfassung
- [docs/architecture_demo.md](docs/architecture_demo.md) - Architektur
- [docs/cli_demo.md](docs/cli_demo.md) - CLI Tools
- [src/api/README.md](src/api/README.md) - API Endpoints

## 🔒 Sicherheit

- Demo-Daten sind nur für Entwicklung gedacht
- Passwörter sind bcrypt gehasht
- In Production eigene Daten verwenden
- DSGVO beachten

## 🧹 Bereinigung

Demo-Daten löschen:

```bash
php bin/console demo:install --reset
```

## 📝 Version

**Version**: 1.0.0  
**Status**: Beta  
**Erstellt**: 2026-05-26

---

## 🎯 Nächste Schritte

1. Demo-Daten in Development verwenden
2. Features entwickeln und testen
3. Demo-Daten durch echte Daten ersetzen
4. Für Produktion verwenden

## 🤝 Support

Bei Fragen:
- GitHub Issues: https://github.com/chris1971nrw/runwayhub/issues
- Dokumentation: https://runwayhub.readthedocs.io

---
