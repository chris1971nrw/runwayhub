<?php
declare(strict_types=1);

/**
 * Simple service test without Monolog
 */

echo "RunwayHub Service Tests\n";
echo str_repeat("=", 50) . "\n\n";

// Test 1: Weather Service (without logger)
try {
    // Create mock logger
    class SimpleLogger {
        public function debug(string $msg): void {}
        public function info(string $msg): void {}
        public function warning(string $msg): void {}
        public function error(string $msg): void {}
        public function critical(string $msg): void {}
    }
    
    $logger = new SimpleLogger();
    
    // Test Weather
    $weather = new \RunwayHub\Services\WeatherService($logger);
    $result = $weather->getCurrentWeather('EDDM');
    echo "✓ WeatherService: " . json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
} catch (Exception $e) {
    echo "✗ WeatherService: " . $e->getMessage() . "\n";
}

// Test 2: Flight Tracking
try {
    $flight = new \RunwayHub\Services\FlightTrackingService($logger);
    $result = $flight->getFlightStatus('3C6647');
    echo "✓ FlightTrackingService: " . json_encode(['status' => $result['status'] ?? 'unknown'], JSON_PRETTY_PRINT) . "\n";
} catch (Exception $e) {
    echo "✗ FlightTrackingService: " . $e->getMessage() . "\n";
}

// Test 3: OpenAIP Service
try {
    $openaip = new \RunwayHub\Services\OpenAIPService($logger);
    $notams = $openaip->getNOTAMs('EDDF');
    echo "✓ OpenAIPService: " . count($notams) . " NOTAMs cached\n";
} catch (Exception $e) {
    echo "✗ OpenAIPService: " . $e->getMessage() . "\n";
}

// Test 4: API Status
try {
    require 'runwayhub/config/config.php';
    echo "✓ Config: version " . $config['app']['version'] . "\n";
} catch (Exception $e) {
    echo "✗ Config: " . $e->getMessage() . "\n";
}

echo str_repeat("=", 50) . "\n";
echo "All tests completed!\n";
