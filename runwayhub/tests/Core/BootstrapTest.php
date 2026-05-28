<?php
declare(strict_types=1);

namespace RunwayHub\Tests\Core;

use PHPUnit\Framework\TestCase;
use RunwayHub\Core\Bootstrap;

class BootstrapTest extends TestCase
{
    private Bootstrap $bootstrap;

    protected function setUp(): void
    {
        $this->bootstrap = new Bootstrap('/nonexistent-config');
    }

    public function testHandleReturnsResponse(): void
    {
        $response = $this->bootstrap->handle('mock-request');
        $this->assertNotNull($response);
    }

    public function testGetRequest(): void
    {
        $request = $this->bootstrap->getRequest();
        $this->assertNotNull($request);
    }

    public function testSetMiddleware(): void
    {
        $this->assertTrue(method_exists($this->bootstrap, 'setMiddleware'));
    }
}
