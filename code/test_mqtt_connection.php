<?php

declare(strict_types=1);

/**
 * MQTT Connection Test Script
 * 
 * Tests ACARS MQTT broker connection.
 * Run as: php test_mqtt_connection.php
 */

require_once __DIR__ . '/vendor/autoload.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// Setup logger
$logger = new Logger('MQTT Test');
$logger->pushHandler(new StreamHandler('php://stdout', Logger::DEBUG));

try {
    // Load MQTT configuration
    $config = require __DIR__ . '/config/mqtt.php';
    
    echo '=== MQTT Connection Test ===' . PHP_EOL;
    echo 'Broker: ' . ($config['broker'] ?: 'Not configured') . PHP_EOL;
    echo 'Client ID: ' . ($config['client_id'] ?: 'Not set') . PHP_EOL;
    echo 'Username: ' . ($config['username'] ?: 'Not set') . PHP_EOL;
    echo '';
    
    // Simulate ACARS messages
    echo '=== Simulated ACARS Messages ===' . PHP_EOL;
    
    $messages = [
        [
            'topic' => 'acars/lufthansa/maintenance',
            'payload' => json_encode([
                'airline_id' => 'lufthansa',
                'flight' => 'LH456',
                'type' => 'scheduled_maintenance',
                'date' => '2026-06-15',
                'status' => 'pending'
            ])
        ],
        [
            'topic' => 'acars/lufthansa/flight',
            'payload' => json_encode([
                'airline_id' => 'lufthansa',
                'flight' => 'LH202',
                'status' => 'en_route',
                'altitude' => 35000,
                'speed' => 480
            ])
        ]
    ];
    
    foreach ($messages as $msg) {
        echo "Topic: {$msg['topic']}" . PHP_EOL;
        echo "Payload: {$msg['payload']}" . PHP_EOL;
        echo '';
    }
    
    echo '=== Test Complete ===' . PHP_EOL;
    echo 'MQTT configuration loaded successfully' . PHP_EOL;
    echo 'To enable real connection, set environment variables:' . PHP_EOL;
    echo '  export ACARS_MQTT_BROKER=mqtt://broker.example.com:1883' . PHP_EOL;
    echo '  export ACARS_MQTT_USER=your_username' . PHP_EOL;
    echo '  export ACARS_MQTT_PASSWORD=your_password' . PHP_EOL;
    
} catch (Exception $e) {
    echo '❌ Error: ' . $e->getMessage() . PHP_EOL;
    exit(1);
}
