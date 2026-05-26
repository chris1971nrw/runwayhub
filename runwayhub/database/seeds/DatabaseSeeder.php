<?php

namespace Database\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with Demo Data
     */
    public function run(): void
    {
        // Airline
        $this->call([
            DemoAirline::class,
        ]);

        // Users
        $this->call([
            DemoUsers::class,
        ]);

        // Fleet
        $this->call([
            DemoFleet::class,
        ]);

        // Flights
        $this->call([
            DemoFlights::class,
        ]);

        // PIREPs
        $this->call([
            DemoPIREPs::class,
        ]);

        // Bookings
        $this->call([
            DemoBookings::class,
        ]);
    }
}
