# RunwayHub Airline Hosting

## Übersicht

Auf **runwayhub.de** können Virtual Airlines ihren Betrieb hosted!

Wir bieten zwei Hosting-Typen an:

### 1. Open Source Hosting (Free Tier)
- **Preise:** Kostenlos
- **System:** Eigene Open Source Version
- **Features:** Alle Features von RunwayHub Open Source
- **Ressourcen:** Shared Resources
- **Support:** Community Support
- **Anzahl Airlines:** 1 Airline

### 2. Managed Hosting (Premium)
- **Preise:** Ab $99/Monat
- **System:** RunwayHub Enterprise
- **Features:** Alle Features + Customization
- **Ressourcen:** Isolierte Instance
- **Support:** Prioritäts-Support
- **Anzahl Airlines:** Bis zu 5 Airlines

### 3. Premium Hosting (Enterprise)
- **Preise:** Ab $299/Monat
- **System:** RunwayHub Enterprise + Customization
- **Features:** Alle Features + Custom Branding
- **Ressourcen:** Dedizierte Instance
- **Support:** 24/7 Support
- **Anzahl Airlines:** Bis zu 20 Airlines

---

## Pricing Tiers

### 🆓 Free Tier (Open Source)

```
Preis: $0/Monat

Inklusive:
✅ RunwayHub Open Source
✅ Alle Features
✅ Community Support
✅ Shared Hosting
✅ 1 Airline
✅ 100 MB Speicher
✅ 1 GB RAM
✅ 500 MB SSD

Limitierungen:
- 1 Airline
- 100 Flüge/Monat
- 100 Passagiere/Flug
- Community Support
```

### 💼 Business Tier (Managed)

```
Preis: $99/Monat

Inklusive:
✅ RunwayHub Enterprise
✅ Alle Features + Customization
✅ Prioritäts-Support
✅ Isolierte Instance
✅ Bis zu 5 Airlines
✅ 1 GB Speicher
✅ 2 GB RAM
✅ 1 GB SSD

Features:
- Automatisierte Backups
- SSL-Zertifikat (Let's Encrypt)
- Monitoring (Uptime)
- Daily Reports
```

### 🏢 Enterprise Tier

```
Preis: $299/Monat

Inklusive:
✅ RunwayHub Enterprise + Custom
✅ Alle Features + Custom Branding
✅ 24/7 Support
✅ Dedizierte Instance
✅ Bis zu 20 Airlines
✅ 5 GB Speicher
✅ 8 GB RAM
✅ 50 GB SSD

Features:
- White-Label Support
- Custom Reports
- Advanced Analytics
- API Access
- SLA: 99.9% Uptime
```

---

## Hosting-Konfiguration

### Shared Hosting (Free Tier)

```php
// config/app.php
'app' => [
    'name' => env('APP_NAME', 'RunwayHub'),
    'env' => env('APP_ENV', 'production'),
    'debug' => env('APP_DEBUG', false),
    'url' => env('APP_URL', 'https://runwayhub.de'),
    'timezone' => 'Europe/Berlin',
    'locale' => 'de_DE',
    
    // Multi-Tenant
    'airline_id' => env('AIRLINE_ID', 'DEMO'),
    'tenant_id' => env('TENANT_ID', 'shared'),
]
```

### Isolierte Instance (Business Tier)

```php
// Docker-Compose für Business Tier
version: '3.8'

services:
  app:
    build: ./docker/app
    volumes:
      - ./storage:/var/www/storage
      - ./config:/var/www/config
    environment:
      - APP_NAME=RunwayHub
      - DB_HOST=db
      - DB_DATABASE=${AIRLINE_ID}
      - DB_USERNAME=${AIRLINE_ID}
    depends_on:
      - db

  db:
    image: mysql:8.0
    volumes:
      - ./db-data:/var/lib/mysql
    environment:
      - MYSQL_ROOT_PASSWORD=${DB_ROOT_PASSWORD}
      - MYSQL_DATABASE=${AIRLINE_ID}

  nginx:
    image: nginx:alpine
    volumes:
      - ./nginx.conf:/etc/nginx/nginx.conf

  php-fpm:
    build: ./docker/php
    environment:
      - PHP_MEMORY_LIMIT=256M
```

---

## Terms of Service

### Acceptable Use Policy

1. **Keine Missbrauch:**
   - Keine Spam-Mails
   - Keine Phishing-Versuche
   - Keine Malware-Hosting

2. **Rechtliche Compliance:**
   - DSGVO-konform
   - Impressum erforderlich
   - AGB akzeptieren

3. **Datenschutz:**
   - Keine personenbezogenen Daten ohne Einwilligung
   - SSL-Zertifikat erforderlich
   - Logs behalten für 90 Tage

4. **Zahlung:**
   - Vorkasse oder Kreditkarte
   - Abrechnung monatlich
   - Stornierung: 30 Tage Vorlauf

---

## Setup Guide für Airline

### Schritt 1: Anmeldung

