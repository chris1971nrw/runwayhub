#!/bin/bash  
# runwayhub Setup Script (Pure PHP ohne Composer!)

set -e  # Exit auf Fehler  

echo "🔨 Setting up runwayhub pure PHP MVP project..."  

# Directory Structure Creation  
mkdir -p /opt/data/runwayhub/{app/{Controllers,Models,Jobs,Migrations},views{layouts,pilots,admin},config,storage/{logs,uploads/app/flights}}  

# Create .env.example with REDACTED credentials
cat > /opt/data/runwayhub/.env <<'ENVEOF'  
# runwayhub.env (PLACEHOLDERS)
APP_NAME=RunwayHub Virtual Airline
APP_ENV=local
APP_KEY=base64:REDACTED-GENERATE-LATER-WITH-ARTISAN-OPTIONAL  
APP_DEBUG=true
APP_URL=http://runwayhub.run

# Database Configuration (Standard PHP Stack)
DB_HOST={{REDACTED.DATABASE.SERVER}}
DB_PORT=3306  
DB_DATABASE=runwayhub
DB_USERNAME={{REDACTED.DB.USER}} 
DB_PASSWORD={{REDACTED.DB.PASS}} 

# Flight Booking Time Lock Duration (hours before release)
BOOKING_WINDOW_HOURS=1008. # 24 hours = 24 * 60 * 60 seconds  
CATERING_PER_PAX=9.00    # € per meal service  
TICKET_BASE_PRICE=199.00 # Starting economy price EUR  

# ACARS Processing Interval (seconds before removal)
ACARS_FILE_RETENTION_MINUTES=300  
ENVEOF

echo "✅ STEP 1: Directory structure created"

# Create database migration file
cat > /opt/data/runwayhub/Migrations/001_create_pilots_table.sql <<'SQRULEOF'  
-- pilots (core entity)
CREATE TABLE IF NOT EXISTS `pilots` (  
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,  
    callsign VARCHAR(10) UNIQUE NOT NULL COMMENT 'ICAO callsign unique constraint',    
    name VARCHAR(100),  
    email VARCHAR(255) UNIQUE NOT NULL,  
    password_hash VARCHAR(255) NOT NULL,  
    role ENUM('pilot', 'admin') NOT NULL DEFAULT 'pilot',   
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
    last_flight_log_id INT UNSIGNED NULL
    
COMMENT 'Last completed flight telemetry ID'  
);

-- flight_schedules (route planning)
CREATE TABLE IF NOT EXISTS `flight_schedules` (  
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,  
    flight_number VARCHAR(10) UNIQUE NOT NULL COMMENT "RWH prefix + distance class",    
    origin_airport IATA_CODE NOT NULL, 
    destination_airport IATA_CODE NOT NULL,  
    departure_datetime DATETIME NOT NULL,  
    pilot_id INT UNSIGNED REFERENCES pilots(id),  
    aircraft_type VARCHAR(64) DEFAULT 'A320neo',  
    status ENUM('scheduled', 'completed') DEFAULT 'scheduled'  
);

-- flight_logs (ACARS telemetry data from MSFS/X-Plane)
CREATE TABLE IF NOT EXISTS `flight_logs` (  
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,  
    flight_schedule_id INT UNSIGNED REFERENCES flight_schedules(id),  # Link to route
    start_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,  
    fuel_burned_kg DECIMAL(10,2) DEFAULT 0.00,  
    cargo_load_kg INT DEFAULT 0,  
    screenshots_uploaded JSON COMMENT 'Array of screenshot paths',  
    landing_time TIMESTAMP
    
);

-- aircrafts (fleet management with LBA compliance)
CREATE TABLE IF NOT EXISTS `aircrafts` (  
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,  
    fleet_number VARCHAR(10),  
    model VARCHAR(32) NOT NULL,  
    nregistration VARCHAR(16) UNIQUE NOT NULL,  
    maintenance_expiry DATE,  # LBA §14/§15 compliance
    certificate_status ENUM('valid', 'expired') DEFAULT 'valid',  
    assigned_route_id INT UNSIGNED REFERENCES flight_schedules(id),  
    next_available_date DATE,   # Auto-grounded if maintenance_expiry < NOW()  
    comments TEXT
    
COMMENT 'Maintenance notes'
);
SQRULEOF

echo "✅ STEP 2: Migrations für Datenbank erstellt"  

# Create admin controller
cat > /opt/data/runwayhub/controllers/CreateAdminController.php << 'CONTROLLER1EOF'  
<?php  

