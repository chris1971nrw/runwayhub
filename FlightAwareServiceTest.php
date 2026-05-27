<?php
/**
 * FlightAwareServiceTest.php
 * Comprehensive test suite for FlightAware API endpoints
 * 
 * Tests:
 * - Flight tracking
 * - Airline listing
 * - Flight search
 * - ETA calculations
 * - Error handling
 */

namespace RunwayHub\Tests\Unit;

use RunwayHub\Services\FlightAwareService;
use PHPUnit\Framework\TestCase;
use Mockery;

class FlightAwareServiceTest extends TestCase
{
    private FlightAwareService $flightService;
    private $mockHttpClient;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mockHttpClient = Mockery::mock('RunwayHub\Http\Client');
        $this->flightService = new FlightAwareService($this->mockHttpClient);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Test flight tracking with flight number
     */
    public function testFlightTracking(): void
    {
        $flightNumber = 'AA123';
        
        // Mock flight status API response
        $this->mockHttpClient
            ->shouldReceive('get')
            ->withPath('/flights/status', ['flight' => $flightNumber])
            ->andReturn([
                'flight' => $flightNumber,
                'flight_status' => 2, // En route
                'last_position' => [
                    'lat' => 40.7128,
                    'lon' => -74.0060,
                ],
                'aircraft' => [
                    'tail_number' => 'N123AA',
                    'manufacturer' => 'Boeing',
                    'model' => '737-800',
                ],
                'airline' => [
                    'name' => 'American Airlines',
                    'iata' => 'AA',
                ],
                'origin' => [
                    'airport' => 'KLAX',
                    'city' => 'Los Angeles',
                    'country' => 'United States',
                ],
                'destination' => [
                    'airport' => 'KJFK',
                    'city' => 'New York',
                    'country' => 'United States',
                ],
                'dep_time' => '2026-05-27T18:00:00Z',
                'est_dep_time' => '2026-05-27T18:00:00Z',
                'act_dep_time' => '2026-05-27T18:05:00Z',
                'arr_time' => '2026-05-27T22:00:00Z',
                'est_arr_time' => '2026-05-27T22:00:00Z',
                'act_arr_time' => null,
                'air_time' => 14400,
                'flight_date' => '2026-05-27',
            ]);

        $result = $this->flightService->getFlightStatus($flightNumber);
        
        $this->assertEquals($flightNumber, $result['flight']);
        $this->assertEquals(2, $result['flight_status']); // En route
        $this->assertArrayHasKey('last_position', $result);
        $this->assertArrayHasKey('aircraft', $result);
        $this->assertArrayHasKey('airline', $result);
        $this->assertArrayHasKey('origin', $result);
        $this->assertArrayHasKey('destination', $result);
    }

    /**
     * Test flight tracking for cancelled flight
     */
    public function testCancelledFlight(): void
    {
        $flightNumber = 'UA456';
        
        // Mock cancelled flight response
        $this->mockHttpClient
            ->shouldReceive('get')
            ->withPath('/flights/status', ['flight' => $flightNumber])
            ->andReturn([
                'flight' => $flightNumber,
                'flight_status' => 1, // Cancelled
                'flight_status_str' => 'CANCELLED',
                'cancellation' => 'Airline Cancellation',
            ]);

        $result = $this->flightService->getFlightStatus($flightNumber);
        
        $this->assertEquals($flightNumber, $result['flight']);
        $this->assertEquals(1, $result['flight_status']);
        $this->assertEquals('CANCELLED', $result['flight_status_str']);
    }

    /**
     * Test airline listing
     */
    public function testAirlineListing(): void
    {
        // Mock airline listing API response
        $this->mockHttpClient
            ->shouldReceive('get')
            ->withPath('/airlines')
            ->andReturn([
                'airlines' => [
                    [
                        'name' => 'Delta Air Lines',
                        'iata' => 'DL',
                        'icao' => 'DAL',
                        'callsign' => 'DELTA',
                        'country' => 'United States',
                    ],
                    [
                        'name' => 'United Airlines',
                        'iata' => 'UA',
                        'icao' => 'UAL',
                        'callsign' => 'UNITED',
                        'country' => 'United States',
                    ],
                    [
                        'name' => 'Lufthansa',
                        'iata' => 'LH',
                        'icao' => 'DLH',
                        'callsign' => 'LUFTHANSA',
                        'country' => 'Germany',
                    ],
                ],
            ]);

        $airlines = $this->flightService->getAirlines();
        
        $this->assertCount(3, $airlines['airlines']);
        $this->assertEquals('Delta Air Lines', $airlines['airlines'][0]['name']);
        $this->assertEquals('DL', $airlines['airlines'][0]['iata']);
    }

