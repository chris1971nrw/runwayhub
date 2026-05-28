-- Migration: 005_flights_table
-- Erstellt: 2026-05-27

CREATE TABLE IF NOT EXISTS `flights` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `route_id` INT UNSIGNED NOT NULL,
    `flight_number` VARCHAR(10) NOT NULL,
    `aircraft_id` INT UNSIGNED NOT NULL,
    `airline_id` INT UNSIGNED NOT NULL,
    `scheduled_departure` DATETIME NOT NULL,
    `scheduled_arrival` DATETIME NOT NULL,
    `actual_departure` DATETIME DEFAULT NULL,
    `actual_arrival` DATETIME DEFAULT NULL,
    `status` ENUM('scheduled', 'active', 'completed', 'cancelled') NOT NULL DEFAULT 'scheduled',
    `pilot_fo_id` INT UNSIGNED DEFAULT NULL,
    `pilot_pa_id` INT UNSIGNED DEFAULT NULL,
    `co_pilot_id` INT UNSIGNED DEFAULT NULL,
    `load_factor` DECIMAL(5,2) DEFAULT 0.00,
    `revenue` DECIMAL(10,2) DEFAULT 0.00,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `route_id` (`route_id`),
    KEY `aircraft_id` (`aircraft_id`),
    KEY `airline_id` (`airline_id`),
    KEY `pilot_fo_id` (`pilot_fo_id`),
    KEY `pilot_pa_id` (`pilot_pa_id`),
    KEY `status` (`status`),
    FOREIGN KEY (`route_id`) REFERENCES `routes`(`id`) ON DELETE RESTRICT,
    FOREIGN KEY (`aircraft_id`) REFERENCES `aircrafts`(`id`) ON DELETE RESTRICT,
    FOREIGN KEY (`airline_id`) REFERENCES `airlines`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
