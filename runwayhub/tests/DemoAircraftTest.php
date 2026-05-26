<?php

namespace App\Tests;

use App\Entity\DemoAircraft;
use PHPUnit\Framework\TestCase;

/**
 * Demo Aircraft Test Suite
 */
class DemoAircraftTest extends TestCase
{
    /**
     * Test: Demo Boeing 737-800 wird erstellt
     */
    public function testDemoBoeing737IsCreated(): void
    {
        $boeing = new DemoAircraft();
        $boeing->setType('Boeing');
        $boeing->setModel('737-800');
        $boeing->setRegistration('DM-FAZ');
        $boeing->setManufacturer('Boeing');
        $boeing->setYear(2023);
        $boeing->setSeats(160);
        $boeing->setEngine('CFM56-7B');
        $boeing->setFuel('Jet A');
        $boeing->setRange(3115);
        $boeing->setMaxAltitude(41000);
        $boeing->setStatus('active');

        $this->assertEquals('Boeing', $boeing->getType());
        $this->assertEquals('737-800', $boeing->getModel());
        $this->assertEquals('DM-FAZ', $boeing->getRegistration());
        $this->assertEquals('Boeing', $boeing->getManufacturer());
        $this->assertEquals(2023, $boeing->getYear());
        $this->assertEquals(160, $boeing->getSeats());
        $this->assertEquals('CFM56-7B', $boeing->getEngine());
        $this->assertEquals('active', $boeing->getStatus());
    }

    /**
     * Test: Demo Airbus A320-200 wird erstellt
     */
    public function testDemoAirbusA320IsCreated(): void
    {
        $airbus = new DemoAircraft();
        $airbus->setType('Airbus');
        $airbus->setModel('A320-200');
        $airbus->setRegistration('DM-FBZ');
        $airbus->setManufacturer('Airbus');
        $airbus->setYear(2022);
        $airbus->setSeats(150);
        $airbus->setEngine('V2500');
        $airbus->setFuel('Jet A');
        $airbus->setRange(3100);
        $airbus->setMaxAltitude(39000);
        $airbus->setStatus('active');

        $this->assertEquals('Airbus', $airbus->getType());
        $this->assertEquals('A320-200', $airbus->getModel());
        $this->assertEquals('DM-FBZ', $airbus->getRegistration());
        $this->assertEquals('Airbus', $airbus->getManufacturer());
        $this->assertEquals(2022, $airbus->getYear());
        $this->assertEquals(150, $airbus->getSeats());
        $this->assertEquals('active', $airbus->getStatus());
    }

    /**
     * Test: Demo Cessna 172 wird erstellt
     */
    public function testDemoCessna172IsCreated(): void
    {
        $cessna = new DemoAircraft();
        $cessna->setType('Cessna');
        $cessna->setModel('172');
        $cessna->setRegistration('DM-C172');
        $cessna->setManufacturer('Cessna');
        $cessna->setYear(2024);
        $cessna->setSeats(4);
        $cessna->setEngine('Lycoming IO-360');
        $cessna->setFuel('AvGas');
        $cessna->setRange(764);
        $cessna->setMaxAltitude(10000);
        $cessna->setStatus('active');

        $this->assertEquals('Cessna', $cessna->getType());
        $this->assertEquals('172', $cessna->getModel());
        $this->assertEquals('DM-C172', $cessna->getRegistration());
        $this->assertEquals('Cessna', $cessna->getManufacturer());
        $this->assertEquals(2024, $cessna->getYear());
        $this->assertEquals(4, $cessna->getSeats());
        $this->assertEquals('active', $cessna->getStatus());
    }

    /**
     * Test: Demo Aircraft kann im Maintenance sein
     */
    public function testDemoAircraftStatusIsMaintenance(): void
    {
        $aircraft = new DemoAircraft();
        $aircraft->setStatus('maintenance');

        $this->assertEquals('maintenance', $aircraft->getStatus());
    }

    /**
     * Test: Demo Aircraft kann reserviert sein
     */
    public function testDemoAircraftStatusIsReserved(): void
    {
        $aircraft = new DemoAircraft();
        $aircraft->setStatus('reserved');

        $this->assertEquals('reserved', $aircraft->getStatus());
    }

    /**
     * Test: Demo Aircraft kann Maintenance-Intervall haben
     */
    public function testDemoAircraftHasMaintenanceInterval(): void
    {
        $aircraft = new DemoAircraft();
        $aircraft->setMaintenanceInterval(500);
        $aircraft->setLastMaintenance(new \DateTime('2026-01-01'));

        $this->assertEquals(500, $aircraft->getMaintenanceInterval());
    }
}
