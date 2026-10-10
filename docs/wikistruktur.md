# runwayhub - Documentation & Wikistruktur

## 🎯 Purpose

**Handbuch für virtuelle Airline Community:**
- **Piloten:** Flugplanung, Reporting, Maintenance Guidelines  
- **Admins:** Schedule Management, System-Wartung  
- **Common:** LBA Compliance, Safety Procedures  

---

## 📁 Wiki Strukturschema (MkDocs / Docusaurus Blueprint)

```
/opt/data/runwayhub/docs/
├── index.md                      # Landing page für Wiki
├── quickstart/
│   ├── pilot-quickstart.md       # Piloten First-Steps Guide  
│   ├── admin-quickstart.md       # Admin Quick-Guide  
│   └── register-pilot.md         # Registrierung Anleitung  
│   
├── flights/
│   ├── plan-a-flight.md          # Flugplanung (Route, Aircraft)  
│   ├── assign-aircraft.md        # Flugzeug-Zuweisung Guide  
│   ├── log-flight.md             # Flight Log Eintrag Verfahren  
│   └── return-trip.md            # Rückflug Management
│   
├── fleet/
│   ├── maintenance-guide.md      # Wartungsanweisungen (LBA)  
│   ├── aircraft-types.md         # Typ-Spezifikationen  
│   └── availability-check.md     # Verfügbarkeitsprüfung Guide
│   
├── routes/
│   ├── create-new-route.md       # Neue Route erstellen  
│   ├── schedule-management.md    # Schedule-Management für Admins
│   └── weather-check-guide.md    # Wetter-Anleitung für Piloten
│   
├── safety/
│   ├── vfr-minimums.md          # Sichtflug-Vorschriften  
│   ├── icing-warnings.md        # Vereisung-Warnungen  
│   └── emergency-procedures.md   # Notfallverfahren

├── api/
│   ├── endpoint-references.md    # API Endpunkte für Entwickler  
│   └── payload-examples.md       # JSON Request/Response Beispiele  
│   
└── admin-guide/
    ├── system-administration.md  # Admin Dashboard Guide  
    ├── schedule-ops.md           # Schedule Operations Guide  
    └── maintenance-management.md  # Wartungsplanung für Flotte
```

---

## 📝 Quickstart Guides (Erste Schritte)

### quickstart/pilot-quickstart.md Blueprint:

```markdown
# 🛫 Pilot Quickstart Guide

**Willkommen auf runwayhub!** Dieser Guide führt Sie durch die ersten Schritte:  

## 1. Registrierung & Profil-Erstellung

Steps:
1. Öffne [runwayhub.de](https://runwayhub.de)  
2. Klicke auf "Registrieren"  
3. Fülle Pilotenformular aus (ICAO Callsign, Home Airport)  
4. Bestätige Registrierungs-E-Mail-Link  

## 2. Login & Dashboard

After registration:
1. Log in mit deinem E-Mail/Passwort  
2. Dashboard zeigt zugewiesene Flüge (assigned_flights list)  
3. Prüfe Flugstatus vor Abflug  

## 3. Meine Flüge anzeigen

Navigate to `/flights/assigned` → Siehe Liste deiner Schedules:
- Flugnummer (RWH100, RWH200...)  
- Route & Zeitplan  
- Assigned Aircraft Type  

## 4. Flight Log Eintrag (Nach Abflug)

Procedural Steps:
1. Nach Landung: Öffne `/flights/{id}/log`  
2. Lade Screenshots hoch (Landes, Takeoff, Arrival Photos)  
3. Trage tatsächliche Zeiten ein (Takeoff, Arrival)  
4. Speichere Eintrag → Dashboard aktualisiert sich  

## 5. Nächster Schritt

Check your dashboard for assigned return flights!
```

---

### quickstart/admin-quickstart.md Blueprint:

