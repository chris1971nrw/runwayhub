<?php

declare(strict_types=1);

namespace RunwayHub\Api\Controller;

use RunwayHub\Core\Controller;
use RunwayHub\Core\Request;
use RunwayHub\Core\Response;

class TerminalsController extends Controller
{
    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
    }

    public function getTerminals(Response $response): Response
    {
        $response->contentType('application/json');
        $response->content(json_encode([
            'success' => true,
            'data' => [
                'terminals' => [
                    [
                        'id' => 'TER001',
                        'name' => 'Terminal 2',
                        'airport' => 'EDDF',
                        'airlines' => ['Lufthansa', 'Eurowings'],
                    ],
                ],
            ],
        ]));
        $response->send();
        return $response;
    }
}
