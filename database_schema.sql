-- pilots (core entity)
CREATE TABLE IF NOT EXISTS `pilots` (  
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,  
    callsign VARCHAR(10) UNIQUE NOT NULL COMMENT 'ICAO callsign unique constraint',    
    name VARCHAR(100),  
    email VARCHAR(255) UNIQUE NOT NULL,  
    password_hash VARCHAR(255) NOT NULL,  
    role ENUM('pilot', 'admin') NOT NULL DEFAULT 'pilot',   
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
    
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
    fuel_burned_kg DECIMAL(10,2) DEFAULT0.00,  
    cargo_load_kgINT DEFAULT 0,  
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
    certificate_status ENUM('valid', 'expired') DEFAULT'valid',  
    assigned_route_id INT UNSIGNED REFERENCES flight_schedules(id),  
    next_available_date DATE,   # Auto-grounded if maintenance_expiry < NOW()  
    comments TEXT
    
COMMENT 'Maintenance notes'
);
