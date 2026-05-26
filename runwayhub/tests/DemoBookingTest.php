<?php

namespace App\Tests;

use App\Entity\DemoBooking;
use PHPUnit\Framework\TestCase;

/**
 * Demo Booking Test Suite
 */
class DemoBookingTest extends TestCase
{
    /**
     * Test: Demo Buchung DM001 wird erstellt
     */
    public function testDemoBookingDM001IsCreated(): void
    {
        $booking = new DemoBooking();
        $booking->setBookingNumber('DM001');
        $booking->setPassengerName('Max Mustermann');
        $booking->setPassengerEmail('max@example.com');
        $booking->setClass('economy');
        $booking->setPrice(299.00);
        $booking->setTax(15.00);
        $booking->setTotal(314.00);
        $booking->setPaymentMethod('credit_card');
        $booking->setPaymentStatus('paid');
        $booking->setStatus('confirmed');

        $this->assertEquals('DM001', $booking->getBookingNumber());
        $this->assertEquals('Max Mustermann', $booking->getPassengerName());
        $this->assertEquals('max@example.com', $booking->getPassengerEmail());
        $this->assertEquals('economy', $booking->getClass());
        $this->assertEquals(299.00, $booking->getPrice());
        $this->assertEquals(15.00, $booking->getTax());
        $this->assertEquals(314.00, $booking->getTotal());
        $this->assertEquals('credit_card', $booking->getPaymentMethod());
        $this->assertEquals('paid', $booking->getPaymentStatus());
        $this->assertEquals('confirmed', $booking->getStatus());
    }

    /**
     * Test: Demo Buchung DM002 wird erstellt
     */
    public function testDemoBookingDM002IsCreated(): void
    {
        $booking = new DemoBooking();
        $booking->setBookingNumber('DM002');
        $booking->setPassengerName('Erika Musterfrau');
        $booking->setPassengerEmail('erika@example.com');
        $booking->setClass('economy');
        $booking->setPrice(299.00);
        $booking->setTax(15.00);
        $booking->setTotal(314.00);
        $booking->setPaymentMethod('credit_card');
        $booking->setPaymentStatus('paid');
        $booking->setStatus('confirmed');

        $this->assertEquals('DM002', $booking->getBookingNumber());
        $this->assertEquals('Erika Musterfrau', $booking->getPassengerName());
        $this->assertEquals('erika@example.com', $booking->getPassengerEmail());
    }

    /**
     * Test: Demo Buchung DM003 wird erstellt
     */
    public function testDemoBookingDM003IsCreated(): void
    {
        $booking = new DemoBooking();
        $booking->setBookingNumber('DM003');
        $booking->setPassengerName('Hans Beispielmann');
        $booking->setPassengerEmail('hans@example.com');
        $booking->setClass('economy');
        $booking->setPrice(299.00);
        $booking->setTax(15.00);
        $booking->setTotal(314.00);
        $booking->setPaymentMethod('paypal');
        $booking->setPaymentStatus('paid');
        $booking->setStatus('confirmed');

        $this->assertEquals('DM003', $booking->getBookingNumber());
        $this->assertEquals('Hans Beispielmann', $booking->getPassengerName());
        $this->assertEquals('paypal', $booking->getPaymentMethod());
    }

    /**
     * Test: Demo Buchung kann verschiedene Klassen haben
     */
    public function testDemoBookingClasses(): void
    {
        $booking = new DemoBooking();
        
        $validClasses = ['economy', 'business', 'first'];
        
        foreach ($validClasses as $class) {
            $booking->setClass($class);
            $this->assertEquals($class, $booking->getClass());
        }
    }

    /**
     * Test: Demo Buchung kann verschiedene Status haben
     */
    public function testDemoBookingStatuses(): void
    {
        $booking = new DemoBooking();
        
        // Alle gültigen Status
        $validStatuses = ['pending', 'confirmed', 'checked_in', 'aboard', 'cancelled'];
        
        foreach ($validStatuses as $status) {
            $booking->setStatus($status);
            $this->assertEquals($status, $booking->getStatus());
        }
    }

    /**
     * Test: Demo Buchung kann verschiedene Zahlungsmethoden haben
     */
    public function testDemoBookingPaymentMethods(): void
    {
        $booking = new DemoBooking();
        
        $validMethods = ['credit_card', 'paypal', 'bank_transfer', 'cash'];
        
        foreach ($validMethods as $method) {
            $booking->setPaymentMethod($method);
            $this->assertEquals($method, $booking->getPaymentMethod());
        }
    }

    /**
     * Test: Demo Buchung kann verschiedene Zahlungsrufen haben
     */
    public function testDemoBookingPaymentStatuses(): void
    {
        $booking = new DemoBooking();
        
        $validStatuses = ['pending', 'paid', 'refunded'];
        
        foreach ($validStatuses as $status) {
            $booking->setPaymentStatus($status);
            $this->assertEquals($status, $booking->getPaymentStatus());
        }
    }
}
