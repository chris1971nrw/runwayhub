<?php

/**
 * SMTP Email Configuration
 * Configure for production email alerts
 */

return [
    // Enable SMTP email notifications
    'enabled' => getenv('SMTP_ENABLED') === '1',
    
    // SMTP Server Settings
    'host' => getenv('SMTP_HOST') ?: 'smtp.mailgun.net',
    'port' => getenv('SMTP_PORT') ?: 587,
    'encryption' => getenv('SMTP_ENCRYPTION') ?: 'tls',
    
    // Authentication
    'username' => getenv('SMTP_USERNAME') ?: 'runwayhub@example.com',
    'password' => getenv('SMTP_PASSWORD') ?: '',
    'from' => getenv('SMTP_FROM') ?: 'noreply@runwayhub.de',
    'from_name' => getenv('SMTP_FROM_NAME') ?: 'RunwayHub',
    
    // Email Templates
    'templates' => [
        'welcome' => 'mail/welcome.php',
        'password_reset' => 'mail/password-reset.php',
        'flight_update' => 'mail/flight-update.php',
        'weather_alert' => 'mail/weather-alert.php',
        'maintenance' => 'mail/maintenance.php',
    ],
    
    // Email Notifications
    'notifications' => [
        'new_booking' => true,
        'flight_status_change' => true,
        'weather_warning' => true,
        'maintenance_scheduled' => true,
        'system_alert' => getenv('SMTP_SYSTEM_ALERT') === '1',
    ],
    
    // Rate limiting for email sending
    'rate_limit' => [
        'enabled' => true,
        'requests' => getenv('SMTP_RATE_REQUESTS') ?: 30,
        'period' => getenv('SMTP_RATE_PERIOD') ?: 60, // seconds
    ],
];