-- MySQL Migration für RunwayHub
-- Diese SQL-Anweisungen kannst du in MySQL ausführen:
-- mysql -u root -p runwayhub < migrate-to-mysql.sql

USE runwayhub;

-- 1. Users Tabelle erstellen
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') NOT NULL DEFAULT 'user',
    status ENUM('active', 'inactive', 'suspended') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 2. Flugs Tabelle erstellen
CREATE TABLE IF NOT EXISTS flights (
    id INT AUTO_INCREMENT PRIMARY KEY,
    flight_number VARCHAR(10) NOT NULL,
    callsign VARCHAR(6),
    origin VARCHAR(4),
    destination VARCHAR(4),
    departure_time DATETIME,
    arrival_time DATETIME,
    status ENUM('scheduled', 'in-flight', 'landed', 'cancelled') DEFAULT 'scheduled',
    aircraft_registration VARCHAR(8),
    pilot_id INT,
    gate VARCHAR(4),
    terminal VARCHAR(1),
    baggage VARCHAR(2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (pilot_id) REFERENCES users(id)
);

-- 3. Flugzeuge Tabelle erstellen
CREATE TABLE IF NOT EXISTS aircrafts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    registration VARCHAR(8) NOT NULL UNIQUE,
    type VARCHAR(50) NOT NULL,
    airline VARCHAR(50),
    status ENUM('active', 'maintenance', 'grounded') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 4. Flughäfen Tabelle erstellen
CREATE TABLE IF NOT EXISTS airports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    iata VARCHAR(3) NOT NULL UNIQUE,
    icao VARCHAR(4) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    city VARCHAR(100) NOT NULL,
    country VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 5. Routen Tabelle erstellen
CREATE TABLE IF NOT EXISTS routes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    flight_number VARCHAR(10) NOT NULL,
    origin_airport VARCHAR(4) NOT NULL,
    destination_airport VARCHAR(4) NOT NULL,
    distance_km INT DEFAULT 0,
    duration_minutes INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 6. Buchungen Tabelle erstellen
CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_code VARCHAR(10) NOT NULL UNIQUE,
    flight_id INT NOT NULL,
    user_id INT NOT NULL,
    passenger_name VARCHAR(100) NOT NULL,
    passenger_email VARCHAR(255),
    status ENUM('confirmed', 'cancelled', 'completed') DEFAULT 'confirmed',
    price DECIMAL(10, 2) DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (flight_id) REFERENCES flights(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- 7. Piloten Tabelle erstellen
CREATE TABLE IF NOT EXISTS pilots (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    callsign VARCHAR(10) UNIQUE,
    rating VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- 8. PIREPs Tabelle erstellen
CREATE TABLE IF NOT EXISTS pireps (
    id INT AUTO_INCREMENT PRIMARY KEY,
    flight_number VARCHAR(10) NOT NULL,
    report_type VARCHAR(50) NOT NULL,
    description TEXT,
    severity ENUM('minor', 'moderate', 'severe') DEFAULT 'minor',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 9. Rollen Tabelle erstellen
CREATE TABLE IF NOT EXISTS roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    description TEXT,
    permissions JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 10. Airlines Tabelle erstellen
CREATE TABLE IF NOT EXISTS airlines (
    id INT AUTO_INCREMENT PRIMARY KEY,
    iata VARCHAR(3) NOT NULL UNIQUE,
    icao VARCHAR(3) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    callsign VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 11. Admins Tabelle erstellen
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    permissions JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- 12. Admin Benutzer erstellen
INSERT INTO users (username, email, password, role, status) VALUES
('admin', 'admin@runwayhub.local', '$2y$10$92IXUNpkjO0rO05WMiM9L.wlO/KoenMQJ7SY.58zM72Yz5ovsP', 'admin', 'active');

-- Hinweis: Das Passwort '$2y$10$92IXUNpkjO0rO05WMiM9L.wlO/KoenMQJ7SY.58zM72Yz5ovsP' ist der bcrypt-hash für 'admin123'

SELECT '✅ MySQL-Datenbank erfolgreich migriert!';
SELECT 'Admin Benutzername: admin' AS info;
SELECT 'Admin Passwort: admin123' AS info;
