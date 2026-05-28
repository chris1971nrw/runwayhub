<?php

declare(strict_types=1);

namespace RunwayHub\Api\Controller;

use RunwayHub\Core\Controller;
use RunwayHub\Core\Request;
use RunwayHub\Core\Response;

class NavaidsController extends Controller
{
    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
    }

    public function getNavaids(Response $response): Response
    {
        $response->contentType('application/json');
        $response->content(json_encode([
            'success' => true,
            'data' => [
                'navaids' => [
                    [
                        'id' => 'NBD001',
                        'type' => 'NDB',
                        'name' => 'Eden NDB',
                        'frequency' => 380.0,
                        'identifier' => 'E',
                        'location' => ['latitude' => 48.3537, 'longitude' => 11.7860],
                    ],
                ],
            ],
        ]));
        $response->send();
        return $response;
    }
}
