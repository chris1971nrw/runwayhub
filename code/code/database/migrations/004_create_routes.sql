-- Migration 004: create_routes.sql
-- Routen-Definition
-- RunwayHub (SQLite compatible)

CREATE TABLE IF NOT EXISTS routes (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  flight_number VARCHAR(10) NOT NULL,
  origin_airport VARCHAR(4) NOT NULL,
  origin_icao VARCHAR(4) NOT NULL,
  origin_latitude DECIMAL(10, 6),
  origin_longitude DECIMAL(10, 6),
  origin_elevation INTEGER,
  destination_airport VARCHAR(4) NOT NULL,
  destination_icao VARCHAR(4) NOT NULL,
  destination_latitude DECIMAL(10, 6),
  destination_longitude DECIMAL(10, 6),
  destination_elevation INTEGER,
  distance_nm DECIMAL(10, 2),
  cruise_speed_kts INTEGER,
  flight_time_minutes INTEGER,
  wind_avg_kts DECIMAL(5, 2),
  wind_dir_degrees INTEGER,
  temperature_c INTEGER,
  remarks TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_routes_flight_number ON routes(flight_number);
CREATE INDEX idx_routes_origin ON routes(origin_airport, origin_icao);
CREATE INDEX idx_routes_destination ON routes(destination_airport, destination_icao);
