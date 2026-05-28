<?php

declare(strict_types=1);

namespace RunwayHub\Api\Controller;

use RunwayHub\Core\Controller;
use RunwayHub\Core\Request;
use RunwayHub\Core\Response;

class MoonController extends Controller
{
    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
    }

    public function getMoonPhase(Response $response): Response
    {
        $response->contentType('application/json');
        $response->content(json_encode([
            'success' => true,
            'data' => [
                'moon' => [
                    'phase' => 0.5,
                    'rising' => '18:00:00',
                    'setting' => '08:00:00',
                ],
            ],
        ]));
        $response->send();
        return $response;
    }
}
