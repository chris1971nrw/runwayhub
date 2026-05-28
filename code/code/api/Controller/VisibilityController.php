<?php

declare(strict_types=1);

namespace RunwayHub\Api\Controller;

use RunwayHub\Core\Controller;
use RunwayHub\Core\Request;
use RunwayHub\Core\Response;

class VisibilityController extends Controller
{
    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
    }

    public function getVisibility(Response $response): Response
    {
        $response->contentType('application/json');
        $response->content(json_encode([
            'success' => true,
            'data' => [
                'visibility' => [
                    'distance' => 9999,
                    'conditions' => 'clear',
                ],
            ],
        ]));
        $response->send();
        return $response;
    }
}
