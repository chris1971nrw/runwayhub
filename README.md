# 🚀 RunwayHub

**Flugzeugverfolgungs- und Buchungsmanagement-System**

Einhaltige Webanwendung zur Verwaltung von Flugzeugen, Flügen, Buchungen und Passagierinformationen.

---

## 📁 Projektstruktur

```
projekt/
├── .github/          # CI/CD Workflows für GitHub Actions
├── .gitignore        # Git Ignorlisten
├── README.md         # 📖 Dieses Dokument
├── LICENSE           # Projekt-Lizenzierung
├── CONTRIBUTING.md   # Anleitung für Contributors
├── DOKUMENTATION.md  # 📚 Detaillierte Dokumentationen
├── Dockerfile        # Docker-Konfiguration
├── composer.json     # PHP Dependency Management
└── code/             # 🎯 KERNANWENDUNG (ALLE Dateien)
    ├── api/          # API-Controller
    ├── assets/       # Statika (CSS, JS)
    ├── config/       # Konfigurationsdateien
    ├── database/     # Datenbank-Skripte und SQLite
    ├── docs/         # Dokumentationen
    ├── i18n/        # Internationalisierung
    ├── public/       # Öffentliche Assets
    ├── src/          # Core-Code
    ├── tests/        # Test-Suite
    ├── uploads/      # Upload-Ordner
    ├── releases/     # Release-Archiven
    ├── migrations/   # Datenbank-Migrationen
    └── logs/         # Log-Dateien
```

---

## 🎯 Installation

1. **Repository klonen:**

```bash
git clone https://github.com/chris1971nrw/runwayhub.git
cd runwayhub
```

2. **Umgebung einrichten:**

- Kopiere `.env.example` in `.env`
- Konfiguriere die Datenbankverbindungen
- Führe die Datenbank-Migrationen aus

3. **Anwendung starten:**

```bash
php -S localhost:8080 -t public/
# Oder mit Docker:
docker-compose up -d
```

---

## 📚 Dokumentation

| Dokument | Pfad |
|----------|------|
| **Haupt-README** | `code/README.md` |
| **Installationsanleitung** | `code/INSTALLATION.md` |
| **Deployment** | `code/DEPLOYMENT.md` |
| **Technische Dokumentation** | `code/DOKUMENTATION.md` |
| **Fehlerbehebung** | `code/DOKUMENTATION.md#fehlerbehebung` |

---

## 🛠️ Entwicklung

### PHP-Requirements

- PHP >= 8.0
- Composer >= 2.0
- SQLite >= 3.39 oder MySQL/MariaDB

### Abhängigkeiten installieren

```bash
composer install
```

### Tests ausführen

```bash
php vendor/bin/phpunit
```

---

## 📖 Features

- ✈️ Flugzeugverwaltung
- 🛫 Flugplanung und -überwachung
- 📋 Buchungsmanagement
- 👨‍✈️ Piloten-Verwaltung
- 🗺️ Airport- und Route-Daten
- 🌤️ Wetter-Integration
- 📊 Statistiken und Reporting
- 🔐 Authentifizierung und Rollenmanagement

---

## 🤝 Contributing

Bitte Lies zuerst [`CONTRIBUTING.md`](CONTRIBUTING.md) für Informationen darüber, wie du beitragen kannst.

---

## 📄 Lizenz

Dieses Projekt steht unter der [MIT Lizenz](LICENSE).

---

## 🔗 Links

- [GitHub Repository](https://github.com/chris1971nrw/runwayhub)
- [Dokumentation](code/DOKUMENTATION.md)
- [API-Dokumentation](code/api/endpoints.md)

---

## 📮 Support

Für Fragen oder Issues bitte einen Issue im GitHub Repository erstellen.
