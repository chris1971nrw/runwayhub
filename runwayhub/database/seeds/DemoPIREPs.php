<?php

namespace Database\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoPIREPs extends Seeder
{
    /**
     * Fügt Demo PIREPs hinzu
     */
    public function run(): void
    {
        // PIREP für Flug DMF001
        DB::table('demo_pireps')->insert([
            [
                'flight_number' => 'DMF001',
                'aircraft_id' => null,
                'pilot_id' => 3,
                'altitude' => 35000,
                'speed' => 450,
                'fuel' => '75%',
                'temperature' => -45.0,
                'weather_conditions' => 'Gewitter',
                'visibility' => '5nm',
                'wind_speed' => '45kt',
                'wind_direction' => 'SW',
                'turbulence' => 'Moderate',
                'icing' => 'None',
                'comments' => 'Glatter Flug, schöne Sicht. Gewitterwolken im Süden, aber keine Auswirkungen auf den Flugweg.',
                'is_public' => true,
                'status' => 'submitted',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
