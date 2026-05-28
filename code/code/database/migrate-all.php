<?php
/**
 * RunwayHub Database Migration Runner
 * Applies all pending migrations to the SQLite database
 */

$db = new SQLite3(__DIR__ . '/../../database.sqlite');
$db->enableExceptions(true);

// Get list of all migration files
$migrationsDir = __DIR__ . '/';
$migrationFiles = glob($migrationsDir . '0*.php');

// Get already applied migrations from sqlite_sequence
$appliedMigrations = [];
$result = $db->exec("SELECT name FROM sqlite_sequence WHERE name LIKE '0%'");
while ($row = $result->fetchArray()) {
    $appliedMigrations[] = $row['name'];
}

// Run each migration
foreach ($migrationFiles as $file) {
    $filename = basename($file, '.php');
    
    // Check if already applied
    if (in_array($filename, $appliedMigrations)) {
        echo "✓ Migration $filename already applied\n";
        continue;
    }
    
    echo "Running migration: $filename...\n";
    
    try {
        include $file;
        $appliedMigrations[] = $filename;
        echo "✓ Migration $filename completed\n";
    } catch (Exception $e) {
        echo "✗ Migration $filename failed: " . $e->getMessage() . "\n";
    }
}

// Update sqlite_sequence
$statement = $db->prepare('INSERT OR IGNORE INTO sqlite_sequence (name, seq) VALUES (?, 0)');
foreach ($appliedMigrations as $migration) {
    $statement->bindValue(1, $migration, SQLITE3_TEXT);
    $statement->execute();
}

// List all tables
$tables = $db->query("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name");
echo "\nMigration complete!\n";
echo "Applied migrations: " . count($appliedMigrations) . "\n\n";
echo "Database tables:\n";
while ($table = $tables->fetchArray()) {
    echo "  - " . $table['name'] . "\n";
}

$db->close();
?>
