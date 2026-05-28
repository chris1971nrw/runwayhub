<?php

declare(strict_types=1);

namespace RunwayHub\Api\Controller;

use RunwayHub\Core\Controller;
use RunwayHub\Core\Request;
use RunwayHub\Core\Response;

class GatesController extends Controller
{
    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
    }

    public function getGates(Response $response): Response
    {
        $response->contentType('application/json');
        $response->content(json_encode([
            'success' => true,
            'data' => [
                'gates' => [
                    [
                        'id' => 'GATE001',
                        'name' => 'Gate T2-A1',
                        'terminal' => 'T2',
                        'airline' => 'Lufthansa',
                        'position' => '1A',
                    ],
                ],
            ],
        ]));
        $response->send();
        return $response;
    }
}
