# Changelog

Alle bedeutenden Änderungen an runwayhub werden in dieser Datei dokumentiert.

## [1.0.0-beta] - 2026-10-11

### 🎉 Beta Release

Erstes Beta-Release von runwayhub mit allen MVP Features.

#### Hinzugefügt:

- ✅ **Reines PHP** — Kein Laravel, kein Composer
- ✅ **Ein-Klick-Installation** — `install.php` (Browser-basiert)
- ✅ **MySQL Auto-Setup** — Datenbank + Tabellen automatisch
- ✅ **Seed Data** — Admin + Sample Piloten + Aircrafts
- ✅ **PHP Session Auth** — Keine JWT/OAuth2 nötig
- ✅ **GitHub CI/CD** — Test Badges & Security Scans

#### Dateien:

- `install.php` — Ein-Klick-Installation (Browser-basiert)
- `index.php` — Login Form mit Auto-DB Setup
- `dashboard.php` — Dashboard mit allen Features
- `database_schema.sql` — MySQL Schema für direkten Import
- `docs/` — Technische Dokumentation

#### Sicherheits-Features:

- Session-based Authentication (kein JWT/OAuth2)
- Role-based Authorization: pilot, crew, ground_control, admin
- Password Hashing mit Bcrypt
- HTTP Only Cookies
- CSRF Protection in allen Forms

---

## [0.1.0] - 2026-10-10

### MVP Phase 2 Setup

Laravel Skeleton + Migrations + UI Templates + Tests + CI/CD.

#### Hinzugefügt:

- Laravel 10.x Skeleton
- MySQL/MariaDB Datenbank mit 4 Tabellen
- Eloquent Models für Core Entities
- PHPUnit Tests mit Coverage Pipeline
- GitHub Actions Workflows

---

## [Unreleased]

### Nächste Features:

- Screenshot Upload & Gallery
- Discussion Threads pro Route
- Leaderboard mit Performance Stats
- OAuth2 Integration (Google/GitHub Login)
- WebSocket Notifications
