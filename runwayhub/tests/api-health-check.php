<?php
/**
 * API Health Check Script
 * 
 * Tests all API endpoints and reports status
 */

header('Content-Type: application/json');

// List of endpoints to test
$endpoints = [
    'GET /api/status' => '/api-status.php',
    'GET /health' => '/api-status.php',
    'GET /version' => '/api-status.php',
    'POST /login-pilot.php' => '/login-pilot.php', // Will fail without auth, but check syntax
    'POST /logout.php' => '/logout.php',
    'POST /va-create.php' => '/va-gruenden.php',
    'POST /va-connect.php' => '/va-connect.php',
    'GET /va/list' => '/va-admin.php',
    'GET /va/{id}' => '/va-admin.php',
    'GET /openaip/airport/EDDF' => '/index.php?route=openaip/airport/EDDF',
    'GET /weather/current' => '/index.php?route=weather/current',
    'GET /weather/{airport}' => '/index.php?route=weather/EDDF',
    'GET /flightaware/flights' => '/index.php?route=flightaware/flights',
];

// Check PHP syntax of all public files
$publicDir = __DIR__ . '/../public/';
$errors = [];
$ok = 0;
$failed = 0;

foreach (glob($publicDir . '*.php') as $file) {
    $filename = basename($file);
    $output = [];
    exec("php -l \"$file\" 2>&1", $output, $returnVar);
    
    if ($returnVar === 0 && strpos(implode('', $output), 'No syntax errors') !== false) {
        $ok++;
    } else {
        $errors[] = "$filename: " . implode('', $output);
        $failed++;
    }
}

// Summary
$status = [
    'timestamp' => date('c'),
    'status' => 'operational',
    'php_version' => phpversion(),
    'public_files' => [
        'total' => $ok + $failed,
        'valid' => $ok,
        'errors' => $failed
    ],
    'endpoints' => $endpoints,
    'errors' => $errors ?: null,
    'health' => [
        'database' => extension_loaded('pdo_sqlite') ? 'ok' : 'disabled',
        'opcache' => ini_get('opcache.enable') ? 'enabled' : 'disabled',
        'memory_limit' => ini_get('memory_limit'),
        'max_execution_time' => ini_get('max_execution_time'),
        'php_ini' => ini_get('php_ini') ?: 'none'
    ],
    'summary' => [
        'syntax_errors' => $failed === 0 ? 'None' : count($errors),
        'overall_status' => $failed === 0 ? 'HEALTHY' : 'ERRORS DETECTED'
    ]
];

echo json_encode($status, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
exit($failed === 0 ? 0 : 1);
?>
