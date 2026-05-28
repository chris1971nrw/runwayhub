<?php

declare(strict_types=1);

/**
 * VA Controller Test
 * Tests Virtual Airline controller functionality
 */

use runwayhub\Controllers\VAController;

require_once __DIR__ . '/../bootstrap.php';

class VAControllerTest extends PHPUnit\Framework\TestCase
{
    protected VAController $controller;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new VAController();
    }
    
    public function testCreateVAVoid(): void
    {
        $result = $this->controller->createVA(null);
        
        $this->assertArrayHasKey('error', $result);
    }
    
    public function testCreateVAMissingFields(): void
    {
        $result = $this->controller->createVA([]);
        
        $this->assertArrayHasKey('error', $result);
    }
    
    public function testCreateVAMissingName(): void
    {
        $result = $this->controller->createVA(['iata' => 'XX']);
        
        $this->assertArrayHasKey('error', $result);
    }
    
    public function testCreateVAMissingIata(): void
    {
        $result = $this->controller->createVA(['name' => 'Test Airline']);
        
        $this->assertArrayHasKey('error', $result);
    }
    
    public function testGetVAAcknowledge(): void
    {
        $vaId = 'LH';
        $result = $this->controller->getVA($vaId);
        
        $this->assertIsArray($result);
    }
    
    public function testGetVAMissingId(): void
    {
        $result = $this->controller->getVA(null);
        
        $this->assertArrayHasKey('error', $result);
    }
    
    public function testConnectVA(): void
    {
        $vaId = 'LH';
        $result = $this->controller->connectVA($vaId);
        
        $this->assertIsArray($result);
    }
    
    public function testConnectVAMissingId(): void
    {
        $result = $this->controller->connectVA(null);
        
        $this->assertArrayHasKey('error', $result);
    }
    
    public function testConnectVAMissingFields(): void
    {
        $result = $this->controller->connectVA([]);
        
        $this->assertArrayHasKey('error', $result);
    }
}
