<?php

/**
 * PIREPGenerator - PIREP-Simulation
 * Generiert Pilot Reports für abgeflogene Flüge
 */

declare(strict_types=1);

namespace Demo\Agents;

use RunwayHub\Database\Database;
use RunwayHub\Models\Flight;
use RunwayHub\Models\Pirep;

class PIREPGenerator
{
    private array $weatherPatterns = [
        'clear', 'partly_cloudy', 'cloudy', 'rain', 'snow', 'fog'
    ];

    private array $windPatterns = [
        ['direction' => 'NW', 'speed' => [5, 15]],
        ['direction' => 'N', 'speed' => [10, 25]],
        ['direction' => 'NE', 'speed' => [8, 20]],
        ['direction' => 'E', 'speed' => [12, 30]],
        ['direction' => 'SE', 'speed' => [15, 35]],
        ['direction' => 'S', 'speed' => [20, 40]],
        ['direction' => 'SW', 'speed' => [10, 25]],
        ['direction' => 'W', 'speed' => [5, 20]],
    ];

    public function __construct(
        private Database $db,
        private array $config = []
    ) {
    }

    /**
     * Generiert PIREPs für abgeflogene Flüge
     */
    public function generatePIREPsForCompletedFlights(): array
    {
        $flights = Flight::where('status', 'arrived')->orderBy('completed_at', 'desc')->get();
        $pireps = [];

        foreach ($flights as $flight) {
            $pirep = $this->generatePIREP($flight);
            if ($pirep) {
                $pireps[] = $pirep;
            }
        }

        return $pireps;
    }

    /**
     * Generiert einen PIREP für einen Flug
     */
    private function generatePIREP(Flight $flight): ?PIREP
    {
        $pirepData = [
            'flight_id' => $flight->id,
            'altitude' => $this->randomInt(2000, 42000),
            'wind_direction' => $this->randomInt(10, 350),
            'wind_speed' => $this->randomInt(5, 60),
            'temperature_c' => $this->randomInt(-40, 35),
            'visibility_km' => $this->randomFloat(0.5, 40, 2),
            'weather_condition' => $this->randomElement($this->weatherPatterns),
            'remarks' => $this->generateRemarks($flight),
            'report_time' => now(),
        ];

        return PIREP::create($pirepData);
    }

    /**
     * Generiert Bemerkungen für den PIREP
     */
    private function generateRemarks(Flight $flight): string
    {
        $remarks = [];

        if ($this->hasWeather($flight->destination_id)) {
            $remarks[] = 'Unwetter am Zielhafen erwartet.';
        }

        if ($flight->progress > 50) {
            $remarks[] = 'Flug auf Normalhöheniveau.';
        }

        $remarks[] = 'Flug sicher durchgeführt.';

        return implode('. ', $remarks) . '.';
    }

    /**
     * Prüft, ob am Zielhafen Wetter herrscht
     */
    private function hasWeather(int $airportId): bool
    {
        // Simulierter Wetter-Check
        return rand(0, 100) < 30; // 30% Chance auf Wetter
    }

    /**
     * Hilfsfunktion: Zufällige Zahl
     */
    private function randomInt(int $min, int $max): int
    {
        return random_int($min, $max);
    }

    /**
     * Hilfsfunktion: Zufälliges Element
     */
    private function randomElement(array $array): mixed
    {
        return $array[array_rand($array)];
    }

    /**
     * Hilfsfunktion: Zufällige Float-Zahl
     */
    private function randomFloat(float $min, float $max, int $decimals = 1): float
    {
        return round($this->randomInt((int) ($min * 100), (int) ($max * 100)) / 100, $decimals);
    }
}
