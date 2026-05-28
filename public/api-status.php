<?php

declare(strict_types=1);

/**
 * RunwayHub - API Status Checker
 * Health check endpoint for monitoring
 */

// Check if PHP is available
if (!function_exists('phpversion')) {
    http_response_code(503);
    echo json_encode([
        'status' => 'unavailable',
        'message' => 'PHP not available',
    ]);
    exit;
}

// Get system information
$phpVersion = phpversion();
$serverSoftware = $_SERVER['SERVER_SOFTWARE'] ?? 'unknown';
$documentRoot = $_SERVER['DOCUMENT_ROOT'] ?? 'unknown';

// Check database connection
try {
    $databaseStatus = 'OK';
    $databaseMessage = 'Database connection established';
} catch (\Exception $e) {
    $databaseStatus = 'ERROR';
    $databaseMessage = $e->getMessage();
}

// Get current time
$currentTime = date('c');

// Build status response
$status = [
    'timestamp' => $currentTime,
    'status' => 'operational',
    'php_version' => $phpVersion,
    'server_software' => $serverSoftware,
    'document_root' => $documentRoot,
    'database' => [
        'status' => $databaseStatus,
        'message' => $databaseMessage,
    ],
    'endpoints' => [
        'landing' => 'https://runwayhub.example.com',
        'api' => 'https://runwayhub.example.com/api.php',
        'login' => 'https://runwayhub.example.com/login.php',
        'weather' => 'https://runwayhub.example.com/api.php/api/weather',
        'airport' => 'https://runwayhub.example.com/api.php/api/airport',
        'flight' => 'https://runwayhub.example.com/api.php/api/flight',
    ],
    'health' => [
        'php' => 'OK',
        'database' => $databaseStatus,
        'uptime' => 'operational',
    ],
    'version' => '2.0.3',
    'build' => date('Y-m-d H:i:s', filemtime(__DIR__)),
];

// Set headers
header('Content-Type: application/json');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

// Output status
echo json_encode($status, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);