```markdown  
# ⚙️ Admin Quickstart Guide

**Willkommen beim runwayhub Admin!** Dieser Guide hilft dir bei der Verwaltung:  

## 1. Dashboard Overview

`/dashboard` shows system health & upcoming schedules:
- Active flights  
- Maintenance alerts  
- Weather warnings  
- Recent flight logs  

## 2. New Route Creation

Creating a new schedule:
Steps:
1. Navigate to `/schedules/new` (Ground Control)  
2. Fill route details (ICAO codes, departure time, aircraft type)  
3. Check auto-generated flight number (RWH + Strecken-Klassen)  
4. ☑️ Auto-Create Return Flight (optional default=true)  
5. Click "Save & Create" → System generates RWH100/200 for round trip  

## 3. Flight Maintenance Management

Check aircraft status:
- `/admin/fleet` → View all aircrafts & maintenance status  
- Filter by type (C172, A320neo, B738)  
- View next inspection dates (LBA Compliance §15 LuftV)  

## 4. System Updates

Admin-only Update Flow:
1. Go to `/admin/system` → Check for new version alerts  
2. If update available → Click "Apply Update Now"  
3. System rolls back if needed (backup before deploy)  
```

---

## 🔍 API Endpoints Dokumentation Blueprint

### api/endpoint-references.md Struktur:

```markdown
# runwayhub API Reference

## Base URL

`https://api.runwayhub.de/v1`  

### Authentication

All requests require `Bearer {token}` header (Laravel Session-based Auth)

---

## Flight Schedule Endpoints

### POST /schedules/create

Create new flight schedule with auto-generated number:

**Request Body:**
```json
{
  "from_icao": "EDDM",
  "to_icao": "EGLL",  
  "departure_datetime": "2026-10-15T14:30:00+02",
  "aircraft_type": "A320neo",
  "auto_create_return": true,
  "buffer_minutes": 15
}
```

**Response (success):**
```json
{
  "id": 123,
  "from_icao": "EDDM",
  "to_icao": "EGLL",
  "flight_number": "RWH100",
  "departure_datetime": "2026-10-15T14:30:00+02",
  "aircraft_type": "A320neo",
  "auto_created_return": {
    "id": 124,
    "from_icao": "EGLL",
    "to_icao": "EDDM", 
    "flight_number": "RWH200" /* reverse route! */,
    "departure_datetime": "2026-10-30T14:30:00+02" /* +14 days */
  }
}
```

### GET /schedules/{id}/weather

Get forecast for scheduled route:

**Response:**
```json
{
  "visibility_km": 15.2,
  "wind_speed_knots": 22,
  "temperature_celsius": -2,
  "dewpoint_diff_degcelsius": 4,
  "flight_suitability_score": 5,
  "vfr_conditions_met": true
}
```

---

## Flight Logs Endpoints

### POST /flights/assigned/{id}/log/log-flight

Submit flight completion log after takeoff:

**Request:**
```json
{
  "takeoff_datetime": "2026-10-15T14:28:00+02" /* actual vs scheduled */,
  "arrival_datetime": "2026-10-15T16:45:00+02",  
    "screenshots": [{  
      "path": "/storage/app/flights/2026-10-15/EDDM-EGLL_Landing.jpg"  
    }]
}
```

**Response (success):**
```json
{
  "log_id": 789,
  "scheduled_vs_actual": {
    "takeoff_diff_minutes": -2,  /* 2 min early! */
    "arrival_diff_minutes": +13  
  }
}
```

---

## Weather API Endpoints

### GET /weather/forecast/{airport_code_icao}

Get 24h forecast for airport:

**Response:**
```json
{
  "airport": "EDDM", 
    "daily": { 
      "01": "weathercode=0, visibility=15km, windspeed=22kt"  
    },
    "hourly": ["temp=-3C, dewpoint=-7C"]
}
```

---

## Fleet Management Endpoints

### GET /fleet/{registration}/maintenance-check

Check aircraft maintenance status (LBA Compliance):

**Response:**
```json
{
  "aircraft_status": "airworthy",  
  "next_inspection_date": "2027-10-15",  
  "days_until_next_inspection": 368, 
    "maintenance_status_field": true /* airworthiness_cert valid? */
}
```

---

**📖 More API Documentation:** Visit `/docs/api` for complete reference

