<?php
/**
 * Database connection configuration for RunwayHub.
 * Handles SQLite connection and establishes a PDO instance.
 */

$dbPath = __DIR__ . '/database.sqlite';

try {
    // Validate path
    if (!file_exists($dbPath)) {
        error_log("CRITICAL: Database file not found at " . $dbPath);
    }

    $dsn = "sqlite:" . $dbPath;
    $pdo = new PDO($dsn);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Enable foreign keys for SQLite
    $pdo->exec("PRAGMA foreign_keys = ON;");
} catch (PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());
    die("System Error: Database connection could not be established.");
}

/**
 * Returns the PDO instance.
 * @return \PDO
 */
function getDB(): PDO {
    global $pdo;
    if ($pdo === null) {
        // Fallback if global isn't set or initialization fails
        $dsn = "sqlite:" . __DIR__ . "/database.sqlite";
        $pdo = new PDO($dsn);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }
    return $pdo;
}
?>