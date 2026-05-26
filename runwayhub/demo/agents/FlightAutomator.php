<?php

/**
 * FlightAutomator - Automatischer Flug-Scheduler
 * Generiert demo-flüge und verwaltet den Flugplan
 */

declare(strict_types=1);

namespace Demo\Agents;

use RunwayHub\Database\Database;
use RunwayHub\Models\Flight;
use RunwayHub\Models\Airport;

class FlightAutomator
{
    public function __construct(
        private Database $db,
        private array $config = []
    ) {
    }

    /**
     * Generiert demo-flüge für die nächsten 24 Stunden
     */
    public function generateFlights(): array
    {
        $airports = Airport::all();
        $flights = [];

        foreach ($airports as $fromAirport) {
            foreach ($airports as $toAirport) {
                if ($fromAirport->code === $toAirport->code) {
                    continue;
                }

                $flight = Flight::create([
                    'flight_number' => 'DH' . str_pad($fromAirport->id . $toAirport->id, 3, '0', STR_PAD_LEFT),
                    'origin_id' => $fromAirport->id,
                    'destination_id' => $toAirport->id,
                    'scheduled_departure' => now()->format('Y-m-d H:i:s'),
                    'scheduled_arrival' => now()->modify('+2 hours')->format('Y-m-d H:i:s'),
                    'status' => 'scheduled',
                ]);

                $flights[] = $flight;
            }
        }

        return $flights;
    }

    /**
     * Simuliert Flugschritte
     */
    public function simulateFlightProgress(int $flightId): ?Flight
    {
        $flight = Flight::find($flightId);

        if (!$flight) {
            return null;
        }

        $now = new \DateTime();
        $scheduledArrival = new \DateTime($flight->scheduled_arrival);
        $progress = max(0, min(100, ($now->getTimestamp() - $scheduledArrival->getTimestamp()) / 2 / 3600 * 100));

        if ($progress >= 100) {
            $flight->status = 'arrived';
            $flight->completed_at = now()->format('Y-m-d H:i:s');
            $flight->save();
        } elseif ($progress > 0) {
            $flight->status = 'in_air';
            $flight->progress = $progress;
            $flight->save();
        }

        return $flight;
    }
}
