-- Migration 009: create_roles_permissions.sql
-- Rollen & Rechte
-- RunwayHub

-- Rollen-Tabelle
CREATE TABLE IF NOT EXISTS `roles` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL UNIQUE,
  `description` VARCHAR(255) DEFAULT NULL,
  `permissions` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Berechtigungen-Tabelle
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL UNIQUE,
  `description` VARCHAR(255) DEFAULT NULL,
  `resource` VARCHAR(100) DEFAULT NULL,
  `action` VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Berechtigungen für Ressourcen
INSERT INTO `permissions` (`name`, `description`, `resource`, `action`) VALUES
('aircrafts_view', 'Flugzeuge ansehen', 'aircrafts', 'view'),
('aircrafts_create', 'Flugzeuge erstellen', 'aircrafts', 'create'),
('aircrafts_edit', 'Flugzeuge bearbeiten', 'aircrafts', 'edit'),
('aircrafts_delete', 'Flugzeuge löschen', 'aircrafts', 'delete'),
('flights_view', 'Flüge ansehen', 'flights', 'view'),
('flights_create', 'Flüge erstellen', 'flights', 'create'),
('flights_edit', 'Flüge bearbeiten', 'flights', 'edit'),
('flights_delete', 'Flüge löschen', 'flights', 'delete'),
('pilots_view', 'Piloten ansehen', 'pilots', 'view'),
('pilots_create', 'Piloten erstellen', 'pilots', 'create'),
('pilots_edit', 'Piloten bearbeiten', 'pilots', 'edit'),
('pilots_delete', 'Piloten löschen', 'pilots', 'delete'),
('bookings_view', 'Buchungen ansehen', 'bookings', 'view'),
('bookings_create', 'Buchungen erstellen', 'bookings', 'create'),
('bookings_edit', 'Buchungen bearbeiten', 'bookings', 'edit'),
('bookings_delete', 'Buchungen löschen', 'bookings', 'delete'),
('pireps_view', 'PIREPs ansehen', 'pireps', 'view'),
('pireps_create', 'PIREPs erstellen', 'pireps', 'create'),
('pireps_edit', 'PIREPs bearbeiten', 'pireps', 'edit'),
('pireps_delete', 'PIREPs löschen', 'pireps', 'delete'),
('reports_view', 'Reports ansehen', 'reports', 'view'),
('reports_export', 'Reports exportieren', 'reports', 'export');

-- Rollen mit Berechtigungen
INSERT INTO `roles` (`name`, `description`, `permissions`) VALUES
('admin', 'Administrator mit Vollzugriff', '[
  "aircrafts_view",
  "aircrafts_create",
  "aircrafts_edit",
  "aircrafts_delete",
  "flights_view",
  "flights_create",
  "flights_edit",
  "flights_delete",
  "pilots_view",
  "pilots_create",
  "pilots_edit",
  "pilots_delete",
  "bookings_view",
  "bookings_create",
  "bookings_edit",
  "bookings_delete",
  "pireps_view",
  "pireps_create",
  "pireps_edit",
  "pireps_delete",
  "reports_view",
  "reports_export"
]'),
('staff', 'Personal mit edit-Rechten', '[
  "aircrafts_view",
  "aircrafts_edit",
  "flights_view",
  "flights_create",
  "flights_edit",
  "pilots_view",
  "bookings_view",
  "bookings_create",
  "reports_view"
]'),
('pilot', 'Pilot mit Logbuch-Zugang', '[
  "flights_view",
  "flights_create",
  "flights_edit",
  "pireps_view",
  "pireps_create"
]'),
('guest', 'Gast mit Lesezugriff', '[
  "flights_view",
  "pireps_view",
  "reports_view"
]');
