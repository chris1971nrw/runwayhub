<?php

/**
 * OpenAIP Datenbank-Schema Generator
 *
 * Erstellt SQL-Statements für OpenAIP-Tabellen
 */

$airportsOpenAIP = "
-- Tabelle: Flughäfen aus OpenAIP
CREATE TABLE IF NOT EXISTS airports_openaip (
    id INTEGER PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    city VARCHAR(255),
    country VARCHAR(255),
    iata VARCHAR(4),
    icao VARCHAR(4),
    latitude DECIMAL(10, 6) NOT NULL,
    longitude DECIMAL(10, 6) NOT NULL,
    elevation INTEGER,
    elevation_feet INTEGER,
    elevation_meters INTEGER,
    timezone VARCHAR(50),
    weather_station VARCHAR(100),
    traffic_pattern VARCHAR(255),
    customs VARCHAR(255),
    frequency VARCHAR(50),
    glideslope VARCHAR(50),
    localizer VARCHAR(50),
    navaid VARCHAR(50),
    gps_waypoint VARCHAR(50),
    airport_code VARCHAR(10),
    type VARCHAR(50),
    latitude_d DECIMAL(3, 2),
    latitude_m DECIMAL(3, 2),
    latitude_s DECIMAL(3, 2),
    longitude_d DECIMAL(3, 2),
    longitude_m DECIMAL(3, 2),
    longitude_s DECIMAL(3, 2),
    city_id INTEGER,
    country_id INTEGER,
    synced_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_icao (icao),
    INDEX idx_iata (iata),
    INDEX idx_country (country),
    INDEX idx_type (type),
    INDEX idx_latlng (latitude, longitude)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabelle: Wegpunkte aus OpenAIP
CREATE TABLE IF NOT EXISTS waypoints_openaip (
    id INTEGER PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    type VARCHAR(50) NOT NULL,
    latitude DECIMAL(10, 6) NOT NULL,
    longitude DECIMAL(10, 6) NOT NULL,
    country VARCHAR(255),
    description TEXT,
    navaid VARCHAR(50),
    airport VARCHAR(4),
    elevation INTEGER,
    frequency VARCHAR(50),
    synced_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_type (type),
    INDEX idx_country (country),
    INDEX idx_latlng (latitude, longitude)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabelle: Luftwege aus OpenAIP
CREATE TABLE IF NOT EXISTS airways_openaip (
    id INTEGER PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    type VARCHAR(50) NOT NULL,
    latitude DECIMAL(10, 6) NOT NULL,
    longitude DECIMAL(10, 6) NOT NULL,
    description TEXT,
    country VARCHAR(255),
    airport1 VARCHAR(4),
    airport2 VARCHAR(4),
    synced_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_type (type),
    INDEX idx_country (country)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabelle: Navigationshilfen aus OpenAIP
CREATE TABLE IF NOT EXISTS navaids_openaip (
    id INTEGER PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    type VARCHAR(50) NOT NULL,
    latitude DECIMAL(10, 6) NOT NULL,
    longitude DECIMAL(10, 6) NOT NULL,
    description TEXT,
    country VARCHAR(255),
    frequency VARCHAR(50),
    power VARCHAR(50),
    range VARCHAR(50),
    synced_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_type (type),
    INDEX idx_country (country),
    INDEX idx_freq (frequency)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";

// Ausgabe des SQL-Schemas
echo $airportsOpenAIP;
?>
