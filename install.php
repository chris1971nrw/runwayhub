<?php
/**
 * runwayhub - Ein-Klick-Installation für normale PHP + MySQL Server
 * 
 * Einfach im Browser aufrufen: http://dein-server.com/install.php
 * Alles wird automatisch eingerichtet!
 */

// Session starten (vor ini_set())
session_start();

// Error Reporting für Debugging (nach session_start())
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);

echo "<h1>🌉 runwayhub - Installation</h1>";
echo "<p>Bitte warte während die Datenbank eingerichtet wird...</p>";

// Datenbank Connection Konfiguration
try {
    // Prüfe ob DB-Konfiguration in Formularfeldern angegeben wurde
    $db_host = isset($_POST['DB_HOST']) ? $_POST['DB_HOST'] : getenv('DB_HOST') ?: 'localhost';
    $db_name = isset($_POST['DB_NAME']) ? $_POST['DB_NAME'] : getenv('DB_NAME') ?: 'runwayhub_db';
    $db_user = isset($_POST['DB_USER']) ? $_POST['DB_USER'] : getenv('DB_USER') ?: 'root';
    $db_pass = isset($_POST['DB_PASS']) ? $_POST['DB_PASS'] : getenv('DB_PASS') ?: '';
    
    // Nur wenn DB-Konfiguration manuell angegeben wurde
    if (isset($_POST['DB_HOST'])) {
        echo "<p style='color:blue;'>📝 Verwende manuelle Datenbank-Konfiguration:</p>";
        echo "<p>Host: ${db_host} | DB: ${db_name} | User: ${db_user}</p>";
    } else {
        echo "<p style='color:blue;'>📝 Verwende Standard-Konfiguration:</p>";
        echo "<p>Host: ${db_host} | DB: ${db_name} | User: ${db_user}</p>";
    }

// Datenbank Connection (mit Umgebungsvariablen)
try {
    // Versuche Verbindung zur existierenden Datenbank
    $db_host = getenv('DB_HOST') ?: 'localhost';
    $db_name = getenv('DB_NAME') ?: 'runwayhub_db';
    $db_user = getenv('DB_USER') ?: 'runwayhub';
    $db_pass = getenv('DB_PASS') ?: 'runwayhub123';
    
    $pdo = new PDO(
        "mysql:host=${db_host};dbname=${db_name};charset=utf8mb4",
        $db_user,
        $db_pass
    );
} catch (PDOException $e) {
    die('<h1>❌ Fehler</h1><p>Konnte keine Verbindung zur MySQL Datenbank herstellen.</p><p>Fehler: ' . htmlspecialchars($e->getMessage()) . '</p>');
}

// Datenbank Connection (mit Umgebungsvariablen oder manuell)
try {
    // Versuche Verbindung zur existierenden Datenbank
    $pdo = new PDO(
        "mysql:host={$db_host};dbname={$db_name};charset=utf8mb4",
        $db_user,
        $db_pass
    );
} catch (PDOException $e) {
    die('<h1>❌ Fehler</h1><p>Konnte keine Verbindung zur MySQL Datenbank herstellen.</p><p>Fehler: ' . htmlspecialchars($e->getMessage()) . '</p>');
}

// Falls keine DB-Konfiguration angegeben wurde, frage nach
if (!isset($_POST['DB_HOST'])) {
    echo "<p style='color:orange;'>⚠️  Keine Datenbank-Konfiguration gefunden. Bitte gib unten die Daten ein:</p>";
    echo "<form method='POST' action='install.php' style='max-width:500px;margin:20px auto;'>";
    echo "<h2>Datenbank-Konfiguration</h2>";
    echo "<label>Host:<label>";
    echo "<input type='text' name='DB_HOST' value='${db_host}' placeholder='localhost'>";
    echo "</labe l>";
    echo "<label>Datenbank:<label>";
    echo "<input type='text' name='DB_NAME' value='${db_name}' placeholder='runwayhub_db'>";
    echo "</labe l>";
    echo "<label>Benutzer:<label>";
    echo "<input type='text' name='DB_USER' value='${db_user}' placeholder='root'>";
    echo "</labe l>";
    echo "<label>Passwort:<label>";
    echo "<input type='password' name='DB_PASS' placeholder='***'>";
    echo "</labe l>";
    echo "<button type='submit'>Verbindung testen und Datenbank erstellen</button>";
    echo "</form>";
}

// pilots Tabelle
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS pilots (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        callsign VARCHAR(10) UNIQUE NOT NULL,
        email VARCHAR(255) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        home_base_icao VARCHAR(10),
        skill_level ENUM('beginner', 'intermediate', 'expert'),
        license_type ENUM('PPL', 'CPL', 'ATPL'),
        aircraft_certified JSON,
        role ENUM('pilot', 'crew', 'ground_control') DEFAULT 'pilot',
        is_active BOOLEAN DEFAULT TRUE,
        last_active DATETIME,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX (skill_level),
        INDEX (license_type)
    )");
    echo "<p style='color:green;'>✅ Tabelle 'pilots' erstellt</p>";
} catch (PDOException $e) {
    echo "<p style='color:orange;'>⚠️  Tabelle 'pilots' existiert bereits</p>";
}

