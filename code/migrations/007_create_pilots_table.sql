-- Migration: 007_pilots_table
-- Erstellt: 2026-05-27

CREATE TABLE IF NOT EXISTS `pilots` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `license_number` VARCHAR(50) NOT NULL UNIQUE,
    `type` ENUM('captain', 'first_officer', 'pilot') NOT NULL DEFAULT 'pilot',
    `rating_type` ENUM('multi', 'helicopter', 'fixedwing', 'floatplane', 'glider', 'ultralight') NOT NULL,
    `license_valid_until` DATE NOT NULL,
    `medical_valid_until` DATE NOT NULL,
    `experience_years` INT UNSIGNED DEFAULT 0,
    `flight_hours` DECIMAL(10,2) DEFAULT 0.00,
    `status` ENUM('active', 'inactive', 'suspended', 'retired') NOT NULL DEFAULT 'active',
    `home_base` VARCHAR(100) DEFAULT NULL,
    `email` VARCHAR(255) DEFAULT NULL,
    `phone` VARCHAR(20) DEFAULT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `license_number` (`license_number`),
    KEY `type` (`type`),
    KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
