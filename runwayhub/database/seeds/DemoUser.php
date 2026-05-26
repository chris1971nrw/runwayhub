<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Demo-User Seeder für DemoAirline
 * 
 * Erstellt Demo-User in der DemoAirline
 * 
 * User:
 * 1. Demo Administrator
 * 2. Demo Pilot
 * 3. Demo Guest
 */
class DemoUser extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Airline-ID finden (DemoAirline)
        $airline = \App\Models\Airline::where('name', 'DemoAirline')->first();

        if (!$airline) {
            throw new Exception('DemoAirline nicht gefunden!');
        }

        $airlineId = $airline->id;

        // Demo Benutzer anlegen
        $users = [
            [
                'username' => 'demo_admin',
                'email' => 'admin@runwayhub.de',
                'password' => Hash::make('admin123'), // Passwort: admin123
                'role' => 'admin',
                'first_name' => 'Demo',
                'last_name' => 'Administrator',
                'bio' => 'Administrative Demo-Benutzer',
                'status' => 'active',
            ],
            [
                'username' => 'demo_pilot',
                'email' => 'pilot@runwayhub.de',
                'password' => Hash::make('pilot123'), // Passwort: pilot123
                'role' => 'pilot',
                'first_name' => 'Demo',
                'last_name' => 'Pilot',
                'bio' => 'Demo-Pilot mit Boeing 737, Airbus A320, Cessna 172 Qualifikation',
                'status' => 'active',
            ],
            [
                'username' => 'demo_guest',
                'email' => 'guest@runwayhub.de',
                'password' => Hash::make('guest123'), // Passwort: guest123
                'role' => 'guest',
                'first_name' => 'Demo',
                'last_name' => 'Gast',
                'bio' => 'Demo-Gastbenutzer',
                'status' => 'active',
            ],
        ];

        foreach ($users as $userData) {
            $user = \App\Models\User::create([
                'airline_id' => $airlineId,
                'username' => $userData['username'],
                'email' => $userData['email'],
                'password' => $userData['password'],
                'role' => $userData['role'],
                'first_name' => $userData['first_name'],
                'last_name' => $userData['last_name'],
                'bio' => $userData['bio'],
                'status' => $userData['status'],
            ]);

            // Avatar hinzufügen
            if (isset($userData['avatar'])) {
                $user->avatar = $userData['avatar'];
                $user->save();
            }
        }

        $this->command->info("Demo-User erstellt:");
        $this->command->info("- demo_admin / admin123");
        $this->command->info("- demo_pilot / pilot123");
        $this->command->info("- demo_guest / guest123");
    }
}
