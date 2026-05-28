<?php

declare(strict_types=1);

/**
 * RunwayHub Configuration Example
 * 
 * Copy this file to config.php and configure for your environment.
 */

// Application
return [
    'app' => [
        'name' => 'RunwayHub',
        'version' => '2.0.3',
        'debug' => false,
        'timezone' => 'Europe/Berlin',
        'locale' => 'de_DE',
    ],
    
    // Database
    'database' => [
        'driver' => 'sqlite',
        'database' => 'runwayhub.sqlite',
        'prefix' => '',
        'foreign_key_constraints' => true,
    ],
    
    // Cache
    'cache' => [
        'driver' => 'file',
        'path' => storage_path('cache'),
        'ttl' => 300, // 5 minutes
    ],
    
    // Weather API
    'weather' => [
        'provider' => 'wttrin', // wttrin, openmeteo, flightaware
        'cache_ttl' => 300, // 5 minutes
        'default_airport' => 'EDDF',
    ],
    
    // Flight Tracking
    'flight' => [
        'provider' => 'flightaware',
        'api_key' => getenv('FLIGHT_AWARE_API_KEY'),
        'cache_ttl' => 60, // 1 minute
    ],
    
    // SMTP
    'smtp' => [
        'enabled' => false,
        'host' => getenv('SMTP_HOST'),
        'port' => 587,
        'username' => getenv('SMTP_USERNAME'),
        'password' => getenv('SMTP_PASSWORD'),
        'from' => getenv('SMTP_FROM'),
    ],
    
    // MQTT
    'mqtt' => [
        'enabled' => false,
        'broker' => getenv('ACARS_MQTT_BROKER'),
        'client_id' => uniqid(),
        'tls' => true,
    ],
    
    // Security
    'security' => [
        'bcrypt_cost' => 12,
        'session_lifetime' => 86400, // 24 hours
        'csrf_token_lifetime' => 3600, // 1 hour
    ],
    
    // Rate Limiting
    'rate_limit' => [
        'enabled' => true,
        'requests' => 100,
        'period' => 60, // 60 seconds
    ],
    
    // Storage
    'storage' => [
        'driver' => 'local',
        'root' => storage_path('app'),
    ],
    
    // Logging
    'logging' => [
        'driver' => 'single',
        'path' => storage_path('logs/laravel.log'),
        'level' => 'debug',
        'channels' => [
            'stack' => [
                'single' => [
                    'driver' => 'single',
                    'path' => storage_path('logs/laravel.log'),
                    'level' => 'debug',
                ],
            ],
            'daily' => [
                'driver' => 'daily',
                'path' => storage_path('logs/daily.log'),
                'level' => 'debug',
                'days' => 14,
            ],
        ],
    ],
];