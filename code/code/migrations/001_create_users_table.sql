-- Migration: 001_users_table
-- Erstellt: 2026-05-27

CREATE TABLE IF NOT EXISTS `users` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(50) NOT NULL,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `role` ENUM('admin', 'staff', 'pilot', 'guest') NOT NULL DEFAULT 'guest',
    `status` ENUM('active', 'inactive', 'suspended') NOT NULL DEFAULT 'active',
    `avatar` VARCHAR(255) DEFAULT NULL,
    `timezone` VARCHAR(50) DEFAULT 'Europe/Berlin',
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `username` (`username`),
    KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed-Admin-Account (wird in separate Datei)
-- INSERT IGNORE INTO users (username, email, password, role, status)
-- VALUES ('admin', 'admin@runwayhub.de', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'active');
