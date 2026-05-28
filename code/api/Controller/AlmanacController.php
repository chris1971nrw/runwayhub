<?php

declare(strict_types=1);

namespace RunwayHub\Api\Controller;

use RunwayHub\Core\Controller;
use RunwayHub\Core\Request;
use RunwayHub\Core\Response;

class AlmanacController extends Controller
{
    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
    }

    public function getAlmanac(Response $response): Response
    {
        $response->contentType('application/json');
        $response->content(json_encode([
            'success' => true,
            'data' => [
                'sun' => [
                    'rising' => '06:00:00',
                    'setting' => '20:00:00',
                ],
                'moon' => [
                    'rising' => '18:00:00',
                    'setting' => '08:00:00',
                ],
            ],
        ]));
        $response->send();
        return $response;
    }
}