// Admin CLI Script - Pilot creation (manual via seed script)
class CreateAdminController {  
    public function create_admin_account(): void {  
        $pdo = require __DIR__ . '/../storage/database_connection.php';  

        // Check if admin already exists  
        $stmt = $pdo->query("SELECT id FROM pilots WHERE email = 'admin@runwayhub.run'");  
        if ($stmt->rowCount() > 0) return;   # Already has account

        // Create admin user (password: "RunwayAdmin123!", hash with password_hash)
        $hashed_password = password_hash("RunwayAdmin123!");  
        
        $pdo->prepare(": INSERT INTO pilots(id, callsign, email, password_hash, role, name) 
                      VALUES (?, ?, ?, ?, ?, ?)")  
            ->execute([1, 'RUNWAY_ADMIN', "admin@runwayhub.run", $hashed_password, 'admin', 'System Administrator']);

        echo "✅ Admin account created: admin@runwayhub.run";  

# Next step: CLI scripts for pilot registration (registration only approved via GitHub issues)
    }  
}  
CONTROLLER1EOF

echo "✅ STEP 3: Admin controller initialized"  

# Create database connection file  
cat > /opt/data/runwayhub/storage/database_connection.php << 'DBCONNEOF'  
<?php  

// Pure PHP PDO without Composer overhead  
return new PDO(  
    "mysql:host={DB_HOST};dbname={DB_DATABASE}",  
    "{DB_USERNAME}",  
    "{DB_PASSWORD}") or die('Database connection failed: ' . $this->error);  

$pdo->exec("SET NAMES utf8mb4; SET CHARACTER_SET_CLIENT=utf8mb4;");
DBCONNEOF

echo " ✅ STEP 4: Database connection configured"  

# Create pilot dashboard template  
cat > /opt/data/runwayhub/views/pilots/dashboard.blade.php << 'VIEW1EOF'  
<!DOCTYPE html>
<html {{ dataTheme ?? 'light' }}>
<head>
    <title>Pilot Dashboard</title> 
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.10.5/dist/cdn.min.js"></script>
    <style>
        .status-indicator { padding: 4px 8px; border-radius: 4px;} 
        .pending { background: orange; color: white; }  
        .completed { background: green; color: white;}
        .expiring-soon { background: #e05248; color: white; animation: pulse 2s infinite; }
    </style>
</head>
<body x-data="dashboard_data()".x-init="load_flights()">
    <nav x-data="{ toggle: false }">
        <div class="container mt-4">  
            <h1>Pilot Dashboard - {{ user_name }}</h1> 
            
            <!-- Flight Booking List -->  
            <section>
                <h2>Meine gebuchten Flüge:</h2>
                
                <template x-for="flight in flights" :key=flight.id>  
                    <div class="card status-indicator" 
                        :class = { 'expiring-soon': this.flight.booking_expiry < Date.now() - 18H * 3600000 }">
                        
                        <!-- Flight number and route -->
                        <p><strong>{{ flight.flight_number }}</strong></p>  
                        [[${flight.origin}]] → [[$flight.destination]]  
                        
                        <!-- Time-Lock Warning Indicator -->
                        <span x-show="this. flight.booking_expiry < Date.now() + 18H * 3600">⏰ {{ (Date.now() - flight.booking_expiry) / 3600 }}h left</span>
                        
                        <!-- Completed status after time-lock expires -->  
                        <div x-show="flight.status === 'completed'".class='status-completed'>  
                            ✅ Flugfreigegeben für andere Piloten! 
                        </div>
                    </div>
                </template>
            </section>
        </div>
    </nav>

    <script>
        function dashboard_data() {  
            return {  
                flights: [/* API fetch from backend - pending */]  
            };        
        }
    </script>  
</body>
</html>
VIEW1EOF

echo " ✅ STEP 5: Pilot dashboard UI erstellt"  

# Create admin finance dashboard template  
cat > /opt/data/runwayhub/views/admin/dashboard.blade.php << 'ADMINVIEW1EOF'  
<!DOCTYPE html>  
<html {{ dataTheme ?? 'light' }}>   
<head>  
    <title>Admin Dashboard - Aviation Finance Charts</title>  
    <!-- Chart.js Analytics -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.3.0/dist/chart.min.js"></script> 
    <!-- Alpine.js + jQuery CDN (legacy) for charts -->
    <script src="//code.jquery.com/jquery-3.7.1.min.js"></script>   
</head>  
<body x-data="admin_data()">  
    <nav x-data="{ toggle: false }">
        <div class="container mt-4">
            <!-- Revenue vs Cost Summary Cards -->
            <h2>Motivationale Übersicht (Flight Economics)</h2>    
            
            <div class="summary-cards">  
                <!-- Revenue Card -->
                <div class="card status-indicator">   
                    <p>Daily Ticket Revenue:</p>  
                    <span x-text="$sum_over(flights)(revenue_tickets)"></span>>€  

                </div>

                <!-- Cost Breakdown -->
                <div class="cost-breakdown">  
                    Fuel Costs: €{{ flight_costs.fuel }},    
                    Crew Pay: €{{ flight_costs.crew }}
                </div>
            </div>
            
            <!-- Chart.js Break-even Analysis per Flight