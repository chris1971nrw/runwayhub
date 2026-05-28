<?php

declare(strict_types=1);

namespace RunwayHub\Services;

use Monolog\Logger;

/**
 * ACARS Service (Eigene ACARS-Entwicklung)
 * 
 * Handles real-time flight tracking via ACARS protocol.
 * Simulates ACARS connection for development.
 */
class ACARSService
{
    private object $logger;
    private ?string $apiKey = null;
    private ?string $acarsApiUrl = null;
    private array $flights = [];
    private int $cacheTTL = 300; // 5 minutes
    
    public function __construct(object $logger = null)
    {
        $this->logger = $logger ?? new class {
            public function debug(string $msg): void {}
            public function info(string $msg): void { echo $msg . "\n"; }
            public function warning(string $msg): void {}
            public function error(string $msg): void { echo "❌ " . $msg . "\n"; }
            public function critical(string $msg): void {}
        };
        
        $this->apiKey = getenv('ACARS_API_KEY') ?: null;
        $this->acarsApiUrl = getenv('ACARS_API_URL') ?: 'https://api.runwayhub.example/acars';
        
        // Check if ACARS API is available
        if ($this->apiKey && $this->acarsApiUrl) {
            $this->logger->info("ACARS-API verfügbar: {$this->acarsApiUrl}");
        } else {
            $this->logger->info("ACARS-Modus (Mock-Daten): API-Keys nicht konfiguriert");
        }
    }
    
    /**
     * Initialize ACARS service
     * 
     * @return void
     */
    public function initialize(): void
    {
        $this->flights = [
            'LH456' => [
                'origin' => 'FRA',
                'destination' => 'JFK',
                'departure_time' => '2026-05-28 14:30:00',
                'arrival_time' => '2026-05-28 17:45:00',
                'status' => 'scheduled',
                'aircraft' => 'D-AIMA',
                'lat' => 50.0379,
                'lon' => 8.5622,
                'altitude' => 0,
                'ground_speed' => 0,
                'track' => 0,
                'last_heartbeat' => null,
                'acars_status' => 'on_ground',
                'delay_minutes' => 0,
                'baggage_claim' => null,
                'gate' => 'A12',
            ],
            'LH458' => [
                'origin' => 'FRA',
                'destination' => 'JFK',
                'departure_time' => '2026-05-28 18:30:00',
                'arrival_time' => '2026-05-28 21:45:00',
                'status' => 'scheduled',
                'aircraft' => 'D-AIMA2',
                'lat' => 50.0379,
                'lon' => 8.5622,
                'altitude' => 0,
                'ground_speed' => 0,
                'track' => 0,
                'last_heartbeat' => null,
                'acars_status' => 'on_ground',
                'delay_minutes' => 0,
                'baggage_claim' => null,
                'gate' => 'A13',
            ],
            'BA123' => [
                'origin' => 'LHR',
                'destination' => 'FRA',
                'departure_time' => '2026-05-28 12:00:00',
                'arrival_time' => '2026-05-28 14:30:00',
                'status' => 'scheduled',
                'aircraft' => 'D-AIME',
                'lat' => 51.4700,
                'lon' => -0.4543,
                'altitude' => 0,
                'ground_speed' => 0,
                'track' => 0,
                'last_heartbeat' => null,
                'acars_status' => 'on_ground',
                'delay_minutes' => 0,
                'baggage_claim' => '1',
                'gate' => 'B2',
            ],
            'AF054' => [
                'origin' => 'CDG',
                'destination' => 'FRA',
                'departure_time' => '2026-05-28 13:00:00',
                'arrival_time' => '2026-05-28 15:00:00',
                'status' => 'scheduled',
                'aircraft' => 'D-AIMI',
                'lat' => 49.0097,
                'lon' => 2.5479,
                'altitude' => 0,
                'ground_speed' => 0,
                'track' => 0,
                'last_heartbeat' => null,
                'acars_status' => 'on_ground',
                'delay_minutes' => 0,
                'baggage_claim' => '2',
                'gate' => 'A5',
            ],
            'LH202' => [
                'origin' => 'MUC',
                'destination' => 'JFK',
                'departure_time' => '2026-05-28 15:00:00',
                'arrival_time' => '2026-05-28 18:15:00',
                'status' => 'scheduled',
                'aircraft' => 'D-AIMI2',
                'lat' => 48.3538,
                'lon' => 11.7861,
                'altitude' => 0,
                'ground_speed' => 0,
                'track' => 0,
                'last_heartbeat' => null,
                'acars_status' => 'on_ground',
                'delay_minutes' => 0,
                'baggage_claim' => '3',
                'gate' => 'B7',
            ],
        ];
        
        $this->logger->info("ACARS Service initialisiert mit " . count($this->flights) . " Flügen");
    }
    
