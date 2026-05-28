-- Migration 004: create_routes.sql
-- Routen-Verwaltung
-- RunwayHub

CREATE TABLE IF NOT EXISTS `routes` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `origin_airport` INT(11) UNSIGNED NOT NULL,
  `destination_airport` INT(11) UNSIGNED NOT NULL,
  `name` VARCHAR(100) DEFAULT NULL,
  `distance_km` INT(11) DEFAULT NULL,
  `flight_time_minutes` INT(11) DEFAULT NULL,
  `status` ENUM('active', 'inactive', 'seasonal') NOT NULL DEFAULT 'active',
  `frequency` VARCHAR(50) DEFAULT NULL,
  `notes` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `origin_airport` (`origin_airport`),
  KEY `destination_airport` (`destination_airport`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Beispiel-Routen
INSERT INTO `routes` (`origin_airport`, `destination_airport`, `name`, `distance_km`, `flight_time_minutes`, `status`) VALUES
(1, 2, 'Frankfurt - München', 480, 75, 'active'),
(2, 1, 'München - Frankfurt', 480, 75, 'active'),
(1, 5, 'Frankfurt - Hamburg', 640, 90, 'active'),
(5, 1, 'Hamburg - Frankfurt', 640, 90, 'active'),
(1, 3, 'Frankfurt - Düsseldorf', 180, 30, 'active');
