<?php

declare(strict_types=1);

namespace RunwayHub\Api\Controller;

use RunwayHub\Core\Controller;
use RunwayHub\Core\Request;
use RunwayHub\Core\Response;

class PilotController extends Controller
{
    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
    }

    public function getPilots(Response $response): Response
    {
        $response->contentType('application/json');
        $response->content(json_encode([
            'success' => true,
            'data' => [
                'pilots' => [
                    [
                        'callsign' => 'CALLSIGN01',
                        'name' => 'Max Mustermann',
                        'license' => 'ATPL',
                        'type_rating' => ['A320', 'B737'],
                        'hours' => 3500,
                        'status' => 'active',
                    ],
                ],
            ],
        ]));
        $response->send();
        return $response;
    }

    public function getPilot(Response $response): Response
    {
        $callsign = $this->request->get('callsign');
        
        if (empty($callsign)) {
            $response->status(400);
            $response->contentType('application/json');
            $response->content(json_encode([
                'success' => false,
                'error' => true,
                'message' => 'Missing callsign parameter',
            ]));
            $response->send();
            return $response;
        }

        $response->contentType('application/json');
        $response->content(json_encode([
            'success' => true,
            'data' => [
                'pilot' => [
                    'callsign' => $callsign,
                    'name' => 'Max Mustermann',
                    'license' => 'ATPL',
                    'type_rating' => ['A320', 'B737'],
                    'hours' => 3500,
                    'status' => 'active',
                ],
            ],
        ]));
        $response->send();
        return $response;
    }
}
