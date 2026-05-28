<?php

declare(strict_types=1);

namespace RunwayHub\Api\Controller;

use RunwayHub\Core\Controller;
use RunwayHub\Core\Request;
use RunwayHub\Core\Response;

class WeatherAlertsController extends Controller
{
    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
    }

    public function getWeatherAlerts(Response $response): Response
    {
        $response->contentType('application/json');
        $response->content(json_encode([
            'success' => true,
            'data' => [
                'alerts' => [
                    [
                        'id' => 'ALERT001',
                        'type' => 'wind',
                        'severity' => 'moderate',
                        'airport' => 'EDDF',
                        'start' => '2026-05-27T12:00:00Z',
                        'end' => '2026-05-27T16:00:00Z',
                        'message' => 'Strong winds expected',
                    ],
                ],
            ],
        ]));
        $response->send();
        return $response;
    }
}
