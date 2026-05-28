<?php

// .env laden
if (file_exists(__DIR__ . '/.env')) {
    include __DIR__ . '/.env';
}

// SQLite aktivieren
if (!extension_loaded('sqlite3')) {
    // SQLite3 Extension aktivieren
    $sqlite = new SQLite3(__DIR__ . '/database.sqlite', SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
    $sqlite->enableExceptions(true);
    
    // Tabelle users erstellen
    $sqlite->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username VARCHAR(50) NOT NULL UNIQUE,
            email VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            role VARCHAR(20) NOT NULL DEFAULT 'user',
            status VARCHAR(20) NOT NULL DEFAULT 'active',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");
    
    // Admin Benutzer erstellen
    $sqlite->exec("
        INSERT OR IGNORE INTO users (username, email, password, role, status)
        VALUES ('admin', 'admin@runwayhub.local', '\$2y\$10\$92IXUNpkjO0rO05WMiM9L.wlO/KoenMQJ7SY.58zM72Yz5ovsP', 'admin', 'active')
    ");
    
    // Klassen für das Framework simulieren
    class Database {
        private $sqlite;
        public function __construct() { $this->sqlite = sqlite3_open(__DIR__ . '/database.sqlite'); }
        public function query($sql) { return $this->sqlite->exec($sql); }
        public function fetch($sql) { $res = $this->sqlite->query($sql); return $res->fetchArray(); }
        public function fetchAll($sql) { return $this->sqlite->query($sql)->fetchAll(); }
        public function fetchOne($sql) { $res = $this->sqlite->query($sql); return $res->fetchArray(); }
    }
    
    return new Database();
}

// Falls SQLite3 nicht verfügbar
$pdo = new PDO('sqlite:' . __DIR__ . '/database.sqlite');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Tabelle erstellen
try {
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username VARCHAR(50) NOT NULL UNIQUE,
            email VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            role VARCHAR(20) NOT NULL DEFAULT 'user',
            status VARCHAR(20) NOT NULL DEFAULT 'active',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");
} catch (PDOException $e) {}

// Admin Benutzer
try {
    $pdo->exec("
        INSERT OR IGNORE INTO users (username, email, password, role, status)
        VALUES ('admin', 'admin@runwayhub.local', '\$2y\$10\$92IXUNpkjO0rO05WMiM9L.wlO/KoenMQJ7SY.58zM72Yz5ovsP', 'admin', 'active')
    ");
} catch (PDOException $e) {}

// Klasse simulieren
class Database {
    private $pdo;
    public function __construct() { $this->pdo = $pdo; }
    public function query($sql, $params = []) { $stmt = $this->pdo->prepare($sql); $stmt->execute($params); return $stmt; }
    public function fetch($sql, $params = []) { $stmt = $this->pdo->prepare($sql); $stmt->execute($params); return $stmt->fetch(PDO::FETCH_ASSOC); }
    public function fetchAll($sql, $params = []) { $stmt = $this->pdo->prepare($sql); $stmt->execute($params); return $stmt->fetchAll(PDO::FETCH_ASSOC); }
}

return new Database();
