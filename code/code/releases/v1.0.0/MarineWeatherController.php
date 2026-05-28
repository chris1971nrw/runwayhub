<?php

declare(strict_types=1);

namespace RunwayHub\Api\Controller;

use RunwayHub\Core\Controller;
use RunwayHub\Core\Request;
use RunwayHub\Core\Response;

class MarineWeatherController extends Controller
{
    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
    }

    public function getMarineWeather(Response $response): Response
    {
        $response->contentType('application/json');
        $response->content(json_encode([
            'success' => true,
            'data' => [
                'marine' => [
                    'wave_height' => 2.5,
                    'wind_speed' => 15,
                    'visibility' => 10,
                    'conditions' => 'rough',
                ],
            ],
        ]));
        $response->send();
        return $response;
    }
}
