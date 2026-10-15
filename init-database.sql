-- runwayhub Datenbank Initialisierung
-- Erstellt: 2026-10-15
-- Status: Ready for execution

-- Datenbank erstellen
CREATE DATABASE IF NOT EXISTS runwayhub_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE runwayhub_db;

-- pilots Tabelle
CREATE TABLE IF NOT EXISTS pilots (
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
);

-- flight_schedules Tabelle
CREATE TABLE IF NOT EXISTS flight_schedules (
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
);

-- flight_logs Tabelle
CREATE TABLE IF NOT EXISTS flight_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pilot_id INT NOT NULL,
    schedule_id INT NOT NULL,
    actual_from_airport VARCHAR(10),
    actual_to_airport VARCHAR(10),
    actual_off DATETIME,
    actual_arrival DATETIME,
    fuel_consumed FLOAT,
    weather_conditions TEXT,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (pilot_id) REFERENCES pilots(id) ON DELETE CASCADE,
    FOREIGN KEY (schedule_id) REFERENCES flight_schedules(id) ON DELETE CASCADE
);

-- aircrafts Tabelle
CREATE TABLE IF NOT EXISTS aircrafts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    registration VARCHAR(10) UNIQUE NOT NULL,
    model_type VARCHAR(10) NOT NULL,
    total_seats INT,
    total_crew_required INT,
    maintenance_status ENUM('airworthy', 'scheduled_maint', 'overdue_maint') DEFAULT 'airworthy',
    next_maintenance_due DATE,
    engine_hours FLOAT,
    airframe_hours FLOAT,
    last_inspection_date DATE,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- routes Tabelle
CREATE TABLE IF NOT EXISTS routes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    from_airport_icao VARCHAR(10) NOT NULL,
    to_airport_icao VARCHAR(10) NOT NULL,
    distance_nm INT,
    estimated_time_minutes INT,
    fuel_required FLOAT,
    active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- users Tabelle (für Admin-Zugriff)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    role ENUM('admin', 'pilot', 'crew', 'ground_control') DEFAULT 'pilot',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Sample Daten einfügen (optional)
INSERT INTO pilots (name, callsign, email, password, home_base_icao, skill_level, license_type, aircraft_certified, role) VALUES
('Max Mustermann', 'ALPHA 1', 'pilot1@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'EGKK', 'intermediate', 'CPL', '{"A320": true}', 'pilot'),
('Anna Schmidt', 'BRAVO 2', 'pilot2@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'EDDF', 'intermediate', 'CPL', '{"B738": true}', 'pilot'),
('Tom Weber', 'CHARLIE 3', 'pilot3@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'LEMD', 'intermediate', 'CPL', '{"A319": true}', 'pilot'),
('Lisa Müller', 'DELTA 4', 'pilot4@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'LFPG', 'intermediate', 'CPL', '{"B752": true}', 'pilot');

INSERT INTO aircrafts (registration, model_type, total_seats, total_crew_required, maintenance_status) VALUES
('D-AIMR', 'A320', 180, 4, 'airworthy'),
('D-ABCD', 'B738', 160, 4, 'airworthy'),
('D-EFGH', 'A319', 120, 3, 'airworthy'),
('D-IJKL', 'B752', 140, 4, 'scheduled_maint');

-- Hinweis: Admin-User erstellen (Passwort: admin123)
INSERT INTO users (username, password, email, role) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@runwayhub.com', 'admin');

-- Ende der Initialisierung
SELECT '✅ Datenbank runwayhub_db erfolgreich erstellt!' AS status;
SELECT '✅ Tabellen: pilots, flight_schedules, flight_logs, aircrafts, routes, users' AS tables;
SELECT '✅ Sample Daten: 4 Pilots, 4 Aircrafts, 1 Admin User' AS sample_data;
