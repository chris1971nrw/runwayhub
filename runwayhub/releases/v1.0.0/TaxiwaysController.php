<?php

declare(strict_types=1);

namespace RunwayHub\Api\Controller;

use RunwayHub\Core\Controller;
use RunwayHub\Core\Request;
use RunwayHub\Core\Response;

class TaxiwaysController extends Controller
{
    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
    }

    public function getTaxiways(Response $response): Response
    {
        $response->contentType('application/json');
        $response->content(json_encode([
            'success' => true,
            'data' => [
                'taxiways' => [
                    [
                        'id' => 'TWY001',
                        'name' => 'Taxiway Alpha',
                        'airport' => 'EDDF',
                        'length' => 1500,
                        'surface' => 'Concrete',
                    ],
                ],
            ],
        ]));
        $response->send();
        return $response;
    }
}
