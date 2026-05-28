<?php

declare(strict_types=1);

namespace RunwayHub\Api\Controller;

use RunwayHub\Core\Controller as BaseController;
use RunwayHub\Core\Request;
use RunwayHub\Core\Response;

class LeaderboardController extends BaseController
{
    /**
     * LeaderboardController constructor
     */
    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
    }

    /**
     * Get pilot leaderboard
     */
    public function pilots(): Response
    {
        // Simulated leaderboard data
        $pilots = [
            [
                'rank' => 1,
                'callsign' => 'LUFTHA',
                'pilot' => 'Andreas Müller',
                'flights' => 234,
                'hours' => 567.5,
                'miles' => 45670,
                'rating' => 4.9,
                'airline' => 'Lufthansa',
            ],
            [
                'rank' => 2,
                'callsign' => 'BAVARIA',
                'pilot' => 'Stefan Weber',
                'flights' => 212,
                'hours' => 523.2,
                'miles' => 42100,
                'rating' => 4.8,
                'airline' => 'Bavaria Airlines',
            ],
            [
                'rank' => 3,
                'callsign' => 'AIRBERN',
                'pilot' => 'Michael Schmidt',
                'flights' => 198,
                'hours' => 495.8,
                'miles' => 38900,
                'rating' => 4.7,
                'airline' => 'AirBern',
            ],
            [
                'rank' => 4,
                'callsign' => 'SWISSY',
                'pilot' => 'Thomas Klein',
                'flights' => 187,
                'hours' => 456.3,
                'miles' => 36500,
                'rating' => 4.6,
                'airline' => 'Swissy',
            ],
            [
                'rank' => 5,
                'callsign' => 'EUROPA',
                'pilot' => 'Jan Bauer',
                'flights' => 176,
                'hours' => 423.1,
                'miles' => 34200,
                'rating' => 4.5,
                'airline' => 'Europa Airways',
            ],
            [
                'rank' => 6,
                'callsign' => 'ALPINA',
                'pilot' => 'Markus Fischer',
                'flights' => 165,
                'hours' => 398.7,
                'miles' => 32100,
                'rating' => 4.4,
                'airline' => 'Alpina',
            ],
        ];

        $response->contentType('application/json');
        $response->content(json_encode([
            'success' => true,
            'data' => [
                'leaderboard' => $pilots,
                'period' => 'all-time',
                'last_updated' => date('Y-m-d\TH:i:s\Z'),
            ],
        ]))->send();
        return $response;
    }

    /**
     * Get airline leaderboard
     */
    public function airlines(): Response
    {
        // Simulated airline leaderboard
        $airlines = [
            [
                'rank' => 1,
                'airline' => 'Lufthansa',
                'flights' => 1234,
                'customers' => 98500,
                'revenue' => 25000000,
                'fleet_size' => 45,
                'rating' => 4.8,
            ],
            [
                'rank' => 2,
                'airline' => 'Bavaria Airlines',
                'flights' => 987,
                'customers' => 76400,
                'revenue' => 19800000,
                'fleet_size' => 32,
                'rating' => 4.6,
            ],
            [
                'rank' => 3,
                'airline' => 'AirBern',
                'flights' => 856,
                'customers' => 65300,
                'revenue' => 16500000,
                'fleet_size' => 28,
                'rating' => 4.5,
            ],
            [
                'rank' => 4,
                'airline' => 'Swissy',
                'flights' => 743,
                'customers' => 58200,
                'revenue' => 14200000,
                'fleet_size' => 24,
                'rating' => 4.4,
            ],
            [
                'rank' => 5,
                'airline' => 'Europa Airways',
                'flights' => 698,
                'customers' => 52100,
                'revenue' => 12800000,
                'fleet_size' => 22,
                'rating' => 4.3,
            ],
        ];

        $response->contentType('application/json');
        $response->content(json_encode([
            'success' => true,
            'data' => [
                'leaderboard' => $airlines,
                'period' => 'year-to-date',
                'last_updated' => date('Y-m-d\TH:i:s\Z'),
            ],
        ]))->send();
        return $response;
    }

    /**
     * Get airport leaderboard (busiest routes)
     */
    public function airports(): Response
    {
        // Simulated airport leaderboard
        $airports = [
            [
                'rank' => 1,
                'airport' => 'KJFK',
                'name' => 'John F. Kennedy International',
                'flights' => 3456,
                'arrivals' => 1728,
                'departures' => 1728,
                'on_time' => 92,
            ],
            [
                'rank' => 2,
                'airport' => 'EDDF',
                'name' => 'Frankfurt Airport',
                'flights' => 2987,
                'arrivals' => 1493,
                'departures' => 1494,
                'on_time' => 89,
            ],
            [
                'rank' => 3,
                'airport' => 'EGLL',
                'name' => 'London Heathrow',
                'flights' => 2765,
                'arrivals' => 1382,
                'departures' => 1383,
                'on_time' => 87,
            ],
            [
                'rank' => 4,
                'airport' => 'LFPG',
                'name' => 'Paris Charles de Gaulle',
                'flights' => 2543,
                'arrivals' => 1271,
                'departures' => 1272,
                'on_time' => 85,
            ],
            [
                'rank' => 5,
                'airport' => 'EHLR',
                'name' => 'Zurich Airport',
                'flights' => 2234,
                'arrivals' => 1117,
                'departures' => 1117,
                'on_time' => 91,
            ],
        ];

        $response->contentType('application/json');
        $response->content(json_encode([
            'success' => true,
            'data' => [
                'leaderboard' => $airports,
                'metric' => 'flights',
                'last_updated' => date('Y-m-d\TH:i:s\Z'),
            ],
        ]))->send();
        return $response;
    }

    /**
     * Get airline rankings with details
     */
    public function rankings(): Response
    {
        // Detailed airline rankings
        $rankings = [
            [
                'airline' => 'Lufthansa',
                'country' => 'Germany',
                'iata' => 'LH',
                'flights' => 1234,
                'customers' => 98500,
                'satisfaction' => 4.8,
                'market_share' => 28.5,
            ],
            [
                'airline' => 'Bavaria Airlines',
                'country' => 'Germany',
                'iata' => 'BAV',
                'flights' => 987,
                'customers' => 76400,
                'satisfaction' => 4.6,
                'market_share' => 22.1,
            ],
            [
                'airline' => 'AirBern',
                'country' => 'Switzerland',
                'iata' => 'AB',
                'flights' => 856,
                'customers' => 65300,
                'satisfaction' => 4.5,
                'market_share' => 18.9,
            ],
            [
                'airline' => 'Swissy',
                'country' => 'Switzerland',
                'iata' => 'SY',
                'flights' => 743,
                'customers' => 58200,
                'satisfaction' => 4.4,
                'market_share' => 15.7,
            ],
            [
                'airline' => 'Europa Airways',
                'country' => 'Germany',
                'iata' => 'EP',
                'flights' => 698,
                'customers' => 52100,
                'satisfaction' => 4.3,
                'market_share' => 14.8,
            ],
        ];

        $response->contentType('application/json');
        $response->content(json_encode([
            'success' => true,
            'data' => [
                'rankings' => $rankings,
                'last_updated' => date('Y-m-d\TH:i:s\Z'),
            ],
        ]))->send();
        return $response;
    }
}
