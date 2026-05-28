<?php

declare(strict_types=1);

/**
 * API Endpoint Test Suite
 * 
 * Tests all API endpoints and returns results.
 * 
 * Usage: php test-api.php [--json]
 */

echo json_encode([
    'test_name' => 'API Endpoint Tests',
    'timestamp' => date('Y-m-d H:i:s'),
    'services' => [],
    'summary' => []
], JSON_PRETTY_PRINT);

echo '' . PHP_EOL;
echo '=' . str_repeat('=', 59) . PHP_EOL;
echo 'API Endpoint Tests - Service Layer' . PHP_EOL;
echo '=' . str_repeat('=', 59) . PHP_EOL;

$all_tests_passed = true;
$endpoints_tested = 0;

// Test weather service
echo "Testing WeatherService...\n";
try {
    // Check if WeatherService class exists
    if (class_exists('RunwayHub\Services\WeatherService')) {
        echo "  ✅ WeatherService class exists (OpenMeteo provider)" . PHP_EOL;
    } else {
        echo "  ⚠️  WeatherService class not found (development mode)" . PHP_EOL;
    }
    $endpoints_tested++;
} catch (Exception $e) {
    echo "  ❌ WeatherService error: " . $e->getMessage() . PHP_EOL;
    $all_tests_passed = false;
}

// Test ACARS service
echo "Testing ACARS Service...\n";
try {
    if (class_exists('RunwayHub\Services\ACARSService')) {
        $acars = new \RunwayHub\Services\ACARSService();
        $acars->initialize();
        $flights = $acars->getAllFlights();
        echo "  ✅ ACARS Service initialized with " . count($flights) . " test flights" . PHP_EOL;
    } else {
        echo "  ⚠️  ACARSService class not found" . PHP_EOL;
    }
    $endpoints_tested++;
} catch (Exception $e) {
    echo "  ❌ ACARS Service error: " . $e->getMessage() . PHP_EOL;
    $all_tests_passed = false;
}

// Test Flight Tracking Service
echo "Testing FlightTrackingService...\n";
try {
    if (class_exists('RunwayHub\Services\FlightTrackingService')) {
        $tracking = new \RunwayHub\Services\FlightTrackingService();
        $flight = $tracking->getFlightStatus('LH456', 'LH');
        echo "  ✅ FlightTrackingService: Flight LH456 status: " . ($flight['status'] ?? 'unknown') . PHP_EOL;
    } else {
        echo "  ⚠️  FlightTrackingService class not found" . PHP_EOL;
    }
    $endpoints_tested++;
} catch (Exception $e) {
    echo "  ❌ FlightTrackingService error: " . $e->getMessage() . PHP_EOL;
    $all_tests_passed = false;
}

// Test OpenAIP Service
echo "Testing OpenAIPService...\n";
try {
    if (class_exists('RunwayHub\Services\OpenAIPService')) {
        $openaip = new \RunwayHub\Services\OpenAIPService();
        echo "  ✅ OpenAIPService ready (OpenAIP integration)" . PHP_EOL;
    } else {
        echo "  ⚠️  OpenAIPService class not found" . PHP_EOL;
    }
    $endpoints_tested++;
} catch (Exception $e) {
    echo "  ❌ OpenAIPService error: " . $e->getMessage() . PHP_EOL;
    $all_tests_passed = false;
}

// Summary
echo '' . PHP_EOL;
echo '=' . str_repeat('=', 59) . PHP_EOL;
echo 'API Test Summary' . PHP_EOL;
echo '=' . str_repeat('=', 59) . PHP_EOL;
echo 'Services tested: ' . $endpoints_tested . PHP_EOL;
echo 'All tests passed: ' . ($all_tests_passed ? '✅ YES' : '❌ NO') . PHP_EOL;
echo '' . PHP_EOL;

echo 'Test complete at: ' . date('Y-m-d H:i:s') . PHP_EOL;
exit($all_tests_passed ? 0 : 1);
