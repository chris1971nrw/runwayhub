<?php
declare(strict_types=1);

namespace RunwayHub\Cli;

use PDOException;

/**
 * Seed CLI Tool - Populate database with initial data
 */
require_once __DIR__ . '/../../config/database.php';

$config = require __DIR__ . '/../../config/database.php';

try {
    $db = new \RunwayHub\Core\Database($config['default']);
    
    echo "\n=== RunwayHub Database Seed ===\n\n";
    
    // Create admin user
    $adminPasswordHash = password_hash('admin123', PASSWORD_DEFAULT);
    
    echo "Creating admin user...\n";
    $db->insert('users', [
        'username' => 'admin',
        'email' => 'admin@runwayhub.de',
        'password' => $adminPasswordHash,
        'role' => 'admin',
        'status' => 'active',
    ]);
    
    echo "  ✓ Admin user created\n";
    
    // Add some sample airports
    echo "\nAdding sample airports...\n";
    $airports = [
        ['FRA', 'EDDF', 'Frankfurt Airport', 'Frankfurt', 'Germany', 'Europe/Berlin', 50.0333, 8.5706, 364, '+01:00'],
        ['MUC', 'EDDM', 'Munich Airport', 'Munich', 'Germany', 'Europe/Berlin', 48.3538, 11.7861, 453, '+01:00'],
        ['HAJ', 'EDDV', 'Hannover Airport', 'Hannover', 'Germany', 'Europe/Berlin', 52.4611, 9.6829, 183, '+01:00'],
        ['HAM', 'EDDH', 'Hamburg Airport', 'Hamburg', 'Germany', 'Europe/Berlin', 53.6258, 9.9805, 12, '+01:00'],
        ['AMS', 'EHRD', 'Amsterdam Airport', 'Amsterdam', 'Netherlands', 'Europe/Berlin', 52.3105, 4.7683, -3, '+01:00'],
        ['LHR', 'EGLL', 'London Heathrow', 'London', 'United Kingdom', 'Europe/Berlin', 51.4700, -0.4543, 83, '+00:00'],
    ];
    
    foreach ($airports as $airport) {
        $db->insert('airports', [
            'iata_code' => $airport[0],
            'icao_code' => $airport[1],
            'name' => $airport[2],
            'city' => $airport[3],
            'country' => $airport[4],
            'latitude' => $airport[6],
            'longitude' => $airport[7],
            'elevation' => $airport[8],
            'timezone_offset' => $airport[9],
        ]);
    }
    
    echo "  ✓ " . count($airports) . " airports added\n";
    
    // Add sample aircrafts
    echo "\nAdding sample aircrafts...\n";
    $aircrafts = [
        ['D-AIBA', 'A320', 'Airbus', 'A320-214', 180, 4, 124, 128, 'active', '2026-06-01', '2025-06-01', 5000.50],
        ['D-AIAB', 'A320', 'Airbus', 'A320-214', 180, 4, 124, 128, 'active', '2026-07-01', '2025-07-01', 5200.75],
        ['D-AIAC', 'A321', 'Airbus', 'A321-211', 200, 4, 158, 162, 'active', '2026-05-15', '2025-05-15', 6100.00],
    ];
    
    foreach ($aircrafts as $aircraft) {
        $db->insert('aircrafts', [
            'registration' => $aircraft[0],
            'type' => $aircraft[1],
            'manufacturer' => $aircraft[2],
            'model' => $aircraft[3],
            'seat_count' => $aircraft[4],
            'capacity_first' => $aircraft[5],
            'capacity_business' => $aircraft[6],
            'capacity_economy' => $aircraft[7],
            'capacity_total' => $aircraft[8],
            'status' => $aircraft[9],
            'next_maintenance' => $aircraft[10],
            'last_maintenance' => $aircraft[11],
            'flight_hours' => $aircraft[12],
        ]);
    }
    
    echo "  ✓ " . count($aircrafts) . " aircrafts added\n";
    
    // Add sample pilots
    echo "\nAdding sample pilots...\n";
    $pilots = [
        ['Thomas Müller', 'DE-12345678', 'captain', 'multi', '2027-12-31', '2027-06-30', 10, 15000.00, 'active', null, null, null],
        ['Hans Schmidt', 'DE-87654321', 'first_officer', 'multi', '2027-12-31', '2027-06-30', 5, 8000.00, 'active', null, null, null],
        ['Peter Weber', 'DE-11223344', 'pilot', 'multi', '2026-12-31', '2026-06-30', 2, 5000.00, 'active', null, null, null],
    ];
    
    foreach ($pilots as $pilot) {
        $db->insert('pilots', [
            'name' => $pilot[0],
            'license_number' => $pilot[1],
            'type' => $pilot[2],
            'rating_type' => $pilot[3],
            'license_valid_until' => $pilot[4],
            'medical_valid_until' => $pilot[5],
            'experience_years' => $pilot[6],
            'flight_hours' => $pilot[7],
            'status' => $pilot[8],
            'home_base' => $pilot[9],
            'email' => $pilot[10],
            'phone' => $pilot[11],
        ]);
    }
    
    echo "  ✓ " . count($pilots) . " pilots added\n";
    
    echo "\n=== Seed Complete ===\n";
    echo "Database has been populated with initial data.\n";
    
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage() . "\n";
    exit(1);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