```

---

## 🧭 Maintenance System Documentation (LBA §15 Compliance)

### fleet/maintenance-guide.md Blueprint:

```markdown  
# ✈️ Aircraft Maintenance Guide (LBA-Kompliant)

**Willkommen im Wartungssystem für runwayhub!**  

Dieser guide erklärt:
- LuftV §14/§15/§28 Compliance Requirements  
- Nächste Inspektionsfälligkeit Berechnung  
- LBA-Warnungen verstehen & Handeln  

---

## 1. Airworthiness Certificate (LuftV §15)

**Was ist es?**
- Document proving aircraft airworthy status per LBA rules  
- Validity period defined by Type Certificate (TC) issuance Date  

**Checklist:**
- [ ] Airworthiness Certificate expires > today?  
- [ ] Next scheduled maintenance date < 90 days away → Action needed!  
- [ ] Last inspection performed < 24 months? (§14 LuftV)  

---

## 2. Maintenance Status Felder (Datenbank)


| Field | LBA Requirement | Description |
|-------|----------------|-------------|
| `airworthiness_cert` | §15 | True = gültig; expires when certification lapses  
| `maintenance_status` | §14 | ENUM: airworthy, scheduled_maint, inspection_req, grounded  
| `next_inspection_date` | §14+ICAO | Annual/Period Inspection deadline date  

**Maintenance Intervals (LBA Reference):**
- Short-haul aircraft: Every 200 flight hours  
- Medium haul: 500 flight hours or engine cycles  
- Long-haul widebody: 800 flight hours or quarterly inspection  

---

## 3. Maintenance Alert System

**Wie funktioniert die Warnung:**

1. **Cron-Job Daily Check:** Prüft alle aircrafts auf `maintenance_status != 'airworthy'`
2. **Admin Dashboard:** Zeigt "Neue Version verfügbar" Alert nur als Admin  
3. **Email Notifications:** Versendet E-Mail an Piloten deren Flugzeug wartet (optional)  

**Action Workflow für admin:**

Steps:
1. Öffne `/admin/fleet` → Filter auf `maintenance_status = 'scheduled_maint'`  
2. Review aircraft maintenance status & LBA compliance fields  
3. Assign available aircraft to new schedule or replace grounded types  
4. Activate `airworthiness_cert = true` when maintenance complete  

---

## 4. Grounding Procedures

**When to ground aircraft (LBA §15):**

Reasons:
- Expired airworthiness certificate  
- Icing conditions expected (weather widget warning)  
- Crosswind exceeds certified limits (>40kt crosswind, C172 limit)  

**Action:**
Admin muss `aircraft.active = false` setzen → Dashboard nicht flugbereit anzeigen!  

---

## 5. LBA Compliance für Wetter-Safety (VFR/IFR Minimums)

| Condition | VFR Minimum | Action Required |
|-----------|-------------|------------------|
| Visibility < 800m | ❌ VFR minimum broken | Delay flight or switch to IFR procedure  
| Wind > 40kt crosswind | ⚠️ Unsafe takeoff limit | Check aircraft performance certificate for max wind?  
| Temperature < -10°C | ⚠️ Icing conditions possible | Require de-icing facility or delay departure  

**Admin Tool:** `/admin/weather-check-{route}` → LBA Advisory Alert
```

---

## ✅ Documentation Checklist

Checkliste Wiki-Herstellung:

[✅] Quickstart Guides (Pilot vs Admin First Steps)  
[✅] API Blueprint für Endpunkte (Create Schedule, Weather)  
[✅] Maintenance Guide mit LBA §14/§15 Compliance  
[✅] Flight Logging Procedures  
[ ✅] Weather Conditions VFR/IFR Reference  
[ ] SEO Optimization (Keywords für Flugsimulator-Community)?  
[ ] Translation (DE/EN) für internationale Piloten?  

---

## 📌 Nächster Planungs-Schritt?

Soll ich die **API Endpoints Blueprint** schreiben mit Payload-Beispielen?

Oder das **Maintenance Alert System** mit LBA Warning Logic detailliert planen?  

Sag einfach, was als nächstes zu planen ist!
