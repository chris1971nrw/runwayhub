<?php

declare(strict_types=1);

namespace RunwayHub\Api\Controller;

use RunwayHub\Core\Controller;
use RunwayHub\Core\Request;
use RunwayHub\Core\Response;

class TurbulenceController extends Controller
{
    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
    }

    public function getTurbulence(Response $response): Response
    {
        $response->contentType('application/json');
        $response->content(json_encode([
            'success' => true,
            'data' => [
                'turbulence' => [
                    'type' => 'moderate',
                    'altitude' => 35000,
                    'region' => 'EDDF-EDDL',
                ],
            ],
        ]));
        $response->send();
        return $response;
    }
}
