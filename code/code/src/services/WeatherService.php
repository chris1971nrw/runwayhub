<?php

declare(strict_types=1);

namespace RunwayHub\Services;

use Monolog\Logger;

/**
 * Weather Service
 * 
 * Handles METAR/TAF weather data retrieval and caching.
 * Uses OpenMeteo as primary weather provider.
 */
class WeatherService
{
    private object $logger;
    private array $cache = [];
    private int $cacheTTL = 300; // 5 minutes for weather
    private array $weatherProviders = [
        'openmeteo' => 'https://open-meteo.com',
        'wttrin' => 'http://wttr.in',
    ];
    
    public function __construct(object $logger = null)
    {
        $this->logger = $logger ?? new class {
            public function debug(string $msg): void {}
            public function info(string $msg): void {}
            public function warning(string $msg): void {}
            public function error(string $msg): void {}
            public function critical(string $msg): void {}
        };
    }
    
    /**
     * Get current weather for airport
     * 
     * @param string $airport ICAO airport code (e.g., "EDDF")
     * @param string $provider Weather provider to use
     * @return array|false
     */
    public function getCurrentWeather(string $airport, string $provider = 'wttrin'): array|false
    {
        $airport = strtoupper($airport);
        $cacheKey = "weather/{$airport}/current";
        
        // Check cache first
        if (isset($this->cache[$cacheKey])) {
            $data = $this->cache[$cacheKey];
            if (time() - $data['timestamp'] < $this->cacheTTL) {
                $this->logger->debug('Weather cache hit', ['airport' => $airport]);
                return $data['data'];
            }
            $this->logger->debug('Weather cache expired', ['airport' => $airport]);
        }
        
        // Fetch from provider (simulation for demo)
        $data = [
            'airport' => $airport,
            'station' => $airport,
            'time' => date('Y-m-d H:i:s', time()),
            'temperature' => 15.0, // Celsius (demo)
            'dewpoint' => 10.0,
            'windDirection' => 270, // West
            'windSpeed' => 15, // knots
            'visibility' => 10000, // meters
            'altimeter' => 1013.2, // hPa
            'conditions' => 'FEW030 SCT250',
            'metar' => 'EDDF 280530Z 27015KT 9999 FEW030 SCT250 15/10 Q1013 NOSIG',
            'taf' => 'EDDF 280200 2803/2903 27015KT 9999 FEW030 SCT250',
        ];
        
        // Cache the result
        $this->cache[$cacheKey] = [
            'data' => $data,
            'timestamp' => time(),
        ];
        
        return $data;
    }
    
    /**
     * Get TAF (Terminal Aerodrome Forecast)
     */
    public function getTAF(string $airport, string $provider = 'wttrin'): array|false
    {
        $airport = strtoupper($airport);
        $cacheKey = "weather/{$airport}/taf";
        
        // Check cache
        if (isset($this->cache[$cacheKey])) {
            $data = $this->cache[$cacheKey];
            if (time() - $data['timestamp'] < $this->cacheTTL) {
                return $data['data'];
            }
        }
        
        $data = [
            'airport' => $airport,
            'issueTime' => date('Y-m-d H:i:s', strtotime('-2 hours')),
            'validPeriodStart' => date('Y-m-d H:i:s', strtotime('+30 minutes')),
            'validPeriodEnd' => date('Y-m-d H:i:s', strtotime('+48 hours')),
            'forecast' => '2803/2903 27015KT 9999 FEW030 SCT250',
            'forecast2' => '2903/3003 25012KT 9999 SCT040',
            'forecast3' => '3003/3103 22008KT 9999 BKN050',
        ];
        
        $this->cache[$cacheKey] = [
            'data' => $data,
            'timestamp' => time(),
        ];
        
        return $data;
    }
    
    /**
     * Get METAR report
     */
    public function getMETAR(string $airport): array|false
    {
        $weather = $this->getCurrentWeather($airport);
        if (!$weather) {
            return false;
        }
        
        // Convert to METAR format
        return [
            'station' => $weather['station'],
            'time' => substr($weather['time'], 11),
            'flightCategory' => $this->getFlightCategory($weather['visibility'], $weather['windSpeed']),
            'raw' => $weather['metar'],
            'parsed' => [
                'sky_condition' => explode(' ', $weather['conditions']),
                'weather' => [], // Would include mist, rain, snow, etc.
            ],
        ];
    }
    
    /**
     * Get flight category based on visibility and ceiling
     */
    private function getFlightCategory(int $visibility, int $windSpeed): string
    {
        if ($visibility <= 800) {
            return 'IL'; // ILS approach minimums
        } elseif ($visibility <= 1600) {
            return 'CAT II';
        } elseif ($visibility <= 4000) {
            return 'CAT I';
        }
        
        // Check ceiling
        $ceiling = 2500; // Simplified
        
        if ($ceiling < 1000) {
            return 'CAT I';
        }
        
        return 'VFR'; // Visual Flight Rules
    }
    
    /**
     * Get weather for multiple airports
     */
    public function getMultiAirportWeather(array $airports): array
    {
        $results = [];
        foreach ($airports as $airport) {
            $weather = $this->getCurrentWeather($airport);
            if ($weather) {
                $results[$airport] = $weather;
            }
        }
        return $results;
    }
    
    /**
     * Clear weather cache
     */
    public function clearCache(): void
    {
        $this->cache = [];
        $this->logger->info('Weather cache cleared');
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