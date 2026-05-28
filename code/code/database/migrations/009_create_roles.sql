-- Migration 009: create_roles.sql
-- Rollen & Rechte
-- RunwayHub (SQLite compatible)

CREATE TABLE IF NOT EXISTS roles (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  name VARCHAR(50) NOT NULL UNIQUE,
  description TEXT,
  permissions TEXT DEFAULT '[]',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS role_users (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  role_id INTEGER NOT NULL,
  user_id INTEGER NOT NULL,
  assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE(role_id, user_id),
  FOREIGN KEY(role_id) REFERENCES roles(id) ON DELETE CASCADE,
  FOREIGN KEY(user_id) REFERENCES admins(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS permissions (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  name VARCHAR(50) NOT NULL UNIQUE,
  description TEXT DEFAULT NULL
);

-- Default roles
INSERT INTO roles (name, description, permissions) VALUES
('admin', 'Administrative access', '["flights:create", "flights:read", "flights:update", "flights:delete", "aircrafts:all", "pilots:all", "bookings:all", "bookings:read"]'),
('airline_manager', 'Airline management', '["flights:read", "flights:update", "flights:delete", "aircrafts:read", "flights:create"]'),
('pilot', 'Pilot access', '["flights:read", "bookings:read", "pireps:create"]'),
('ground_staff', 'Ground operations', '["flights:read", "flights:update", "bookings:read"]'),
('guest', 'Read-only access', '["flights:read", "bookings:read"]');

-- Default permissions
INSERT INTO permissions (name, description) VALUES
('flights:read', 'View flight information'),
('flights:create', 'Create new flights'),
('flights:update', 'Update flight information'),
('flights:delete', 'Delete flights'),
('flights:manage', 'Full flight management'),
('aircrafts:read', 'View aircraft fleet'),
('aircrafts:create', 'Add new aircraft'),
('aircrafts:update', 'Update aircraft information'),
('aircrafts:delete', 'Remove aircraft'),
('aircrafts:manage', 'Full aircraft management'),
('pilots:read', 'View pilot directory'),
('pilots:create', 'Add new pilots'),
('pilots:update', 'Update pilot information'),
('pilots:delete', 'Remove pilots'),
('pilots:manage', 'Full pilot management'),
('bookings:read', 'View bookings'),
('bookings:create', 'Create bookings'),
('bookings:update', 'Update bookings'),
('bookings:delete', 'Cancel bookings'),
('pireps:read', 'View PIREPs'),
('pireps:create', 'Submit PIREPs'),
('reports:read', 'Access reports'),
('reports:create', 'Create reports'),
('settings:read', 'View settings'),
('settings:update', 'Modify settings'),
('reports:manage', 'Full reporting access'),
('settings:manage', 'Full settings access');
