<?php

declare(strict_types=1);

/**
 * FlightController Test
 * Tests the flight tracking functionality
 */

use runwayhub\Controllers\FlightController;

require_once __DIR__ . '/../bootstrap.php';

class FlightControllerTest extends PHPUnit\Framework\TestCase
{
    protected FlightController $controller;
    protected array $mocks;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new FlightController();
        $this->mocks = new class() {
            public function getFlightStatus($flightNumber, $airlineCode): array
            {
                if ($flightNumber === 'LH400' && $airlineCode === 'LH') {
                    return [
                        'flightNumber' => 'LH400',
                        'airline' => 'Lufthansa',
                        'callsign' => 'DLH400',
                        'status' => 'en-route',
                        'origin' => 'FRA',
                        'destination' => 'JFK',
                        'departure' => '2026-05-28T08:00:00+02:00',
                        'arrival' => '2026-05-28T11:30:00+05:00',
                        'altitude' => 37000,
                        'speed' => 485,
                        'latitude' => 52.5,
                        'longitude' => 10.4,
                        'updated' => '2026-05-28T06:35:00+00:00'
                    ];
                }
                return ['error' => 'Flight not found'];
            }
        };
    }
    
    public function testGetFlightStatus(): void
    {
        $result = $this->controller->getFlightStatus('LH400', 'LH');
        
        $this->assertIsArray($result);
        $this->assertEquals('LH400', $result['flightNumber']);
        $this->assertEquals('en-route', $result['status']);
    }
    
    public function testGetFlightStatusNotFound(): void
    {
        $result = $this->controller->getFlightStatus('AB123', 'AB');
        
        $this->assertArrayHasKey('error', $result);
    }
    
    public function testGetFlightStatusEmptyParams(): void
    {
        $result = $this->controller->getFlightStatus('', '');
        
        $this->assertArrayHasKey('error', $result);
    }
    
    public function testGetFlightStatusInvalidParams(): void
    {
        $result = $this->controller->getFlightStatus(null, null);
        
        $this->assertArrayHasKey('error', $result);
    }
    
    public function testGetArrivals(): void
    {
        $arrivals = $this->controller->getArrivals('JFK');
        
        $this->assertIsArray($arrivals);
    }
    
    public function testGetDepartures(): void
    {
        $departures = $this->controller->getDepartures('FRA');
        
        $this->assertIsArray($departures);
    }
    
    public function testGetDelayedFlights(): void
    {
        $delayed = $this->controller->getDelayedFlights();
        
        $this->assertIsArray($delayed);
    }
}
