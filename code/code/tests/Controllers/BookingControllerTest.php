<?php

declare(strict_types=1);

/**
 * BookingController Test
 * Tests the booking management functionality
 */

use runwayhub\Controllers\BookingController;

require_once __DIR__ . '/../bootstrap.php';

class BookingControllerTest extends PHPUnit\Framework\TestCase
{
    protected BookingController $controller;
    protected array $mocks;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new BookingController();
        $this->mocks = new class() {
            public function validateBooking($data): bool {
                return isset($data['flight_id']) && isset($data['passenger_name']);
            }
            
            public function createBooking($booking): bool {
                return true;
            }
            
            public function getBooking($id): ?array {
                return ['id' => $id, 'status' => 'active'];
            }
            
            public function deleteBooking($id): bool {
                return true;
            }
        };
    }
    
    public function testValidateBookingValid(): void
    {
        $data = [
            'flight_id' => 'LH400',
            'passenger_name' => 'John Doe',
            'seat' => '12A'
        ];
        
        $result = $this->controller->validateBooking($data);
        
        $this->assertTrue($result);
    }
    
    public function testValidateBookingInvalid(): void
    {
        $data = [
            'passenger_name' => 'John Doe'
        ];
        
        $result = $this->controller->validateBooking($data);
        
        $this->assertFalse($result);
    }
    
    public function testCreateBooking(): void
    {
        $bookingData = [
            'flight_id' => 'LH400',
            'passenger_name' => 'Jane Smith',
            'seat' => '12B'
        ];
        
        $result = $this->controller->createBooking($bookingData);
        
        $this->assertTrue($result);
    }
    
    public function testGetBooking(): void
    {
        $bookingId = 12345;
        $result = $this->controller->getBooking($bookingId);
        
        $this->assertIsArray($result);
        $this->assertEquals($bookingId, $result['id']);
    }
    
    public function testGetBookingNotFound(): void
    {
        $bookingId = 99999;
        $result = $this->controller->getBooking($bookingId);
        
        $this->assertNull($result);
    }
    
    public function testDeleteBooking(): void
    {
        $bookingId = 12345;
        $result = $this->controller->deleteBooking($bookingId);
        
        $this->assertTrue($result);
    }
    
    public function testDeleteBookingNotFound(): void
    {
        $bookingId = 99999;
        $result = $this->controller->deleteBooking($bookingId);
        
        $this->assertFalse($result);
    }
}
