<?php

declare(strict_types=1);

namespace RunwayHub\Api\Controller;

use RunwayHub\Core\Controller;
use RunwayHub\Core\Request;
use RunwayHub\Core\Response;

class PIREPController extends Controller
{
    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
    }

    public function getPireps(Response $response): Response
    {
        $response->contentType('application/json');
        $response->content(json_encode([
            'success' => true,
            'data' => [
                'pireps' => [
                    [
                        'id' => 'PIREP001',
                        'pilot' => 'CALLSIGN01',
                        'location' => 'EDDF',
                        'timestamp' => '2026-05-27T10:00:00Z',
                        'weather' => 'FEW035 27010KT',
                        'visibility' => 9999,
                        'remarks' => 'Good conditions',
                    ],
                ],
            ],
        ]));
        $response->send();
        return $response;
    }
}
