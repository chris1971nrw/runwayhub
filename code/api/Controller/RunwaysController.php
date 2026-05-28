<?php

declare(strict_types=1);

namespace RunwayHub\Api\Controller;

use RunwayHub\Core\Controller;
use RunwayHub\Core\Request;
use RunwayHub\Core\Response;

class RunwaysController extends Controller
{
    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
    }

    public function getRunways(Response $response): Response
    {
        $response->contentType('application/json');
        $response->content(json_encode([
            'success' => true,
            'data' => [
                'runways' => [
                    [
                        'id' => 'RWY001',
                        'name' => 'Runway 09L/27R',
                        'airport' => 'EDDF',
                        'length' => 3600,
                        'width' => 45,
                        'surface' => 'Concrete',
                        'lighted' => true,
                    ],
                ],
            ],
        ]));
        $response->send();
        return $response;
    }
}
