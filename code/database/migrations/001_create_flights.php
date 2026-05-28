<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

class CreateFlights extends AbstractMigration
{
    public function change()
    {
        // Create flights table
        $this->table('flights', [
            'id' => 'integer',
            'flight_number' => 'string(10)',
            'callsign' => 'string(6)',
            'origin' => 'string(4)',
            'destination' => 'string(4)',
            'departure_time' => 'datetime',
            'arrival_time' => 'datetime',
            'status' => 'string(20)',
            'aircraft_registration' => 'string(8)',
            'pilot_id' => 'integer',
            'gate' => 'string(4)',
            'terminal' => 'string(1)',
            'baggage' => 'string(2)',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ])
            ->addIndex(['flight_number'])
            ->create();
        
        // Add flight history table
        $this->table('flight_history', [
            'id' => 'integer',
            'flight_number' => 'string(10)',
            'origin' => 'string(4)',
            'destination' => 'string(4)',
            'departure_time' => 'datetime',
            'actual_departure' => 'datetime',
            'status' => 'string(20)',
            'created_at' => 'datetime',
        ])
            ->addIndex(['flight_number'])
            ->create();
        
        $this->logger->info('Flights table created');
    }
}
