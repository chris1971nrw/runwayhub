<?php

declare(strict_types=1);

namespace RunwayHub\Api\Controller;

use RunwayHub\Core\Controller;
use RunwayHub\Core\Request;
use RunwayHub\Core\Response;

class AircraftController extends Controller
{
    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
    }

    public function getAircraft(Response $response): Response
    {
        $response->contentType('application/json');
        $response->content(json_encode([
            'success' => true,
            'data' => [
                'aircraft' => [
                    'type' => 'Boeing 737-800',
                    'manufacturer' => 'Boeing',
                    'model' => 'B738',
                    'registration' => 'D-AIMA',
                    'serial_number' => '30660',
                    'year_manufactured' => 2009,
                    'seats' => 160,
                    'engines' => ['CFM56-7B26', 'CFM56-7B26'],
                ],
            ],
        ]));
        $response->send();
        return $response;
    }
}
