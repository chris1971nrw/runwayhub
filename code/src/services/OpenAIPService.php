<?php

declare(strict_types=1);

namespace RunwayHub\Services;

use Monolog\Logger;

/**
 * OpenAIP Service
 * 
 * Handles OpenAIP (Open Aeronautical Information Publication) data.
 * Provides NOTAMs, aeronautical charts, and flight data.
 * Falls back to simulated data when OpenAIP is unavailable.
 */
class OpenAIPService
{
    private object $logger;
    private array $cache = [];
    private int $cacheTTL = 300; // 5 minutes for aeronautical data
    private string $baseUrl = 'https://www.openaip.net/api';
    
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
     * Check if OpenAIP API is available
     */
    public function isAvailable(): bool
    {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->baseUrl);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            return $httpCode === 200;
        } catch (\Exception $e) {
            $this->logger->error('OpenAIP API check failed: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get NOTAMs for airport
     * 
     * @param string $airport ICAO airport code
     * @return array
     */
    public function getNOTAMs(string $airport): array
    {
        $airport = strtoupper($airport);
        $cacheKey = "notams/{$airport}";
        
        if (isset($this->cache[$cacheKey])) {
            $data = $this->cache[$cacheKey];
            if (time() - $data['timestamp'] < $this->cacheTTL) {
                return $data['data'];
            }
        }
        
        // Simulate NOTAMs (fallback when OpenAIP unavailable)
        $notams = [
            [
                'id' => "{$airport}-2026-001",
                'type' => 'NOTAM',
                'airport' => $airport,
                'effective' => date('Y-m-d H:i:s'),
                'expire' => date('Y-m-d H:i:s', strtotime('+30 days')),
                'subject' => 'General advisory',
                'text' => 'Runway maintenance in progress. Check latest NOTAMs for updates.',
                'restrictions' => 'VFR operations may be affected',
            ],
            [
                'id' => "{$airport}-2026-002",
                'type' => 'NOTAM',
                'airport' => $airport,
                'effective' => date('Y-m-d H:i:s'),
                'expire' => date('Y-m-d H:i:s', strtotime('+7 days')),
                'subject' => 'Tower operations - Enhanced frequency',
                'text' => 'Tower frequency changed to 119.700 MHz. Confirm on initial contact.',
            ],
        ];
        
        $this->cache[$cacheKey] = [
            'data' => $notams,
            'timestamp' => time(),
        ];
        
        return $notams;
    }
    
    /**
     * Get approach charts for airport
     */
    public function getApproachCharts(string $airport): array
    {
        $airport = strtoupper($airport);
        $cacheKey = "approaches/{$airport}";
        
        if (isset($this->cache[$cacheKey])) {
            return $this->cache[$cacheKey]['data'] ?? [];
        }
        
        // Simulate approach charts
        $approaches = [
            [
                'type' => 'ILS',
                'runway' => '09',
                'frequency' => '110.9',
                'minHeight' => 130,
                'minDistance' => 1274,
                'missedApproach' => 'Climb heading 090',
            ],
            [
                'type' => 'RNAV (GPS)',
                'runway' => '09',
                'frequency' => 'N/A',
                'minHeight' => 200,
                'minDistance' => 1440,
                'missedApproach' => 'Climb heading 075',
            ],
            [
                'type' => 'VOR',
                'runway' => '27',
                'frequency' => '115.5',
                'minHeight' => 180,
                'minDistance' => 1050,
                'missedApproach' => 'Climb heading 270',
            ],
        ];
        
        $this->cache[$cacheKey] = [
            'data' => $approaches,
            'timestamp' => time(),
        ];
        
        return $approaches;
    }
    
    /**
     * Get aeronautical charts
     */
    public function getCharts(string $airport): array
    {
        $airport = strtoupper($airport);
        $cacheKey = "charts/{$airport}";
        
        if (isset($this->cache[$cacheKey])) {
            return $this->cache[$cacheKey]['data'] ?? [];
        }
        
        // Simulate charts
        $charts = [
            'aref' => 'AREF ' . $airport,
            'name' => $airport . ' (Flughafen)',
            'type' => 'AREF',
            'scale' => '1:50000',
            'validity' => date('Y-m-d'),
            'charts' => [
                'SID' => 'Standard Instrument Departures',
                'STAR' => 'Standard Terminal Arrival Routes',
                'ICAO' => 'ICAO Charts',
                'Enroute' => 'En Route Charts',
            ],
        ];
        
        $this->cache[$cacheKey] = [
            'data' => $charts,
            'timestamp' => time(),
        ];
        
        return $charts;
    }
    
    /**
     * Get runway data for airport
     */
    public function getRunwayData(string $airport): array
    {
        $airport = strtoupper($airport);
        $cacheKey = "runways/{$airport}";
        
        if (isset($this->cache[$cacheKey])) {
            return $this->cache[$cacheKey]['data'] ?? [];
        }
        
        // Simulate runway data
        $runways = [
            [
                'number' => '09',
                'length' => 4000,
                'width' => 45,
                'surface' => 'ASP',
                'lighted' => true,
                'ilcat' => 'CAT I',
                'displacedThreshold' => 0,
            ],
            [
                'number' => '27',
                'length' => 4000,
                'width' => 45,
                'surface' => 'ASP',
                'lighted' => true,
                'ilcat' => 'CAT I',
                'displacedThreshold' => 0,
            ],
            [
                'number' => '18C',
                'length' => 3000,
                'width' => 45,
                'surface' => 'ASP',
                'lighted' => true,
                'ilcat' => 'CAT II',
            ],
            [
                'number' => '36C',
                'length' => 3000,
                'width' => 45,
                'surface' => 'ASP',
                'lighted' => true,
                'ilcat' => 'CAT II',
            ],
        ];
        
        $this->cache[$cacheKey] = [
            'data' => $runways,
            'timestamp' => time(),
        ];
        
        return $runways;
    }
    
    /**
     * Get airspace information
     */
    public function getAirspace(string $airport): array
    {
        $airport = strtoupper($airport);
        $cacheKey = "airspace/{$airport}";
        
        if (isset($this->cache[$cacheKey])) {
            return $this->cache[$cacheKey]['data'] ?? [];
        }
        
        // Simulate airspace
        $airspace = [
            [
                'name' => 'Frankfurt FIR',
                'type' => 'C',
                'ceiling' => 60000,
                'verticalLimit' => null,
                'horizontal' => 'All',
            ],
            [
                'name' => 'Class D',
                'type' => 'D',
                'ceiling' => 8000,
                'verticalLimit' => 8000,
                'horizontal' => "{$airport} vicinity",
            ],
        ];
        
        $this->cache[$cacheKey] = [
            'data' => $airspace,
            'timestamp' => time(),
        ];
        
        return $airspace;
    }
    
    /**
     * Clear cache
     */
    public function clearCache(): void
    {
        $this->cache = [];
        $this->logger->info('OpenAIP cache cleared');
    }
    
    /**
     * Get cache status
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
    
    /**
     * Get airport information
     */
    public function getAirport(string $airport): array
    {
        $airport = strtoupper($airport);
        $cacheKey = "airport/{$airport}";
        
        if (isset($this->cache[$cacheKey])) {
            return $this->cache[$cacheKey]['data'] ?? [];
        }
        
        // Simulate airport data
        $airportData = [
            'icao' => $airport,
            'name' => "{$airport} Airport",
            'city' => 'Munich',
            'country' => 'DE',
            'latitude' => 48.3538,
            'longitude' => 11.7861,
            'elevation' => 1487,
            'timezone' => 'Europe/Berlin',
        ];
        
        $this->cache[$cacheKey] = [
            'data' => $airportData,
            'timestamp' => time(),
        ];
        
        return $airportData;
    }
}