    /**
     * Test airline lookup by IATA code
     */
    public function testAirlineLookup(): void
    {
        $this->mockHttpClient
            ->shouldReceive('get')
            ->withPath('/airlines', ['iata' => 'LH'])
            ->andReturn([
                'airline' => [
                    'name' => 'Lufthansa',
                    'iata' => 'LH',
                    'icao' => 'DLH',
                    'callsign' => 'LUFTHANSA',
                    'country' => 'Germany',
                ],
            ]);

        $result = $this->flightService->getAirlineByCode('LH');
        
        $this->assertEquals('Lufthansa', $result['name']);
        $this->assertEquals('DLH', $result['icao']);
    }

    /**
     * Test flight search by number range
     */
    public function testFlightSearch(): void
    {
        $flightNumber = 'AA123';
        
        // Mock flight search API response
        $this->mockHttpClient
            ->shouldReceive('get')
            ->withPath('/flights/search', ['flightnumber' => $flightNumber])
            ->andReturn([
                'flights' => [
                    [
                        'flight_number' => 'AA123',
                        'airline' => [
                            'name' => 'American Airlines',
                            'iata' => 'AA',
                        ],
                        'origin' => [
                            'airport' => 'KLAX',
                            'city' => 'Los Angeles',
                        ],
                        'destination' => [
                            'airport' => 'KJFK',
                            'city' => 'New York',
                        ],
                        'dep_time' => '2026-05-27T18:00:00Z',
                        'arr_time' => '2026-05-27T22:00:00Z',
                        'status' => 'On time',
                    ],
                ],
            ]);

        $result = $this->flightService->searchFlight($flightNumber);
        
        $this->assertCount(1, $result['flights']);
        $this->assertEquals($flightNumber, $result['flights'][0]['flight_number']);
    }

    /**
     * Test flight search by route
     */
    public function testFlightSearchByRoute(): void
    {
        $origin = 'KLAX';
        $destination = 'KJFK';
        
        // Mock route search API response
        $this->mockHttpClient
            ->shouldReceive('get')
            ->withPath('/flights/search', ['origin' => $origin, 'destination' => $destination, 'date' => '2026-05-27'])
            ->andReturn([
                'flights' => [
                    [
                        'flight_number' => 'AA100',
                        'airline' => [
                            'name' => 'American Airlines',
                            'iata' => 'AA',
                        ],
                        'origin' => [
                            'airport' => 'KLAX',
                            'city' => 'Los Angeles',
                        ],
                        'destination' => [
                            'airport' => 'KJFK',
                            'city' => 'New York',
                        ],
                        'dep_time' => '2026-05-27T06:00:00Z',
                        'arr_time' => '2026-05-27T10:00:00Z',
                        'status' => 'On time',
                    ],
                    [
                        'flight_number' => 'DL200',
                        'airline' => [
                            'name' => 'Delta Air Lines',
                            'iata' => 'DL',
                        ],
                        'origin' => [
                            'airport' => 'KLAX',
                            'city' => 'Los Angeles',
                        ],
                        'destination' => [
                            'airport' => 'KJFK',
                            'city' => 'New York',
                        ],
                        'dep_time' => '2026-05-27T07:30:00Z',
                        'arr_time' => '2026-05-27T11:30:00Z',
                        'status' => 'Delayed',
                    ],
                ],
            ]);

        $result = $this->flightService->searchFlightByRoute($origin, $destination, '2026-05-27');
        
        $this->assertCount(2, $result['flights']);
        $this->assertEquals($origin, $result['flights'][0]['origin']['airport']);
        $this->assertEquals($destination, $result['flights'][0]['destination']['airport']);
    }

