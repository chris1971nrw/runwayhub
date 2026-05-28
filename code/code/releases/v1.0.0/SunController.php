<?php

declare(strict_types=1);

namespace RunwayHub\Api\Controller;

use RunwayHub\Core\Controller;
use RunwayHub\Core\Request;
use RunwayHub\Core\Response;

class SunController extends Controller
{
    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
    }

    public function getSunrise(Response $response): Response
    {
        $response->contentType('application/json');
        $response->content(json_encode([
            'success' => true,
            'data' => [
                'sun' => [
                    'rising' => '06:00:00',
                    'setting' => '20:00:00',
                ],
            ],
        ]));
        $response->send();
        return $response;
    }

    public function getDayLength(Response $response): Response
    {
        $response->contentType('application/json');
        $response->content(json_encode([
            'success' => true,
            'data' => [
                'day_length' => 14400,
            ],
        ]));
        $response->send();
        return $response;
    }
}
