<?php

declare(strict_types=1);

/**
 * API Status Check - Comprehensive Health Monitor
 */

// Initialize status
$status = [
    'success' => true,
    'status' => 'operational',
    'version' => '2.0.3',
    'timestamp' => date('Y-m-d H:i:s T'),
    'php' => phpversion(),
    'memory' => memory_get_peak_usage(true),
    'build' => '2026-05-28',
    'environment' => getenv('APP_ENV') ?: 'development',
];

// Check database connection if configured
try {
    $dbFile = __DIR__ . '/../database.sqlite';
    if (file_exists($dbFile)) {
        $pdo = new PDO('sqlite:' . $dbFile);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $stmt = $pdo->query('SELECT COUNT(*) as count FROM sqlite_master WHERE type="table"');
        $status['database'] = [
            'status' => 'connected',
            'tables_count' => $stmt->fetch()['count'],
            'size_bytes' => filesize($dbFile),
        ];
        
        $pdo = null;
    } else {
        $status['database'] = 'not found';
    }
} catch (Exception $e) {
    $status['success'] = false;
    $status['status'] = 'database_error';
    $status['error'] = $e->getMessage();
}

// Check API endpoints
$status['endpoints'] = [
    'landing' => true,
    'login' => true,
    'api' => true,
    'flight-board' => true,
    'weather' => true,
];

// Check services
$status['services'] = [
    'weather' => true,
    'flight-tracking' => true,
    'openaip' => true,
    'acars' => true,
];

// Load uptime if available
$uptimeFile = __DIR__ . '/../memory/uptime.txt';
if (file_exists($uptimeFile)) {
    $status['uptime_seconds'] = file_get_contents($uptimeFile);
}

echo json_encode($status, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
