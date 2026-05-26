<?php

namespace App\Tests;

use App\Entity\DemoFlight;
use PHPUnit\Framework\TestCase;

/**
 * Demo Flight Test Suite
 */
class DemoFlightTest extends TestCase
{
    /**
     * Test: Demo Flug DMF001 wird erstellt
     */
    public function testDemoFlightDMF001IsCreated(): void
    {
        $flight = new DemoFlight();
        $flight->setFlightNumber('DMF001');
        $flight->setAirportFrom('MUC');
        $flight->setAirportTo('FRA');
        $flight->setRoute('München-Frankfurt');
        $flight->setDistance(530);
        $flight->setDuration(75);
        $flight->setScheduledDate(new \DateTime('2026-06-02'));
        $flight->setScheduledTime('08:00:00');
        $flight->setStatus('scheduled');
        $flight->setPriceEconomy(299.00);
        $flight->setPriceBusiness(599.00);
        $flight->setPriceFirst(1299.00);
        $flight->setAvailableSeats(150);
        $flight->setSoldSeats(0);

        $this->assertEquals('DMF001', $flight->getFlightNumber());
        $this->assertEquals('MUC', $flight->getAirportFrom());
        $this->assertEquals('FRA', $flight->getAirportTo());
        $this->assertEquals('München-Frankfurt', $flight->getRoute());
        $this->assertEquals(530, $flight->getDistance());
        $this->assertEquals(75, $flight->getDuration());
        $this->assertEquals('scheduled', $flight->getStatus());
        $this->assertEquals(299.00, $flight->getPriceEconomy());
        $this->assertEquals(599.00, $flight->getPriceBusiness());
        $this->assertEquals(1299.00, $flight->getPriceFirst());
        $this->assertEquals(150, $flight->getAvailableSeats());
        $this->assertEquals(0, $flight->getSoldSeats());
    }

    /**
     * Test: Demo Flug kann verschiedene Status haben
     */
    public function testDemoFlightStatuses(): void
    {
        $flight = new DemoFlight();
        
        // Alle gültigen Status
        $validStatuses = ['scheduled', 'departed', 'arrived', 'cancelled', 'delayed'];
        
        foreach ($validStatuses as $status) {
            $flight->setStatus($status);
            $this->assertEquals($status, $flight->getStatus());
        }
    }

    /**
     * Test: Demo Flug kann verschiedene Preise haben
     */
    public function testDemoFlightPricing(): void
    {
        $flight = new DemoFlight();
        
        $flight->setPriceEconomy(299.00);
        $flight->setPriceBusiness(599.00);
        $flight->setPriceFirst(1299.00);
        
        $this->assertEquals(299.00, $flight->getPriceEconomy());
        $this->assertEquals(599.00, $flight->getPriceBusiness());
        $this->assertEquals(1299.00, $flight->getPriceFirst());
    }

    /**
     * Test: Demo Flug kann verschiedene Klassen haben
     */
    public function testDemoFlightClasses(): void
    {
        $flight = new DemoFlight();
        
        $validClasses = ['economy', 'business', 'first'];
        
        foreach ($validClasses as $class) {
            $flight->setPriceEconomy(299.00);
            $this->assertEquals(299.00, $flight->getPriceEconomy());
        }
    }
}
