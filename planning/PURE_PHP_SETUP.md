# runwayhub - Pure PHP Setup (Ohne Laravel Composer Dependency)

## 🎯 Warum: Standard-PHP Server Kompatibilität

**User Request:** "läuft über alle PHP-Server ohne zusätzliche Installation"  
**Status:** Docker Container mit s6 supervision → composer/php nicht dauerhaft verfügbar  

---

## ✅ Pure PHP Alternative zu Laravel MVC

Ziel: **Lightweight MVC ohne Composer** (Standard-PHP Stack)  

### Core Components:

| Component | Purpose | File Location  
----------|---------|----------------
**Router** | URL Routing Logic | `/app/Routes/web.php`  
**Controller** | Request Handling | `/app/Controllers/PilotController.php`  
**Model** | Database ORM (Custom SimpleDB) | `/app/Models/Pilot.php`  
**View Engine** | Blade-like Templates | `/app/views/{page}.blade.php`  

### Datenbanken:

```sql
-- MySQL/MariaDB direkt (ohne Docker Overhead!)  
CREATE TABLE pilots (  
    id INT PRIMARY KEY AUTO_INCREMENT,  
    name VARCHAR(100),  
    email VARCHAR(255) UNIQUE NOT NULL,  
    callsign VARCHAR(10) UNIQUE NOT NULL,  
    password_hash VARCHAR(255),  
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE flight_schedules (  
    id INT PRIMARY KEY AUTO_INCREMENT,  
    flight_number VARCHAR(10) UNIQUE NOT NULL,  
    route_from VARCHAR(10),  
    route_to VARCHAR(10),  
    departure_date DATETIME,  
    pilot_id INT REFERENCES pilots(id)
);

```

---

## 🎨 Blade-like Template Engine für Pure PHP

**Features:**
- `@extends('layout')` + `@yield('content')`  
- Alpine.js Integration (X-data={})  
- Progress Bar for Bookings (24h Time-Lock Indicator)  

### Beispiel: /app/views/dashboard.blade.php

```php
<!DOCTYPE html>
<html {{ dataTheme ?? 'light' }}="light">
<head>
    <meta charset="UTF-8">
    <title>@yield('page_title', 'Dashboard')</title>
    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.10.5/dist/cdn.min.js"></script>
</head>
<body x-data="{ loading: false }" x-init="loading=true">  
    @include('nav') 
    
    <!-- Main Content -->
    <main class="container">
        @yield('content')
        
        <!-- Progress Bar (24h Booking Warning) -->
        <div x-show="booking_expires <= NOW - 18H">
            ⏰ Time Left: {{ (booking_expiry - NOW) / 3600 }} hours
        </div>
    </main>
</body>
</html>
```

---

## 🌐 ACARS Queue Worker (Pure PHP Implementation)

**Workflow:** File Watcher auf `/storage/app/flights/`

### /app/Jobs/ProcessFlightLog.php

```php
<?php  

class ProcessFlightLog {
    public function handle(string $flight_log_path): void {
        if (!file_exists($flight_log_path)) return;
        
        // SimConnect/X-Plane Data Ref Parsing:
        $json = file_get_contents($flight_log_path);  
        
        // Store in MySQL/MariaDB (direct connection, no container overhead)  
        $pdo = new PDO("mysql:host={DB_HOST};dbname=runwayhub", DB_USER, DB_PASS);
        $pdo->query(": INSERT INTO flight_logs VALUES (:id, :flight_hours, :fuel_used, :cargo_weight)");
        
        // Delete processed file after 5min delay  
        sleep(300); 
        unlink($flight_log_path);  

        // Trigger ACARS notification event system  
        $this->sendEmailNotification(json_decode($json));
    }
}
```

---

## 🛠️ Setup Command (Single-File Installer):

### /opt/data/runwayhub/setup.sh

```bash
#!/bin/bash  
# Pure PHP MVC für Standard-PHP Server Kompatibilität

set -e 

echo "🔧 Setting up runwayhub..."
mkdir -p /opt/data/runwayhub/{app/config,app/Routes,app/Models,app/Controllers}
mkdir -p /storage/app/flights /storage/uploads

# Datenbank connection (native PHP PDO)  
cat > /opt/data/runwayhub/storage/database.php <<'EOF'
<?php return new PDO("mysql:host={{DBHOST}};dbname=runwayhub", "{{DBUSER}}", "{{DBPASS}}"); EOF

echo "✅ Setup completed!"
echo "\n\nStart instructions:"
echo "1. Configure database/connection credentials in storage/database.php"  
echo "2. Create pilot accounts via admin CLI script (/opt/data/runwayhub/create_admin.php)"  
echo "3. Access pilot dashboard at http://your-server/dashboard/ (Pure PHP, no Composer!)"  

```

---

## 📊 Status Update: MVP Approach Gewählt ✅

**Entscheidung:**
- ❌ **Laravel ohne Composer unavailable im Docker Container**  
- ✅ Pure PHP + MySQL/MariaDB nativ verfügbar  
- ✅ Blade-like Templates mit Alpine.js (kein Build-Overhead)  

### MVP Roadmap angepasst:

| Phasen-Milestone | Status  
-----------------|---------
✅ Planning complete | 16 documents  
✅ Database Schema | MySQL Tables prepared  
🔄 Setup command /opt/data/runwayhub/setup.sh | Pending creation  
⏳ Admin Dashboard (Blade + Alpine.js) | Phase 2  

### Datenbank Setup:

```bash
# Create admin account via CLI script

# Run setup after database credentials configured:  
cd /opt/data/runwayhub  
chmod +x setup.sh ./setup.sh
```

---

## 💬 Nächste Schritte?

**Optionen:**
1. **[✓] Setup-Script erstellen** (/opt/data/runwayhub/setup.sh mit PDO DB connection)  
2. **[ ] Admin CLI Script** (create_admin.php für Pilot User creation)  
3. **[?] Datenbank credentials prüfen** (.env.example redact values)  

Was willst du testen: **Setup command**, **Admin account creator**, oder noch etwas anderes? 🛫