    /**
     * Get flight status with ACARS data
     * 
     * @param string $flightNumber Flight number
     * @return array Flight status
     */
    public function getFlightStatus(string $flightNumber): array
    {
        $flightNumber = strtoupper($flightNumber);
        
        // Check cache first
        $cacheKey = "acars/{$flightNumber}/status";
        if (isset($this->cache[$cacheKey])) {
            $this->logger->debug("ACARS-Cache für Flug {$flightNumber} verwendet");
            return $this->cache[$cacheKey];
        }
        
        // Check if flight exists
        if (isset($this->flights[$flightNumber])) {
            $flight = $this->flights[$flightNumber];
            $status = [
                'flight_number' => $flightNumber,
                'status' => $flight['status'],
                'acars_status' => $flight['acars_status'],
                'origin' => $flight['origin'],
                'destination' => $flight['destination'],
                'departure_time' => $flight['departure_time'],
                'arrival_time' => $flight['arrival_time'],
                'eta' => $flight['departure_time'],
                'latitude' => $flight['lat'],
                'longitude' => $flight['lon'],
                'altitude' => $flight['altitude'],
                'ground_speed' => $flight['ground_speed'],
                'track' => $flight['track'],
                'last_heartbeat' => $flight['last_heartbeat'],
                'aircraft' => $flight['aircraft'],
                'gate' => $flight['gate'] ?? null,
                'baggage_claim' => $flight['baggage_claim'] ?? null,
                'delay_minutes' => $flight['delay_minutes'] ?? 0,
            ];
            
            // Update cache
            $this->cache[$cacheKey] = $status;
            $this->logger->info("ACARS-Status für Flug {$flightNumber} geladen");
            
            return $status;
        }
        
        // Flight not found - return empty status
        return [
            'flight_number' => $flightNumber,
            'status' => 'not_found',
            'acars_status' => null,
            'origin' => null,
            'destination' => null,
            'departure_time' => null,
            'arrival_time' => null,
            'eta' => null,
            'latitude' => null,
            'longitude' => null,
            'altitude' => null,
            'ground_speed' => null,
            'track' => null,
            'last_heartbeat' => null,
            'aircraft' => null,
            'gate' => null,
            'baggage_claim' => null,
            'delay_minutes' => null,
        ];
    }
    
    /**
     * Update flight status from ACARS
     * 
     * @param string $flightNumber Flight number
     * @param array $status Status data
     * @return bool
     */
    public function updateFlightStatus(string $flightNumber, array $status): bool
    {
        $flightNumber = strtoupper($flightNumber);
        
        // Check if flight exists
        if (!isset($this->flights[$flightNumber])) {
            return false;
        }
        
        // Update flight status
        $this->flights[$flightNumber] = array_merge($this->flights[$flightNumber], $status);
        
        // Clear cache
        unset($this->cache["acars/{$flightNumber}/status"]);
        
        $this->logger->info("ACARS-Status für Flug {$flightNumber} aktualisiert");
        
        return true;
    }
    
    /**
     * Get all flights status
     * 
     * @return array List of all flights with status
     */
    public function getAllFlights(): array
    {
        return array_values($this->flights);
    }
}
