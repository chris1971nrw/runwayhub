<?php

declare(strict_types=1);

/**
 * API Router für RunwayHub
 * 
 * Central API endpoint für alle API-Endpunkte
 */

// Enable CORS for API
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/core/Database.php';
require_once __DIR__ . '/../src/core/Response.php';
require_once __DIR__ . '/../src/services/AcarsClient.php';
require_once __DIR__ . '/../src/services/WeatherClient.php';
require_once __DIR__ . '/../src/services/ACARSClient.php';

// Handle OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Get route
$method = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['REQUEST_URI'];
$endpoint = $path;

// Parse path (remove leading /)
$endpoint = substr($endpoint, 1);

// Routes
$routes = [
    // Login
    '/api/login-pilot.php' => 'LoginController',
    '/api/logout.php' => 'LoginController',
    '/api/pilot/verify/{callsign}' => 'LoginController',
    '/api/pilot/profile/{token}' => 'LoginController',
    
    // VA
    '/api/va-create.php' => 'VAController',
    '/api/va-connect.php' => 'VAController',
    '/api/va/list' => 'VAController',
    '/api/va/{id}' => 'VAController',
    
    // OpenAIP
    '/openaip/airport/{airport}' => 'OpenAIPController',
    '/openaip/weather/current' => 'OpenAIPController',
    '/openaip/weather/forecast' => 'OpenAIPController',
    '/openaip/flights' => 'OpenAIPController',
    '/openaip/asterads' => 'OpenAIPController',
    '/openaip/notams' => 'OpenAIPController',
    '/openaip/pireps' => 'OpenAIPController',
    '/openaip/almanac' => 'OpenAIPController',
    '/openaip/navaids' => 'OpenAIPController',
    '/openaip/airlines' => 'OpenAIPController',
    '/openaip/aircraft' => 'OpenAIPController',
    '/openaip/facilities' => 'OpenAIPController',
    
    // Weather
    '/weather/{airport}' => 'WeatherController',
    '/weather/current' => 'WeatherController',
    '/weather/forecast' => 'WeatherController',
    '/weather/alerts' => 'WeatherController',
    '/weather/turbulence' => 'WeatherController',
    '/weather/visibility' => 'WeatherController',
    
    // ACARS
    '/ACARS/flights' => 'ACARSController',
    '/ACARS/{flight}' => 'ACARSController',
    '/ACARS/airports' => 'ACARSController',
    '/ACARS/delays' => 'ACARSController',
];

// Get request data
$requestData = file_get_contents('php://input');
$params = $_GET;

// Extract named parameters
if (preg_match('#/ACARS/(\S+)#', $endpoint, $matches)) {
    $params['flight'] = $matches[1];
} else if (preg_match('#/openaip/airport/(\S+)#', $endpoint, $matches)) {
    $params['airport'] = $matches[1];
} else if (preg_match('#/api/pilot/verify/(\S+)#', $endpoint, $matches)) {
    $params['callsign'] = $matches[1];
} else if (preg_match('#/api/va/(.*)#', $endpoint, $matches)) {
    $params['vaId'] = $matches[1];
} else if (preg_match('#/weather/(\S+)#', $endpoint, $matches)) {
    $params['airport'] = $matches[1];
}

// Remove query parameters from path
$endpoint = parse_url($endpoint, PHP_URL_PATH);

// Find matching route
$routeKey = null;
foreach ($routes as $route => $controller) {
    if (strpos($endpoint, $route) === 0) {
        $routeKey = $route;
        break;
    }
}

// If no route found, return 404
if (!$routeKey) {
    echo json_encode(['error' => 'Not Found', 'path' => $endpoint]);
    http_response_code(404);
    exit;
}

// Load request data
$method = $_SERVER['REQUEST_METHOD'];

// Route method to controller
$controllerClass = $routes[$routeKey];
$controller = new $controllerClass(new \RunwayHub\Core\Database($config['default']));

// Dispatch
try {
    // Extract method name
    $methodName = $method === 'POST' ? 'handlePost' : ('$method' === 'GET' ? 'handleGet' : $method);
    
    // Try method
    if (method_exists($controller, $methodName)) {
        echo json_encode($controller->$methodName($requestData, $params));
    } else {
        echo json_encode(['error' => 'Method Not Allowed', 'method' => $method]);
        http_response_code(405);
    }
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
    http_response_code(500);
}
