<?php

declare(strict_types=1);

namespace RunwayHub\Api\Controller;

use RunwayHub\Core\Controller;
use RunwayHub\Core\Request;
use RunwayHub\Core\Response;

class StatisticsController extends Controller
{
    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
    }

    public function getStatistics(Response $response): Response
    {
        $response->contentType('application/json');
        $response->content(json_encode([
            'success' => true,
            'data' => [
                'flights' => [
                    'total' => 12345,
                    'today' => 567,
                    'on_time' => 9850,
                    'late' => 1495,
                    'cancelled' => 0,
                ],
                'fleet' => [
                    'total' => 125,
                    'active' => 118,
                    'maintenance' => 5,
                    'stored' => 2,
                ],
                'airlines' => [
                    'total' => 10,
                    'active' => 8,
                    'partners' => 5,
                ],
                'revenue' => [
                    'monthly' => 500000.00,
                    'yearly' => 6000000.00,
                ],
            ],
        ]));
        $response->send();
        return $response;
    }
}
