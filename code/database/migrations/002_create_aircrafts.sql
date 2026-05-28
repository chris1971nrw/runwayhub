-- Migration 002: create_aircrafts.sql
-- Flugzeug-Verwaltung
-- RunwayHub (SQLite compatible)

CREATE TABLE IF NOT EXISTS aircrafts (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  registration VARCHAR(8) NOT NULL UNIQUE,
  type VARCHAR(50) NOT NULL,
  manufacturer VARCHAR(50) NOT NULL,
  model VARCHAR(50) NOT NULL,
  capacity INTEGER DEFAULT NULL,
  range INTEGER DEFAULT NULL,
  max_altitude INTEGER DEFAULT NULL,
  max_speed INTEGER DEFAULT NULL,
  status VARCHAR(20) DEFAULT 'active',
  purchase_date DATE DEFAULT NULL,
  next_maintenance DATE DEFAULT NULL,
  last_maintenance DATETIME DEFAULT NULL,
  notes TEXT DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_aircrafts_registration ON aircrafts(registration);
CREATE INDEX idx_aircrafts_status ON aircrafts(status);
CREATE INDEX idx_aircrafts_type ON aircrafts(type);
