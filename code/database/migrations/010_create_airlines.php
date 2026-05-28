<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

class CreateAirlines extends AbstractMigration
{
    public function change()
    {
        $this->table('airlines', [
            'id' => 'integer',
            'name' => 'string(200)',
            'iata_code' => 'string(2)',
            'icao_code' => 'string(4)',
            'callsign' => 'string(50)',
            'country' => 'string(100)',
            'logo_url' => 'string(255)',
            'website' => 'string(255)',
            'status' => 'string(20)',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ])
            ->addIndex(['iata_code'])
            ->addIndex(['icao_code'])
            ->create();
        
        // Insert default airline (Lufthansa)
        $this->query("INSERT OR REPLACE INTO airlines (name, iata_code, icao_code, callsign, country, logo_url, website, status) 
                     VALUES ('Lufthansa', 'LH', 'DLH', 'Lufthansa', 'Germany', 'https://example.com/logos/lh.png', 'https://www.lufthansa.com', 'active');");
        
        // Insert admin user
        $this->query("INSERT OR REPLACE INTO admins (username, password, email, role) 
                     VALUES ('admin', 'admin123', 'admin@example.com', 'admin');");
    }
}
