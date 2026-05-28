<?php

declare(strict_types=1);

namespace RunwayHub\Api\Controller;

use RunwayHub\Core\Controller;
use RunwayHub\Core\Request;
use RunwayHub\Core\Response;

class AirportController extends Controller
{
    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
    }

    public function getAirport(Response $response): Response
    {
        $airport = $this->request->get('airport');
        
        if (empty($airport)) {
            $response->status(400);
            $response->contentType('application/json');
            $response->content(json_encode([
                'success' => false,
                'error' => true,
                'message' => 'Missing airport parameter',
            ]));
            $response->send();
            return $response;
        }

        // Mock response - replace with real implementation
        $response->contentType('application/json');
        $response->content(json_encode([
            'success' => true,
            'data' => [
                'airport' => strtoupper($airport),
                'name' => "Airfield {$airport}",
                'iata' => 'XX',
                'icao' => 'XXXX',
                'latitude' => 50.0,
                'longitude' => 8.5,
                'elevation' => 500,
                'timezone' => 'Europe/Berlin',
            ],
        ]));
        $response->send();
        return $response;
    }
}
