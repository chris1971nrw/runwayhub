<?php

declare(strict_types=1);

namespace RunwayHub\Api\Controller;

use RunwayHub\Core\Controller;
use RunwayHub\Core\Request;
use RunwayHub\Core\Response;

class FrequenciesController extends Controller
{
    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
    }

    public function getFrequencies(Response $response): Response
    {
        $response->contentType('application/json');
        $response->content(json_encode([
            'success' => true,
            'data' => [
                'frequencies' => [
                    [
                        'type' => 'ATIS',
                        'name' => 'ATIS FRA',
                        'frequency' => 118.0,
                        'location' => 'EDDF',
                    ],
                    [
                        'type' => 'Tower',
                        'name' => 'Tower 1',
                        'frequency' => 118.7,
                        'location' => 'EDDF',
                    ],
                ],
            ],
        ]));
        $response->send();
        return $response;
    }
}
