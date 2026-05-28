<?php

declare(strict_types=1);

namespace RunwayHub\Api\Controller;

use RunwayHub\Core\Controller;
use RunwayHub\Core\Request;
use RunwayHub\Core\Response;

class ObstaclesController extends Controller
{
    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
    }

    public function getObstacles(Response $response): Response
    {
        $response->contentType('application/json');
        $response->content(json_encode([
            'success' => true,
            'data' => [
                'obstacles' => [
                    [
                        'id' => 'OBS001',
                        'name' => 'Communication Tower',
                        'airport' => 'EDDF',
                        'height' => 45,
                        'location' => ['latitude' => 48.3547, 'longitude' => 11.7870],
                    ],
                ],
            ],
        ]));
        $response->send();
        return $response;
    }
}
