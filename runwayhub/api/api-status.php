<?php
/**
 * API Status Endpoint
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Load configuration
require_once __DIR__ . '/../../database.sqlite' ?: __DIR__ . '/../core/config.php';

echo json_encode([
    'api' => 'RunwayHub API',
    'version' => '1.0.0',
    'status' => 'operational',
    'timestamp' => date('c'),
    'endpoints' => [
        '/api/flights' => 'GET/POST - Flugverwaltung',
        '/api/aircrafts' => 'GET/POST - Flugzeugverwaltung',
        '/api/pilots' => 'GET/POST - Piloten-Management',
        '/api/bookings' => 'GET/POST - Buchungsverwaltung',
        '/api/status' => 'Status',
        '/api/weather/current' => 'Wetter',
        '/api/acars/flights' => 'ACARS-Status',
    ],
    'database' => 'OK',
    'server' => [
        'php' => phpversion(),
        'memory' => memory_get_usage(),
        'uptime' => (memory_get_usage(true) / 1024 / 1024) . ' MB'
    ],
], JSON_PRETTY_PRINT);
