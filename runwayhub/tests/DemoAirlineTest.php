<?php

namespace App\Tests;

use App\Entity\DemoAirline;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

/**
 * Demo Airline Test Suite
 */
class DemoAirlineTest extends TestCase
{
    private EntityManagerInterface $em;

    protected function setUp(): void
    {
        // Hier würde man eine echte Datenbank-Verbindung konfigurieren
        // Für Demo-Zwecke können wir Mocks verwenden
    }

    protected function tearDown(): void
    {
        $this->em = null;
    }

    /**
     * Test: Demo Airline wird korrekt erstellt
     */
    public function testDemoAirlineIsCreated(): void
    {
        $airline = new DemoAirline();
        $airline->setName('DemoFly');
        $airline->setCode('DMO');
        $airline->setIata('DM');
        $airline->setIcao('DMFLY');
        $airline->setCountry('DE');
        $airline->setColor('#0066cc');
        $airline->setIsActive(true);

        // Assertion
        $this->assertEquals('DemoFly', $airline->getName());
        $this->assertEquals('DMO', $airline->getCode());
        $this->assertEquals('DM', $airline->getIata());
        $this->assertEquals('DMFLY', $airline->getIcao());
        $this->assertEquals('DE', $airline->getCountry());
        $this->assertEquals('#0066cc', $airline->getColor());
        $this->assertTrue($airline->getIsActive());
    }

    /**
     * Test: Demo Airline Felder sind gesetzt
     */
    public function testDemoAirlineFieldsAreSet(): void
    {
        $airline = new DemoAirline();
        $airline->setName('DemoFly');
        $airline->setCode('DMO');
        $airline->setIata('DM');
        $airline->setIcao('DMFLY');

        // Assertion
        $this->assertNotNull($airline->getName());
        $this->assertNotNull($airline->getCode());
        $this->assertNotNull($airline->getIata());
        $this->assertNotNull($airline->getIcao());
    }

    /**
     * Test: Demo Airline kann aktiv/deaktiviert werden
     */
    public function testDemoAirlineIsActiveToggle(): void
    {
        $airline = new DemoAirline();
        
        // Standardmäßig aktiv
        $this->assertTrue($airline->getIsActive());
        
        // Deaktivieren
        $airline->setIsActive(false);
        $this->assertFalse($airline->getIsActive());
        
        // Aktivieren
        $airline->setIsActive(true);
        $this->assertTrue($airline->getIsActive());
    }

    /**
     * Test: Demo Airline kann ein Logo haben
     */
    public function testDemoAirlineHasLogo(): void
    {
        $airline = new DemoAirline();
        $airline->setLogo('demo_logo.png');

        $this->assertEquals('demo_logo.png', $airline->getLogo());
    }

    /**
     * Test: Demo Airline kann ein Rufzeichen haben
     */
    public function testDemoAirlineHasCallsign(): void
    {
        $airline = new DemoAirline();
        $airline->setCallsign('DEMOFLY');

        $this->assertEquals('DEMOFLY', $airline->getCallsign());
    }

    /**
     * Test: Demo Airline kann ein Land-Code haben
     */
    public function testDemoAirlineHasCountry(): void
    {
        $airline = new DemoAirline();
        
        // Alle gültigen Länder-Codes
        $validCountries = ['DE', 'US', 'UK', 'FR', 'IT'];
        
        foreach ($validCountries as $country) {
            $airline->setCountry($country);
            $this->assertEquals($country, $airline->getCountry());
        }
    }
}
