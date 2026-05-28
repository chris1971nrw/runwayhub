<?php

declare(strict_types=1);

namespace RunwayHub\Api\Controller;

use RunwayHub\Core\Controller;
use RunwayHub\Core\Request;
use RunwayHub\Core\Response;

class AirlineController extends Controller
{
    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
    }

    public function getAirlines(Response $response): Response
    {
        $response->contentType('application/json');
        $response->content(json_encode([
            'success' => true,
            'data' => [
                'airlines' => [
                    [
                        'icao' => 'DLH',
                        'iata' => 'LH',
                        'name' => 'Lufthansa',
                        'country' => 'Germany',
                        'fleet' => 250,
                    ],
                    [
                        'icao' => 'AAL',
                        'iata' => 'AA',
                        'name' => 'American Airlines',
                        'country' => 'USA',
                        'fleet' => 800,
                    ],
                ],
            ],
        ]));
        $response->send();
        return $response;
    }
}
