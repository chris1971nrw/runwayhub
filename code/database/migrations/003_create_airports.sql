-- Migration 003: create_airports.sql
-- Flughafen-Verwaltung
-- RunwayHub (SQLite compatible)

CREATE TABLE IF NOT EXISTS airports (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  iata VARCHAR(3) NOT NULL UNIQUE,
  icao VARCHAR(4) NOT NULL UNIQUE,
  name VARCHAR(100) NOT NULL,
  city VARCHAR(100) NOT NULL,
  country VARCHAR(100) NOT NULL DEFAULT 'Germany',
  latitude DECIMAL(10, 6) DEFAULT NULL,
  longitude DECIMAL(10, 6) DEFAULT NULL,
  timezone VARCHAR(50) DEFAULT 'Europe/Berlin',
  elevation INTEGER DEFAULT NULL,
  website VARCHAR(255) DEFAULT NULL,
  notes TEXT DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Beispiel-Flughäfen
INSERT INTO airports (iata, icao, name, city, country, latitude, longitude, timezone, elevation) VALUES
('FRA', 'EDDF', 'Frankfurt Airport', 'Frankfurt', 'Germany', 50.0379, 8.5622, 'Europe/Berlin', 362),
('MUC', 'EDDM', 'Munich Airport', 'München', 'Germany', 48.3538, 11.7861, 'Europe/Berlin', 453),
('HAM', 'EDDH', 'Hamburg Airport', 'Hamburg', 'Germany', 53.6304, 9.9882, 'Europe/Berlin', 13),
('DUS', 'EDDL', 'Düsseldorf Airport', 'Düsseldorf', 'Germany', 51.2895, 6.7668, 'Europe/Berlin', 45),
('CGN', 'EDDK', 'Cologne Bonn Airport', 'Köln', 'Germany', 50.8659, 7.1427, 'Europe/Berlin', 167),
('STR', 'EDDS', 'Stuttgart Airport', 'Stuttgart', 'Germany', 48.6898, 9.2218, 'Europe/Berlin', 825),
('HAJ', 'EDDV', 'Hannover Airport', 'Hannover', 'Germany', 52.4611, 9.6850, 'Europe/Berlin', 188),
('NUE', 'EDJA', 'Nuremberg Airport', 'Nürnberg', 'Germany', 49.4987, 11.0669, 'Europe/Berlin', 337),
('LEJ', 'EDLP', 'Leipzig/Halle Airport', 'Leipzig', 'Germany', 51.4138, 12.2409, 'Europe/Berlin', 135),
('DRS', 'EDCD', 'Dresden Airport', 'Dresden', 'Germany', 51.1311, 13.7669, 'Europe/Berlin', 703),
('FMO', 'EDDG', 'Münster Osnabrück Airport', 'Münster', 'Germany', 51.9979, 7.6794, 'Europe/Berlin', 42),
('PAD', 'EDAP', 'Paderborn Lippstadt Airport', 'Paderborn', 'Germany', 51.6394, 8.6153, 'Europe/Berlin', 453),
('SCN', 'EDFA', 'Schausburg Airport', 'Schausburg', 'Germany', 50.2977, 8.8513, 'Europe/Berlin', 380),
('ERF', 'EDNE', 'Erfurt-Weimar Airport', 'Erfurt', 'Germany', 50.9798, 10.9786, 'Europe/Berlin', 351),
('FLL', 'EDFL', 'Flensburg Airport', 'Flensburg', 'Germany', 54.7830, 8.5143, 'Europe/Berlin', 12);

CREATE INDEX idx_airports_iata ON airports(iata);
CREATE INDEX idx_airports_icao ON airports(icao);
CREATE INDEX idx_airports_country ON airports(country);
