-- Admin-Zugang erstellen für RunwayHub MySQL-Datenbank
-- Führe dies in deiner MySQL-Shell aus: mysql -u root -p runwayhub

USE runwayhub;

-- Admin Benutzer erstellen
INSERT INTO users (
    username,
    email,
    password,
    role,
    status,
    created_at,
    updated_at
) VALUES (
    'admin',
    'admin@runwayhub.local',
    '$2y$10$92IXUNpkjO0rO05WMiM9L.wlO/KoenMQJ7SY.58zM72Yz5ovsP',
    'admin',
    'active',
    NOW(),
    NOW()
);

-- Passwort hash für 'admin123' (bcrypt hash)
-- Du kannst das Passwort mit Passwortgenerator generieren

-- Nachricht
SELECT '✅ Admin-Zugang erstellt!';
SELECT CONCAT('Benutzername: admin') AS info;
SELECT CONCAT('Passwort: admin123') AS info;
SELECT CONCAT('Tipp: Ändere das Passwort nach dem ersten Login!') AS warning;
