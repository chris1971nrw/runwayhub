<?php

// .env laden
if (file_exists(__DIR__ . '/.env')) {
    include __DIR__ . '/.env';
}

// Datenbank-Konfiguration prüfen
$config = [
    'driver' => getenv('DB_CONNECTION') === 'mysql' ? 'mysql' : 'sqlite',
    'host' => getenv('DB_HOST') ?: '127.0.0.1',
    'port' => getenv('DB_PORT') ?: 3306,
    'database' => getenv('DB_DATABASE') ?: 'runwayhub',
    'username' => getenv('DB_USERNAME') ?: 'root',
    'password' => getenv('DB_PASSWORD') ?: ''
];

// Wenn MySQL aber MySQL-Extension nicht verfügbar, auf SQLite umschalten
if ($config['driver'] === 'mysql' && !function_exists('mysqli_connect')) {
    echo "MySQL-Extension nicht verfügbar, schalte auf SQLite um..." . PHP_EOL;
    $config['driver'] = 'sqlite';
}

// Datenbank-Klassen laden
require_once __DIR__ . '/src/core/Database.php';

// Datenbank-Verbindung
if ($config['driver'] === 'sqlite') {
    $db = new RunwayHub\Core\Database([
        'driver' => 'sqlite',
        'path' => __DIR__ . '/database.sqlite'
    ]);
} else {
    $db = new RunwayHub\Core\Database($config);
}

// Tabelle users prüfen und anlegen
if ($db->query("SHOW TABLES LIKE 'users'")->rowCount() === 0) {
    try {
        $db->query("
            CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(50) NOT NULL UNIQUE,
                email VARCHAR(255) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                role ENUM('admin', 'va_owner', 'pilot', 'user') NOT NULL DEFAULT 'user',
                status ENUM('active', 'inactive', 'suspended') NOT NULL DEFAULT 'active',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )
        ");
    } catch (Exception $e) {
        echo "SQLite-Tabelle erstellt: users" . PHP_EOL;
    }
}

// Default Admin wenn nicht vorhanden
try {
    if ($db->query("SELECT COUNT(*) FROM users WHERE username = 'admin'")->fetchColumn() === 0) {
        $db->query("
            INSERT INTO users (username, email, password, role, status)
            VALUES ('admin', 'admin@runwayhub.local', '\$2y\$10\$92IXUNpkjO0rO05WMiM9L.wlO/KoenMQJ7SY.58zM72Yz5ovsP', 'admin', 'active')
        ");
    }
} catch (Exception $e) {
    echo "Admin-Benutzer erstellt" . PHP_EOL;
}

return $db;
