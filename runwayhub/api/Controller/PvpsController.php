<?php

declare(strict_types=1);

namespace RunwayHub\Api\Controller;

use RunwayHub\Core\Controller;
use RunwayHub\Core\Request;
use RunwayHub\Core\Response;

class PvpsController extends Controller
{
    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
    }

    public function getPvps(Response $response): Response
    {
        $response->contentType('application/json');
        $response->content(json_encode([
            'success' => true,
            'data' => [
                'pvps' => [
                    [
                        'id' => 'PVP001',
                        'name' => 'PVP Alpha',
                        'location' => 'EDDF',
                        'latitude' => 48.3537,
                        'longitude' => 11.7860,
                    ],
                ],
            ],
        ]));
        $response->send();
        return $response;
    }
}