    /**
     * Test ETA calculations
     */
    public function testETACalculations(): void
    {
        $flightData = [
            'dep_time' => '2026-05-27T18:00:00Z',
            'est_dep_time' => '2026-05-27T18:05:00Z',
            'arr_time' => '2026-05-27T22:00:00Z',
            'est_arr_time' => '2026-05-27T22:00:00Z',
            'act_arr_time' => '2026-05-27T22:10:00Z',
            'air_time' => 14400, // 4 hours
        ];

        // Test estimated arrival time calculation
        $estimatedArrival = $this->flightService->calculateEstimatedArrival($flightData);
        $this->assertEquals('2026-05-27T22:00:00Z', $estimatedArrival);

        // Test actual arrival time calculation
        $actualArrival = $this->flightService->calculateActualArrival($flightData);
        $this->assertEquals('2026-05-27T22:10:00Z', $actualArrival);

        // Test delay calculation
        $estimatedDeparture = $this->flightService->calculateDepartureDelay($flightData);
        $this->assertEquals(5, $estimatedDeparture); // 5 minutes delay

        // Test if flight is on time
        $isOnTime = $this->flightService->isFlightOnTime($flightData);
        $this->assertFalse($isOnTime); // Has a 5 minute delay
    }

    /**
     * Test delay time calculation
     */
    public function testDelayCalculation(): void
    {
        $testCases = [
            [
                'name' => 'On time',
                'data' => [
                    'dep_time' => '2026-05-27T18:00:00Z',
                    'est_dep_time' => '2026-05-27T18:00:00Z',
                    'act_dep_time' => null,
                ],
                'expectedDelay' => 0,
            ],
            [
                'name' => 'Delayed departure',
                'data' => [
                    'dep_time' => '2026-05-27T18:00:00Z',
                    'est_dep_time' => '2026-05-27T18:30:00Z',
                    'act_dep_time' => null,
                ],
                'expectedDelay' => 30,
            ],
            [
                'name' => 'Early departure',
                'data' => [
                    'dep_time' => '2026-05-27T18:00:00Z',
                    'est_dep_time' => '2026-05-27T17:30:00Z',
                    'act_dep_time' => null,
                ],
                'expectedDelay' => -30,
            ],
        ];

        foreach ($testCases as $testCase) {
            $delay = $this->flightService->calculateDepartureDelay($testCase['data']);
            $this->assertEquals($testCase['expectedDelay'], $delay, $testCase['name']);
        }
    }

    /**
     * Test flight duration calculation
     */
    public function testFlightDurationCalculation(): void
    {
        $flightData = [
            'air_time' => 14400, // 4 hours in seconds
            'dep_time' => '2026-05-27T18:00:00Z',
            'arr_time' => '2026-05-27T22:00:00Z',
        ];

        $durationHours = $this->flightService->calculateFlightDuration($flightData);
        $this->assertEquals(4, $durationHours);

        $durationMinutes = $this->flightService->calculateFlightDuration($flightData, 'minutes');
        $this->assertEquals(240, $durationMinutes);

        $durationSeconds = $this->flightService->calculateFlightDuration($flightData, 'seconds');
        $this->assertEquals(14400, $durationSeconds);
    }

    /**
     * Test arrival status calculation
     */
    public function testArrivalStatusCalculation(): void
    {
        // Test landed
        $landedData = [
            'dep_time' => '2026-05-27T18:00:00Z',
            'arr_time' => '2026-05-27T22:00:00Z',
            'act_arr_time' => '2026-05-27T22:05:00Z',
            'flight_status' => 4, // Landed
        ];

        $status = $this->flightService->calculateArrivalStatus($landedData);
        $this->assertEquals('Landed', $status);

        // Test arriving soon
        $arrivingData = [
            'dep_time' => '2026-05-27T18:00:00Z',
            'arr_time' => '2026-05-27T22:00:00Z',
            'act_arr_time' => null,
            'last_position' => [
                'lat' => 39.0,
                'lon' => -100.0,
            ],
            'flight_status' => 2, // En route
        ];

        $status = $this->flightService->calculateArrivalStatus($arrivingData);
        $this->assertEquals('Arriving soon', $status);

        // Test delayed
        $delayedData = [
            'dep_time' => '2026-05-27T18:00:00Z',
            'arr_time' => '2026-05-27T22:00:00Z',
            'act_arr_time' => '2026-05-27T23:30:00Z',
            'flight_status' => 2, // En route
        ];

        $status = $this->flightService->calculateArrivalStatus($delayedData);
        $this->assertEquals('Delayed', $status);
    }

