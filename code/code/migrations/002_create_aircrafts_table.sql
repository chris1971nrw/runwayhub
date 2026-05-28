-- Migration: 002_aircrafts_table
-- Erstellt: 2026-05-27

CREATE TABLE IF NOT EXISTS `aircrafts` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `registration` VARCHAR(10) NOT NULL UNIQUE,
    `type` VARCHAR(100) NOT NULL,
    `manufacturer` VARCHAR(100) NOT NULL,
    `model` VARCHAR(100) NOT NULL,
    `seat_count` INT UNSIGNED NOT NULL,
    `capacity_first` INT DEFAULT 0,
    `capacity_business` INT DEFAULT 0,
    `capacity_economy` INT DEFAULT 0,
    `capacity_total` INT NOT NULL,
    `status` ENUM('active', 'inactive', 'maintenance', 'scrapped') NOT NULL DEFAULT 'active',
    `next_maintenance` DATE DEFAULT NULL,
    `last_maintenance` DATE DEFAULT NULL,
    `flight_hours` DECIMAL(10,2) DEFAULT 0.00,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `registration` (`registration`),
    KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
