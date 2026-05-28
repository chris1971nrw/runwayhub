-- Migration 002: create_aircrafts.sql
-- Flugzeug-Verwaltung
-- RunwayHub

CREATE TABLE IF NOT EXISTS `aircrafts` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `registration` VARCHAR(20) NOT NULL UNIQUE,
  `type` VARCHAR(50) NOT NULL,
  `manufacturer` VARCHAR(100) NOT NULL,
  `serial_number` VARCHAR(50) DEFAULT NULL,
  `year` INT(4) DEFAULT NULL,
  `seats` INT(11) DEFAULT NULL,
  `status` ENUM('active', 'maintenance', 'retired', 'reserve') NOT NULL DEFAULT 'active',
  `owner` VARCHAR(255) DEFAULT NULL,
  `notes` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `type` (`type`),
  KEY `owner` (`owner`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Beispiel-Flugzeuge
INSERT INTO `aircrafts` (`registration`, `type`, `manufacturer`, `serial_number`, `year`, `seats`, `status`, `owner`, `notes`) VALUES
('D-ABCA', 'A320', 'Airbus', '1234', 2018, 160, 'active', 'RunwayHub Airlines', 'Standard A320'),
('D-ABCB', 'A320', 'Airbus', '1235', 2018, 160, 'active', 'RunwayHub Airlines', 'Standard A320'),
('D-ABDC', 'B737', 'Boeing', '2345', 2019, 140, 'active', 'RunwayHub Airlines', 'B737-800'),
('D-ABED', 'A319', 'Airbus', '1236', 2017, 120, 'maintenance', 'RunwayHub Airlines', 'Wartung bis Oktober 2026'),
('D-ABFE', 'B787', 'Boeing', '3456', 2020, 290, 'active', 'RunwayHub Airlines', 'Boeing 787 Dreamliner');
