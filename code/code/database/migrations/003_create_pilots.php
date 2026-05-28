<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

class CreatePilots extends AbstractMigration
{
    public function change()
    {
        $this->table('pilots', [
            'id' => 'integer',
            'first_name' => 'string(50)',
            'surname' => 'string(50)',
            'callsign' => 'string(8)',
            'license_type' => 'string(50)',
            'license_number' => 'string(50)',
            'email' => 'string(100)',
            'phone' => 'string(20)',
            'rating' => 'string(1)',
            'status' => 'string(10)',
            'hire_date' => 'date',
            'password' => 'string(255)',
            'username' => 'string(50)',
            'reset_token' => 'string(128)',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ])
            ->addIndex(['callsign', 'email'])
            ->create();
        
        // Add pilot history table
        $this->table('pilot_history', [
            'id' => 'integer',
            'pilot_id' => 'integer',
            'flight_number' => 'string(10)',
            'departure_time' => 'datetime',
            'actual_departure' => 'datetime',
            'status' => 'string(20)',
            'notes' => 'text',
            'created_at' => 'datetime',
        ])
            ->addIndex(['pilot_id', 'flight_number'])
            ->create();
        
        $this->logger->info('Pilots and pilot_history tables created');
    }
}
