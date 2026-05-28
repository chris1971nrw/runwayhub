<?php

declare(strict_types=1);

namespace RunwayHub\Services;

use Monolog\Logger;

/**
 * Flight Tracking Service
 * 
 * Handles flight status tracking via FlightAware API.
 * Supports multiple airlines and flight numbers.
 */
class FlightTrackingService
{
    private object $logger;
    private ?string $apiKey = null;
    private array $cache = [];
    private int $cacheTTL = 60; // 1 minute for flight data
    private string $baseUrl = 'https://flightaware.com';
    
    public function __construct(object $logger = null)
    {
        $this->logger = $logger ?? new class {
            public function debug(string $msg): void {}
            public function info(string $msg): void {}
            public function warning(string $msg): void {}
            public function error(string $msg): void {}
            public function critical(string $msg): void {}
        };
        $this->apiKey = getenv('FLIGHT_AWARE_API_KEY') ?: null;
    }
    
    /**
     * Get flight status by flight number and airline
     * 
     * @param string $flightNumber Flight number (e.g., "DL123")
     * @param string $airline Airline code (e.g., "DL" for Delta)
     * @return array|false
     */
    public function getFlightStatus(string $flightNumber, string $airline = ''): array|false
    {
        $flightNumber = strtoupper($flightNumber);
        $airline = strtoupper($airline);
        
        // Cache key
        $cacheKey = "flight/{$flightNumber}/{$airline}";
        
        // Check cache
        if (isset($this->cache[$cacheKey])) {
            $data = $this->cache[$cacheKey];
            if (time() - $data['timestamp'] < $this->cacheTTL) {
                return $data['data'];
            }
        }
        
        // Simulate flight status (demo)
        $flight = [
            'number' => $flightNumber,
            'airline' => $airline ?: 'Unknown',
            'callsign' => $airline ?: 'FLIGHT',
            'status' => 'En Route',
            'estimatedArrival' => date('Y-m-d H:i:s', strtotime('+2 hours')),
            'estimatedDeparture' => date('Y-m-d H:i:s', strtotime('-2 hours')),
            'origin' => 'EDDF',
            'destination' => 'KJFK',
            'latitude' => 51.0,
            'longitude' => 8.0,
            'altitude' => 35000,
            'groundSpeed' => 480,
            'track' => 45,
            'aircraftType' => 'B738',
            'flightHistory' => [
                ['airport' => 'EDDM', 'time' => date('Y-m-d H:i:s', strtotime('-3 hours'))],
                ['airport' => 'EDDF', 'time' => date('Y-m-d H:i:s', strtotime('-2 hours'))],
            ],
        ];
        
        // Cache result
        $this->cache[$cacheKey] = [
            'data' => $flight,
            'timestamp' => time(),
        ];
        
        return $flight;
    }
    
    /**
     * Get flight by flight ID
     */
    public function getFlightByFlightID(string $flightId): array|false
    {
        $cacheKey = "flight/{$flightId}";
        
        if (isset($this->cache[$cacheKey])) {
            $data = $this->cache[$cacheKey];
            if (time() - $data['timestamp'] < $this->cacheTTL) {
                return $data['data'];
            }
        }
        
        // Simulate
        $flight = [
            'id' => $flightId,
            'number' => 'HA921',
            'airline' => 'HA',
            'status' => 'En Route',
            'origin' => 'KBOS',
            'destination' => 'KIAH',
            'estimatedArrival' => date('Y-m-d H:i:s', strtotime('+4 hours')),
            'latitude' => 40.0,
            'longitude' => -75.0,
            'altitude' => 31000,
            'groundSpeed' => 460,
        ];
        
        $this->cache[$cacheKey] = [
            'data' => $flight,
            'timestamp' => time(),
        ];
        
        return $flight;
    }
    
