<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pilot;
use App\Models\FlightSchedule;
use App\Models\Aircraft;
use App\Models\FlightLog;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user (ground_control)
        Pilot::create([
            'name' => 'System Admin',
            'callsign' => 'ADMIN 1',
            'email' => 'admin@runwayhub.run',
            'password' => bcrypt('password'),
            'role' => 'ground_control',
            'is_active' => true,
        ]);

        // Create sample pilots
        $pilots = [
            ['name' => 'Max Mustermann', 'callsign' => 'ALPHA 1', 'email' => 'pilot1@example.com', 'role' => 'pilot'],
            ['name' => 'Anna Schmidt', 'callsign' => 'BRAVO 2', 'email' => 'pilot2@example.com', 'role' => 'pilot'],
            ['name' => 'Tom Weber', 'callsign' => 'CHARLIE 3', 'email' => 'pilot3@example.com', 'role' => 'pilot'],
            ['name' => 'Lisa Müller', 'callsign' => 'DELTA 4', 'email' => 'pilot4@example.com', 'role' => 'pilot'],
        ];

        foreach ($pilots as $index => $pilotData) {
            Pilot::create(array_merge($pilotData, [
                'home_base_icao' => ['EGKK', 'EDDF', 'LEMD', 'LFPG'][$index % 4],
                'skill_level' => ['beginner', 'intermediate', 'expert', 'intermediate'][$index],
                'license_type' => ['PPL', 'CPL', 'ATPL', 'CPL'][$index],
                'aircraft_certified' => ['A320', 'B738', 'A319', 'B752'][$index % 4],
            ]));
        }

        // Create aircraft fleet
        $aircrafts = [
            ['registration' => 'D-AIMR', 'model_type' => 'A320', 'total_seats' => 180, 'crew_required' => 4],
            ['registration' => 'D-ABCD', 'model_type' => 'B738', 'total_seats' => 160, 'crew_required' => 4],
            ['registration' => 'D-EFGH', 'model_type' => 'A319', 'total_seats' => 120, 'crew_required' => 3],
            ['registration' => 'D-IJKL', 'model_type' => 'B752', 'total_seats' => 140, 'crew_required' => 4],
        ];

        foreach ($aircrafts as $index => $aircraftData) {
            Aircraft::create(array_merge($aircraftData, [
                'maintenance_status' => ['airworthy', 'scheduled_maint', 'airworthy', 'grounded'][$index % 4],
                'next_maintenance_due' => now()->addMonths(3 * ($index + 1)),
                'engine_cycles' => 1000 + $index * 500,
                'available' => true,
            ]));
        }

        // Create flight schedules (sample data)
        for ($i = 1; $i <= 10; $i++) {
            FlightSchedule::create([
                'callsign' => FlightSchedule::generateCallsign($i),
                'departure_date' => now()->copy()->addDays($i * 3),
                'from_airport_icao' => ['EGKK', 'EDDF', 'LEMD', 'LFPG'][$i % 4],
                'to_airport_icao' => ['LHBB', 'EDDM', 'LEMD', 'LFPG'][($i + 1) % 4],
                'estimated_off' => now()->copy()->addDays($i * 3)->addHours(8),
                'estimated_arrival' => now()->copy()->addDays($i * 3)->addHours(9),
                'aircraft_type' => ['A320', 'B738', 'A319', 'B752'][$i % 4],
                'distance_nm' => 400 + ($i * 50) % 600,
                'active' => $i <= 5, // Only first 5 schedules active for demo
            ]);
        }

        // Create completed flight logs (sample data)
        for ($i = 1; $i <= 3; $i++) {
            FlightLog::create([
                'pilot_id' => 2 + ($i % 3), // Random pilot from sample set
                'schedule_id' => 1 + ($i % 5), // Random active schedule
                'actual_from_airport' => 'EGKK',
                'actual_to_airport' => 'LHBB',
                'actual_off' => now()->copy()->subDays($i)->addHours(8),
                'actual_arrival' => now()->copy()->subDays($i)->addHours(9),
                'fuel_burned_gallons' => 45 + ($i * 10),
                'comments' => "Flight completed successfully. Weather conditions were good.",
                'completed' => true,
                'weather_conditions' => 'vfr_clear',
            ]);
        }

        // TODO: Add more seed data as needed
    }
}
