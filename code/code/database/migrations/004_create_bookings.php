<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

class CreateBookings extends AbstractMigration
{
    public function change()
    {
        $this->table('bookings', [
            'id' => 'integer',
            'flight_number' => 'string(10)',
            'passenger_email' => 'string(100)',
            'passenger_name' => 'string(50)',
            'passenger_seats' => 'string(50)',
            'passenger_phone' => 'string(20)',
            'booking_reference' => 'string(20)',
            'status' => 'string(20)',
            'total_price' => 'decimal(10,2)',
            'payment_method' => 'string(50)',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ])
            ->addIndex(['flight_number', 'booking_reference'])
            ->create();
        
        // Add passengers table
        $this->table('passengers', [
            'id' => 'integer',
            'booking_id' => 'integer',
            'first_name' => 'string(50)',
            'surname' => 'string(50)',
            'email' => 'string(100)',
            'phone' => 'string(20)',
            'seat' => 'string(4)',
            'seat_class' => 'string(10)',
            'checked_bags' => 'integer',
            'created_at' => 'datetime',
        ])
            ->addIndex(['booking_id', 'seat'])
            ->create();
        
        // Add seats table
        $this->table('seats', [
            'id' => 'integer',
            'flight_number' => 'string(10)',
            'seat_number' => 'string(4)',
            'row' => 'integer',
            'seat_class' => 'string(10)',
            'status' => 'string(20)',
            'price' => 'decimal(8,2)',
            'created_at' => 'datetime',
        ])
            ->addIndex(['flight_number', 'seat_number'])
            ->create();
        
        $this->logger->info('Bookings, passengers, and seats tables created');
    }
}
