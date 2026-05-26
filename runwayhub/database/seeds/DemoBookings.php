<?php

namespace Database\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoBookings extends Seeder
{
    /**
     * Fügt Demo Buchungen hinzu
     */
    public function run(): void
    {
        // Demo Buchung 1
        DB::table('demo_bookings')->insert([
            [
                'flight_id' => 1,
                'user_id' => null, // Wird später zugewiesen
                'passenger_name' => 'Max Mustermann',
                'passenger_email' => 'max@example.com',
                'class' => 'economy',
                'price' => 299.00,
                'booking_number' => 'DM001',
                'status' => 'confirmed',
                'payment_method' => 'credit_card',
                'payment_status' => 'paid',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Demo Buchung 2
        DB::table('demo_bookings')->insert([
            [
                'flight_id' => 1,
                'user_id' => null,
                'passenger_name' => 'Erika Musterfrau',
                'passenger_email' => 'erika@example.com',
                'class' => 'economy',
                'price' => 299.00,
                'booking_number' => 'DM002',
                'status' => 'confirmed',
                'payment_method' => 'credit_card',
                'payment_status' => 'paid',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Demo Buchung 3
        DB::table('demo_bookings')->insert([
            [
                'flight_id' => 1,
                'user_id' => null,
                'passenger_name' => 'Hans Beispielmann',
                'passenger_email' => 'hans@example.com',
                'class' => 'economy',
                'price' => 299.00,
                'booking_number' => 'DM003',
                'status' => 'confirmed',
                'payment_method' => 'paypal',
                'payment_status' => 'paid',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
