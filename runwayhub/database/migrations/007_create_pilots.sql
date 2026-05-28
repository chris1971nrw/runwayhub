-- Migration 007: create_pilots.sql
-- Piloten-Verwaltung
-- RunwayHub

CREATE TABLE IF NOT EXISTS `pilots` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `full_name` VARCHAR(100) NOT NULL,
  `license_type` ENUM('ATPL', 'FPL', 'B1', 'B787', 'BAE146') DEFAULT 'ATPL',
  `license_number` VARCHAR(50) DEFAULT NULL,
  `issued_date` DATE DEFAULT NULL,
  `expiry_date` DATE DEFAULT NULL,
  `total_flight_hours` INT(11) DEFAULT 0,
  `experience_years` INT(11) DEFAULT 0,
  `status` ENUM('active', 'inactive', 'retired') NOT NULL DEFAULT 'active',
  `availability` VARCHAR(255) DEFAULT NULL,
  `certifications` TEXT DEFAULT NULL,
  `notes` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `status` (`status`),
  KEY `license_type` (`license_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Beispiel-Piloten
INSERT INTO `pilots` (`username`, `email`, `full_name`, `license_type`, `license_number`, `issued_date`, `expiry_date`, `total_flight_hours`, `experience_years`, `status`, `certifications`, `notes`) VALUES
('pilot1', 'pilot1@runwayhub.de', 'Max Mustermann', 'ATPL', '12345678', '2020-01-01', '2030-01-01', 3500, 8, 'active', 'Type Rating A320, B737', 'Erfahrener Pilot'),
('pilot2', 'pilot2@runwayhub.de', 'Anna Meyer', 'ATPL', '87654321', '2019-06-15', '2029-06-15', 2800, 6, 'active', 'Type Rating A320, A319', 'Sicherheitsbeauftragter'),
('pilot3', 'pilot3@runwayhub.de', 'Thomas Schmidt', 'FPL', '11223344', '2021-03-10', '2026-03-10', 1200, 4, 'active', 'Type Rating B737', 'Erfahrene Co-Pilotin');
