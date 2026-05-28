<?php

/**
 * RunwayHub Monitoring Configuration
 * 
 * Set up monitoring, alerting, and logging for production deployment.
 */

return [
    // Monitoring Service (Prometheus, Datadog, New Relic, etc.)
    'monitoring' => [
        'enabled' => false,
        'provider' => 'prometheus', // prometheus, datadog, newrelic, self-hosted
        'endpoint' => env('MONITORING_ENDPOINT', '/metrics'),
        'port' => env('MONITORING_PORT', 9090),
        'interval' => env('MONITORING_INTERVAL', 15), // seconds
    ],
    
    // Alerting Configuration
    'alerts' => [
        'enabled' => false,
        'channels' => [
            'email' => [
                'enabled' => true,
                'to' => env('ALERT_EMAIL', 'admin@example.com'),
                'subject' => 'RunwayHub Alert',
                'template' => 'alerts/email-alert.php',
            ],
            'webhook' => [
                'enabled' => false,
                'url' => env('ALERT_WEBHOOK_URL'),
                'method' => 'POST',
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
            ],
            'slack' => [
                'enabled' => false,
                'webhook_url' => env('SLACK_WEBHOOK_URL'),
                'channel' => '#alerts',
            ],
        ],
        
        // Alert Rules
        'rules' => [
            [
                'name' => 'High Error Rate',
                'condition' => 'error_rate > 0.05', // 5% error rate
                'interval' => 5 * 60, // 5 minutes
                'severity' => 'critical',
                'message' => 'High error rate detected: {error_rate}',
            ],
            [
                'name' => 'High Response Time',
                'condition' => 'response_time_p99 > 1000', // 1 second
                'interval' => 2 * 60, // 2 minutes
                'severity' => 'warning',
                'message' => 'High response time: {response_time}ms',
            ],
            [
                'name' => 'Database Connection Issues',
                'condition' => 'db_connections_failed > 0',
                'interval' => 1 * 60, // 1 minute
                'severity' => 'critical',
                'message' => 'Database connection issues detected',
            ],
            [
                'name' => 'Memory Usage High',
                'condition' => 'memory_usage_percent > 85',
                'interval' => 5 * 60, // 5 minutes
                'severity' => 'warning',
                'message' => 'High memory usage: {memory_percent}%',
            ],
            [
                'name' => 'Disk Space Low',
                'condition' => 'disk_usage_percent > 85',
                'interval' => 10 * 60, // 10 minutes
                'severity' => 'critical',
                'message' => 'Low disk space: {disk_percent}%',
            ],
            [
                'name' => 'API Unavailable',
                'condition' => 'api_status != "healthy"',
                'interval' => 30, // 30 seconds
                'severity' => 'critical',
                'message' => 'API health check failed',
            ],
        ],
    ],
    
    // Logging Configuration
    'logging' => [
        'driver' => 'file', // file, stream, syslog, custom
        'path' => storage_path('logs'),
        'level' => env('LOG_LEVEL', 'info'), // debug, info, notice, warning, error, critical, alert, emergency
        'single_file' => false, // Use separate files per log channel
        
        // Log Channels
        'channels' => [
            'stack' => [
                'driver' => 'single',
                'path' => storage_path('logs/app.log'),
                'level' => env('LOG_LEVEL', 'debug'),
            ],
            'daily' => [
                'driver' => 'daily',
                'path' => storage_path('logs/daily.log'),
                'level' => 'debug',
                'days' => 14,
            ],
            'errors' => [
                'driver' => 'single',
                'path' => storage_path('logs/errors.log'),
                'level' => 'error',
            ],
            'api' => [
                'driver' => 'single',
                'path' => storage_path('logs/api.log'),
                'level' => 'info',
            ],
            'access' => [
                'driver' => 'single',
                'path' => storage_path('logs/access.log'),
                'level' => 'info',
            ],
        ],
        
        // Performance Monitoring Logs
        'performance' => [
            'enabled' => true,
            'threshold' => 100, // Log queries taking > 100ms
            'slow_queries_log' => storage_path('logs/slow_queries.log'),
        ],
    ],
    
    // Health Check Endpoints
    'health' => [
        'enabled' => true,
        'endpoints' => [
            [
                'path' => '/health',
                'method' => 'GET',
                'response' => [
                    'status' => 'healthy',
                    'version' => '2.0.3',
                    'timestamp' => date('c'),
                ],
            ],
            [
                'path' => '/metrics',
                'method' => 'GET',
                'response' => 'Prometheus metrics',
            ],
        ],
    ],
    
    // Uptime Tracking
    'uptime' => [
        'enabled' => false,
        'provider' => 'self-hosted', // self-hosted, datadog, newrelic
        'endpoint' => env('UPTIME_ENDPOINT'),
        'interval' => 60, // Check every minute
    ],
    
    // Tracing (OpenTelemetry)
    'tracing' => [
        'enabled' => false,
        'provider' => 'jaeger', // jaeger, zipkin, datadog
        'endpoint' => env('TRACE_EXPORTER_ENDPOINT'),
        'service_name' => 'runwayhub',
        'sample_rate' => env('TRACE_SAMPLE_RATE', 1.0),
    ],
];
