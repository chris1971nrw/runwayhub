-- Migration 006: create_bookings.sql
-- Buchungs-Verwaltung
-- RunwayHub

CREATE TABLE IF NOT EXISTS `bookings` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `booking_reference` VARCHAR(20) NOT NULL UNIQUE,
  `user_id` INT(11) UNSIGNED NOT NULL,
  `flight_id` INT(11) UNSIGNED NOT NULL,
  `seat` VARCHAR(10) DEFAULT NULL,
  `price` DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
  `class` ENUM('economy', 'business', 'first') NOT NULL DEFAULT 'economy',
  `status` ENUM('pending', 'confirmed', 'checked_in', 'completed', 'cancelled') NOT NULL DEFAULT 'pending',
  `payment_method` ENUM('credit_card', 'paypal', 'bank_transfer', 'cash') DEFAULT NULL,
  `payment_status` ENUM('pending', 'completed', 'failed') NOT NULL DEFAULT 'pending',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `flight_id` (`flight_id`),
  KEY `booking_reference` (`booking_reference`),
  KEY `status` (`status`),
  KEY `payment_status` (`payment_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Beispiel-Buchungen
INSERT INTO `bookings` (`booking_reference`, `user_id`, `flight_id`, `seat`, `price`, `class`, `status`, `payment_method`, `payment_status`) VALUES
('REF001', 1, 1, '12A', 150.00, 'economy', 'pending', 'credit_card', 'pending'),
('REF002', 1, 1, '12B', 150.00, 'economy', 'pending', 'paypal', 'pending');

-- Benutzer-Tabelle verlinken
-- Wenn die users Tabelle noch nicht existiert, wird sie in 001 erstellt
-- Wenn die bookings Tabelle referenziert, muss users existieren
