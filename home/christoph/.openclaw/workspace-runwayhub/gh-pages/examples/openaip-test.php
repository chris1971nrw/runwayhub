<?php
/**
 * RunwayHub - OpenAIP API Test Script
 * 
 * This script demonstrates OpenAIP API integration.
 * Replace with actual production code.
 * 
 * Usage: php examples/openaip-test.php
 */

// Configuration
define('OPENAIP_URL', getenv('OPENAIP_API_URL') ?: 'http://openaip.net');
define('OPENAIP_API_KEY', getenv('OPENAIP_API_KEY') ?: 'demo'); // Demo key, use production key in production

/**
 * Make API request to OpenAIP
 * @param string $endpoint
 * @param string $params
 * @return array|false
 */
function openaipRequest($endpoint, $params = '')
{
    $url = OPENAIP_URL . '/' . $endpoint . ($params ? '?key=' . OPENAIP_API_KEY . '&' . $params : '?key=' . OPENAIP_API_KEY);
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Accept: application/json',
        'User-Agent: RunwayHub/1.0'
    ]);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200) {
        return json_decode($response, true);
    }
    
    return false;
}

echo "=== RunwayHub OpenAIP API Test ===\n\n";

// Test 1: Check API health
echo "1. Testing API connection...\n";
$result = openaipRequest('check');
if ($result) {
    echo "   ✓ API is accessible\n";
} else {
    echo "   ✗ API connection failed\n";
}

// Test 2: Get airports list
echo "\n2. Fetching airports...\n";
$airports = openaipRequest('airports', 'limit=5');
if ($airports && is_array($airports)) {
    echo "   ✓ Retrieved " . count($airports) . " airports\n";
    echo "   Sample: " . json_encode($airports[0] ?? ['N/A' => 'No data']) . "\n";
}

// Test 3: Get waypoint
echo "\n3. Fetching waypoint F119...\n";
$waypoint = openaipRequest('waypoints', 'id=F119&limit=1');
if ($waypoint) {
    echo "   ✓ Waypoint retrieved\n";
}

// Test 4: Get airway
echo "\n4. Fetching airway L248...\n";
$airway = openaipRequest('airways', 'id=L248&limit=1');
if ($airway) {
    echo "   ✓ Airway retrieved\n";
}

// Test 5: Get navaid
echo "\n5. Fetching navaid...\n";
$navaid = openaipRequest('navaids', 'type=VORT&limit=1');
if ($navaid) {
    echo "   ✓ Navaid retrieved\n";
}

echo "\n=== Test Complete ===\n";