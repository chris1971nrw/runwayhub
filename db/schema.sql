-- Schema for RunwayHub Aircraft and Maintenance Management
-- Use of RHxxxx for Fleet IDs as per project requirements.

CREATE TABLE IF NOT EXISTS aircraft (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    rh_id VARCHAR(10) UNIQUE NOT NULL, -- Must follow 'RHxxxx' format
    model_name VARCHAR(50) NOT NULL,
    manufacturer VARCHAR(50),
    status VARCHAR(20) DEFAULT 'Active', -- e.g., Active, Maintenance, Grounded, Retired
    total_hours REAL DEFAULT 0.0,        -- Total flying hours of the aircraft
    last_service_date DATE,              -- Date of last maintenance
    next_service_due_hours REAL,        -- Warning threshold for next maintenance in hours
    current_location VARCHAR(100),       -- Current airport or hangar location
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS maintenance_logs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    aircraft_id INTEGER,
    service_date DATE NOT NULL,
    description TEXT,
    performed_by VARCHAR(100),          -- Technician or Admin name
    parts_replaced TEXT,
    notes TEXT,
    FOREIGN KEY (aircraft_id) REFERENCES aircraft(id) ON DELETE CASCADE
);

-- Index for faster lookups by Rhine-ID
\\nCREATE UNIQUE INDEX idx_rh_id ON aircraft(rh_id);\\n
\\n-- New ACARS Support Table\\\\n
CREATE TABLE IF NOT EXISTS acars_messages (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    aircraft_id INTEGER,
    raw_data TEXT,                -- The original ACARS string
    parsed_status VARCHAR(50),    -- Result of the processing logic
    received_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    source_type VARCHAR(20)       -- 'manual' or 'auto_client'
);

-- PiRep (Pilot Reporting) System
-- Automates environment data to minimize pilot input.
CREATE TABLE IF NOT EXISTS pi_reports (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    flight_booking_id VARCHAR(50), -- Links to the current active booking
    aircraft_id INTEGER,            -- Link to aircraft table
    auto_metar TEXT,                 -- Pre-fetched weather data (METAR/TAF)
    pilot_notes TEXT,                -- Manual observations from pilot
    report_timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(20) DEFAULT 'pending' -- e.g., pending, published
);

CREATE INDEX idx_pi_report_booking ON pi_reports(flight_booking_id);
CREATE INDEX idx_pi_report_aircraft ON pi_reports(aircraft_id);

-- Existing Indices
CREATE UNIQUE INDEX idx_rh_id ON aircraft(rh_id);
_**End Patch**
CREATE INDEX idx_acars_status ON acars_message(parsed_status);