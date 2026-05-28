# Contributing zu RunwayHub

Willkommen! Wenn du zu diesem Projekt beitragen möchtest, lies bitte diesen Leitfaden.

## Code Style

### PHP Standards
- PSR-12 für Code Styling
- PHP 8.0+ erforderlich
- Strikte Typisierung (`declare(strict_types=1);`)
- Kommentare für komplexe Logik
- Maximal 120 Zeichen pro Zeile

### Dateien im Repository

```
runwayhub/
├── public/
│   ├── index.php              # Landing page mit Flight Board
│   ├── login.php              # Piloten-Login
│   ├── va-gruenden.php        # VA erstellen Form
│   ├── va-connect.php         # VA verbinden Form
│   ├── weather-widget.html    # Wetter-Widget
│   └── flight-board.html      # Flight Board HTML
├── database.sqlite             # SQLite Datenbank (lokal)
├── .env                        # Environment Variables
├── .env.example               # Beispiel Environment
├── .gitignore                 # Git Ignorlist
├── CHANGELOG.md               # Versions-Changelog
├── CONTRIBUTING.md           # Dieser Beitrag-File
├── README.md                  # Projekt-Dokumentation
└── TODO.md                    # TODO Liste
```

## Git Workflow

### Commit Messages
```
[type](scope): description

- [feat] Neue Funktion
- [fix] Bugfix
- [docs] Dokumentation
- [style] Formatierung
- [refactor] Code-Umstrukturierung
- [perf] Performance
- [test] Tests
- [chore] Andere Änderungen

Bsp:
feat(login): Redirect nach Airline-Dashboard

- Airline-Logik implementiert
- Tests angepasst
```

### Branches
- `main` - Produktions-Code
- `feature/*` - Neue Funktionen
- `bugfix/*` - Bugfixes
- `docs/*` - Dokumentation
- `test/*` - Test-Code

## Pull Requests

### Voraussetzungen
- Code läuft lokal auf `localhost:8080`
- Tests bestehen
- Keine OpenClaw-Dateien im Pull Request
- Git Status sauber

### Review Prozess
1. PR erstellen
2. Wartender Reviewer
3. Kommentare bearbeiten
4. Merge nach `main`

## Environment Variables

Siehe `.env.example` für verfügbare Variablen:

```env
# Datenbank
DB_PATH=runwayhub/database.sqlite

# Airline Konfiguration
AIRLINES_CONFIG=runwayhub/config/airlines.json

# ACARS (zukünftig)
ACARS_HOST=localhost
ACARS_PORT=1883
ACARS_TOPIC=runwayhub/+/+

# Wetter API (zukünftig)
WEATHER_API_URL=https://api.open-meteo.com/v1/airport
```

## Testing

Lokales Testen:

```bash
cd runwayhub
php -S localhost:8080 -t public
```

Test URLs:
- Landing: http://localhost:8080
- Login: http://localhost:8080/login.php
- VA Gründen: http://localhost:8080/va-gruenden.php
- VA Verbinden: http://localhost:8080/va-connect.php
- Weather Widget: http://localhost:8080/weather-widget.html

## Deployment

Für Produktion:

1. `.env` nicht committen
2. `.env.example` committen
3. `database.sqlite` nicht committen
4. Nur PHP-Code committen
5. Dependencies installieren

## Security

- Keine sensitive Daten im Code
- `.env` für Konfiguration
- Prepared Statements für SQL
- Input-Validierung
- CORS-Konfiguration

## Lizenz

Siehe `LICENSE` Datei.