    /**
     * Test airline listing with pagination
     */
    public function testAirlineListingPagination(): void
    {
        $page = 1;
        $perPage = 20;
        
        // Mock paginated response
        $this->mockHttpClient
            ->shouldReceive('get')
            ->withPath('/airlines', ['page' => $page, 'per_page' => $perPage])
            ->andReturn([
                'airlines' => [
                    ['name' => 'Airline A'],
                    ['name' => 'Airline B'],
                ],
                'meta' => [
                    'page' => $page,
                    'per_page' => $perPage,
                    'total' => 100,
                    'total_pages' => 5,
                ],
            ]);

        $result = $this->flightService->getAirlines($page, $perPage);
        
        $this->assertCount(2, $result['airlines']);
        $this->assertEquals($page, $result['meta']['page']);
        $this->assertEquals(100, $result['meta']['total']);
    }

    /**
     * Test error handling for invalid flight numbers
     */
    public function testErrorHandlingInvalidFlight(): void
    {
        $invalidFlight = 'INVALID123';
        
        // Mock invalid flight response
        $this->mockHttpClient
            ->shouldReceive('get')
            ->withPath('/flights/status', ['flight' => $invalidFlight])
            ->andReturn(['error' => 'Flight not found']);

        try {
            $result = $this->flightService->getFlightStatus($invalidFlight);
            $this->assertStringContainsString('Flight not found', json_encode($result));
        } catch (\Exception $e) {
            $this->assertEquals('Flight not found', $e->getMessage());
        }
    }

    /**
     * Test error handling for route not found
     */
    public function testErrorHandlingNotFoundRoute(): void
    {
        $this->mockHttpClient
            ->shouldReceive('get')
            ->withPath('/flights/search', ['origin' => 'XXX', 'destination' => 'YYY'])
            ->andReturn(['error' => 'Route not found']);

        $result = $this->flightService->searchFlightByRoute('XXX', 'YYY', '2026-05-27');
        
        $this->assertStringContainsString('Route not found', json_encode($result));
    }

    /**
     * Test error handling for API rate limiting
     */
    public function testErrorHandlingRateLimit(): void
    {
        $this->mockHttpClient
            ->shouldReceive('get')
            ->withPath('/flights/status')
            ->andReturn(['error' => 'Rate limit exceeded']);

        $result = $this->flightService->getFlightStatus('AA123');
        
        $this->assertStringContainsString('Rate limit', json_encode($result));
    }

    /**
     * Test flight status codes
     */
    public function testFlightStatusCodes(): void
    {
        $statusCodes = [
            1 => 'Cancelled',
            2 => 'En route',
            3 => 'On the ground',
            4 => 'Landed',
            5 => 'Scheduled',
            12 => 'Boarding',
            47 => 'Airborne',
            99 => 'Error',
        ];

        foreach ($statusCodes as $code => $name) {
            $flightData = [
                'flight' => 'AA123',
                'flight_status' => $code,
            ];

            $status = $this->flightService->getFlightStatusName($code);
            $this->assertEquals($name, $status);
        }
    }

    /**
     * Test aircraft type extraction
     */
    public function testAircraftTypeExtraction(): void
    {
        $flightData = [
            'aircraft' => [
                'tail_number' => 'N123AA',
                'manufacturer' => 'Boeing',
                'model' => '737-800',
                'type' => '737-800',
            ],
        ];

        $aircraftType = $this->flightService->getAircraftType($flightData);
        $this->assertEquals('737-800', $aircraftType);

        // Test with only model available
        $flightData['aircraft']['model'] = 'A320';
        $aircraftType = $this->flightService->getAircraftType($flightData);
        $this->assertEquals('A320', $aircraftType);
    }

