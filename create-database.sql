-- MySQL-Datenbank Initialisierung
-- Führe dies in der MySQL-Shell aus: mysql -u root -p

USE runwayhub;

-- Users-Tabelle
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'va_owner', 'pilot', 'user') NOT NULL DEFAULT 'user',
    status ENUM('active', 'inactive', 'suspended') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Admin Benutzer
INSERT INTO users (username, email, password, role, status)
VALUES ('admin', 'admin@runwayhub.local', '$2y$10$92IXUNpkjO0rO05WMiM9L.wlO/KoenMQJ7SY.58zM72Yz5ovsP', 'admin', 'active');

-- Flüge Tabelle
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
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Flugzeuge Tabelle
CREATE TABLE IF NOT EXISTS aircrafts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    registration VARCHAR(8) NOT NULL UNIQUE,
    type VARCHAR(50) NOT NULL,
    airline VARCHAR(50),
    status ENUM('active', 'maintenance', 'grounded') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Flughäfen Tabelle
CREATE TABLE IF NOT EXISTS airports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    iata VARCHAR(3) NOT NULL UNIQUE,
    icao VARCHAR(4) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    city VARCHAR(100) NOT NULL,
    country VARCHAR(50) NOT NULL
);

-- Routen Tabelle
CREATE TABLE IF NOT EXISTS routes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    flight_number VARCHAR(10) NOT NULL,
    origin_airport VARCHAR(4) NOT NULL,
    destination_airport VARCHAR(4) NOT NULL,
    distance_km INT DEFAULT 0,
    duration_minutes INT DEFAULT 0
);

-- Buchungen Tabelle
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
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

SELECT '✅ Datenbank erfolgreich erstellt!';
