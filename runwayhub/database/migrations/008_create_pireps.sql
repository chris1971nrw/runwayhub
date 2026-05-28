-- Migration 008: create_pireps.sql
-- PIREP-System (Pilot Reports)
-- RunwayHub

CREATE TABLE IF NOT EXISTS `pireps` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `flight_id` INT(11) UNSIGNED DEFAULT NULL,
  `pilot_id` INT(11) UNSIGNED NOT NULL,
  `airport_id` INT(11) UNSIGNED DEFAULT NULL,
  `date` DATE NOT NULL,
  `time` TIME DEFAULT NULL,
  `title` VARCHAR(100) NOT NULL,
  `category` ENUM('weather', 'aircraft', 'incident', 'routine', 'recommendation') NOT NULL DEFAULT 'routine',
  `priority` ENUM('low', 'normal', 'high', 'urgent') NOT NULL DEFAULT 'normal',
  `content` TEXT NOT NULL,
  `weather_conditions` TEXT DEFAULT NULL,
  `flight_conditions` TEXT DEFAULT NULL,
  `aircraft_status` TEXT DEFAULT NULL,
  `recommendations` TEXT DEFAULT NULL,
  `status` ENUM('pending', 'reviewed', 'published', 'archived') NOT NULL DEFAULT 'pending',
  `views` INT(11) DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `flight_id` (`flight_id`),
  KEY `pilot_id` (`pilot_id`),
  KEY `airport_id` (`airport_id`),
  KEY `date` (`date`),
  KEY `category` (`category`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Beispiel-PIREPs
INSERT INTO `pireps` (`flight_id`, `pilot_id`, `airport_id`, `date`, `time`, `title`, `category`, `priority`, `content`, `weather_conditions`, `flight_conditions`, `status`) VALUES
(1, 1, 1, '2026-06-01', '15:30:00', 'Gute Sichtbedingungen', 'routine', 'low', 'Sicht 10km, wolkenfrei, gute Landebahnsicht', 'Sicht 10km', 'Flugzeug in gutem Zustand', 'published');
