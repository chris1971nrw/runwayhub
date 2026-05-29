<?php

declare(strict_types=1);

/**
 * Automated Database Backup Script
 * 
 * Creates SQLite database backups with timestamp and compression.
 * 
 * Usage: php database_backup.php [--compress] [--destination=/path]
 */

require_once __DIR__ . '/../../database.sqlite';

// Default settings
$config = [
    'database' => __DIR__ . '/../../database.sqlite',
    'backup_dir' => __DIR__ . '/../../backups',
    'compress' => getenv('BACKUP_COMPRESS') === '1',
    'retention_days' => getenv('BACKUP_RETENTION_DAYS') ?: 30,
    'log_file' => __DIR__ . '/../../backups/backup.log',
];

// Create backup directory if needed
if (!is_dir($config['backup_dir'])) {
    mkdir($config['backup_dir'], 0755, true);
    echo "Created backup directory: {$config['backup_dir']}" . PHP_EOL;
}

// Generate backup filename
$timestamp = date('Y-m-d_His');
$filename = "runwayhub_backup_{$timestamp}.sqlite";

// Create backup copy
$backup_path = "{$config['backup_dir']}/{$filename}";

if (!copy($config['database'], $backup_path)) {
    echo "❌ Backup failed: Could not create {$backup_path}" . PHP_EOL;
    exit(1);
}

echo '=== Database Backup Complete ===' . PHP_EOL;
echo "Source: {$config['database']}" . PHP_EOL;
echo "Backup: {$backup_path}" . PHP_EOL;
echo "Size: " . filesize($backup_path) . " bytes" . PHP_EOL;
echo "Timestamp: {$timestamp}" . PHP_EOL;

// Compress if requested
if ($config['compress']) {
    $compress_path = str_replace('.sqlite', '.sqlite.gz', $backup_path);
    
    exec("gzip -f '{$backup_path}'");
    
    if (is_file($compress_path)) {
        echo "Compressed: {$compress_path}" . PHP_EOL;
        echo "Compressed size: " . filesize($compress_path) . " bytes" . PHP_EOL;
        
        // Remove uncompressed backup
        if (file_exists($backup_path)) {
            unlink($backup_path);
            echo "Removed uncompressed backup" . PHP_EOL;
        }
    }
}

// Cleanup old backups
echo '' . PHP_EOL;
echo 'Cleanup: Removing backups older than ' . $config['retention_days'] . ' days...' . PHP_EOL;
$cleanup_count = 0;

$old_files = glob("{$config['backup_dir']}/runwayhub_backup_*.sqlite.gz");
foreach ($old_files as $old_file) {
    $mtime = filemtime($old_file);
    $age_days = floor((time() - $mtime) / 86400);
    
    if ($age_days > $config['retention_days']) {
        unlink($old_file);
        echo "  Removed: " . basename($old_file) . " ({$age_days} days old)" . PHP_EOL;
        $cleanup_count++;
    }
}

echo "Cleaned up {$cleanup_count} old backup(s)" . PHP_EOL;

// Log backup
$backup_info = [
    'timestamp' => date('Y-m-d H:i:s'),
    'source' => $config['database'],
    'backup' => is_file($compress_path) ? $compress_path : $backup_path,
    'compressed' => is_file($compress_path),
    'size' => is_file($compress_path) ? filesize($compress_path) : filesize($backup_path) ?: 0,
    'retention' => $config['retention_days'] . ' days',
];

file_put_contents($config['log_file'], json_encode($backup_info) . PHP_EOL, FILE_APPEND);

echo PHP_EOL;
echo '=== Backup Status: SUCCESS ===' . PHP_EOL;
echo "Total backups in directory: " . count(glob("{$config['backup_dir']}/runwayhub_backup_*.gz")) . PHP_EOL;
echo "Oldest backup: " . (glob("{$config['backup_dir']}/runwayhub_backup_*.gz") ? min(array_map('filemtime', glob("{$config['backup_dir']}/runwayhub_backup_*.gz"))) : 'None' . PHP_EOL;
echo "Latest backup: " . (glob("{$config['backup_dir']}/runwayhub_backup_*.gz") ? max(array_map('filemtime', glob("{$config['backup_dir']}/runwayhub_backup_*.gz"))) : 'None' . PHP_EOL;
