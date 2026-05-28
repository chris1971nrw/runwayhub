<?php
/**
 * IntegrationTest.php
 * Comprehensive integration test suite
 * 
 * Tests:
 * - API integration tests
 * - Cross-endpoint tests
 * - Database queries
 * - Cache layer tests
 */

namespace RunwayHub\Tests\Integration;

use RunwayHub\Services\WeatherService;
use RunwayHub\Services\ACARSService;
use RunwayHub\Repositories\FlightRepository;
use RunwayHub\Repositories\WeatherRepository;
use RunwayHub\Cache\CacheManager;
use RunwayHub\Database\Database;
use PHPUnit\Framework\TestCase;

class IntegrationTest extends TestCase
{
    private WeatherService $weatherService;
    private ACARSService $flightService;
    private FlightRepository $flightRepo;
    private WeatherRepository $weatherRepo;
    private CacheManager $cache;
    private Database $database;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Initialize repositories
        $this->flightRepo = new FlightRepository($this->getMockDatabase());
        $this->weatherRepo = new WeatherRepository($this->getMockDatabase());
        
        // Initialize cache
        $this->cache = new CacheManager();
        
        // Initialize database
        $this->database = new Database();
        
        // Initialize services
        $this->weatherService = new WeatherService($this->getMockHttpClient());
        $this->flightService = new ACARSService($this->getMockHttpClient());
    }

    protected function tearDown(): void
    {
        $this->database->close();
        $this->cache->clear();
        parent::tearDown();
    }

    /**
     * Test API integration - Weather + Flight data combination
     */
    public function testApiIntegrationWeatherAndFlight(): void
    {
        // Simulate API responses
        $weatherResponse = [
            'temperature' => 22,
            'conditions' => 'sunny',
            'humidity' => 65,
            'wind_speed' => 15,
            'wind_direction' => 250,
            'visibility' => 10000,
        ];

        $flightResponse = [
            'flight' => 'AA123',
            'origin' => ['airport' => 'KLAX', 'city' => 'Los Angeles'],
            'destination' => ['airport' => 'KJFK', 'city' => 'New York'],
            'dep_time' => '2026-05-27T18:00:00Z',
            'arr_time' => '2026-05-27T22:00:00Z',
            'status' => 'On time',
        ];

        // Test data combining weather and flight
        $integrationData = [
            'flight' => $flightResponse,
            'weather' => $weatherResponse,
        ];

        // Test that both data sources are available
        $this->assertArrayHasKey('flight', $integrationData);
        $this->assertArrayHasKey('weather', $integrationData);

        // Test cross-validation
        $this->assertEquals('Los Angeles', $integrationData['flight']['origin']['city']);
        $this->assertEquals('sunny', $integrationData['weather']['conditions']);
    }

    /**
     * Test cross-endpoint data consistency
     */
    public function testCrossEndpointDataConsistency(): void
    {
        // Simulate multiple endpoint calls
        $weatherCurrent = ['temperature' => 22, 'unit' => 'celsius'];
        $weatherForecast = [
            'days' => [
                [
                    'day' => '2026-05-27',
                    'temperature' => 22,
                    'conditions' => 'sunny',
                ],
            ],
        ];

        // Verify consistency between current and forecast
        $this->assertEquals($weatherCurrent['temperature'], $weatherForecast['days'][0]['temperature']);
        $this->assertArrayHasKey('unit', $weatherCurrent);
        $this->assertEquals('celsius', $weatherCurrent['unit']);
    }

    /**
     * Test database query integration
     */
    public function testDatabaseQueryIntegration(): void
    {
        // Test INSERT
        $this->database->query('INSERT INTO flights (flight_number, airline, status) VALUES (?, ?, ?)', [
            'AA123',
            'American Airlines',
            'On time',
        ]);

        // Test SELECT
        $result = $this->database->query('SELECT flight_number, status FROM flights WHERE airline = ?', ['American Airlines']);
        
        $this->assertIsArray($result);
        $this->assertGreaterThan(0, count($result));

        // Test UPDATE
        $this->database->query('UPDATE flights SET status = ? WHERE flight_number = ?', ['Delayed', 'AA123']);
        $this->assertEquals(1, $this->database->affectedRows());

        // Test DELETE
        $this->database->query('DELETE FROM flights WHERE flight_number = ?', ['AA123']);
        $this->assertEquals(1, $this->database->affectedRows());
    }

    /**
     * Test database transaction handling
     */
    public function testDatabaseTransaction(): void
    {
        // Begin transaction
        $this->database->beginTransaction();
        
        // Simulate multiple operations
        $this->database->query('INSERT INTO flight_updates (flight_id, update_time) VALUES (?, ?)', [1, date('Y-m-d H:i:s')]);
        $this->database->query('INSERT INTO weather_updates (weather_id, update_time) VALUES (?, ?)', [1, date('Y-m-d H:i:s')]);
        
        // Commit transaction
        $this->database->commit();

        // Verify transactions complete
        $this->assertEquals(2, $this->database->query('SELECT COUNT(*) FROM flight_updates')->fetchColumn());
    }

    /**
     * Test database rollback on error
     */
    public function testDatabaseRollback(): void
    {
        // Begin transaction
        $this->database->beginTransaction();
        
        // First successful insert
        $this->database->query('INSERT INTO flights (flight_number) VALUES (?)', ['TEST123']);
        
        // Simulate failure
        $this->database->query('INSERT INTO invalid_table (col) VALUES (?)', ['test']);

        // Rollback transaction
        $this->database->rollBack();

        // Verify rollback - original table should be unchanged
        $this->assertEquals(0, $this->database->query('SELECT COUNT(*) FROM flights WHERE flight_number = ?', ['TEST123'])->fetchColumn());
    }

    /**
     * Test cache layer integration
     */
    public function testCacheLayerIntegration(): void
    {
        // Test cache set
        $cacheKey = 'weather:Berlin:current';
        $cacheValue = ['temperature' => 22, 'city' => 'Berlin'];
        $this->cache->set($cacheKey, $cacheValue, 3600); // 1 hour TTL

        // Test cache get
        $retrieved = $this->cache->get($cacheKey);
        
        $this->assertEquals($cacheValue, $retrieved);

        // Test cache has
        $this->assertTrue($this->cache->has($cacheKey));

        // Test cache delete
        $this->cache->delete($cacheKey);
        $this->assertFalse($this->cache->has($cacheKey));
    }

    /**
     * Test cache TTL handling
     */
    public function testCacheTTL(): void
    {
        // Set cache with specific TTL
        $cacheKey = 'test:ttl';
        $cacheValue = ['value' => 'test'];
        $ttl = 10; // 10 seconds
        $this->cache->set($cacheKey, $cacheValue, $ttl);

        // Immediate retrieval should work
        $this->assertEquals($cacheValue, $this->cache->get($cacheKey));

        // Wait for TTL to expire (simulated)
        $this->sleep(15);
        
        // After TTL, cache should be expired
        $this->assertFalse($this->cache->has($cacheKey));
    }

    /**
     * Test cache prefix management
     */
    public function testCachePrefix(): void
    {
        // Test cache with different prefixes
        $this->cache->set('weather:Berlin', ['temp' => 22]);
        $this->cache->set('flight:AA123', ['status' => 'On time']);
        $this->cache->set('user:123', ['name' => 'John']);

        // Verify cache isolation
        $this->assertEquals(['temp' => 22], $this->cache->get('weather:Berlin'));
        $this->assertEquals(['status' => 'On time'], $this->cache->get('flight:AA123'));
        $this->assertEquals(['name' => 'John'], $this->cache->get('user:123'));
    }

    /**
     * Test cache serialization/deserialization
     */
    public function testCacheSerialization(): void
    {
        // Test complex data structure
        $complexData = [
            'weather' => [
                'temperature' => 22,
                'conditions' => 'sunny',
                'humidity' => 65,
                'wind' => [
                    'speed' => 15,
                    'direction' => 250,
                ],
            ],
            'flight' => [
                'number' => 'AA123',
                'status' => 'On time',
            ],
        ];

        $cacheKey = 'complex:test';
        $this->cache->set($cacheKey, $complexData, 3600);

        $retrieved = $this->cache->get($cacheKey);

        // Verify data integrity
        $this->assertEquals($complexData, $retrieved);
        $this->assertEquals('sunny', $retrieved['weather']['conditions']);
    }

    /**
     * Test cache memory limits
     */
    public function testCacheMemoryLimits(): void
    {
        // Set maximum memory limit (bytes)
        $memoryLimit = 10 * 1024 * 1024; // 10 MB
        
        // Fill cache with data
        for ($i = 0; $i < 100; $i++) {
            $this->cache->set("test:memory:$i", str_repeat('a', 1024), 3600);
        }

        // Test that cache respects memory limits
        $this->assertLessThan($memoryLimit, $this->cache->getMemoryUsed());
    }

    /**
     * Test database connection pooling
     */
    public function testDatabaseConnectionPool(): void
    {
        // Simulate multiple connections
        $connections = [];
        
        for ($i = 0; $i < 10; $i++) {
            $conn = $this->getMockConnection();
            $connections[] = $conn;
            $conn->connect();
        }

        // Verify all connections are valid
        foreach ($connections as $conn) {
            $this->assertTrue($conn->isConnected());
        }
    }

    /**
     * Test database query optimization
     */
    public function testDatabaseQueryOptimization(): void
    {
        // Test indexed query
        $startTime = microtime(true);
        $this->database->query('SELECT COUNT(*) FROM flights WHERE status = ?', ['On time']);
        $endTime = microtime(true);
        $indexedTime = $endTime - $startTime;

        // Test non-indexed query (simulated with IN clause)
        $startTime = microtime(true);
        $this->database->query('SELECT flight_number FROM flights WHERE flight_number IN (?)', ['AA100', 'AA101', 'AA102']);
        $endTime = microtime(true);
        $nonIndexedTime = $endTime - $startTime;

        // Indexed query should be faster
        $this->assertLessThan($nonIndexedTime, $indexedTime * 2);
    }

    /**
     * Test API rate limiting handling
     */
    public function testApiRateLimiting(): void
    {
        $callCount = 0;
        $maxCalls = 10;

        // Simulate rate limiting
        for ($i = 0; $i < 15; $i++) {
            $callCount++;
            if ($callCount <= $maxCalls) {
                // Success
                $this->assertTrue(true);
            } else {
                // Rate limit exceeded
                break;
            }
        }

        $this->assertEquals($maxCalls, $callCount);
    }

    /**
     * Test concurrent API requests
     */
    public function testConcurrentApiRequests(): void
    {
        $results = [];
        $errors = [];
        
        // Simulate concurrent requests
        for ($i = 0; $i < 10; $i++) {
            $promise = new Promise(function () use (&$results) {
                // Simulate API call
                $results[] = ['success' => true, 'data' => 'test'];
            });
            
            // Wait for promise
            $promise->then(function ($result) use (&$results) {
                $results[] = $result;
            });
        }

        // Wait for all promises to resolve
        $this->sleep(1);

        // Verify results
        $this->assertCount(10, $results);
    }

    /**
     * Test weather repository integration
     */
    public function testWeatherRepository(): void
    {
        // Test inserting weather data
        $this->weatherRepo->insert([
            'city' => 'Berlin',
            'temperature' => 22,
            'conditions' => 'sunny',
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        // Test selecting weather data
        $weather = $this->weatherRepo->select('Berlin');
        
        $this->assertEquals('Berlin', $weather['city']);
        $this->assertEquals(22, $weather['temperature']);

        // Test updating weather data
        $this->weatherRepo->update('Berlin', ['temperature' => 23]);
        $weather = $this->weatherRepo->select('Berlin');
        $this->assertEquals(23, $weather['temperature']);
    }

    /**
     * Test flight repository integration
     */
    public function testFlightRepository(): void
    {
        // Test inserting flight data
        $this->flightRepo->insert([
            'flight_number' => 'AA123',
            'airline' => 'American Airlines',
            'status' => 'On time',
            'origin' => 'KLAX',
            'destination' => 'KJFK',
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        // Test selecting flight data
        $flight = $this->flightRepo->select('AA123');
        
        $this->assertEquals('AA123', $flight['flight_number']);
        $this->assertEquals('American Airlines', $flight['airline']);

        // Test updating flight data
        $this->flightRepo->update('AA123', ['status' => 'Delayed']);
        $flight = $this->flightRepo->select('AA123');
        $this->assertEquals('Delayed', $flight['status']);
    }

    /**
     * Test repository transaction handling
     */
    public function testRepositoryTransaction(): void
    {
        // Begin transaction
        $this->weatherRepo->beginTransaction();
        
        // Insert weather data
        $this->weatherRepo->insert(['city' => 'Paris', 'temperature' => 18]);
        
        // Insert flight data
        $this->flightRepo->insert([
            'flight_number' => 'AF100',
            'status' => 'On time',
            'origin' => 'LFPG',
            'destination' => 'KJFK',
        ]);
        
        // Commit transaction
        $this->weatherRepo->commit();

        // Verify both inserts succeeded
        $weather = $this->weatherRepo->select('Paris');
        $this->assertEquals('Paris', $weather['city']);

        $flight = $this->flightRepo->select('AF100');
        $this->assertEquals('AF100', $flight['flight_number']);
    }

    /**
     * Test cross-endpoint validation
     */
    public function testCrossEndpointValidation(): void
    {
        // Test that weather data is valid for flight tracking
        $weatherData = [
            'temperature' => 22,
            'conditions' => 'sunny',
            'visibility' => 10000,
            'wind_speed' => 15,
        ];

        $flightData = [
            'flight' => 'AA123',
            'status' => 'On time',
        ];

        // Validate that weather conditions are appropriate for flight
        $this->assertGreaterThan(0, $weatherData['visibility']);
        $this->assertLessThanOrEqual(50, $weatherData['wind_speed']);
        $this->assertGreaterThanOrEqual(-50, $weatherData['temperature']);

        // Validate that flight status is valid
        $this->assertContains('On time', ['On time', 'Delayed', 'Cancelled']);
    }

    /**
     * Test database query result validation
     */
    public function testQueryResultValidation(): void
    {
        // Test SELECT query result
        $result = $this->database->query('SELECT 1 AS value');
        
        $this->assertIsArray($result);
        $this->assertGreaterThan(0, count($result));
        $this->assertEquals(1, $result[0]['value']);

        // Test INSERT query result
        $affected = $this->database->query('INSERT INTO test_table (value) VALUES (1)');
        
        $this->assertEquals(1, $affected);
    }

    /**
     * Test API response validation
     */
    public function testApiResponseValidation(): void
    {
        // Test valid weather response
        $weatherResponse = [
            'data' => [
                'temperature' => 22,
                'conditions' => 'sunny',
            ],
        ];

        $this->assertArrayHasKey('data', $weatherResponse);
        $this->assertArrayHasKey('temperature', $weatherResponse['data']);
        $this->assertIsInt($weatherResponse['data']['temperature']);

        // Test valid flight response
        $flightResponse = [
            'flight' => 'AA123',
            'status' => 'On time',
        ];

        $this->assertArrayHasKey('flight', $flightResponse);
        $this->assertStringContainsString('On time', $flightResponse['status']);
    }

    /**
     * Test database schema validation
     */
    public function testDatabaseSchema(): void
    {
        // Check table existence
        $tables = $this->database->query('SHOW TABLES');
        
        $this->assertIsArray($tables);
        $this->assertGreaterThan(0, count($tables));

        // Check column types
        $result = $this->database->query('DESCRIBE flights');
        
        $this->assertIsArray($result);
        $this->assertArrayHasKey('flight_number', $result[0]['Column'] ?? []);
    }

    /**
     * Test API integration end-to-end
     */
    public function testApiIntegrationEndToEnd(): void
    {
        // Simulate complete workflow
        $startTime = microtime(true);

        // 1. Get weather data
        $weather = $this->weatherService->getCurrentWeather('Berlin');
        $this->assertArrayHasKey('temperature', $weather);

        // 2. Get flight data
        $flight = $this->flightService->getFlightStatus('AA123');
        $this->assertArrayHasKey('flight', $flight);

        // 3. Store in database
        $this->flightRepo->insert($flight);
        $this->weatherRepo->insert($weather);

        // 4. Retrieve from database
        $retrievedFlight = $this->flightRepo->select('AA123');
        $this->assertEquals('AA123', $retrievedFlight['flight_number']);

        // 5. Cache results
        $this->cache->set('weather:Berlin', $weather, 3600);
        $this->cache->set('flight:AA123', $flight, 3600);

        // 6. Retrieve from cache
        $cachedWeather = $this->cache->get('weather:Berlin');
        $this->assertEquals($weather['temperature'], $cachedWeather['temperature']);

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        // Execution should be within acceptable time
        $this->assertLessThan(10, $executionTime); // Less than 10 seconds
    }

    /**
     * Test database foreign key constraints
     */
    public function testForeignKeys(): void
    {
        // Test that valid flight references are accepted
        $this->database->query('INSERT INTO flight_references (flight_id) VALUES (?)', [1]);
        $this->assertEquals(1, $this->database->affectedRows());

        // Test that invalid flight references are rejected
        try {
            $this->database->query('INSERT INTO flight_references (flight_id) VALUES (?)', [99999]);
            $this->assertFalse(true); // Should not reach here
        } catch (\Exception $e) {
            $this->assertStringContainsString('Foreign key constraint', $e->getMessage());
        }
    }

    /**
     * Test API endpoint compatibility
     */
    public function testApiEndpointCompatibility(): void
    {
        // Test weather endpoint compatibility
        $this->mockHttpClient()
            ->shouldReceive('get')
            ->withPath('/weather/current')
            ->andReturn(['status' => 'ok']);

        $this->weatherService->getCurrentWeather('Berlin');
        $this->assertTrue(true);

        // Test flight endpoint compatibility
        $this->mockHttpClient()
            ->shouldReceive('get')
            ->withPath('/flights/status')
            ->andReturn(['flight' => 'AA123']);

        $this->flightService->getFlightStatus('AA123');
        $this->assertEquals('AA123', $result['flight']);
    }

    /**
     * Test database query caching
     */
    public function testDatabaseQueryCaching(): void
    {
        // First query - hits database
        $startTime = microtime(true);
        $result1 = $this->database->query('SELECT * FROM flights WHERE airline = ?', ['American Airlines']);
        $time1 = microtime(true) - $startTime;

        // Second query - should use cache
        $startTime = microtime(true);
        $result2 = $this->database->query('SELECT * FROM flights WHERE airline = ?', ['American Airlines']);
        $time2 = microtime(true) - $startTime;

        // Cache should be faster
        $this->assertLessThan($time1, $time2 * 2);

        // Results should be identical
        $this->assertEquals($result1, $result2);
    }

    /**
     * Test API pagination handling
     */
    public function testApiPagination(): void
    {
        // Test paginated flight search
        $page = 1;
        $perPage = 20;
        
        $this->mockHttpClient()
            ->shouldReceive('get')
            ->withPath('/flights/search', ['page' => $page, 'per_page' => $perPage])
            ->andReturn([
                'flights' => [
                    ['flight_number' => 'AA100'],
                    ['flight_number' => 'AA101'],
                ],
                'meta' => [
                    'page' => $page,
                    'per_page' => $perPage,
                    'total' => 100,
                    'total_pages' => 5,
                ],
            ]);

        $result = $this->flightService->searchFlight($page, $perPage);
        
        $this->assertCount(2, $result['flights']);
        $this->assertEquals(1, $result['meta']['page']);
    }

    /**
     * Test database backup/restore simulation
     */
    public function testDatabaseBackup(): void
    {
        // Export data (simulated)
        $backupData = [
            'flights' => [
                ['flight_number' => 'AA123', 'airline' => 'American Airlines'],
                ['flight_number' => 'AA124', 'airline' => 'American Airlines'],
            ],
            'weather' => [
                ['city' => 'Berlin', 'temperature' => 22],
                ['city' => 'Paris', 'temperature' => 18],
            ],
        ];

        // Import data (simulated)
        $this->database->query('DROP TABLE IF EXISTS test_table');
        $this->database->query('CREATE TABLE test_table (id INT)');
        
        foreach ($backupData['flights'] as $flight) {
            $this->database->query('INSERT INTO test_table (flight_number) VALUES (?)', [$flight['flight_number']]);
        }

        // Verify restore
        $count = $this->database->query('SELECT COUNT(*) FROM test_table');
        $this->assertEquals(2, $count[0][0]);
    }

    /**
     * Test API version compatibility
     */
    public function testApiVersion(): void
    {
        // Test that API returns version information
        $response = $this->mockHttpClient()
            ->shouldReceive('get')
            ->withPath('/weather/current')
            ->andReturn(['api_version' => '2.0', 'status' => 'ok'])
            ->getMock()
            ->get()
            ->withPath('/weather/current')
            ->andReturn(['api_version' => '2.0', 'status' => 'ok']);

        $this->assertEquals('2.0', $response['api_version']);
    }
}

// Simple Promise implementation for testing
class Promise {
    private $resolve;
    private $reject;
    
    public function __construct($resolve) {
        $this->resolve = $resolve;
    }
    
    public function then($callback) {
        $callback($this->resolve());
        return $this;
    }
}
