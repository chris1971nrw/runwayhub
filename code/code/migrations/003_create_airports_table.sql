-- Migration: 003_airports_table
-- Erstellt: 2026-05-27

CREATE TABLE IF NOT EXISTS `airports` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `iata_code` VARCHAR(3) NOT NULL UNIQUE,
    `icao_code` VARCHAR(4) NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `city` VARCHAR(100) NOT NULL,
    `country` VARCHAR(50) NOT NULL,
    `timezone` VARCHAR(50) NOT NULL DEFAULT 'Europe/Berlin',
    `latitude` DECIMAL(10,6) NOT NULL,
    `longitude` DECIMAL(11,6) NOT NULL,
    `elevation` INT UNSIGNED DEFAULT NULL,
    `timezone_offset` TIME NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `iata_code` (`iata_code`),
    UNIQUE KEY `icao_code` (`icao_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
