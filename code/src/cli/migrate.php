<?php
declare(strict_types=1);

namespace RunwayHub\Cli;

use PDOException;

/**
 * Migration CLI Tool
 */
require_once __DIR__ . '/../../config/database.php';

$config = require __DIR__ . '/../../config/database.php';

try {
    $db = new \RunwayHub\Core\Database($config['default']);
    
    echo "\n=== RunwayHub Database Migration ===\n\n";
    
    // Read migration files
    $migrationDir = __DIR__ . '/../../migrations/';
    $files = glob($migrationDir . '*.sql');
    sort($files);
    
    $migrated = 0;
    
    foreach ($files as $file) {
        $filename = basename($file);
        echo "Executing: $filename\n";
        
        $sql = file_get_contents($file);
        
        // Execute migrations (skip already executed if you add check logic)
        try {
            $db->execute($sql);
            echo "  ✓ Successfully executed\n";
            $migrated++;
        } catch (PDOException $e) {
            echo "  ✗ Error: " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n=== Migration Complete ===\n";
    echo "Migrated: $migrated / " . count($files) . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
