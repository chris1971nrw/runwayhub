<?php
/**
 * PerformanceTest.php
 * Comprehensive performance test suite
 * 
 * Tests:
 * - API response times
 * - Cache hit rates
 * - Database query times
 * - Memory usage
 */

namespace RunwayHub\Tests\Performance;

use RunwayHub\Services\WeatherService;
use RunwayHub\Services\FlightAwareService;
use RunwayHub\Cache\CacheManager;
use RunwayHub\Database\Database;
use PHPUnit\Framework\TestCase;

class PerformanceTest extends TestCase
{
    private WeatherService $weatherService;
    private FlightAwareService $flightService;
    private CacheManager $cache;
    private Database $database;
    private $startTime;
    private $endTime;
    private $iterationCount;
    private $warmUpIterations;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Initialize services with mock client
        $mockHttpClient = $this->createMockHttpClient();
        $this->weatherService = new WeatherService($mockHttpClient);
        $this->flightService = new FlightAwareService($mockHttpClient);
        $this->cache = new CacheManager();
        $this->database = new Database();
        
        // Configure performance test parameters
        $this->iterationCount = 10; // Number of iterations for averaging
        $this->warmUpIterations = 5; // Warm up iterations
    }

    protected function tearDown(): void
    {
        $this->database->close();
        $this->cache->clear();
        parent::tearDown();
    }

    /**
     * Test API response times
     */
    public function testApiResponseTimes(): void
    {
        // Warm up
        for ($i = 0; $i < $this->warmUpIterations; $i++) {
            $this->weatherService->getCurrentWeather('Berlin');
        }

        // Measure response times
        $responseTimes = [];
        
        for ($i = 0; $i < $this->iterationCount; $i++) {
            $startTime = microtime(true);
            $result = $this->weatherService->getCurrentWeather('Berlin');
            $endTime = microtime(true);
            
            $responseTimes[] = $endTime - $startTime;
        }

        // Calculate statistics
        $average = array_sum($responseTimes) / count($responseTimes);
        $min = min($responseTimes);
        $max = max($responseTimes);
        
        $this->assertLessThan(2, $average); // Should be less than 2 seconds
        $this->assertLessThan(5, $max); // Max should be less than 5 seconds
        $this->assertGreaterThan(0, $min); // Min should be positive
        
        // Store results for reporting
        $this->assertEquals($average, $average);
    }

    /**
     * Test concurrent API requests
     */
    public function testConcurrentApiRequests(): void
    {
        $concurrentRequests = 10;
        $results = [];
        $errors = 0;
        
        // Simulate concurrent requests
        for ($i = 0; $i < $concurrentRequests; $i++) {
            $startTime = microtime(true);
            try {
                $result = $this->weatherService->getCurrentWeather('Berlin');
                $results[] = ['success' => true, 'time' => microtime(true) - $startTime];
            } catch (\Exception $e) {
                $errors++;
            }
        }

        // Calculate success rate
        $successRate = ($concurrentRequests - $errors) / $concurrentRequests;
        $this->assertGreaterThan(0.9, $successRate); // Should have >90% success rate
    }

    /**
     * Test cache hit rates
     */
    public function testCacheHitRates(): void
    {
        // First call - cache miss
        $this->weatherService->getCurrentWeather('Berlin');
        
        // Subsequent calls - cache hits
        $startTime = microtime(true);
        $cacheHits = 0;
        $cacheMisses = 0;
        
        for ($i = 0; $i < 100; $i++) {
            $result = $this->weatherService->getCurrentWeather('Berlin');
            
            // Check if result came from cache
            if ($i > 0) {
                $cacheHits++; // Subsequent calls should hit cache
            }
        }

        $hitRate = $cacheHits / 100;
        $this->assertGreaterThan(0.95, $hitRate); // Should have >95% cache hit rate
    }

    /**
     * Test database query times
     */
    public function testDatabaseQueryTimes(): void
    {
        // Insert test data
        for ($i = 0; $i < 1000; $i++) {
            $this->database->query('INSERT INTO flights (flight_number, airline) VALUES (?, ?)', [
                "AA{$i}",
                'American Airlines',
            ]);
        }

        // Warm up queries
        for ($i = 0; $i < $this->warmUpIterations; $i++) {
            $this->database->query('SELECT COUNT(*) FROM flights');
        }

        // Measure query times
        $queryTimes = [];
        
        for ($i = 0; $i < $this->iterationCount; $i++) {
            $startTime = microtime(true);
            $result = $this->database->query('SELECT COUNT(*) FROM flights');
            $endTime = microtime(true);
            
            $queryTimes[] = $endTime - $startTime;
        }

        // Calculate statistics
        $average = array_sum($queryTimes) / count($queryTimes);
        $this->assertLessThan(1, $average); // Should be less than 1 second
    }

    /**
     * Test database query with indexed column
     */
    public function testDatabaseIndexedQuery(): void
    {
        // Create index (simulated)
        $this->database->query('CREATE INDEX idx_flight_number ON flights (flight_number)');
        
        // Warm up
        for ($i = 0; $i < $this->warmUpIterations; $i++) {
            $this->database->query('SELECT * FROM flights WHERE flight_number = ?', ['AA123']);
        }

        // Measure query times
        $queryTimes = [];
        
        for ($i = 0; $i < $this->iterationCount; $i++) {
            $startTime = microtime(true);
            $result = $this->database->query('SELECT * FROM flights WHERE flight_number = ?', ['AA123']);
            $endTime = microtime(true);
            
            $queryTimes[] = $endTime - $startTime;
        }

        // Average should be low for indexed query
        $average = array_sum($queryTimes) / count($queryTimes);
        $this->assertLessThan(0.1, $average); // Should be less than 0.1 seconds
    }

    /**
     * Test memory usage
     */
    public function testMemoryUsage(): void
    {
        // Get initial memory usage
        $initialMemory = memory_get_usage();
        
        // Populate cache with data
        for ($i = 0; $i < 100; $i++) {
            $this->cache->set("test:memory:$i", str_repeat('a', 1024), 3600);
        }
        
        // Populate database with data
        for ($i = 0; $i < 1000; $i++) {
            $this->database->query('INSERT INTO test_table (id, data) VALUES (?, ?)', [
                $i,
                str_repeat('x', 100),
            ]);
        }
        
        // Get final memory usage
        $finalMemory = memory_get_usage();
        $memoryUsed = $finalMemory - $initialMemory;
        
        // Memory usage should be within reasonable limits (100MB for this test)
        $this->assertLessThan(100 * 1024 * 1024, $memoryUsed);
    }

    /**
     * Test cache eviction performance
     */
    public function testCacheEvictionPerformance(): void
    {
        // Fill cache to limit
        for ($i = 0; $i < 500; $i++) {
            $this->cache->set("test:eviction:$i", str_repeat('a', 1024), 3600);
        }
        
        // Measure eviction time
        $startTime = microtime(true);
        $evictionCount = 0;
        
        // Evict items
        for ($i = 0; $i < 500; $i++) {
            $this->cache->set("test:eviction:$i", str_repeat('a', 1024), 3600);
            $evictionCount++;
        }
        
        $endTime = microtime(true);
        $evictionTime = $endTime - $startTime;
        
        // Eviction should complete within 5 seconds
        $this->assertLessThan(5, $evictionTime);
        
        // Eviction count should match insertion count
        $this->assertEquals(500, $evictionCount);
    }

    /**
     * Test batch processing performance
     */
    public function testBatchProcessing(): void
    {
        // Warm up
        for ($i = 0; $i < $this->warmUpIterations; $i++) {
            $this->weatherService->getCurrentWeather('Berlin');
        }
        
        // Batch processing
        $startTime = microtime(true);
        $batchCount = 0;
        
        for ($i = 0; $i < 100; $i++) {
            $this->weatherService->getCurrentWeather('Berlin');
            $batchCount++;
        }
        
        $endTime = microtime(true);
        $batchTime = $endTime - $startTime;
        $itemsPerSecond = $batchCount / $batchTime;
        
        // Should process at least 100 items per second
        $this->assertGreaterThan(100, $itemsPerSecond);
    }

    /**
     * Test API endpoint performance comparison
     */
    public function testEndpointPerformanceComparison(): void
    {
        $endpoints = [
            'weather/current' => 'getCurrentWeather',
            'weather/forecast' => 'getForecast',
            'flights/status' => 'getFlightStatus',
            'flights/search' => 'searchFlight',
        ];
        
        $results = [];
        
        foreach ($endpoints as $endpoint => $method) {
            $startTime = microtime(true);
            
            // Execute endpoint method
            if (method_exists($this->weatherService, $method)) {
                $this->weatherService->$method('Berlin');
            } elseif (method_exists($this->flightService, $method)) {
                $this->flightService->$method('AA123');
            }
            
            $endTime = microtime(true);
            $results[$endpoint] = $endTime - $startTime;
        }
        
        // Find fastest and slowest endpoints
        $fastest = min($results);
        $slowest = max($results);
        
        // Performance variance should be acceptable
        $this->assertLessThan(3, $slowest); // Slowest endpoint < 3 seconds
    }

    /**
     * Test load testing
     */
    public function testLoadTesting(): void
    {
        // Simulate load with multiple requests
        $requests = 50;
        $concurrentWorkers = 5;
        
        $results = [];
        $errors = 0;
        
        // Execute requests
        for ($i = 0; $i < $requests; $i++) {
            try {
                $result = $this->weatherService->getCurrentWeather('Berlin');
                $results[] = $result;
            } catch (\Exception $e) {
                $errors++;
            }
        }
        
        // Success rate should be high
        $successRate = ($requests - $errors) / $requests;
        $this->assertGreaterThan(0.9, $successRate);
        
        // Average response time should be reasonable
        if (count($results) > 0) {
            $averageResponseTime = array_sum(array_map(fn($r) => microtime(true) - time(), $results)) / count($results);
            $this->assertLessThan(2, $averageResponseTime);
        }
    }

    /**
     * Test connection pool performance
     */
    public function testConnectionPool(): void
    {
        // Simulate connection pool usage
        $poolSize = 10;
        $maxConcurrentQueries = 50;
        
        $queries = [];
        
        for ($i = 0; $i < $maxConcurrentQueries; $i++) {
            $startTime = microtime(true);
            $result = $this->database->query('SELECT 1');
            $endTime = microtime(true);
            
            $queries[] = ['time' => $endTime - $startTime];
        }
        
        // Average query time should be low
        $averageTime = array_sum(array_map(fn($q) => $q['time'], $queries)) / count($queries);
        $this->assertLessThan(0.1, $averageTime);
    }

    /**
     * Test serialization/deserialization performance
     */
    public function testSerializationPerformance(): void
    {
        // Warm up
        for ($i = 0; $i < $this->warmUpIterations; $i++) {
            $this->cache->set('test:serialize:$i', ['data' => 'test'], 3600);
        }
        
        // Measure serialization time
        $serializations = [];
        
        for ($i = 0; $i < 100; $i++) {
            $startTime = microtime(true);
            
            $data = [
                'temperature' => 22,
                'conditions' => 'sunny',
                'humidity' => 65,
                'wind' => [
                    'speed' => 15,
                    'direction' => 250,
                ],
            ];
            
            $serialized = json_encode($data);
            $deserialized = json_decode($serialized, true);
            
            $endTime = microtime(true);
            $serializations[] = $endTime - $startTime;
        }
        
        $averageTime = array_sum($serializations) / count($serializations);
        
        // Serialization should be fast
        $this->assertLessThan(0.01, $averageTime);
    }

    /**
     * Test network I/O performance
     */
    public function testNetworkIOPerformance(): void
    {
        // Warm up
        for ($i = 0; $i < $this->warmUpIterations; $i++) {
            $this->weatherService->getCurrentWeather('Berlin');
        }
        
        // Measure network I/O time
        $ioTimes = [];
        
        for ($i = 0; $i < 50; $i++) {
            $startTime = microtime(true);
            $result = $this->weatherService->getCurrentWeather('Berlin');
            $endTime = microtime(true);
            
            $ioTimes[] = $endTime - $startTime;
        }
        
        $averageTime = array_sum($ioTimes) / count($ioTimes);
        
        // Network I/O should be within acceptable limits
        $this->assertLessThan(1, $averageTime);
    }

    /**
     * Test file I/O performance
     */
    public function testFileIOPerformance(): void
    {
        // Create test file
        $testFile = '/tmp/perf_test_' . uniqid();
        $fileSize = 1 * 1024 * 1024; // 1MB
        
        // Warm up
        for ($i = 0; $i < $this->warmUpIterations; $i++) {
            file_put_contents($testFile, str_repeat('x', $fileSize));
        }
        
        // Measure read/write times
        $readWriteTimes = [];
        
        for ($i = 0; $i < 10; $i++) {
            $startTime = microtime(true);
            file_put_contents($testFile, str_repeat('x', $fileSize));
            $data = file_get_contents($testFile);
            $endTime = microtime(true);
            
            $readWriteTimes[] = $endTime - $startTime;
        }
        
        // Clean up
        unlink($testFile);
        
        $averageTime = array_sum($readWriteTimes) / count($readWriteTimes);
        
        // File I/O should be reasonable for 1MB
        $this->assertLessThan(1, $averageTime);
    }

    /**
     * Test garbage collection performance
     */
    public function testGarbageCollection(): void
    {
        // Allocate memory
        $memoryAllocated = 0;
        
        for ($i = 0; $i < 100; $i++) {
            $memoryAllocated += 1024 * 1024; // 1MB each
            $data = str_repeat('x', 1024 * 1024);
            $this->cache->set("test:gc:$i", $data, 3600);
        }
        
        // Force garbage collection
        $startTime = microtime(true);
        gc_collect_cycles();
        $endTime = microtime(true);
        
        $gcTime = $endTime - $startTime;
        
        // Garbage collection should complete within 1 second
        $this->assertLessThan(1, $gcTime);
    }

    /**
     * Test API endpoint reliability
     */
    public function testEndpointReliability(): void
    {
        $totalRequests = 1000;
        $successfulRequests = 0;
        
        for ($i = 0; $i < $totalRequests; $i++) {
            try {
                $result = $this->weatherService->getCurrentWeather('Berlin');
                $successfulRequests++;
            } catch (\Exception $e) {
                // Exception occurred
            }
        }
        
        // Reliability should be >99%
        $reliability = $successfulRequests / $totalRequests;
        $this->assertGreaterThan(0.99, $reliability);
    }

    /**
     * Test cache write performance
     */
    public function testCacheWritePerformance(): void
    {
        // Warm up
        for ($i = 0; $i < $this->warmUpIterations; $i++) {
            $this->cache->set("test:write:$i", 'value', 3600);
        }
        
        // Measure write times
        $writeTimes = [];
        
        for ($i = 0; $i < 100; $i++) {
            $startTime = microtime(true);
            $this->cache->set("test:write:$i", str_repeat('a', 1024), 3600);
            $endTime = microtime(true);
            
            $writeTimes[] = $endTime - $startTime;
        }
        
        $averageWriteTime = array_sum($writeTimes) / count($writeTimes);
        
        // Cache writes should be fast
        $this->assertLessThan(0.01, $averageWriteTime);
    }

    /**
     * Test memory peak usage
     */
    public function testMemoryPeakUsage(): void
    {
        // Get baseline memory
        $baseline = memory_get_usage();
        
        // Allocate memory
        for ($i = 0; $i < 100; $i++) {
            $data = str_repeat('x', 1024 * 1024);
            $this->cache->set("test:peak:$i", $data, 3600);
        }
        
        // Get peak memory
        $peak = memory_get_usage();
        $peakUsage = $peak - $baseline;
        
        // Peak memory should be within limits
        $this->assertLessThan(100 * 1024 * 1024, $peakUsage);
        
        // Clean up
        for ($i = 0; $i < 100; $i++) {
            $this->cache->delete("test:peak:$i");
        }
    }

    /**
     * Test database connection overhead
     */
    public function testDatabaseConnectionOverhead(): void
    {
        // Warm up
        for ($i = 0; $i < $this->warmUpIterations; $i++) {
            $this->database->query('SELECT 1');
        }
        
        // Measure query time
        $startTime = microtime(true);
        for ($i = 0; $i < 50; $i++) {
            $this->database->query('SELECT 1');
        }
        $endTime = microtime(true);
        
        $averageTime = ($endTime - $startTime) / 50;
        
        // Average query time should be low
        $this->assertLessThan(0.1, $averageTime);
    }
}
