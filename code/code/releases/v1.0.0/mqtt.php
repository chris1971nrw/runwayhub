<?php

declare(strict_types=1);

/**
 * MQTT Configuration for ACARS
 * 
 * Konfiguration für ACARS MQTT-Broker Verbindung
 */

return [
    'broker' => getenv('ACARS_MQTT_BROKER') ?: 'mqtt://localhost:1883',
    'client_id' => 'runwayhub_acars_client',
    'username' => getenv('ACARS_MQTT_USER') ?: null,
    'password' => getenv('ACARS_MQTT_PASSWORD') ?: null,
    'keepalive' => 60,
    'clean_session' => true,
    
    'topics' => [
        'messages' => 'acars/{airline_id}/messages',
        'flights' => 'acars/{airline_id}/flights',
        'pirep' => 'acars/{airline_id}/pirep',
        'maintenance' => 'acars/{airline_id}/maintenance',
        'security' => 'acars/{airline_id}/security',
    ],
    
    'subscriptions' => [
        'messages',
        'flights',
        'pirep',
        'maintenance',
        'security',
    ],
    
    'publish' => [
        'pirep' => 'acars/{airline_id}/pirep',
        'maintenance' => 'acars/{airline_id}/maintenance',
        'security' => 'acars/{airline_id}/security',
    ],
    
    'logging' => [
        'enabled' => true,
        'level' => getenv('ACARS_LOG_LEVEL') ?: 'info',
        'file' => getenv('ACARS_LOG_FILE') ?: '/var/log/acars/acars.log',
    ],
];
