<?php

declare(strict_types=1);

/**
 * RunwayHub - API Router
 * Central API endpoint routing
 */

// Load the application bootstrap
require_once dirname(dirname(dirname(__DIR__))).'/runwayhub/src/core/Bootstrap.php';

// Bootstrap the application
$bootstrap = new \RunwayHub\Core\Bootstrap();
$bootstrap->boot();

// Route API requests
try {
    $route = $bootstrap->router->getRoute();
    
    // API routes
    if (strpos($route, 'api/') === 0) {
        // Extract the API endpoint
        $endpoint = str_replace('api/', '', $route);
        
        // Route to appropriate API controller
        switch ($endpoint) {
            case 'weather':
            case 'weather/':
            case 'weather/current':
            case 'weather/latest':
                $controller = new \RunwayHub\Api\Controller\WeatherController($bootstrap->request, $bootstrap->response);
                break;
            case 'airport':
            case 'airport/':
                $controller = new \RunwayHub\Api\Controller\AirportController($bootstrap->request, $bootstrap->response);
                break;
            case 'flight':
            case 'flight/':
            case 'flight/status':
            case 'flight/check':
                $controller = new \RunwayHub\Api\Controller\FlightController($bootstrap->request, $bootstrap->response);
                break;
            case 'pirp':
            case 'pirp/':
                $controller = new \RunwayHub\Api\Controller\PIREPController($bootstrap->request, $bootstrap->response);
                break;
            case 'statistics':
            case 'statistics/':
                $controller = new \RunwayHub\Api\Controller\StatisticsController($bootstrap->request, $bootstrap->response);
                break;
            case 'leaderboard':
            case 'leaderboard/':
                $controller = new \RunwayHub\Api\Controller\LeaderboardController($bootstrap->request, $bootstrap->response);
                break;
            case 'booking':
            case 'booking/':
                $controller = new \RunwayHub\Api\Controller\BookingController($bootstrap->request, $bootstrap->response);
                break;
            case 'turbulence':
            case 'turbulence/':
                $controller = new \RunwayHub\Api\Controller\TurbulenceController($bootstrap->request, $bootstrap->response);
                break;
            case 'acars':
            case 'acars/':
                $controller = new \RunwayHub\Api\Controller\AcarsController($bootstrap->request, $bootstrap->response);
                break;
            case 'pireps':
            case 'pireps/':
            default:
                $controller = new \RunwayHub\Api\Controller\ApiController($bootstrap->request, $bootstrap->response);
                break;
        }
        
        // Handle the request
        if ($controller) {
            if (strpos($endpoint, 'get') !== false || strpos($endpoint, 'fetch') !== false) {
                $controller->get($bootstrap->response);
            } elseif (strpos($endpoint, 'post') !== false || strpos($endpoint, 'create') !== false || strpos($endpoint, 'submit') !== false) {
                $controller->post($bootstrap->response);
            } elseif (strpos($endpoint, 'put') !== false || strpos($endpoint, 'update') !== false) {
                $controller->put($bootstrap->response);
            } elseif (strpos($endpoint, 'delete') !== false || strpos($endpoint, 'remove') !== false) {
                $controller->delete($bootstrap->response);
            } else {
                $controller->get($bootstrap->response);
            }
        } else {
            $bootstrap->response->status(404);
            $bootstrap->response->contentType('application/json');
            $bootstrap->response->content(json_encode([
                'success' => false,
                'error' => true,
                'message' => 'API endpoint not found',
            ]));
            $bootstrap->response->send();
        }
    } else {
        // Not an API route
        $bootstrap->response->status(404);
        $bootstrap->response->contentType('application/json');
        $bootstrap->response->content(json_encode([
            'success' => false,
            'error' => true,
            'message' => 'Invalid API endpoint',
        ]));
        $bootstrap->response->send();
    }
} catch (\Exception $e) {
    // Handle errors
    $bootstrap->response->status(500);
    $bootstrap->response->contentType('application/json');
    $bootstrap->response->content(json_encode([
        'success' => false,
        'error' => true,
        'message' => 'Internal server error',
        'error_code' => $e->getCode(),
        'message' => $e->getMessage(),
    ]));
    $bootstrap->response->send();
}