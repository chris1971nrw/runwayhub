-- Migration: 004_routes_table
-- Erstellt: 2026-05-27

CREATE TABLE IF NOT EXISTS `routes` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `airline_id` INT UNSIGNED NOT NULL,
    `airport_from` VARCHAR(3) NOT NULL,
    `airport_to` VARCHAR(3) NOT NULL,
    `flight_number_prefix` VARCHAR(10) NOT NULL,
    `frequency_hours` DECIMAL(5,2) DEFAULT 24.00,
    `distance_km` INT UNSIGNED NOT NULL,
    `estimated_time_minutes` INT UNSIGNED NOT NULL,
    `status` ENUM('active', 'inactive', 'planned') NOT NULL DEFAULT 'planned',
    `season_start` DATE DEFAULT NULL,
    `season_end` DATE DEFAULT NULL,
    `notes` TEXT DEFAULT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `airline_id` (`airline_id`),
    KEY `airport_from` (`airport_from`),
    KEY `airport_to` (`airport_to`),
    FOREIGN KEY (`airline_id`) REFERENCES `airlines`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
