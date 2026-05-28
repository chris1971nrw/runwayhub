-- Migration: 006_bookings_table
-- Erstellt: 2026-05-27

CREATE TABLE IF NOT EXISTS `bookings` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `booking_code` VARCHAR(6) NOT NULL,
    `flight_id` INT UNSIGNED NOT NULL,
    `passenger_name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(20) DEFAULT NULL,
    `ticket_number` VARCHAR(20) DEFAULT NULL,
    `booking_class` ENUM('economy', 'business', 'first', 'premium_economy') NOT NULL DEFAULT 'economy',
    `price_total` DECIMAL(10,2) NOT NULL,
    `price_tax` DECIMAL(10,2) DEFAULT 0.00,
    `payment_method` ENUM('credit_card', 'paypal', 'bank_transfer', 'cash') NOT NULL DEFAULT 'credit_card',
    `payment_status` ENUM('pending', 'paid', 'refunded') NOT NULL DEFAULT 'pending',
    `status` ENUM('confirmed', 'cancelled', 'completed') NOT NULL DEFAULT 'confirmed',
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `booking_code` (`booking_code`),
    KEY `flight_id` (`flight_id`),
    KEY `email` (`email`),
    KEY `booking_class` (`booking_class`),
    KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
