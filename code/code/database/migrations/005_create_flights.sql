-- Migration 005: create_flights.sql (already exists)
-- This creates an additional flights table with more fields
-- RunwayHub (SQLite compatible)

-- Note: flights table already exists from migration 001
-- This migration adds additional functionality

-- Create flight_routes for route management
CREATE TABLE IF NOT EXISTS flight_routes (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  flight_number VARCHAR(10) NOT NULL,
  origin VARCHAR(4) NOT NULL,
  destination VARCHAR(4) NOT NULL,
  distance_nm DECIMAL(10, 2),
  estimated_time INTEGER,
  fuel_required INTEGER,
  status VARCHAR(20) DEFAULT 'planned',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_flight_routes_flight_number ON flight_routes(flight_number);
CREATE INDEX idx_flight_routes_status ON flight_routes(status);
