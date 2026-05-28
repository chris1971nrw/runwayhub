<?php

/**
 * SMTP Email Configuration Example
 * 
 * Configure this file for email alerts and notifications.
 */

return [
    // Enable SMTP email notifications
    'enabled' => false,
    
    // SMTP Server Settings
    'host' => env('SMTP_HOST', 'smtp.example.com'),
    'port' => env('SMTP_PORT', 587),
    'encryption' => env('SMTP_ENCRYPTION', 'tls'),
    
    // Authentication
    'username' => env('SMTP_USERNAME', 'your-email@example.com'),
    'password' => env('SMTP_PASSWORD', 'your-secure-password'),
    'from' => env('SMTP_FROM', 'noreply@runwayhub.example.com'),
    'from_name' => env('SMTP_FROM_NAME', 'RunwayHub'),
    
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
        'system_alert' => false,
    ],
    
    // Rate limiting for email sending
    'rate_limit' => [
        'enabled' => true,
        'requests' => 50,
        'period' => 60, // seconds
    ],
];