    /**
     * Get arrival flights for airport
     */
    public function getArrivingFlights(string $airport, int $limit = 20): array
    {
        $airport = strtoupper($airport);
        $cacheKey = "arrivals/{$airport}";
        
        if (isset($this->cache[$cacheKey])) {
            $data = $this->cache[$cacheKey];
            if (time() - $data['timestamp'] < $this->cacheTTL * 2) {
                return $data['data'] ?? [];
            }
        }
        
        // Simulate arrival flights
        $flights = [
            ['number' => 'DL1234', 'airline' => 'DL', 'origin' => 'KATL', 'estimated' => date('Y-m-d H:i:s', strtotime('+15 minutes'))],
            ['number' => 'BA117', 'airline' => 'BA', 'origin' => 'EGLL', 'estimated' => date('Y-m-d H:i:s', strtotime('+25 minutes'))],
            ['number' => 'LH456', 'airline' => 'LH', 'origin' => 'EDDF', 'estimated' => date('Y-m-d H:i:s', strtotime('+35 minutes'))],
            ['number' => 'UA200', 'airline' => 'UA', 'origin' => 'KORD', 'estimated' => date('Y-m-d H:i:s', strtotime('+45 minutes'))],
        ];
        
        $this->cache[$cacheKey] = [
            'data' => $flights,
            'timestamp' => time(),
        ];
        
        return $flights;
    }
    
    /**
     * Get departing flights for airport
     */
    public function getDepartingFlights(string $airport, int $limit = 20): array
    {
        $airport = strtoupper($airport);
        $cacheKey = "departures/{$airport}";
        
        if (isset($this->cache[$cacheKey])) {
            $data = $this->cache[$cacheKey];
            if (time() - $data['timestamp'] < $this->cacheTTL * 2) {
                return $data['data'] ?? [];
            }
        }
        
        // Simulate departing flights
        $flights = [
            ['number' => 'LH457', 'airline' => 'LH', 'destination' => 'EDDF', 'estimated' => date('Y-m-d H:i:s', strtotime('-5 minutes'))],
            ['number' => 'DL1235', 'airline' => 'DL', 'destination' => 'KATL', 'estimated' => date('Y-m-d H:i:s', strtotime('+1 hour'))],
            ['number' => 'BA118', 'airline' => 'BA', 'destination' => 'EGLL', 'estimated' => date('Y-m-d H:i:s', strtotime('+1 hour 30 min'))],
        ];
        
        $this->cache[$cacheKey] = [
            'data' => $flights,
            'timestamp' => time(),
        ];
        
        return $flights;
    }
    
    /**
     * Get flight history
     */
    public function getFlightHistory(string $flightNumber, string $airline = '', int $days = 7): array
    {
        $flightNumber = strtoupper($flightNumber);
        $cacheKey = "history/{$flightNumber}/{$airline}/{$days}days";
        
        if (isset($this->cache[$cacheKey])) {
            return $this->cache[$cacheKey]['data'] ?? [];
        }
        
        // Simulate flight history
        $history = [];
        for ($i = 0; $i < 10; $i++) {
            $history[] = [
                'date' => date('Y-m-d', strtotime("-{$i} days")),
                'number' => $flightNumber,
                'airline' => $airline,
                'origin' => 'EDDF',
                'destination' => $i % 2 ? 'KJFK' : 'EGLL',
                'status' => 'On Time',
            ];
        }
        
        $this->cache[$cacheKey] = [
            'data' => $history,
            'timestamp' => time(),
        ];
        
        return $history;
    }
    
    /**
     * Clear flight cache
     */
    public function clearCache(): void
    {
        $this->cache = [];
        $this->logger->info('Flight tracking cache cleared');
    }
    
    /**
     * Check cache status
     */
    public function getCacheStatus(): array
    {
        return [
            'cacheHits' => count(array_filter($this->cache, fn($d) => time() - $d['timestamp'] < $this->cacheTTL)),
            'cacheMisses' => count(array_diff_key($this->cache, array_filter($this->cache, fn($d) => time() - $d['timestamp'] < $this->cacheTTL))),
            'cacheSize' => count($this->cache),
            'ttl' => $this->cacheTTL,
        ];
    }
}