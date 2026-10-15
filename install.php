<?php
/**
 * runwayhub - Ein-Klick-Installation für normale PHP + MySQL Server
 * 
 * @version 1.0.0-beta-3
 * @author chris1971nrw
 * @copyright 2026
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

// Datenbank Connection Konfiguration (einmalig!)
try {
    // Prüfe ob DB-Konfiguration in Formularfeldern angegeben wurde
    $db_host = (isset($_POST['DB_HOST']) ? $_POST['DB_HOST'] : getenv('DB_HOST')) ?: 'localhost';
    $db_name = (isset($_POST['DB_NAME']) ? $_POST['DB_NAME'] : getenv('DB_NAME')) ?: 'runwayhub_db';
    $db_user = (isset($_POST['DB_USER']) ? $_POST['DB_USER'] : getenv('DB_USER')) ?: 'root';
    $db_pass = (isset($_POST['DB_PASS']) ? $_POST['DB_PASS'] : getenv('DB_PASS')) ?: '';
    
    // Nur wenn DB-Konfiguration manuell angegeben wurde
    if (isset($_POST['DB_HOST'])) {
        echo "<p style='color:blue;'>📝 Verwende manuelle Datenbank-Konfiguration:</p>";
        echo "<p>Host: " . htmlspecialchars($db_host) . " | DB: " . htmlspecialchars($db_name) . " | User: " . htmlspecialchars($db_user) . "</p>";
    } else {
        echo "<p style='color:blue;'>📝 Verwende Standard-Konfiguration:</p>";
        echo "<p>Host: " . htmlspecialchars($db_host) . " | DB: " . htmlspecialchars($db_name) . " | User: " . htmlspecialchars($db_user) . "</p>";
    }

    // Datenbank Connection (einmalig - KEINE Duplikate!)
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
        echo "<label>Host:</label><input type='text' name='DB_HOST' value='{$db_host}' placeholder='localhost'>";
        echo "</label>";
        echo "<label>Datenbank:</label><input type='text' name='DB_NAME' value='{$db_name}' placeholder='runwayhub_db'>";
        echo "</label>";
        echo "<label>Benutzer:</label><input type='text' name='DB_USER' value='{$db_user}' placeholder='root'>";
        echo "</label>";
        echo "<label>Passwort:</label><input type='password' name='DB_PASS' placeholder='***'>";
        echo "</label>";
        echo "<button type='submit'>Verbindung testen und Datenbank erstellen</button>";
        echo "</form>";
    }

    // Erstelle Datenbank (falls nicht existiert)
    try {
        $pdo->exec("CREATE DATABASE IF NOT EXISTS " . addslashes($db_name) . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        echo "<p style='color:green;'>✅ Datenbank erstellt</p>";
    } catch (PDOException $e) {
        echo "<p style='color:orange;'>⚠️  Datenbank existiert bereits</p>";
    }

    // Erstelle Tabellen
    try {
        $pdo->exec("USE " . addslashes($db_name));
    } catch (PDOException $e) {
        die('<h1>❌ Fehler</h1><p>Kann Datenbank nicht verwenden.</p>');
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
        
    } catch (PDOException $e) {
        echo "<p style='color:orange;'>⚠️  Fehler beim Einfügen von Seed-Daten</p>";
    }

    echo "<h2>🎉 Installation erfolgreich!</h2>";
    echo "<p>Datenbank '{$db_name}' wurde erstellt und initialisiert.</p>";
    echo "<p><a href='dashboard.php'>Zum Dashboard</a></p>";
} catch (Exception $e) {
    echo "<h1>❌ Fehler</h1><p>" . htmlspecialchars($e->getMessage()) . "</p>";
}

?>