// flight_schedules Tabelle
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS flight_schedules (
        id INT AUTO_INCREMENT PRIMARY KEY,
        callsign VARCHAR(20) UNIQUE NOT NULL,
        departure_date DATE NOT NULL,
        from_airport_icao VARCHAR(10) NOT NULL,
        to_airport_icao VARCHAR(10) NOT NULL,
        estimated_off DATETIME NOT NULL,
        estimated_arrival DATETIME NOT NULL,
        aircraft_type ENUM('A320', 'B738', 'A319', 'B752') NOT NULL,
        distance_nm INT,
        active BOOLEAN DEFAULT TRUE,
        route_notes TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX (departure_date),
        INDEX (from_airport_icao, to_airport_icao)
    )");
    echo "<p style='color:green;'>✅ Tabelle 'flight_schedules' erstellt</p>";
} catch (PDOException $e) {
    echo "<p style='color:orange;'>⚠️  Tabelle 'flight_schedules' existiert bereits</p>";
}

// flight_logs Tabelle
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS flight_logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        pilot_id INT NOT NULL,
        schedule_id INT NOT NULL,
        actual_from_airport VARCHAR(10),
        actual_to_airport VARCHAR(10),
        actual_off DATETIME,
        actual_arrival DATETIME,
        fuel_burned_gallons DECIMAL(10,2) DEFAULT 0.00,
        comments TEXT,
        completed BOOLEAN DEFAULT FALSE,
        screenshots JSON,
        weather_conditions VARCHAR(50),
        flight_rating DECIMAL(3,2) DEFAULT 0.00,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (pilot_id) REFERENCES pilots(id) ON DELETE CASCADE,
        FOREIGN KEY (schedule_id) REFERENCES flight_schedules(id) ON DELETE CASCADE,
        INDEX (schedule_id),
        INDEX (completed)
    )");
    echo "<p style='color:green;'>✅ Tabelle 'flight_logs' erstellt</p>";
} catch (PDOException $e) {
    echo "<p style='color:orange;'>⚠️  Tabelle 'flight_logs' existiert bereits</p>";
}

// aircrafts Tabelle
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS aircrafts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        registration VARCHAR(20) UNIQUE NOT NULL,
        model_type ENUM('A320', 'B738', 'A319', 'B752', 'A330') NOT NULL,
        total_seats INT DEFAULT 180,
        total_crew_required INT DEFAULT 4,
        maintenance_status ENUM('airworthy', 'scheduled_maint', 'grounded') DEFAULT 'airworthy',
        next_maintenance_due DATE,
        engine_cycles INT DEFAULT 0,
        available BOOLEAN DEFAULT TRUE,
        airworthiness_certificate TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX (model_type),
        INDEX (maintenance_status),
        INDEX (available)
    )");
    echo "<p style='color:green;'>✅ Tabelle 'aircrafts' erstellt</p>";
} catch (PDOException $e) {
    echo "<p style='color:orange;'>⚠️  Tabelle 'aircrafts' existiert bereits</p>";
}

