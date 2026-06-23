# Projektübersicht: RunwayHub

## 1. Einleitung
RunwayHub ist eine Management-Plattform für betriebliche Abläufe in der Luftfahrt. Sie bietet Werkzeuge zur Verwaltung von Flugzeugflotten, zur Planung von Flügen und zur Verfolgung von Wartungsarbeiten sowie Finanzübersichten für Luftfahrtunternehmen.

## 2. Kernmodule und Funktionen
- **Flottenmanagement:** Verfolgung des Status, der Typen (z. B. A320, B737) und der Kapazitäten von Flugzeugen.
|--- **Flugbetrieb:** Verwaltung von Routen, Zeitplänen und dem aktuellen Status der Flüge in Echtzeit (inklusive automatischer Rückflug-Generierung mit dedizierten Flugnummern im Format RWXXXX und ICAO-basiertem Grouping).\n
- **ACARD-System:** Integration einer dedizierten Datenverarbeitung über spezialisierte Parser (`lib/parsers`).
- **Wartung & Finanzen:** Module zur Verfolgung von Ausfallzeiten für Flugzeuge sowie zu damit verbundenen Kosten.
- **Simulation:** Enthält einen ACARS-Simulator zum Testen von Kommunikation.

## 3. Technische Architektur
- **Backend:** PHP (modulare Struktur in `lib/`).
- **Datenbank:** SQLite (verwaltet über `db_config.php` und `schema_setup.sql`).
- **Frontend:** Weboberfläche mit administrativen Dashboards und Ansichten für Piloten.
- **Sicherheit:** Rollenbasierte Zugriffskontrolle (RBAC) für die Rollen 'admin', 'pilot' und 'ground_staff'.

## 4. Verzeichnisstruktur
- `/lib/core`: Kernlogik und Brückenfunktionen des Systems.
- `/lib/modules`: Spezifische Teilsysteme (Finanzen, Wartung, ACARD).
- `/lib/parsers`: Logik zum Parsen externer Datenaustauschformate der Luftfahrt.
- `/lib/simulators`: Werkzeuge zur Simulation von Hardware-Interaktionen (ACARS).
- `db/`: Datenbank-Schemata und Definitionen für die SQL-Tabellen.

## 5. Entwicklungsumgebung
- **Sprache:** PHP
- **Datenbanktechnologie:** SQLite
- **Testen:** Skripte in den `test_`-Dateien sowie benutzerdefinierte Protokolle zur Fehlersuche.
