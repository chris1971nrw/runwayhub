<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\FlightLog;

class ProcessFlightLogJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $filePath,
        public string $scheduleId,
        public ?string $pilotId = null
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Read flight log file from storage
            $fileContent = Storage::get($this->filePath);

            if (!$fileContent) {
                Log::warning("Flight log file is empty: {$this->filePath}");
                return;
            }

            // Parse MSFS/X-Plane telemetry data
            $flightData = $this->parseTelemetryData($fileContent);

            // Validate flight data
            if (!$this->validateFlightData($flightData)) {
                Log::warning("Invalid flight data: {$this->filePath}");
                return;
            }

            // Create or update flight log record
            $flightLog = FlightLog::firstOrCreate(
                ['schedule_id' => $this->scheduleId],
                [
                    'pilot_id' => $this->pilotId,
                    'actual_from_airport' => $flightData['from_airport'] ?? null,
                    'actual_to_airport' => $flightData['to_airport'] ?? null,
                    'actual_off' => $flightData['departure_time'] ?? null,
                    'actual_arrival' => $flightData['arrival_time'] ?? null,
                    'fuel_burned_gallons' => $flightData['fuel_consumed'] ?? null,
                    'comments' => $flightData['notes'] ?? null,
                    'weather_conditions' => $flightData['weather'] ?? null,
                ]
            );

            // Process uploaded screenshots if any
            $this->processScreenshots($flightLog);

            // Store parsed telemetry data for analytics
            $this->storeAnalytics($flightLog);

            Log::info("Flight log processed successfully: {$this->filePath}");

        } catch (\Exception $e) {
            Log::error("Error processing flight log: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Parse MSFS/X-Plane telemetry data.
     */
    protected function parseTelemetryData(string $fileContent): array
    {
        // Extract key telemetry metrics from file content
        // This is a simplified parser - replace with actual parsing logic
        $data = [
            'from_airport' => null,
            'to_airport' => null,
            'departure_time' => null,
            'arrival_time' => null,
            'fuel_consumed' => null,
            'notes' => null,
            'weather' => null,
        ];

        // Parse departure airport and time
        if (preg_match('/Departure: ([A-Z]{4})? - (.+)/', $fileContent, $matches)) {
            $data['from_airport'] = $matches[1] ?? null;
            $data['notes'] = $matches[2] ?? null;
        }

        // Parse arrival airport and time
        if (preg_match('/Arrival: ([A-Z]{4})? - (.+)/', $fileContent, $matches)) {
            $data['to_airport'] = $matches[1] ?? null;
            $data['notes'] .= ' ' . $matches[2] ?? '';
        }

        // Parse fuel consumption
        if (preg_match('/Fuel Consumed: (\d+\.?\d*)/', $fileContent, $matches)) {
            $data['fuel_consumed'] = floatval($matches[1]);
        }

        // Parse weather conditions
        if (preg_match('/Weather Conditions: (.+)/', $fileContent, $matches)) {
            $data['weather'] = trim($matches[1]);
        }

        return $data;
    }

    /**
     * Validate flight data.
     */
    protected function validateFlightData(array $data): bool
    {
        // Basic validation
        return true; // Add more validation logic as needed
    }

    /**
     * Process uploaded screenshots.
     */
    protected function processScreenshots(FlightLog $flightLog): void
    {
        // Check for screenshot files in storage
        $screenshotsDir = 'app/flights/' . $flightLog->schedule_id;
        
        if (!Storage::exists($screenshotsDir)) {
            return;
        }

        // Get all screenshot files
        $files = Storage::allFiles($screenshotsDir);

        foreach ($files as $file) {
            // Store screenshot path in flight log
            $flightLog->screenshots[] = $file;
        }

        $flightLog->save();
    }

    /**
     * Store analytics data for performance tracking.
     */
    protected function storeAnalytics(FlightLog $flightLog): void
    {
        // TODO: Implement analytics storage logic
        // Could store in separate analytics table or cache
    }

    /**
     * The job failed to execute.
     */
    public function failed(\Throwable $throwable): void
    {
        Log::error("Flight log processing failed: " . $throwable->getMessage());
    }
}
