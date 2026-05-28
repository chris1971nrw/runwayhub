<?php
// RunwayHub Konfiguration
// Bitte anpassen nach deinem Server

// App
define('APP_NAME', 'RunwayHub');
define('APP_VERSION', '1.0.0');
define('APP_URL', 'https://runwayhub.example.com');

// Datenbank
define('DB_PATH', __DIR__ . '/../database.sqlite');
define('DB_DRIVER', 'sqlite');

// Pfade
define('LOG_PATH', __DIR__ . '/../logs');
define('UPLOAD_PATH', __DIR__ . '/../uploads');
define('TEMP_PATH', sys_get_temp_dir());

// SMTP Email
define('SMTP_HOST', getenv('SMTP_HOST') ?: 'localhost');
define('SMTP_PORT', getenv('SMTP_PORT') ?: '587');
define('SMTP_USER', getenv('SMTP_USER') ?: '');
define('SMTP_PASS', getenv('SMTP_PASS') ?: '');
define('SMTP_SECURE', getenv('SMTP_SECURE') ?: 'false');
define('MAILER_DOMAIN', getenv('MAILER_DOMAIN') ?: 'localhost');

// Admin
define('ADMIN_EMAIL', getenv('ADMIN_EMAIL') ?: 'admin@example.com');

// Features
define('NOTIFICATIONS_ENABLED', getenv('NOTIFICATIONS_ENABLED') ?: 'true');
define('ALLOW_REGISTRATION', getenv('ALLOW_REGISTRATION') ?: 'false');
define('ALLOW_BOOKING', getenv('ALLOW_BOOKING') ?: 'false');
define('ALLOW_ADMIN', getenv('ALLOW_ADMIN') ?: 'false');

// ACARS API (Optional)
define('ACARS_API_URL', getenv('ACARS_API_URL') ?: 'https://api.runwayhub.example/acars');
define('ACARS_API_KEY', getenv('ACARS_API_KEY') ?: '');

// Wetter API
define('WEATHER_PROVIDER', getenv('WEATHER_PROVIDER') ?: 'openmeteo');
define('WEATHER_CACHE_TTL', (int)(getenv('WEATHER_CACHE_TTL') ?: '300'));

// Limitierungen
define('MAX_BOOKINGS_PER_USER', (int)(getenv('MAX_BOOKINGS_PER_USER') ?: '100'));

// Sicherheit
define('ALLOWED_HOSTS', explode(',', getenv('ALLOWED_HOSTS') ?: '*'));
define('SESSION_LIFETIME', (int)(getenv('SESSION_LIFETIME') ?: '7200'));
