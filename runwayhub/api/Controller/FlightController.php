<?php

declare(strict_types=1);

namespace RunwayHub\Api\Controller;

use RunwayHub\Core\Controller as BaseController;
use RunwayHub\Core\Request;
use RunwayHub\Core\Response;

class FlightController extends BaseController
{
    /**
     * @var string Last insert ID counter
     */
    protected int $lastId = 1000;

    /**
     * FlightController constructor
     */
    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
    }

    /**
     * Get flight by flight number
     */
    public function getFlight(Response $response): Response
    {
        $flightNumber = $this->request->getGet('number') ?? 'LH456';
        
        // Simulated flight data
        $flight = [
            'flightNumber' => $flightNumber,
            'callsign' => str_pad((int)substr($flightNumber, -3), 3, '0', STR_PAD_LEFT),
            'origin' => 'EDDF',
            'destination' => 'KJFK',
            'departureTime' => '2026-05-27T14:30:00Z',
            'arrivalTime' => '2026-05-27T17:45:00Z',
            'status' => 'scheduled',
            'aircraft' => 'Boeing 737-800',
            'registration' => 'D-AIMA',
            'airline' => 'Lufthansa',
            'distanceKm' => 6206,
            'gate' => 'A12',
            'terminal' => '1',
            'baggage' => 'B7',
        ];

        $response->contentType('application/json');
        $response->content(json_encode([
            'success' => true,
            'data' => $flight,
        ]))->send();
        return $response;
    }

    /**
     * Get flight status
     */
    public function getStatus(Response $response): Response
    {
        // Simulated flight status tracking
        $status = [
            'flights' => [
                [
                    'flightNumber' => 'LH456',
                    'status' => 'en-route',
                    'progress' => 65,
                    'eta' => '2026-05-27T17:30:00Z',
                    'altitude' => 37000,
                    'speed' => 485,
                    'latitude' => 48.5,
                    'longitude' => -5.2,
                ],
                [
                    'flightNumber' => 'BA123',
                    'status' => 'scheduled',
                    'progress' => 0,
                    'eta' => '2026-05-27T18:00:00Z',
                    'gate' => 'B23',
                ],
            ],
            'timestamp' => time(),
        ];

        $response->contentType('application/json');
        $response->content(json_encode([
            'success' => true,
            'data' => $status,
        ]))->send();
        return $response;
    }

    /**
     * Get all flights
     */
    public function getAll(): Response
    {
        // Simulated flights list
        $flights = [
            [
                'id' => $this->lastId++,
                'flightNumber' => 'LH456',
                'origin' => 'EDDF',
                'destination' => 'KJFK',
                'departure' => '2026-05-27T14:30:00Z',
                'arrival' => '2026-05-27T17:45:00Z',
                'status' => 'en-route',
                'aircraft' => 'Boeing 737-800',
            ],
            [
                'id' => $this->lastId++,
                'flightNumber' => 'BA123',
                'origin' => 'EGLL',
                'destination' => 'EDDF',
                'departure' => '2026-05-27T16:00:00Z',
                'arrival' => '2026-05-27T18:30:00Z',
                'status' => 'scheduled',
                'aircraft' => 'Airbus A320',
            ],
            [
                'id' => $this->lastId++,
                'flightNumber' => 'AF1234',
                'origin' => 'LFPG',
                'destination' => 'EDDF',
                'departure' => '2026-05-27T15:15:00Z',
                'arrival' => '2026-05-27T17:00:00Z',
                'status' => 'on-time',
                'aircraft' => 'Airbus A319',
            ],
        ];

        $response->contentType('application/json');
        $response->content(json_encode([
            'success' => true,
            'data' => [
                'flights' => $flights,
                'count' => count($flights),
            ],
        ]))->send();
        return $response;
    }

    /**
     * Create flight
     */
    public function create(): Response
    {
        $flight = [
            'flightNumber' => $this->request->getPost('flight_number') ?? 'UNKNOWN',
            'origin' => $this->request->getPost('origin') ?? '',
            'destination' => $this->request->getPost('destination') ?? '',
            'departure' => $this->request->getPost('departure') ?? '',
            'arrival' => $this->request->getPost('arrival') ?? '',
            'aircraft' => $this->request->getPost('aircraft') ?? '',
            'status' => 'scheduled',
        ];

        $response->contentType('application/json');
        $response->content(json_encode([
            'success' => true,
            'data' => $flight,
            'message' => 'Flight created successfully',
        ]))->send();
        return $response;
    }
}
