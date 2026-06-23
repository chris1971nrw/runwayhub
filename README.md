# RunwayHub - Virtual Airline Platform

## Projektbeschreibung
RunwayHub ist eine Webanwendung für die Verwaltung einer virtuellen Fluggesellschaft (Virtual Airline). Sie dient als Plattform für Flugsimulatoren wie MSFS und X-Plane, um Flotten zu verwalten, Rollen zuzuweisen und Mitglieder zu organisieren.

## Kernfunktionen
- **Flottenmanagement**: Verwaltung von Flugzeugen und deren Status.
- **Rollen & Berechtigungen**: Differenzierte Zugriffe für Admins und Piloten.
- **Service-Module**: Integration spezialisierter Tools (z.B. ACARD Parser, PiRep Service).
- **Sicherheitsfokus**: Robustes Login-System und Session-Management.

## Technologie-Stack
- **Backend:** Native PHP (OOP Design)
- **Datenbank:** SQLite (via PDO)
- **Frontend:** HTML5, CSS3 & Vanilla JavaScript
- **Architektur:** „Plug-and-Play“ – Keine Abhängigkeiten von Composer oder Frameworks.

## Dateistruktur Highlights
- `/lib/`: Kernmodule und Services
- `db/`: Datenbank-Schema und Definitionen
- `docs/`: Handbücher für Piloten und Administratoren
- `ROADMAP.md`: Übersicht der zukünftigen Entwicklungen

---
*Dieses Repository ist so konfiguriert, dass es sofort auf jedem Webserver mit PHP & SQLite einsatzbereit ist.*