    /**
     * Test airport information retrieval
     */
    public function testAirportInformation(): void
    {
        $this->mockHttpClient
            ->shouldReceive('get')
            ->withPath('/airports', ['airport' => 'KLAX'])
            ->andReturn([
                'airport' => [
                    'airport' => 'KLAX',
                    'name' => 'Los Angeles International Airport',
                    'city' => 'Los Angeles',
                    'country' => 'United States',
                    'iata' => 'LAX',
                    'icao' => 'KLAX',
                    'latitude' => 33.9425,
                    'longitude' => -118.4081,
                    'timezone' => 'America/Los_Angeles',
                ],
            ]);

        $result = $this->flightService->getAirportInfo('KLAX');
        
        $this->assertEquals('Los Angeles International Airport', $result['name']);
        $this->assertEquals('KLAX', $result['iata']);
        $this->assertEquals('America/Los_Angeles', $result['timezone']);
    }

    /**
     * Test flight search with date range
     */
    public function testFlightSearchDateRange(): void
    {
        $this->mockHttpClient
            ->shouldReceive('get')
            ->withPath('/flights/search', [
                'origin' => 'KLAX',
                'destination' => 'KJFK',
                'start_date' => '2026-05-27',
                'end_date' => '2026-05-29',
            ])
            ->andReturn([
                'flights' => [
                    [
                        'flight_number' => 'AA100',
                        'dep_date' => '2026-05-27',
                        'dep_time' => '2026-05-27T06:00:00Z',
                        'arr_time' => '2026-05-27T10:00:00Z',
                        'status' => 'On time',
                    ],
                    [
                        'flight_number' => 'DL200',
                        'dep_date' => '2026-05-27',
                        'dep_time' => '2026-05-27T07:30:00Z',
                        'arr_time' => '2026-05-27T11:30:00Z',
                        'status' => 'Delayed',
                    ],
                ],
            ]);

        $result = $this->flightService->searchFlightByDateRange(
            'KLAX',
            'KJFK',
            '2026-05-27',
            '2026-05-29'
        );
        
        $this->assertCount(2, $result['flights']);
    }

    /**
     * Test flight search with airline filter
     */
    public function testFlightSearchWithAirlineFilter(): void
    {
        $this->mockHttpClient
            ->shouldReceive('get')
            ->withPath('/flights/search', [
                'origin' => 'KLAX',
                'destination' => 'KJFK',
                'airline' => 'AA',
            ])
            ->andReturn([
                'flights' => [
                    [
                        'flight_number' => 'AA100',
                        'airline' => [
                            'name' => 'American Airlines',
                            'iata' => 'AA',
                        ],
                        'dep_time' => '2026-05-27T06:00:00Z',
                        'arr_time' => '2026-05-27T10:00:00Z',
                        'status' => 'On time',
                    ],
                ],
            ]);

        $result = $this->flightService->searchFlightByAirline('AA', 'KLAX', 'KJFK');
        
        $this->assertCount(1, $result['flights']);
        $this->assertEquals('AA', $result['flights'][0]['airline']['iata']);
    }

    /**
     * Test historical flight data
     */
    public function testHistoricalFlightData(): void
    {
        $this->mockHttpClient
            ->shouldReceive('get')
            ->withPath('/flights/historical', ['flight' => 'AA123', 'date' => '2026-05-20'])
            ->andReturn([
                'flight' => 'AA123',
                'flight_date' => '2026-05-20',
                'origin' => [
                    'airport' => 'KLAX',
                    'city' => 'Los Angeles',
                ],
                'destination' => [
                    'airport' => 'KJFK',
                    'city' => 'New York',
                ],
                'dep_time' => '2026-05-20T18:00:00Z',
                'arr_time' => '2026-05-20T22:00:00Z',
                'act_dep_time' => '2026-05-20T18:05:00Z',
                'act_arr_time' => '2026-05-20T22:05:00Z',
            ]);

        $result = $this->flightService->getHistoricalFlight('AA123', '2026-05-20');
        
        $this->assertEquals('2026-05-20', $result['flight_date']);
    }
}
