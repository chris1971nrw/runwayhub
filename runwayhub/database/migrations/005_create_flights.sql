-- Migration 005: create_flights.sql
-- Flug-Verwaltung
-- RunwayHub

CREATE TABLE IF NOT EXISTS `flights` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `flight_number` VARCHAR(10) NOT NULL,
  `route_id` INT(11) UNSIGNED NOT NULL,
  `aircraft_id` INT(11) UNSIGNED DEFAULT NULL,
  `pilot_id` INT(11) UNSIGNED DEFAULT NULL,
  `flight_date` DATE NOT NULL,
  `flight_time` TIME DEFAULT NULL,
  `departure_time` TIME DEFAULT NULL,
  `arrival_time` TIME DEFAULT NULL,
  `status` ENUM('scheduled', 'in_flight', 'completed', 'cancelled', 'delayed') NOT NULL DEFAULT 'scheduled',
  `gate_departure` VARCHAR(10) DEFAULT NULL,
  `gate_arrival` VARCHAR(10) DEFAULT NULL,
  `passengers` INT(11) DEFAULT NULL,
  `notes` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `flight_number_date` (`flight_number`, `flight_date`),
  KEY `route_id` (`route_id`),
  KEY `aircraft_id` (`aircraft_id`),
  KEY `pilot_id` (`pilot_id`),
  KEY `flight_date` (`flight_date`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Beispiel-Flüge
INSERT INTO `flights` (`flight_number`, `route_id`, `aircraft_id`, `pilot_id`, `flight_date`, `flight_time`, `status`, `notes`) VALUES
('RH101', 1, 1, 1, '2026-06-01', '14:00:00', 'scheduled', 'Morgendflug'),
('RH102', 1, 2, 2, '2026-06-01', '18:00:00', 'scheduled', 'Abendflug'),
('RH201', 2, 3, 3, '2026-06-01', '09:00:00', 'scheduled', 'Morgenflug zurück'),
('RH202', 2, 4, 4, '2026-06-01', '15:00:00', 'scheduled', 'Mittagsflug');
