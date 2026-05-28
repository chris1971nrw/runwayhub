<?php

declare(strict_types=1);

/**
 * WeatherController Test
 * Tests the weather service integration
 */

use runwayhub\Controllers\WeatherController;

require_once __DIR__ . '/../bootstrap.php';

class WeatherControllerTest extends PHPUnit\Framework\TestCase
{
    protected WeatherController $controller;
    protected array $mocks;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new WeatherController();
        $this->mocks = new class() {
            public function getWeather($airport, $type = 'metar'): array
            {
                if ($airport === 'EDDF') {
                    if ($type === 'metar') {
                        return [
                            'station' => 'EDDF',
                            'observation' => 'METAR EDDF 280800Z 27008KT 9999 FEW023 SCT025 19/11 Q1018 NOSIG',
                            'time' => '2026-05-28 08:00:00',
                            'wind' => '270 8',
                            'visibility' => '9999',
                            'weather' => '',
                            'temperature' => 19,
                            'dewpoint' => 11,
                            'altimeter' => '1018'
                        ];
                    }
                }
                return ['error' => 'Weather data not available'];
            }
        };
    }
    
    public function testGetWeatherMETAR(): void
    {
        $airport = 'EDDF';
        $result = $this->controller->getWeather($airport, 'metar');
        
        $this->assertIsArray($result);
        $this->assertEquals('EDDF', $result['station']);
        $this->assertEquals('METAR EDDF 280800Z 27008KT 9999 FEW023 SCT025 19/11 Q1018 NOSIG', $result['observation']);
    }
    
    public function testGetWeatherTAF(): void
    {
        $airport = 'EDDF';
        $result = $this->controller->getWeather($airport, 'taf');
        
        $this->assertIsArray($result);
        $this->assertEquals('TAF', $result['type'] ?? null);
    }
    
    public function testGetWeatherInvalidAirport(): void
    {
        $airport = 'XXX';
        $result = $this->controller->getWeather($airport);
        
        $this->assertContains('error', $result);
    }
    
    public function testGetWeatherEmptyAirport(): void
    {
        $airport = '';
        $result = $this->controller->getWeather($airport);
        
        $this->assertContains('error', $result);
    }
    
    public function testGetWeatherNullAirport(): void
    {
        $airport = null;
        $result = $this->controller->getWeather($airport);
        
        $this->assertContains('error', $result);
    }
}
