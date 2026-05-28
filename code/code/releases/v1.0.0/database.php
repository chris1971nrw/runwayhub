<?php
declare(strict_types=1);

// Database configuration - Use MySQL if available, fallback to SQLite
$dbPath = getenv('DB_DATABASE') ?: '/home/christoph/.openclaw/workspace-runwayhub/runwayhub/database.sqlite';

return [
    'default' => [
        // Try SQLite first, fallback to MySQL if sqlite not available
        'driver' => extension_loaded('pdo_sqlite') ? 'sqlite' : 'mysql',
        'path' => $dbPath,
        
        'host' => extension_loaded('pdo_sqlite') ? null : ('localhost'),
        'port' => extension_loaded('pdo_sqlite') ? null : (3306),
        'database' => extension_loaded('pdo_sqlite') ? null : ('runwayhub'),
        'username' => extension_loaded('pdo_sqlite') ? null : (getenv('DB_USERNAME') ?: 'root'),
        'password' => extension_loaded('pdo_sqlite') ? null : (getenv('DB_PASSWORD') ?: ''),
        'charset' => extension_loaded('pdo_sqlite') ? 'utf8' : ('utf8mb4'),
        
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ],
    ],
];
