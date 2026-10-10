# runwayhub - Subagent Workstreams für Codierung (GitHub Preparation)

## 🎯 Zielsetzung

**Parallele Entwicklung mit Subagenten:**
- Laravel Projekt-Skeleton + Migrations  
- Eloquent Models & Relationships  
- Flight Booking Controller  
- ACARS Queue Worker Implementation  
- Pilot Dashboard UI mit Alpine.js  
- Admin Finance Dashboard  
- Git Init & CI/CD Setup  

---

## 📦 Workstream Verteilungsplan

### Subagent 1: Laravel Skeleton
**Task:** Laravel Installation + Migrations Skeletons  
**Output:** 
- `composer.json` mit Laravel 10 dependencies  
- Migrations für pilots, flights, logs, aircrafts Tabellen  
- `.env.example` mit DB placeholders (`[REDACTED]`)  

### Subagent 2: Eloquent Models
**Task:** Core Entities & Relationships  
**Output:**
- `app/Models/Pilot.php` (ICAO Callsign validation)  
- `app/Models/FlightSchedule.php`  
- `app/Models/FlightLog.php`  
- `app/Models/Aircraft.php` mit fleet-management logic  

### Subagent 3: Flight Booking System  
**Task:** 24h Time-Lock Controller  
**Output:**
- `app/Http/Controllers/Api/FlightBookingController.php`  
- Validation: `booked_for_max_24h => true`  
- Routes registration in `routes/web.php`  

### Subagent 4: ACARS Queue Worker  
**Task:** MSFS/X-Plane Logs → DB Pipeline  
**Output:**
- `app/Jobs/ProcessFlightLogJob.php`  
- Laravel Scheduler Cron Job (`schedule->everyMinute()` check `/storage/app/flights/`)  
- SimConnect SDK integration blueprint  

### Subagent 5: Pilot Dashboard UI  
**Task:** Blade Templates + Alpine.js Components  
**Output:**
- `resources/views/pilots/dashboard.blade.php`  
- `resources/views/pilots/flights/index.blade.php`  
- Weather Widget Component Integration  

### Subagent 6: Admin Finance Dashboard  
**Task:** Revenue/Cost Charts (Chart.js)  
**Output:**
- `resources/views/admin/dashboard.blade.php`  
- Break-even Analysis Chart per flight  
- Cargo Revenue Tracking  

### Subagent 7: Git Setup & CI/CD  
**Task:** Repository Init + Security Policy  
**Output:**
- `.gitignore` for Laravel (vendor, storage/.env)  
- GitHub Workflow YML mit Admin-only Update Enforcement  

---

## ✅ GitHub Readiness Checklist

**Rechte vor Codierung:**

- [✓] GitHub Organization/Repo init planiert  
- [ ✓] Laravel 10 als monolithischer App Stack gewählt  
- [✓] Session-based Auth (kein JWT, kein Custom Claims)  
- [✓] Admin-only Update Policy dokumentiert für CI/CD  

**Sofort nach Codierung:**
```bash
git init 
composer create-project laravel/laravel runwayhub --prefer-dist

# Repository Struktur:
/opt/data/runwayhub/

.gitignore (vendor, storage/.env)
.env.example (DB placeholders redacted)
.github/workflows/deploy.yml (Admin Update Workflow)
README.md (GitHub PR Policy)
```

---

## 🛠️ Laravel Project Structure Blueprint

