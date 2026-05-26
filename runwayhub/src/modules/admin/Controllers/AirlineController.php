<?php

namespace RunwayHub\Modules\Admin\Controllers;

use RunwayHub\Core\Airline;
use Exception;

/**
 * Airline-Controller für Multi-Tenant Management
 * 
 * Verwaltet Airlines, Hosting-Tiers, Billing
 * 
 * @package RunwayHub\Modules\Admin\Controllers
 */
class AirlineController
{
    /**
     * Airline-Repository
     * @var Airline
     */
    private Airline $airline;

    /**
     * Konstruktor
     */
    public function __construct()
    {
        $this->airline = new Airline();
    }

    /**
     * Airline-Liste anzeigen
     * 
     * @return array View-Daten
     */
    public function index(): array
    {
        $airlines = $this->airline->getAll();

        return [
            'airlines' => $airlines,
            'total' => count($airlines),
            'active' => array_filter($airlines, fn($a) => $a['is_active'])->count(),
            'inactive' => count($airlines) - array_filter($airlines, fn($a) => $a['is_active'])->count(),
        ];
    }

    /**
     * Airline erstellen
     * 
     * @param array $data Airline-Daten
     * @param string $tier Hosting-Tier (free|business|enterprise)
     * @return array['success' => bool, 'airline_id' => int|null, 'message' => string]
     */
    public function create(array $data, string $tier = 'free'): array
    {
        try {
            // Validierung
            if (!isset($data['name'])) {
                throw new Exception('Airline-Name ist erforderlich.');
            }

            if (!isset($data['iata'])) {
                throw new Exception('IATA-Code ist erforderlich.');
            }

            // Check ob IATA-Code bereits vergeben
            $existing = $this->airline->findByIata($data['iata']);
            if ($existing) {
                throw new Exception("IATA-Code '{$data['iata']}' ist bereits vergeben.");
            }

            // Airline erstellen
            $airlineId = $this->airline->create($data);

            // Tier konfigurieren
            $this->setTier($airlineId, $tier);

            // API Key generieren
            $apiKey = $this->generateApiKey($airlineId);

            return [
                'success' => true,
                'airline_id' => $airlineId,
                'name' => $data['name'],
                'tier' => $tier,
                'api_key' => $apiKey,
                'message' => "Airline '$data[name]' wurde erstellt.",
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'airline_id' => null,
            ];
        }
    }

    /**
     * Airline-Details
     * 
     * @param int $id Airline-ID
     * @return array Airline-Detail-Daten
     */
    public function show(int $id): array
    {
        $airline = $this->airline->find($id);

        if (!$airline) {
            throw new Exception('Airline nicht gefunden.');
        }

        // Statistics
        $flights = $this->airline->getFlights($id);
        $bookings = $this->airline->getBookings($id);
        $pireps = $this->airline->getPIREPs($id);

        return [
            'airline' => $airline,
            'statistics' => [
                'total_flights' => count($flights),
                'total_bookings' => count($bookings),
                'total_pireps' => count($pireps),
                'active_aircraft' => $this->airline->getActiveAircraft($id)->count(),
                'total_passengers' => $this->airline->getTotalPassengers($id),
            ],
        ];
    }

    /**
     * Tier ändern
     * 
     * @param int $airlineId Airline-ID
     * @param string $tier Neuer Tier
     * @return array['success' => bool, 'message' => string]
     */
    public function setTier(int $airlineId, string $tier): array
    {
        $tiers = ['free', 'business', 'enterprise'];

        if (!in_array(strtolower($tier), $tiers)) {
            throw new Exception("Ungültiges Tier: {$tier}. Gültige Tiers: " . implode(', ', $tiers));
        }

        // Airline-Update
        $this->airline->update($airlineId, [
            'tier' => $tier,
        ]);

        return [
            'success' => true,
            'message' => "Tier auf '$tier' geändert.",
        ];
    }

    /**
     * Billing-Daten
     * 
     * @param int $airlineId Airline-ID
     * @return array Billing-Daten
     */
    public function billing(int $airlineId): array
    {
        $airline = $this->airline->find($airlineId);

        if (!$airline) {
            throw new Exception('Airline nicht gefunden.');
        }

        // Pricing Berechnung
        $pricing = [
            'tier' => $airline['tier'] ?? 'free',
            'monthly_fee' => 0,
            'usage' => [
                'flights' => 0,
                'passengers' => 0,
                'storage' => 0,
            ],
        ];

        switch ($airline['tier']) {
            case 'business':
                $pricing['monthly_fee'] = 99;
                break;
            case 'enterprise':
                $pricing['monthly_fee'] = 299;
                break;
        }

        return [
            'airline_id' => $airlineId,
            'airline_name' => $airline['name'],
            'tier' => $pricing['tier'],
            'monthly_fee' => $pricing['monthly_fee'],
            'pricing' => $pricing,
        ];
    }

    /**
     * API Keys erstellen
     * 
     * @param int $airlineId Airline-ID
     * @return array API-Key-Daten
     */
    public function generateApiKey(int $airlineId): array
    {
        $apiKey = bin2hex(random_bytes(32));

        // In Database speichern
        $this->airline->update($airlineId, [
            'api_key' => $apiKey,
        ]);

        return [
            'airline_id' => $airlineId,
            'api_key' => $apiKey,
            'expires_at' => now()->addYear(),
        ];
    }

    /**
     * Airline deaktivieren
     * 
     * @param int $id Airline-ID
     * @return array['success' => bool, 'message' => string]
     */
    public function deactivate(int $id): array
    {
        $airline = $this->airline->find($id);

        if (!$airline) {
            throw new Exception('Airline nicht gefunden.');
        }

        $this->airline->update($id, [
            'is_active' => false,
        ]);

        return [
            'success' => true,
            'message' => "Airline deaktiviert.",
        ];
    }

    /**
     * API Health Check
     * 
     * @return array
     */
    public function health(): array
    {
        return [
            'status' => 'ok',
            'airlines' => Airline::count(),
            'active_airlines' => Airline::where('is_active', true)->count(),
            'storage' => \Storage::disk('filesystem')->usage(),
            'database' => config('database.default'),
            'openaip' => [
                'status' => 'available',
                'cache' => true,
                'ttl' => 3600,
            ],
            'uptime' => now()->subDay()->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * API Key validieren
     * 
     * @param string $apiKey API-Key
     * @return bool
     */
    public function validateApiKey(string $apiKey): bool
    {
        try {
            $airline = $this->airline->findByApiKey($apiKey);

            if (!$airline) {
                return false;
            }

            if (!$airline['is_active']) {
                return false;
            }

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * API Key widerrufen
     * 
     * @param string $apiKey API-Key
     * @return array['success' => bool, 'message' => string]
     */
    public function revokeApiKey(string $apiKey): array
    {
        $airline = $this->airline->findByApiKey($apiKey);

        if (!$airline) {
            throw new Exception('API Key nicht gefunden.');
        }

        $this->airline->update($airline['id'], [
            'api_key' => null,
        ]);

        return [
            'success' => true,
            'message' => "API Key widerrufen.",
        ];
    }
}
