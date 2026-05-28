<?php

declare(strict_types=1);

namespace RunwayHub\Api\Controller;

use RunwayHub\Core\Controller;
use RunwayHub\Core\Request;
use RunwayHub\Core\Response;

class FacilitiesController extends Controller
{
    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
    }

    public function getFacilities(Response $response): Response
    {
        $response->contentType('application/json');
        $response->content(json_encode([
            'success' => true,
            'data' => [
                'facilities' => [
                    [
                        'id' => 'FAC001',
                        'name' => 'Fuel Station',
                        'airport' => 'EDDF',
                        'type' => 'fuel',
                        'location' => 'A1',
                    ],
                    [
                        'id' => 'FAC002',
                        'name' => 'Hangar',
                        'airport' => 'EDDF',
                        'type' => 'hangar',
                        'location' => 'B2',
                    ],
                ],
            ],
        ]));
        $response->send();
        return $response;
    }
}
