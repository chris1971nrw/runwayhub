-- RunwayHub Datenbank-Initialisierung

-- Flüge Tabelle
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
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX IF NOT EXISTS idx_flights_number ON flights(flight_number);

-- Flugverlauf Tabelle
CREATE TABLE IF NOT EXISTS flight_history (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    flight_number VARCHAR(10),
    origin VARCHAR(4),
    destination VARCHAR(4),
    departure_time DATETIME,
    actual_departure DATETIME,
    status VARCHAR(20),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX IF NOT EXISTS idx_history_flight ON flight_history(flight_number);

-- Flugzeuge Tabelle
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
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE UNIQUE INDEX IF NOT EXISTS idx_aircrafts_registration ON aircrafts(registration);

-- Wartung Tabelle
CREATE TABLE IF NOT EXISTS maintenance (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    aircraft_registration VARCHAR(8),
    date DATE,
    type VARCHAR(50),
    description TEXT,
    cost DECIMAL(10,2),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX IF NOT EXISTS idx_maintenance_aircraft ON maintenance(aircraft_registration);

-- Piloten Tabelle
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
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE UNIQUE INDEX IF NOT EXISTS idx_pilots_callsign ON pilots(callsign);
CREATE UNIQUE INDEX IF NOT EXISTS idx_pilots_email ON pilots(email);

-- Piloten Verlauf Tabelle
CREATE TABLE IF NOT EXISTS pilot_history (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    pilot_id INTEGER,
    flight_number VARCHAR(10),
    departure_time DATETIME,
    actual_departure DATETIME,
    status VARCHAR(20),
    notes TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Buchungen Tabelle
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
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE UNIQUE INDEX IF NOT EXISTS idx_bookings_reference ON bookings(booking_reference);
CREATE INDEX IF NOT EXISTS idx_bookings_flight ON bookings(flight_number);

-- Passagiere Tabelle
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
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX IF NOT EXISTS idx_passengers_booking ON passengers(booking_id);

-- Plätze Tabelle
CREATE TABLE IF NOT EXISTS seats (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    flight_number VARCHAR(10),
    seat_number VARCHAR(4),
    row INTEGER,
    seat_class VARCHAR(10),
    status VARCHAR(20),
    price DECIMAL(8,2),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE UNIQUE INDEX IF NOT EXISTS idx_seats_flight ON seats(flight_number, seat_number);

-- Wetter-Tabelle
CREATE TABLE IF NOT EXISTS weather_cache (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    airport VARCHAR(4),
    location VARCHAR(100),
    data_type VARCHAR(20),
    timestamp DATETIME,
    data JSON,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE UNIQUE INDEX IF NOT EXISTS idx_weather_cache ON weather_cache(airport, data_type, timestamp);

-- ACARS-Tabelle
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

-- Flüge initialisieren
INSERT OR REPLACE INTO flights (flight_number, origin, destination, departure_time, status) VALUES
    ('LH456', 'FRA', 'JFK', '2026-05-28 14:30:00', 'scheduled'),
    ('LH458', 'FRA', 'JFK', '2026-05-28 18:30:00', 'scheduled'),
    ('BA123', 'LHR', 'FRA', '2026-05-28 12:00:00', 'scheduled'),
    ('AF054', 'CDG', 'FRA', '2026-05-28 13:00:00', 'scheduled'),
    ('LH202', 'MUC', 'JFK', '2026-05-28 15:00:00', 'scheduled');

-- Flugzeuge initialisieren
INSERT OR REPLACE INTO aircrafts (registration, type, manufacturer, model, capacity, range, status) VALUES
    ('D-AIMA', 'Boeing 737-800', 'Boeing', '737-800', 162, 5400, 'active'),
    ('D-AIMA2', 'Boeing 737-800', 'Boeing', '737-800', 162, 5400, 'active'),
    ('D-AIME', 'Airbus A320', 'Airbus', 'A320', 180, 6100, 'active'),
    ('D-AIMI', 'Airbus A319', 'Airbus', 'A319', 140, 6500, 'active');

-- Piloten initialisieren
INSERT OR REPLACE INTO pilots (first_name, surname, callsign, email, rating, status) VALUES
    ('Hans', 'Mueller', 'HLM1', 'h.mueller@airline.com', '1', 'active'),
    ('Greta', 'Schmidt', 'HLS1', 'g.schmidt@airline.com', '1', 'active'),
    ('Otto', 'Weber', 'HLW1', 'o.weber@airline.com', '1', 'active'),
    ('Emil', 'Fischer', 'HLE1', 'e.fischer@airline.com', '1', 'active');

-- Plätze für erste Flüge
INSERT OR IGNORE INTO seats (flight_number, seat_number, row, seat_class, status, price) VALUES
    ('LH456', '1A', 1, 'business', 'available', 250.00),
    ('LH456', '1B', 1, 'business', 'available', 250.00),
    ('LH456', '1C', 1, 'business', 'available', 250.00),
    ('LH456', '1D', 1, 'business', 'available', 250.00);