```bash
/opt/data/runwayhub/
├── app/
│   ├── Console/
│   │   └── Kernel.php               # Cron Job Schedule
│   ├── Events/                      # ACARS Event System
│   ├── Exceptions/                  # Error Handler für 403/500→GitHub Issues
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/
│   │   │   │   └── FlightBookingController.php
│   │   │   └── UserReportController.php  # GitHub Issues Integration
│   │   └── Kernel.php
│   ├── Jobs/
│   │   └── ProcessFlightLogJob.php  # ACARS Queue Worker
│   ├── Listeners/                   # ACARS Event → DB Store
│   ├── Mail/                        # IssueConfirmation Email Template
│   ├── Models/                      # Eloquent ORM: Pilot, FlightSchedule, Log, Aircraft
│   │   └── * Validation für ICAO Callsign constraints
│   └── Providers/
├── bootstrap/
├── config/                          # session.php TTL 14 days, cron.php version check policy
├── database/
│   ├── Factories/
│   └── Migrations/                 # pilots, schedules, logs, aircrafts  
├── resources/
│   ├── css/                        # Tailwind CSP + WCAG Compliance
│   ├── js/                         # Alpine.js Components
│   └── views/
│       ├── layouts/               # Base Blade Template mit progress bars
│       ├── pilots/                # Dashboard, Flights, Weather Widget
│       └── admin/                 # Finance Dashboard mit Chart.js
├── routes/
│   ├── api.php                    # REST API (Phase 2 OAuth pending)
│   └── web.php                  # Flight Booking Routes + Admin Endpoints
├── tests/                          # PHPUnit Tests für MVP (Booking Time-Lock)
├── .env.example                   # [REDACTED] DB placeholders  
├── .gitignore                     # vendor, storage/.env excluded  
├── composer.json                  # Laravel dependencies list
└── README.md                      # GitHub PR Policy + Contribution Guide

```

---

## 🚀 Sofort nach Git Init: CI/CD Setup

**GitHub Workflow (`.github/workflows/deploy.yml`):**

```yaml
name: Admin Update Check (Only Admin Can Deploy)

on:
  schedule:
    - cron: "*/5 * * * *"          # Cronjob check every 5 minutes
  
jobs:
  update-check:
    runs-on: ubuntu-latest
    
    steps:
      - uses: actions/checkout@v4
      
      - name: Check for New Version Tag
        run: |
          VERSION=$(git describe --tags $(git rev-list --tags --max-count=1))
          
      - name: Notify Admin Dashboard (GitHub Pages)
        if: github.event_name == 'schedule'
        run: |           # POST to webhook endpoint? Or GitHub Pages notification:
          echo "New version available! Admin only can apply update." > notifications.log
  
  deploy-update:
    needs: update-check
    runs-on: ubuntu-latest
    if: github.ref == 'refs/heads/main' && github.actor == 'admin-user-identifier'

    steps:
      - uses: actions/checkout@v4
      
      # Admin approval gate middleware? (Middleware::group('Check:user.isAdmin()'))
      
      - name: Deploy Update (Admin Only Click Required)
        run: |             
          git pull origin main 
          
          # Laravel Forge Style deployment with tags?  
          git tag $(head /opt/data/runwayhub/VERSION_FILE) --force HEAD
          git push --tags          
        
# Security Gate (Only Admin User):  
- `github.actor == "admin-user-identifier"`
- OR manual approval via `.github/approvers/admin.txt`  
```

---

## ✨ MVP Roadmap mit Subagenten:

**Phase 1 (Soort MVP Launch):**
- ✅ Laravel Skeleton + .gitignore  
- ✅ Eloquent Models für Core Entities  
- ✅ Flight Booking Controller  
- ✅ Pilot Dashboard Template  
- ❌ ACARS Job (pending GitHub Pages)  
- ❌ Finance Admin Charts  

**Phase 2 (Post-MVP):**
- ACARS Queue Worker Integration  
- Advanced Analytics mit Chart.js  
- OAuth2 API Endpoints  

---

## 📋 GitHub Readyyy! 

**Setup nach Code:**
1. `/opt/data/runwayhub/` Git Init + `.gitignore` creation  
2. Laravel 10 Skeleton install (composer create-project)  
3. Migrations execute (php artisan migrate)  
4. Subagenten Arbeit parallel verteilen  

Soll ich **Git Repository jetzt initieren** oder erst Codierung mit Subagenten starten? Sag einfach! 🚀
