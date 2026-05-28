<?php

declare(strict_types=1);

namespace RunwayHub\Api\Controller;

use RunwayHub\Core\Controller;
use RunwayHub\Core\Request;
use RunwayHub\Core\Response;

class RouteController extends Controller
{
    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
    }

    public function getRoutes(Response $response): Response
    {
        $response->contentType('application/json');
        $response->content(json_encode([
            'success' => true,
            'data' => [
                'routes' => [
                    [
                        'origin' => 'EDDF',
                        'destination' => 'EDDL',
                        'distance' => 750,
                        'frequency' => 15,
                        'aircraft_types' => ['A320', 'B737'],
                    ],
                    [
                        'origin' => 'EDDM',
                        'destination' => 'EDDF',
                        'distance' => 250,
                        'frequency' => 45,
                        'aircraft_types' => ['A320', 'B737', 'A319'],
                    ],
                ],
            ],
        ]));
        $response->send();
        return $response;
    }
}
