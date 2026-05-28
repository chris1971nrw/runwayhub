<?php

declare(strict_types=1);

namespace RunwayHub\Api\Controller;

use RunwayHub\Core\Controller;
use RunwayHub\Core\Request;
use RunwayHub\Core\Response;

class NotamsController extends Controller
{
    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
    }

    public function getNotams(Response $response): Response
    {
        $response->contentType('application/json');
        $response->content(json_encode([
            'success' => true,
            'data' => [
                'notams' => [
                    [
                        'id' => 'NOTAM001',
                        'airport' => 'EDDF',
                        'valid_from' => '2026-05-27T00:00:00Z',
                        'valid_to' => '2026-06-27T23:59:59Z',
                        'message' => 'Runway 09L/27R closed for maintenance',
                    ],
                ],
            ],
        ]));
        $response->send();
        return $response;
    }
}
