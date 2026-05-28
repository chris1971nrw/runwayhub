<?php
/**
 * Admin User Initialisierung
 */

require_once __DIR__ . '/../src/core/Database.php';

$database = new Database(DB_PATH);

echo "🔐 Admin-Account initialisieren...\n\n";

// Admin Tabelle erstellen
$database->query("
    CREATE TABLE IF NOT EXISTS admins (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        email VARCHAR(100),
        role VARCHAR(20) DEFAULT 'admin',
        active BOOLEAN DEFAULT 1,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );
");

// Admin user einfügen
$username = 'admin';
$password = 'admin123';
$email = 'admin@example.com';

$adminHash = password_hash($password, PASSWORD_DEFAULT);

$database->query("
    INSERT OR REPLACE INTO admins (username, password, email, role)
    VALUES (?, ?, ?, ?);
", [$username, $adminHash, $email, 'admin']);

echo "✅ Admin-Account erstellt:\n";
echo "   Benutzer: {$username}\n";
echo "   E-Mail: {$email}\n";
echo "   Passwort: {$password}\n";
echo "   Rolle: admin\n\n";

echo "⚠️  Bitte ändern Sie das Passwort nach dem ersten Login!\n";
echo "   E-Mail: {$email}\n";