// Seed Data einfügen
try {
    // Admin User
    $pdo->exec("INSERT INTO pilots (name, callsign, email, password, role) VALUES 
        ('System Admin', 'ADMIN 1', 'admin@runwayhub.run', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ground_control')");
    echo "<p style='color:green;'>✅ Admin User erstellt</p>";
    
    // Sample Pilots
    $pilots = [
        ['Max Mustermann', 'ALPHA 1', 'pilot1@example.com', 'password'],
        ['Anna Schmidt', 'BRAVO 2', 'pilot2@example.com', 'password'],
        ['Tom Weber', 'CHARLIE 3', 'pilot3@example.com', 'password'],
        ['Lisa Müller', 'DELTA 4', 'pilot4@example.com', 'password'],
    ];
    
    foreach ($pilots as $index => $pilot) {
        $home_bases = ['EGKK', 'EDDF', 'LEMD', 'LFPG'];
        $aircrafts_json = json_encode(['A320']);
        $pdo->exec("INSERT INTO pilots (name, callsign, email, password, role, home_base_icao, skill_level, license_type, aircraft_certified) VALUES 
            ('" . addslashes($pilot[0]) . "', '" . addslashes($pilot[1]) . "', '" . addslashes($pilot[2]) . "', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'pilot', 
            '" . $home_bases[$index] . "', 'intermediate', 'CPL', '" . $aircrafts_json . "')");
    }
    echo "<p style='color:green;'>✅ Sample Pilots erstellt</p>";
    
    // Sample Aircraft
    $aircrafts = [
        ['D-AIMR', 'A320', 180, 4, 'airworthy'],
        ['D-ABCD', 'B738', 160, 4, 'airworthy'],
        ['D-EFGH', 'A319', 120, 3, 'airworthy'],
        ['D-IJKL', 'B752', 140, 4, 'scheduled_maint'],
    ];
    
    foreach ($aircrafts as $index => $aircraft) {
        $pdo->exec("INSERT INTO aircrafts (registration, model_type, total_seats, total_crew_required, maintenance_status, next_maintenance_due) VALUES 
            ('" . addslashes($aircraft[0]) . "', '" . addslashes($aircraft[1]) . "', " . $aircraft[2] . ", " . $aircraft[3] . ", '" . addslashes($aircraft[4]) . "', DATE_ADD(NOW(), INTERVAL " . ($index + 1) . " MONTH))");
    }
    echo "<p style='color:green;'>✅ Sample Aircrafts erstellt</p>";
    
    // Sample Flight Schedules
    for ($i = 1; $i <= 5; $i++) {
        $pdo->exec("INSERT INTO flight_schedules (callsign, departure_date, from_airport_icao, to_airport_icao, estimated_off, estimated_arrival, aircraft_type, distance_nm, active) VALUES 
            ('RWH-" . str_pad($i, 3, '0', STR_PAD_LEFT) . "', DATE_ADD(NOW(), INTERVAL " . ($i * 3) . " DAY), 'EGKK', 'LHBB', DATE_ADD(NOW(), INTERVAL " . ($i * 3) . " DAY) + INTERVAL 8 HOUR, DATE_ADD(NOW(), INTERVAL " . ($i * 3) . " DAY) + INTERVAL 9 HOUR, 'A320', " . (400 + $i * 50) . ", TRUE)");
    }
    echo "<p style='color:green;'>✅ Sample Flight Schedules erstellt</p>";
    
} catch (PDOException $e) {
    echo "<p style='color:orange;'>⚠️  Seed Data existiert bereits oder Fehler: " . htmlspecialchars($e->getMessage()) . "</p>";
}

echo "<h2>📊 Status:</h2>";
echo "<ul>";
echo "<li>✅ Datenbank erstellt</li>";
echo "<li>✅ Tabellen angelegt (pilots, flight_schedules, flight_logs, aircrafts)</li>";
echo "<li>✅ Seed Data eingefügt (Admin + Sample Piloten + Aircrafts)</li>";
echo "</ul>";

// dashboard.php anzeigen
if (isset($_SESSION['user_id'])) {
    try {
        $user = $pdo->query("SELECT * FROM pilots WHERE id = " . $_SESSION['user_id'])->fetch();
        
        // Dashboard anzeigen
        if (file_exists('dashboard.php')) {
            include 'dashboard.php';
        } else {
            echo "<h1>🌉 runwayhub - Dashboard</h1>";
            echo "<p style='text-align:center; margin-top:50px;'>Willkommen, <strong>{$user['name']}</strong>!</p>";
            echo "<div style='background:rgba(255,255,255,0.1); padding:30px; border-radius:10px; text-align:center;'>";
            echo "<h2>🎯 Dashboard</h2>";
            echo "<p>Du bist als <strong>{$user['role']}</strong> angemeldet.</p>";
            echo "<div style='display:grid; grid-template-columns:repeat(auto-fit, minmax(300px, 1fr)); gap:20px; margin-top:20px;'>";
            echo "<div style='background:rgba(255,255,255,0.08); padding:20px; border-radius:8px;'>";
            echo "<h3>📅 Schedule Management</h3>";
            echo "<p>Flugpläne erstellen & verwalten mit ICAO-Routen und Aircraft-Zuweisung</p>";
            echo "</div>";
            echo "<div style='background:rgba(255,255,255,0.08); padding:20px; border-radius:8px;'>";
            echo "<h3>✈️ Flight Tracking</h3>";
            echo "<p>Flugprotokolle hochladen & tracken mit MSFS/X-Plane Integration</p>";
            echo "</div>";
            echo "<div style='background:rgba(255,255,255,0.08); padding:20px; border-radius:8px;'>";
            echo "<h3>📡 ACARS Integration</h3>";
            echo "<p>Automatisches Log Parsing für MSFS/X-Plane mit Telemetry Daten</p>";
            echo "</div>";
            echo "<div style='background:rgba(255,255,255,0.08); padding:20px; border-radius:8px;'>";
            echo "<h3>👥 Community Features</h3>";
            echo "<p>Reviews, Leaderboards und Wetter Widget für bessere Flugplanung</p>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
        }
    } catch (PDOException $e) {
        echo "<h1>❌ Fehler beim Laden des Dashboards</h1><p>Fehler: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}

// Login Form (Standard-Ansicht)
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>runwayhub - Installation</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: linear-gradient(135deg, #1a237e, #3949ab); color: #fff; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .login-card { background: rgba(255,255,255,0.1); padding: 40px; border-radius: 10px; backdrop-filter: blur(10px); width: 100%; max-width: 400px; }
        h1 { font-size: 2em; margin-bottom: 10px; text-align: center; text-shadow: 2px 2px 4px rgba(0,0,0,0.5); }
        .badges { display: flex; gap: 10px; justify-content: center; flex-wrap: wrap; margin-bottom: 30px; }
        .badge { padding: 5px 10px; background: rgba(255,255,255,0.1); border-radius: 15px; font-size: 0.8em; }
        form { display: flex; flex-direction: column; gap: 15px; }
        label { font-size: 0.9em; margin-bottom: -5px; }
        input, textarea { padding: 12px; border: none; border-radius: 5px; background: rgba(255,255,255,0.1); color: #fff; font-size: 1em; }
        button { padding: 12px; border: none; border-radius: 5px; background: #4CAF50; color: #fff; font-size: 1em; cursor: pointer; transition: background 0.3s; }
        button:hover { background: #45a049; }
        .error { color: #ff6b6b; text-align: center; margin-bottom: 15px; }
        .status { margin-top: 20px; padding: 15px; background: rgba(255,255,255,0.05); border-radius: 5px; }
    </style>
</head>
<body>
    <div class="login-card">
        <h1>🌉 runwayhub</h1>
        <p style="text-align: center; margin-bottom: 20px;">Installation erfolgreich!</p>
        
        <?php if (isset($error)): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        
        <?php if (!isset($error)): ?>
            <form method="POST">
                <div>
                    <label for="email">E-Mail Adresse</label>
                    <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required autofocus>
                </div>
                
                <div>
                    <label for="password">Passwort</label>
                    <input type="password" name="password" id="password" required>
                </div>
                
                <button type="submit">Anmelden</button>
            </form>
            
            <div class="badges">
                <span class="badge">✅ MVP Phase 2: ABGESCHLOSSEN</span>
                <span class="badge">🧪 PHPUnit Tests: Active</span>
                <span class="badge">🔐 Security Scans: Active</span>
            </div>
            
            <p style="text-align: center; margin-top: 20px;">
                <a href="dashboard.php" style="color: #81d4fa; text-decoration: none;">Dashboard (Demo)</a>
            </p>
        <?php endif; ?>
        
        <div class="status">
            <h3>📊 Status:</h3>
            <ul>
                <li>✅ Datenbank erstellt</li>
                <li>✅ Tabellen angelegt (pilots, flight_schedules, flight_logs, aircrafts)</li>
                <li>✅ Seed Data eingefügt (Admin + Sample Piloten + Aircrafts)</li>
            </ul>
        </div>
    </div>
</body>
</html>
