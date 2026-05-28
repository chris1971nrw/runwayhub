<?php
declare(strict_types=1);

/**
 * RunwayHub - Configuration
 */

return [
    'app' => [
        'name' => 'RunwayHub',
        'version' => '2.0.3',
        'debug' => false,
        'timezone' => 'Europe/Berlin',
    ],
    'database' => require __DIR__ . '/database.php',
    'services' => [
        'flightAware' => [
            'api_key' => getenv('FLIGHTAWARE_API_KEY') ?: '',
            'enabled' => !empty(getenv('FLIGHTAWARE_API_KEY')),
        ],
        'openaip' => [
            'api_key' => getenv('OPENAIP_API_KEY') ?: '',
            'enabled' => !empty(getenv('OPENAIP_API_KEY')),
        ],
    ],
    'features' => [
        'email' => [
            'enabled' => !empty(getenv('SMTP_HOST')),
            'smtp_host' => getenv('SMTP_HOST') ?: '',
            'smtp_port' => (int)(getenv('SMTP_PORT') ?: 587),
            'smtp_user' => getenv('SMTP_USER') ?: '',
            'smtp_pass' => getenv('SMTP_PASS') ?: '',
            'smtp_secure' => getenv('SMTP_SECURE') ?: 'tls',
            'from_email' => getenv('FROM_EMAIL') ?: 'noreply@runwayhub.com',
            'from_name' => getenv('FROM_NAME') ?: 'RunwayHub',
        ],
        'mqtt' => [
            'enabled' => !empty(getenv('MQTT_BROKER_HOST')),
            'broker' => getenv('MQTT_BROKER_HOST') ?: '',
            'port' => (int)(getenv('MQTT_BROKER_PORT') ?: 1883),
            'username' => getenv('MQTT_USERNAME') ?: '',
            'password' => getenv('MQTT_PASSWORD') ?: '',
        ],
    ],
];
