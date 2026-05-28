<?php

declare(strict_types=1);

/**
 * Wetter-API Test Suite
 * 
 * Tests METAR/TAF via OpenMeteo
 */

require_once __DIR__ . '/../src/services/WeatherService.php';

use RunwayHub\Services\WeatherService;

// Setup logger
$logger = new class {
    public function debug(string $msg): void { echo "[DEBUG] " . $msg . "\n"; }
    public function info(string $msg): void { echo "[INFO] " . $msg . "\n"; }
    public function warning(string $msg): void { echo "[WARNING] " . $msg . "\n"; }
    public function error(string $msg): void { echo "[ERROR] " . $msg . "\n"; }
    public function critical(string $msg): void { echo "[CRITICAL] " . $msg . "\n"; }
};

// Create Weather Service
$weather = new WeatherService($logger);

echo "== Wetter-API Test Suite ==\n\n";

// Test 1: Get current weather
echo "Test 1: Wetter für EDDF (Frankfurt)\n";
$weatherData = $weather->getCurrentWeather('EDDF');

if ($weatherData && isset($weatherData['current_temperature'])) {
    echo "✅ Wetter geladen:\n";
    echo "   Temperatur: {$weatherData['current_temperature']}°C\n";
    echo "   Luftfeuchtigkeit: {$weatherData['relative_humidity']}%\n";
    echo "   Wind: {$weatherData['wind_speed']} km/h\n";
    echo "   Wetter: {$weatherData['weather_description']}\n";
} else {
    echo "❌ Fehler: Wetterdaten konnten nicht geladen werden\n";
}
echo "\n";

// Test 2: Get TAF forecast
echo "Test 2: TAF-Vorhersage für EDDF\n";
$tafData = $weather->getTaf('EDDF');

if ($tafData) {
    echo "✅ TAF-Vorhersage geladen:\n";
    echo "   Valid von: {$tafData['valid_from']}\n";
    echo "   Valid bis: {$tafData['valid_to']}\n";
    echo "   Prognosen:\n";
    foreach ($tafData['prognoses'] as $prognosis) {
        echo "     - {$prognosis['time']}: {$prognosis['forecast']}\n";
    }
} else {
    echo "❌ Fehler: TAF-Daten konnten nicht geladen werden\n";
}
echo "\n";

// Test 3: Get hourly forecast
echo "Test 3: Stunden-Vorhersage für EDDF\n";
$hourlyData = $weather->getHourly('EDDF');

if ($hourlyData) {
    echo "✅ Stunden-Vorhersage geladen:\n";
    echo "   Von: {$hourlyData['valid_from']}\n";
    echo "   Bis: {$hourlyData['valid_to']}\n";
    echo "   Anzahl Prognosen: " . count($hourlyData['hourly']) . "\n";
} else {
    echo "❌ Fehler: Stunden-Vorhersage konnte nicht geladen werden\n";
}
echo "\n";

echo "== Wetter-API Test Suite abgeschlossen ==\n";
