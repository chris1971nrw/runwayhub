<?php
declare(strict_types=1);

namespace RunwayHub\Cli;

/**
 * Reset CLI Tool - Clear all tables
 */
require_once __DIR__ . '/../../config/database.php';

$config = require __DIR__ . '/../../config/database.php';

echo "\n=== RunwayHub Database Reset ===\n\n";
echo "WARNING: This will DELETE ALL DATA!\n";
echo "Do you want to continue? (yes/no): ";

$answer = trim(fgets(STDIN));

if ($answer !== 'yes') {
    echo "\nReset cancelled.\n";
    exit(0);
}

try {
    $db = new \RunwayHub\Core\Database($config['default']);
    
    $tables = [
        'aircrafts',
        'airlines',
        'airports',
        'bookings',
        'flights',
        'pilots',
        'pireps',
        'routes',
        'users',
    ];
    
    foreach ($tables as $table) {
        $sql = sprintf("TRUNCATE TABLE %s", $table);
        $db->execute($sql);
        echo "  ✓ Cleared: $table\n";
    }
    
    echo "\n=== Reset Complete ===\n";
    echo "Database has been cleared. Run migration and seed again.\n";
    
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage() . "\n";
    exit(1);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
