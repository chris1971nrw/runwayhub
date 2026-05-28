<?php

declare(strict_types=1);

/**
 * ACARS Test Suite
 * 
 * Tests ACARS Service functionality
 */

require_once __DIR__ . '/../src/services/ACARSService.php';

use RunwayHub\Services\ACARSService;

// Setup logger
$logger = new class {
    public function debug(string $msg): void { echo "[DEBUG] " . $msg . "\n"; }
    public function info(string $msg): void { echo "[INFO] " . $msg . "\n"; }
    public function warning(string $msg): void { echo "[WARNING] " . $msg . "\n"; }
    public function error(string $msg): void { echo "[ERROR] " . $msg . "\n"; }
    public function critical(string $msg): void { echo "[CRITICAL] " . $msg . "\n"; }
};

// Create ACARS service
$acars = new ACARSService($logger);

echo "=== ACARS Test Suite ===\n\n";

// Test 1: Initialize
echo "Test 1: ACARS-Service initialisieren\n";
$acars->initialize();
echo "✅ ACARS-Service initialisiert\n\n";

// Test 2: Get flight status
echo "Test 2: Flugstatus für LH456\n";
$status = $acars->getFlightStatus('LH456');
if ($status['status'] === 'scheduled') {
    echo "✅ Flugstatus geladen: {$status['status']}\n";
    echo "   Flug: {$status['flight_number']}\n";
    echo "   Route: {$status['origin']} → {$status['destination']}\n";
    echo "   Abflug: {$status['departure_time']}\n";
    echo "   Flugzeug: {$status['aircraft']}\n";
    echo "   Gate: {$status['gate']}\n";
    echo "   Status: {$status['acars_status']}\n";
} else {
    echo "❌ Fehler: Flugstatus konnte nicht geladen werden\n";
}
echo "\n";

// Test 3: Get all flights
echo "Test 3: Alle Flüge\n";
$allFlights = $acars->getAllFlights();
echo "   Gesucht " . count($allFlights) . " Flüge\n";
foreach ($allFlights as $flight) {
    echo "   - {$flight['flight_number']}: {$flight['origin']} → {$flight['destination']}\n";
}
echo "\n";

// Test 4: Update flight status
echo "Test 4: Flugstatus aktualisieren\n";
$acars->updateFlightStatus('LH456', [
    'status' => 'in_flight',
    'acars_status' => 'airborne',
    'altitude' => 30000,
    'ground_speed' => 850,
    'track' => 315,
]);
$status = $acars->getFlightStatus('LH456');
echo "✅ Flugstatus aktualisiert: {$status['acars_status']}\n";
echo "   Höhe: {$status['altitude']} ft\n";
echo "   Geschwindigkeit: {$status['ground_speed']} kt\n";
echo "\n";

// Test 5: Get all flights after update
echo "Test 5: Alle Flüge nach Status-Update\n";
$allFlights = $acars->getAllFlights();
foreach ($allFlights as $flight) {
    echo "   - {$flight['flight_number']}: {$flight['acars_status']}\n";
}
echo "\n";

echo "=== ACARS Test Suite abgeschlossen ===\n";
