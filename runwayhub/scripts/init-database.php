<?php

declare(strict_types=1);

/**
 * Datenbank initialisieren
 * 
 * Initialisiere SQLite-Datenbank mit Tabellen
 */

require_once __DIR__.'/../vendor/autoload.php';

use RunwayHub\Core\Database;

$db = new Database('/home/christoph/.openclaw/workspace-runwayhub/runwayhub/database.sqlite');

echo "📊 Datenbank initialisieren...\n\n";

// Flüge Tabelle
$db->query("
    CREATE TABLE IF NOT EXISTS flights (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        flight_number VARCHAR(10) NOT NULL,
        callsign VARCHAR(6),
        origin VARCHAR(4),
        destination VARCHAR(4),
        departure_time DATETIME,
        arrival_time DATETIME,
        status VARCHAR(20),
        aircraft_registration VARCHAR(8),
        pilot_id INTEGER,
        gate VARCHAR(4),
        terminal VARCHAR(1),
        baggage VARCHAR(2),
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (pilot_id) REFERENCES pilots(id)
    );
");

// Flugverlauf Tabelle
$db->query("
    CREATE TABLE IF NOT EXISTS flight_history (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        flight_number VARCHAR(10),
        origin VARCHAR(4),
        destination VARCHAR(4),
        departure_time DATETIME,
        actual_departure DATETIME,
        status VARCHAR(20),
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (flight_number) REFERENCES flights(flight_number)
    );
");

// Flugzeuge Tabelle
$db->query("
    CREATE TABLE IF NOT EXISTS aircrafts (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        registration VARCHAR(8) NOT NULL,
        type VARCHAR(50),
        manufacturer VARCHAR(50),
        model VARCHAR(50),
        capacity INTEGER,
        range INTEGER,
        max_altitude INTEGER,
        max_speed INTEGER,
        status VARCHAR(20),
        purchase_date DATE,
        next_maintenance DATE,
        last_maintenance DATETIME,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        UNIQUE (registration)
    );
");

// Wartung Tabelle
$db->query("
    CREATE TABLE IF NOT EXISTS maintenance (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        aircraft_registration VARCHAR(8),
        date DATE,
        type VARCHAR(50),
        description TEXT,
        cost DECIMAL(10,2),
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (aircraft_registration) REFERENCES aircrafts(registration)
    );
");

// Piloten Tabelle
$db->query("
    CREATE TABLE IF NOT EXISTS pilots (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        first_name VARCHAR(50),
        surname VARCHAR(50),
        callsign VARCHAR(8),
        license_type VARCHAR(50),
        license_number VARCHAR(50),
        email VARCHAR(100),
        phone VARCHAR(20),
        rating VARCHAR(1),
        status VARCHAR(10) DEFAULT 'active',
        hire_date DATE,
        password VARCHAR(255),
        username VARCHAR(50),
        reset_token VARCHAR(128),
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        UNIQUE (callsign),
        UNIQUE (email)
    );
");

// Piloten Verlauf Tabelle
$db->query("
    CREATE TABLE IF NOT EXISTS pilot_history (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        pilot_id INTEGER,
        flight_number VARCHAR(10),
        departure_time DATETIME,
        actual_departure DATETIME,
        status VARCHAR(20),
        notes TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (pilot_id) REFERENCES pilots(id),
        FOREIGN KEY (flight_number) REFERENCES flights(flight_number)
    );
");

// Buchungen Tabelle
$db->query("
    CREATE TABLE IF NOT EXISTS bookings (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        flight_number VARCHAR(10),
        passenger_email VARCHAR(100),
        passenger_name VARCHAR(50),
        passenger_seats VARCHAR(50),
        passenger_phone VARCHAR(20),
        booking_reference VARCHAR(20),
        status VARCHAR(20),
        total_price DECIMAL(10,2),
        payment_method VARCHAR(50),
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        UNIQUE (booking_reference)
    );
");

// Passagiere Tabelle
$db->query("
    CREATE TABLE IF NOT EXISTS passengers (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        booking_id INTEGER,
        first_name VARCHAR(50),
        surname VARCHAR(50),
        email VARCHAR(100),
        phone VARCHAR(20),
        seat VARCHAR(4),
        seat_class VARCHAR(10),
        checked_bags INTEGER,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (booking_id) REFERENCES bookings(id)
    );
");

// Plätze Tabelle
$db->query("
    CREATE TABLE IF NOT EXISTS seats (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        flight_number VARCHAR(10),
        seat_number VARCHAR(4),
        row INTEGER,
        seat_class VARCHAR(10),
        status VARCHAR(20),
        price DECIMAL(8,2),
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        UNIQUE (flight_number, seat_number),
        FOREIGN KEY (flight_number) REFERENCES flights(flight_number)
    );
");

// Wetter-Tabelle (für Caching)
$db->query("
    CREATE TABLE IF NOT EXISTS weather_cache (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        airport VARCHAR(4),
        location VARCHAR(100),
        data_type VARCHAR(20),
        timestamp DATETIME,
        data JSON,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        UNIQUE (airport, data_type, timestamp)
    );
");

// ACARS-Tabelle
$db->query("
    CREATE TABLE IF NOT EXISTS acars_flights (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        flight_number VARCHAR(10),
        origin VARCHAR(4),
        destination VARCHAR(4),
        departure_time DATETIME,
        arrival_time DATETIME,
        status VARCHAR(20),
        latitude REAL,
        longitude REAL,
        altitude INTEGER,
        ground_speed INTEGER,
        track INTEGER,
        last_heartbeat DATETIME,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );
");

// Flüge initialisieren
$flights = [
    ['flight_number' => 'LH456', 'origin' => 'FRA', 'destination' => 'JFK', 'departure_time' => '2026-05-28 14:30:00', 'status' => 'scheduled'],
    ['flight_number' => 'LH458', 'origin' => 'FRA', 'destination' => 'JFK', 'departure_time' => '2026-05-28 18:30:00', 'status' => 'scheduled'],
    ['flight_number' => 'BA123', 'origin' => 'LHR', 'destination' => 'FRA', 'departure_time' => '2026-05-28 12:00:00', 'status' => 'scheduled'],
    ['flight_number' => 'AF054', 'origin' => 'CDG', 'destination' => 'FRA', 'departure_time' => '2026-05-28 13:00:00', 'status' => 'scheduled'],
    ['flight_number' => 'LH202', 'origin' => 'MUC', 'destination' => 'JFK', 'departure_time' => '2026-05-28 15:00:00', 'status' => 'scheduled'],
];

foreach ($flights as $flight) {
    $db->query("INSERT INTO flights (flight_number, origin, destination, departure_time, status) VALUES (?, ?, ?, ?, ?)",
        [$flight['flight_number'], $flight['origin'], $flight['destination'], $flight['departure_time'], $flight['status']]);
}

// Flugzeuge initialisieren
$aircrafts = [
    ['registration' => 'D-AIMA', 'type' => 'Boeing 737-800', 'manufacturer' => 'Boeing', 'model' => '737-800', 'capacity' => 162, 'range' => 5400, 'status' => 'active'],
    ['registration' => 'D-AIMA2', 'type' => 'Boeing 737-800', 'manufacturer' => 'Boeing', 'model' => '737-800', 'capacity' => 162, 'range' => 5400, 'status' => 'active'],
    ['registration' => 'D-AIME', 'type' => 'Airbus A320', 'manufacturer' => 'Airbus', 'model' => 'A320', 'capacity' => 180, 'range' => 6100, 'status' => 'active'],
    ['registration' => 'D-AIMI', 'type' => 'Airbus A319', 'manufacturer' => 'Airbus', 'model' => 'A319', 'capacity' => 140, 'range' => 6500, 'status' => 'active'],
];

foreach ($aircrafts as $aircraft) {
    $db->query("INSERT INTO aircrafts (registration, type, manufacturer, model, capacity, range, status) VALUES (?, ?, ?, ?, ?, ?, ?)",
        [$aircraft['registration'], $aircraft['type'], $aircraft['manufacturer'], $aircraft['model'], $aircraft['capacity'], $aircraft['range'], $aircraft['status']]);
}

// Piloten initialisieren
$pilots = [
    ['first_name' => 'Hans', 'surname' => 'Mueller', 'callsign' => 'HLM1', 'email' => 'h.mueller@airline.com', 'rating' => '1', 'status' => 'active'],
    ['first_name' => 'Greta', 'surname' => 'Schmidt', 'callsign' => 'HLS1', 'email' => 'g.schmidt@airline.com', 'rating' => '1', 'status' => 'active'],
    ['first_name' => 'Otto', 'surname' => 'Weber', 'callsign' => 'HLW1', 'email' => 'o.weber@airline.com', 'rating' => '1', 'status' => 'active'],
    ['first_name' => 'Emil', 'surname' => 'Fischer', 'callsign' => 'HLE1', 'email' => 'e.fischer@airline.com', 'rating' => '1', 'status' => 'active'],
];

foreach ($pilots as $pilot) {
    $db->query("INSERT INTO pilots (first_name, surname, callsign, email, rating, status) VALUES (?, ?, ?, ?, ?, ?)",
        [$pilot['first_name'], $pilot['surname'], $pilot['callsign'], $pilot['email'], $pilot['rating'], $pilot['status']]);
}

echo "✅ Tabellen erstellt und initialisiert!\n";
echo "📊 Tabellen:\n";
echo "  - flights\n";
echo "  - flight_history\n";
echo "  - aircrafts\n";
echo "  - maintenance\n";
echo "  - pilots\n";
echo "  - pilot_history\n";
echo "  - bookings\n";
echo "  - passengers\n";
echo "  - seats\n";
echo "  - weather_cache\n";
echo "  - acars_flights\n";
echo "📋 Initialisierte Daten:\n";
echo "  - 5 Flüge\n";
echo "  - 4 Flugzeuge\n";
echo "  - 4 Piloten\n";