```bash
# Landing Page
https://runwayhub.de

# Airline erstellen
POST /api/airlines
{
  "name": "DemoAirline",
  "iata": "DA",
  "icao": "DEMOAIR",
  "country": "DE",
  "tier": "free",  // free | business | enterprise
}

# Antwort
{
  "airline_id": 123,
  "api_key": "runwayhub_api_key_here",
  "instance_url": "https://runwayhub.de/airline/123",
  "setup_url": "https://runwayhub.de/setup/123"
}
```

### Schritt 2: Migration

```bash
# Datenbank-Migrationen
php artisan migrate

# Seed-Daten
php artisan db:seed --class=DemoAirline

# Config-Update
php artisan config:clear
php artisan route:clear
```

### Schritt 3: Test

```bash
# API-Test
curl https://runwayhub.de/api/health

# Dashboard
https://runwayhub.de/dashboard/airline/123

# API-Keys
POST /api/airlines/123/api-keys
{
  "name": "Flight Search API",
  "scope": "flights",
}
```

---

## Support & SLA

### Free Tier

- **Support-E-Mail:** support@runwayhub.de
- **Response Time:** 3-5 Werktage
- **Uptime:** 99% (kein SLA)

### Business Tier

- **Support-Kanal:** Ticket-System
- **Response Time:** 24 Stunden
- **Uptime:** 99.5% (SLA)

### Enterprise Tier

- **Support:** 24/7 Chat + Telefon
- **Response Time:** 2 Stunden
- **Uptime:** 99.9% (SLA)
- **Dedicated Manager:** Yes

---

## Migration

### Von anderer Platform

```bash
# 1. Export alte Daten
mysqldump -u user -p old_database > backup.sql

# 2. Import auf RunwayHub
php artisan migrate --force
php artisan db:seed --database=backup.sql

# 3. Config anpassen
AIRLINE_ID=123
APP_URL=https://runwayhub.de/airline/123

# 4. Backup erstellen
php artisan db:backup
```

### Datenmigration

```php
// src/Console/Commands/MigrateCommand.php
public function handle(): void
{
    $oldDatabase = env('OLD_DATABASE');
    $newDatabase = env('AIRLINE_ID');
    
    // Daten migrieren
    // Backups erstellen
    // Cleanup alter Daten
}
```

---

## Kostenkalkulation

### Beispielrechnung

```
Free Tier:
- Hosting: $0/Monat
- Database: $0/Monat
- Storage: $0/Monat
- Total: $0/Monat

Business Tier:
- Hosting: $99/Monat
- Database: $15/Monat
- Storage: $10/Monat
- Support: $15/Monat
- Total: $139/Monat (abgerechnet: $99/Monat)
```

### Kostenstruktur

```
RunwayHub Kosten pro Airline:

Hosting (VPS): $20/Monat
Database (AWS RDS): $10/Monat
Storage (AWS S3): $5/Monat
API Calls (OpenAIP): $10/Monat
Support: $10/Monat
Admin Overhead: $15/Monat
Margin: $45/Monat

Total: $70/Monat
Abrechnung: $99/Monat (Business)
Profit: $29/Monat
```

---

## Features für Airlines

### Included Features

- **Multi-Airline Support:** Bis zu 20 Airlines/Instance
- **Flight Planning:** Automatisierte Planung
- **PIREP System:** Pilot Reports
- **Booking System:** Vollständig
- **Statistiken:** Live-Dashboards
- **Leaderboards:** Ranking
- **OpenAIP Integration:** Echtzeit-Daten

### Optional Add-ons

- **Premium Support:** +$50/Monat
- **Custom Domain:** +$15/Jahr
- **White-Label:** +$100/Monat
- **Additional Storage:** +$10/GB
- **Additional Bandwidth:** +$10/GB

---

## FAQ

**Frage:** Kann ich eigene Domain nutzen?
**Antwort:** Ja, Business und Enterprise Tier unterstützen Custom Domains.

**Frage:** Wie viele Passagiere können wir buchen?
**Antwort:** Free: 100/Tag, Business: 500/Tag, Enterprise: unbegrenzt.

**Frage:** Können wir eigene Flugzeuge hinzufügen?
**Antwort:** Ja, alle Tiers erlauben Custom Flotten.

**Frage:** Wie ist der Support?
**Antwort:** Free: E-Mail, Business: Ticket, Enterprise: 24/7.

**Frage:** Was passiert bei Nichtzahlung?
**Antwort:** 30 Tage Vorlauf, dann 30 Tage Grace Period.

---

## Nächste Schritte

1. **Pricing finalisieren** - Exakte Preise ermitteln
2. **Billing System** - Zahlungsabwicklung implementieren
3. **Terms of Service** - Juristische Prüfung
4. **Multi-Tenant Config** - Isolation implementieren
5. **Billing Dashboard** - Admin-Panel für Airlines erstellen

---

**RunwayHub** - Hosten Sie Ihre Virtual Airline! 🛫
