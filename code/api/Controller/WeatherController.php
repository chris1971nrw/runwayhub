<?php

declare(strict_types=1);

namespace RunwayHub\Api\Controller;

use RunwayHub\Core\Controller as BaseController;
use RunwayHub\Core\Request;
use RunwayHub\Core\Response;

class WeatherController extends BaseController
{
    /**
     * @var string API endpoint
     */
    protected string $weatherUrl = 'https://api.open-meteo.com/v1/forecast';

    /**
     * WeatherController constructor
     */
    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
    }

    /**
     * Get weather for airport
     */
    public function getWeather(Response $response): Response
    {
        // Get airport code from request
        $airport = $this->request->getGet('airport') ?? 'EDDF';
        
        // Fetch weather data (simulated for demo)
        $weather = [
            'latitude' => 50.000,
            'longitude' => 8.520,
            'weather_code' => 1,
            'temperature' => 15.0,
            'windspeed' => 12,
            'winddirection' => 270,
            'visibility' => 10000,
            'dewpoint' => 10.0,
            'cloudcover' => 20,
            'precipitation' => 0,
            'sunrise' => '2026-05-27T04:45:00Z',
            'sunset' => '2026-05-27T22:15:00Z',
        ];

        $response->contentType('application/json');
        $response->content(json_encode([
            'success' => true,
            'data' => [
                'airport' => $airport,
                'timestamp' => time(),
                'weather' => $weather,
            ],
        ]))->send();
        return $response;
    }

    /**
     * Get weather alerts
     */
    public function getAlerts(Response $response): Response
    {
        $alerts = [
            'alerts' => [],
            'meta' => [
                'country' => 'DE',
                'generated' => time(),
            ],
        ];

        $response->contentType('application/json');
        $response->content(json_encode($alerts))->send();
        return $response;
    }

    /**
     * Get aviation weather briefing
     */
    public function getAviation(): Response
    {
        $aviation = [
            'taf' => [
                'issue_time' => date('Y-m-d\TH:i:s\Z'),
                'valid_from' => date('Y-m-d\TH:i:s\Z', strtotime('+30 minutes')),
                'valid_to' => date('Y-m-d\TH:i:s\Z', strtotime('+24 hours')),
                'forecast' => [
                    [
                        'time' => date('Y-m-d\TH:i:s\Z'),
                        'wind' => '27010KT',
                        'visibility' => '10SM',
                        'weather' => 'FEW035',
                        'clouds' => 'FEW035 SCT250',
                        'temperature' => 15,
                        'dewpoint' => 10,
                    ],
                ],
            ],
            'metar' => [
                'time' => date('Y-m-d\TH:i:s\Z'),
                'station' => 'EDDF',
                'report' => 'EDDF 271000Z 27010KT 9999 FEW035 15/10 Q1015',
            ],
        ];

        $response->contentType('application/json');
        $response->content(json_encode([
            'success' => true,
            'data' => $aviation,
        ]))->send();
        return $response;
    }

    /**
     * Get weather for multiple airports
     */
    public function getMulti(): Response
    {
        $airports = ['EDDF', 'EDDM', 'EDDL', 'EDDH', 'EDDK'];
        
        $multiWeather = [
            'airports' => array_map(function($airport) {
                return [
                    'airport' => $airport,
                    'temperature' => mt_rand(10, 20),
                    'windspeed' => mt_rand(5, 20),
                    'winddirection' => mt_rand(180, 360),
                    'visibility' => mt_rand(5000, 10000),
                    'precipitation' => 0,
                ];
            }, $airports),
            'timestamp' => time(),
        ];

        $response->contentType('application/json');
        $response->content(json_encode([
            'success' => true,
            'data' => $multiWeather,
        ]))->send();
        return $response;
    }
}
