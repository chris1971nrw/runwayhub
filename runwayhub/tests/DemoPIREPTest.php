<?php

namespace App\Tests;

use App\Entity\DemoPIREP;
use PHPUnit\Framework\TestCase;

/**
 * Demo PIREP Test Suite
 */
class DemoPIREPTest extends TestCase
{
    /**
     * Test: Demo PIREP für DMF001 wird erstellt
     */
    public function testDemoPIREPDMF001IsCreated(): void
    {
        $pirep = new DemoPIREP();
        $pirep->setFlightNumber('DMF001');
        $pirep->setAltitude(35000);
        $pirep->setSpeed(450);
        $pirep->setFuel('75%');
        $pirep->setTemperature(-45.0);
        $pirep->setWeatherConditions('Gewitter');
        $pirep->setVisibility('5nm');
        $pirep->setWindSpeed('45kt');
        $pirep->setWindDirection('SW');
        $pirep->setTurbulence('Moderate');
        $pirep->setIcing('None');
        $pirep->setComments('Glatter Flug, schöne Sicht.');
        $pirep->setIsPublic(true);
        $pirep->setStatus('submitted');

        $this->assertEquals('DMF001', $pirep->getFlightNumber());
        $this->assertEquals(35000, $pirep->getAltitude());
        $this->assertEquals(450, $pirep->getSpeed());
        $this->assertEquals('75%', $pirep->getFuel());
        $this->assertEquals(-45.0, $pirep->getTemperature());
        $this->assertEquals('Gewitter', $pirep->getWeatherConditions());
        $this->assertEquals('5nm', $pirep->getVisibility());
        $this->assertEquals('45kt', $pirep->getWindSpeed());
        $this->assertEquals('SW', $pirep->getWindDirection());
        $this->assertEquals('Moderate', $pirep->getTurbulence());
        $this->assertEquals('None', $pirep->getIcing());
        $this->assertEquals('Glatter Flug, schöne Sicht.', $pirep->getComments());
        $this->assertTrue($pirep->getIsPublic());
        $this->assertEquals('submitted', $pirep->getStatus());
    }

    /**
     * Test: Demo PIREP kann verschiedene Status haben
     */
    public function testDemoPIREPStatuses(): void
    {
        $pirep = new DemoPIREP();
        
        // Alle gültigen Status
        $validStatuses = ['submitted', 'reviewed', 'published'];
        
        foreach ($validStatuses as $status) {
            $pirep->setStatus($status);
            $this->assertEquals($status, $pirep->getStatus());
        }
    }

    /**
     * Test: Demo PIREP kann verschiedene Wetterbedingungen haben
     */
    public function testDemoPIREPWeatherConditions(): void
    {
        $pirep = new DemoPIREP();
        
        $validWeathers = ['Clear', 'Cloudy', 'Rain', 'Snow', 'Fog', 'Gewitter'];
        
        foreach ($validWeathers as $weather) {
            $pirep->setWeatherConditions($weather);
            $this->assertEquals($weather, $pirep->getWeatherConditions());
        }
    }

    /**
     * Test: Demo PIREP kann nicht öffentlich sein
     */
    public function testDemoPIREPIsNotPublic(): void
    {
        $pirep = new DemoPIREP();
        $pirep->setIsPublic(false);

        $this->assertFalse($pirep->getIsPublic());
    }
}
