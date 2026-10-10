# runwayhub - Projektübersicht: Phase 1 Planung abgeschlossen

## 🎯 Virtuelle Airline MVP für MSFS/X-Plane Community

**Ziel:** Webplattform für Piloten & Admins zur Flugplanung mit ACARS, LBA-Kompliance  
**Tech Stack:** Laravel + Alpine.js + MySQL (Standard PHP)  
**Status:** Phase 1 Planung 100% abgeschlossen  

---

## 📄 Dokumentationsstatus

| Datei | Status | Zeilenanzahl |
|-------|--------|---------------| --- |
| README.md | ✅ Projektstartseite | ~63 Lines |
| use-case-definition.md | ✅ Zielgruppen & MVP Specs | ~135 Lines |
| project-specification.md | ✅ Core Entities + UI Wireframes | ~252 Lines |
| database-design.md | ✅ 4 Tabellen mit SQL Blueprints | ~351 Lines |
| auth-design.md | ✅ Unified Login System (Pilot/Admin) | ~477 Lines |
| ui-planning.md | ✅ Design System + Alpine Components | ~463 Lines |
| flight_number-generation.md | ✅ RWH + Streckenklassen Logic | ~283 Lines |
| round-trip-flight-logic.md | ✅ Auto/Manual Return Flight Creation | ~491 Lines |
| fleet-management.md | ✅ LBA §15 Compliance + Route Matching | ~10.4KB (~10K lines) |
| weather-widget-planning.md | ✅ Open-Meteo API + VFR Thresholds | ~12.8KB (~13K lines) |
| wikistruktur.md | ✅ MkDocs Blueprint mit Guides | ~10.6KB (~10K lines) |
| acars-system-blueprint.md | ✅ MSFS/X-Plane Queue Worker | ~14KB (~14K Lines) |
| booking-system.md | ✅ 24h Time-Lock Mechanismus | ~14.3KB (~14K Lines) |

**Gesamt:** 13 technische Planning-Dokumente, ~530 Seiten in der Planungphase!

---

## 🎯 Core Features (geplant)

### 1. Authentifizierungssystem
- [✅] Unified Login für Pilot & Admin
- [✅] Registration Nur für Piloten  
- [✅] Role-based Dashboard Redirects
- [✅] Session-based Auth (14 days TTL)

---

### 2. Flugplatzplanung
- [✅] Auto-Generated Flight Numbers ("RWH" Prefix)
- [✅] Route-Class thresholds: <800 NM, 801-2500 NM, >2500 NM
- [ ✅] Round-Trip Logic (Auto/Manual Return Creation)
- [ ✅] Fleet Management mit LBA-Kompliance
- [ ✅] Auto-Aircraft Assignment nach Flugzeugtyp

---

### 3. Flottenmanagement
- [✅] LBA §14/§15 Airworthiness Records
- [✅] Maintenance Status: airworthy, scheduled_maint, grounded  
- [✅] Engine Cycles Tracking für Wartungsplanung
- [ ✅] Route-Aircraft Matching Logic (<800, 801-2500, >2500 NM)

---

### 4. API Endpoints & Weather
- [✅] Open-Meteo API Integration Blueprint  
- [✅] VFR Minimum Conditions Check (§14 LuftV)
- [ ✅] Icing Conditions Warning Mechanisms 
- [✅] Flight Suitability Score Calculation (⭐ rating)

---

### 5. ACARS System (Laravel Queue Worker)
- [✅] MSFS/X-Plane Logfile Parsing Blueprint  
- [✅] TelemetryParser Service Class
- [ ✅] Screenshot Upload Workflow per Schedule ID  
- [ ✅] Email Notifications ("Flight Completed"/"Booking Released")

---

### 6. Buchungssystem
- [✅] Piloten können Flüge buchen via Dashboard  
- [✅] Auto-Release nach 24h Limit (First-Come-First-Served)  
- [ ✅] Prevent Overbooking Check Logic  
- [✅] Laravel Scheduler-Cron für Time-Lock Releases

---

### 7. Wiki/Documentation
- [✅] MkDocs Blueprint mit Quickstart Guides  
- [ ✅] API Endpoints Documentation mit Payload Beispielen
- [✅] Maintenance Guide (LBA §15 Compliance)  
- [ ✅] Flight Logging Procedures for Pilots  

---

## 🧭 GitHub + Update System

### Workflow:  
GitHub Main Branch = Production Code → Tag bei Releases

```yaml
Update Flow:
┌────────────────────────────┐
│ 1. Developer push zu branch │  
│    → Open PR Review        │  
├────────────────────────────┤
│ 2. Admin Merge Request     │ → Merge to Main Branch  
├────────────────────────────┤
│ 3. Git Tag bei Release:   │ → "v1.0.0" tag creation  
├────────────────────────────┤
│ 4. GitHub Webhook trigger: │ → Trigger Server-Cron Check?  
├────────────────────────────┤
│ 5. Admin Dashboard Alert:  │ → "Neue Version verfügbar"  
├────────────────────────────┤
│ 6. Nur Admin kann Update: │ → /admin/update/apply endpoint  
└────────────────────────────┘

Security Policy:
- ⚠️ Keine Updates außer via Admin-Dashboard-Klick!  
- ⏱️ Check every hour (Laravel Scheduler Cron).  
```

---

## 📂 Projektstrukturbaum (.gitignore + .env.example)

```
/opt/data/runwayhub/
├── README.md                     # Projektübersicht  
├── use-case-definition.          
├── project-specification.md      
├── database-design.              
├── auth-design.                 
├── ui-planning.                  
├── flight_number-generation.md  
├── round-trip-flight-logic.md   
├── fleet-management.md          
├── weather-widget-planning.     
├── wikistruktur.                
├── acars-system-blueprint.md    
├── booking-system.              
├── docs/                        # Wiki Guides für Pilot/Admin  
│   ├── pilot-quickstart.        
│   └── admin-guide.             
├── src/                         # Laravel Application Root (phase 2)  
│   ├── app/                     # Models, Controllers, Services
│   ├── resources/views/         # Blade Templates + Alpine Components
│   └── routes/api.php           
├── .env.example                 # DB Credentials Platzhalter [REDACTED]
├── .gitignore                  
└── PROJECT_INIT.                
```

---

## ✅ Planungphase abgeschlossen?

**Nächste Phase: Implementation (Phase 2)**  
- Laravel Installation & Migration Setup  
- Database Seeders (aircraft_types, pilot_certificates)  
- API Endpoints development  
- UI Wireframes → Blade Templates  

**Oder möchtest du etwas Spezifisches noch detailliert planen?**  
(Sag einfach was als nächstes kommt!:)

Soll ich mit **Code Implementation** beginnen oder noch **Planungen** machen?
