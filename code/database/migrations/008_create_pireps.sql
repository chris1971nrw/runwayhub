-- Migration 008: create_pireps.sql
-- PIREP System - Pilot Reports
-- RunwayHub (SQLite compatible)

CREATE TABLE IF NOT EXISTS pireps (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  flight_number VARCHAR(10) NOT NULL,
  aircraft_registration VARCHAR(8),
  report_time DATETIME NOT NULL,
  position_lat DECIMAL(10, 6) NOT NULL,
  position_lon DECIMAL(10, 6) NOT NULL,
  altitude_feet INTEGER NOT NULL,
  weather_condition TEXT NOT NULL,
  turbulence TEXT DEFAULT NULL,
  icing TEXT DEFAULT NULL,
  visibility_sm INTEGER DEFAULT NULL,
  cloud_ceiling_ft INTEGER DEFAULT NULL,
  wind_gust_kts INTEGER DEFAULT NULL,
  remarks TEXT DEFAULT NULL,
  status VARCHAR(20) DEFAULT 'submitted',
  submitted_by INTEGER,
  submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  reviewed_by INTEGER DEFAULT NULL,
  reviewed_at DATETIME DEFAULT NULL
);

CREATE INDEX idx_pireps_flight_number ON pireps(flight_number);
CREATE INDEX idx_pireps_report_time ON pireps(report_time);
CREATE INDEX idx_pireps_status ON pireps(status);
