<?php

/**
 * OpenAIP API Endpoints
 *
 * REST-API für OpenAIP-Daten (Flughäfen, Wegpunkte, Luftwege, Navigationshilfen)
 */

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/core/Bootstrap.php';

use RunwayHub\Core\Router;
use RunwayHub\Core\Request;
use RunwayHub\Core\Response;
use RunwayHub\Core\OpenAIP\Client;

// Router initialisieren
$router = new Router();

/**
 * /api/openaip/airports - Alle Flughäfen
 */
$router->get('/api/openaip/airports', function(Request $request, Response $response) {
    try {
        $client = new Client();
        $airports = $client->getAirports($request->get('filter', ''), (int)$request->get('limit', 1000));
        
        return Response::json([
            'success' => true,
            'count' => count($airports),
            'data' => $airports,
        ]);
    } catch (\Exception $e) {
        return Response::error($e->getMessage(), 500);
    }
});

/**
 * /api/openaip/airports/{id} - Einzelner Flughafen
 */
$router->get('/api/openaip/airports/{id}', function(Request $request, Response $response, $id) {
    try {
        $client = new Client();
        $airport = $client->getAirport($id);
        
        if (!$airport) {
            return Response::json([
                'success' => false,
                'error' => 'Flughafen nicht gefunden',
            ], 404);
        }
        
        return Response::json([
            'success' => true,
            'data' => $airport,
        ]);
    } catch (\Exception $e) {
        return Response::error($e->getMessage(), 500);
    }
});

/**
 * /api/openaip/waypoints - Alle Wegpunkte
 */
$router->get('/api/openaip/waypoints', function(Request $request, Response $response) {
    try {
        $client = new Client();
        $waypoints = $client->getWaypoints($request->get('filter', ''), (int)$request->get('limit', 1000));
        
        return Response::json([
            'success' => true,
            'count' => count($waypoints),
            'data' => $waypoints,
        ]);
    } catch (\Exception $e) {
        return Response::error($e->getMessage(), 500);
    }
});

/**
 * /api/openaip/waypoints/{id} - Einzelfwegpunkt
 */
$router->get('/api/openaip/waypoints/{id}', function(Request $request, Response $response, $id) {
    try {
        $client = new Client();
        $waypoint = $client->getWaypoint($id);
        
        if (!$waypoint) {
            return Response::json([
                'success' => false,
                'error' => 'Wegpunkt nicht gefunden',
            ], 404);
        }
        
        return Response::json([
            'success' => true,
            'data' => $waypoint,
        ]);
    } catch (\Exception $e) {
        return Response::error($e->getMessage(), 500);
    }
});

/**
 * /api/openaip/airways - Alle Luftwege
 */
$router->get('/api/openaip/airways', function(Request $request, Response $response) {
    try {
        $client = new Client();
        $airways = $client->getAirways($request->get('filter', ''), (int)$request->get('limit', 1000));
        
        return Response::json([
            'success' => true,
            'count' => count($airways),
            'data' => $airways,
        ]);
    } catch (\Exception $e) {
        return Response::error($e->getMessage(), 500);
    }
});

/**
 * /api/openaip/airways/{id} - Einzelflugweg
 */
$router->get('/api/openaip/airways/{id}', function(Request $request, Response $response, $id) {
    try {
        $client = new Client();
        $airway = $client->getAirway($id);
        
        if (!$airway) {
            return Response::json([
                'success' => false,
                'error' => 'Luftweg nicht gefunden',
            ], 404);
        }
        
        return Response::json([
            'success' => true,
            'data' => $airway,
        ]);
    } catch (\Exception $e) {
        return Response::error($e->getMessage(), 500);
    }
});

/**
 * /api/openaip/navaids - Alle Navigationshilfen
 */
$router->get('/api/openaip/navaids', function(Request $request, Response $response) {
    try {
        $client = new Client();
        $navaids = $client->getNavaids($request->get('filter', ''), (int)$request->get('limit', 1000));
        
        return Response::json([
            'success' => true,
            'count' => count($navaids),
            'data' => $navaids,
        ]);
    } catch (\Exception $e) {
        return Response::error($e->getMessage(), 500);
    }
});

/**
 * /api/openaip/navaids/{id} - Einzelnavigationshilfe
 */
$router->get('/api/openaip/navaids/{id}', function(Request $request, Response $response, $id) {
    try {
        $client = new Client();
        $navaid = $client->getNavaid($id);
        
        if (!$navaid) {
            return Response::json([
                'success' => false,
                'error' => 'Navigationshilfe nicht gefunden',
            ], 404);
        }
        
        return Response::json([
            'success' => true,
            'data' => $navaid,
        ]);
    } catch (\Exception $e) {
        return Response::error($e->getMessage(), 500);
    }
});

/**
 * /api/openaip/airspace - Lufträume (falls verfügbar)
 */
$router->get('/api/openaip/airspace', function(Request $request, Response $response) {
    try {
        $client = new Client();
        $airspaces = $client->getAirspaces($request->get('filter', ''), (int)$request->get('limit', 1000));
        
        return Response::json([
            'success' => true,
            'count' => count($airspaces),
            'data' => $airspaces,
        ]);
    } catch (\Exception $e) {
        return Response::error($e->getMessage(), 500);
    }
});

/**
 * /api/openaip/airspace/{id} - Einzelner Luftraum
 */
$router->get('/api/openaip/airspace/{id}', function(Request $request, Response $response, $id) {
    try {
        $client = new Client();
        $airspace = $client->getAirspace($id);
        
        if (!$airspace) {
            return Response::json([
                'success' => false,
                'error' => 'Luftraum nicht gefunden',
            ], 404);
        }
        
        return Response::json([
            'success' => true,
            'data' => $airspace,
        ]);
    } catch (\Exception $e) {
        return Response::error($e->getMessage(), 500);
    }
});

/**
 * /api/openaip/sync - Sync ausführen
 */
$router->post('/api/openaip/sync', function(Request $request, Response $response) {
    try {
        $client = new Client();
        $result = $client->sync();
        
        return Response::json([
            'success' => true,
            'result' => $result,
        ]);
    } catch (\Exception $e) {
        return Response::error($e->getMessage(), 500);
    }
});

/**
 * /api/openaip/status - API-Status
 */
$router->get('/api/openaip/status', function(Request $request, Response $response) {
    try {
        $client = new Client();
        $status = $client->getSyncStatus();
        $apiInfo = $client->apiInfo();
        
        return Response::json([
            'success' => true,
            'status' => $status,
            'api_info' => $apiInfo,
        ]);
    } catch (\Exception $e) {
        return Response::error($e->getMessage(), 500);
    }
});

/**
 * /api/openaip/clearcache - Cache leeren
 */
$router->post('/api/openaip/clearcache', function(Request $request, Response $response) {
    try {
        $client = new Client();
        $client->clearCache();
        
        return Response::json([
            'success' => true,
            'message' => 'Cache erfolgreich geleert',
        ]);
    } catch (\Exception $e) {
        return Response::error($e->getMessage(), 500);
    }
});

// Router ausführen
echo $router->dispatch();
?>
