-- Migration 003: create_airports.sql
-- Flughafen-Verwaltung
-- RunwayHub

CREATE TABLE IF NOT EXISTS `airports` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `iata` VARCHAR(3) NOT NULL UNIQUE,
  `icao` VARCHAR(4) NOT NULL UNIQUE,
  `name` VARCHAR(100) NOT NULL,
  `city` VARCHAR(100) NOT NULL,
  `country` VARCHAR(100) NOT NULL DEFAULT 'Germany',
  `latitude` DECIMAL(10, 6) DEFAULT NULL,
  `longitude` DECIMAL(10, 6) DEFAULT NULL,
  `timezone` VARCHAR(50) DEFAULT 'Europe/Berlin',
  `elevation` INT(11) DEFAULT NULL,
  `website` VARCHAR(255) DEFAULT NULL,
  `notes` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `iata` (`iata`),
  KEY `icao` (`icao`),
  KEY `country` (`country`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Beispiel-Flughäfen
INSERT INTO `airports` (`iata`, `icao`, `name`, `city`, `country`, `latitude`, `longitude`, `timezone`, `elevation`) VALUES
('FRA', 'EDDF', 'Frankfurt Airport', 'Frankfurt', 'Germany', 50.0379, 8.5622, 'Europe/Berlin', 362),
('MUC', 'EDDM', 'Munich Airport', 'München', 'Germany', 48.3538, 11.7861, 'Europe/Berlin', 453),
('HAM', 'EDDH', 'Hamburg Airport', 'Hamburg', 'Germany', 53.6304, 9.9882, 'Europe/Berlin', 13),
('DUS', 'EDDL', 'Düsseldorf Airport', 'Düsseldorf', 'Germany', 51.2895, 6.7668, 'Europe/Berlin', 45),
('CGN', 'EDDK', 'Cologne Bonn Airport', 'Köln', 'Germany', 50.8659, 7.1427, 'Europe/Berlin', 167);
