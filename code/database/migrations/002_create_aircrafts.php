<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

class CreateAircrafts extends AbstractMigration
{
    public function change()
    {
        $this->table('aircrafts', [
            'id' => 'integer',
            'registration' => 'string(8)',
            'type' => 'string(50)',
            'manufacturer' => 'string(50)',
            'model' => 'string(50)',
            'capacity' => 'integer',
            'range' => 'integer',
            'max_altitude' => 'integer',
            'max_speed' => 'integer',
            'status' => 'string(20)',
            'purchase_date' => 'date',
            'next_maintenance' => 'date',
            'last_maintenance' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ])
            ->addIndex(['registration'])
            ->create();
        
        // Add maintenance table
        $this->table('maintenance', [
            'id' => 'integer',
            'aircraft_registration' => 'string(8)',
            'date' => 'date',
            'type' => 'string(50)',
            'description' => 'text',
            'cost' => 'decimal(10,2)',
            'created_at' => 'datetime',
        ])
            ->addIndex(['aircraft_registration'])
            ->create();
        
        $this->logger->info('Aircrafts and maintenance tables created');
    }
}
