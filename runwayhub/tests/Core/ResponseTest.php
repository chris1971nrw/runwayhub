<?php

declare(strict_types=1);

namespace RunwayHub\Tests\Core;

use PHPUnit\Framework\TestCase;
use RunwayHub\Core\Response;

class ResponseTest extends TestCase
{
    public function testContentType(): void
    {
        $response = new Response();
        
        $response->contentType('application/json');
        
        $this->assertEquals('application/json', $response->getContentType());
    }

    public function testContent(): void
    {
        $response = new Response();
        
        $content = 'Hello World';
        $response->content($content);
        
        $this->assertEquals($content, $response->getContent());
    }

    public function testHeader(): void
    {
        $response = new Response();
        
        $response->header('X-Custom-Header', 'test-value');
        
        $this->assertEquals(['X-Custom-Header' => 'test-value'], $response->getHeaders());
    }

    public function testGetFullHeaders(): void
    {
        $response = new Response();
        
        $response->header('Content-Type', 'text/html');
        $response->header('X-Custom', 'value');
        
        $headers = $response->getFullHeaders();
        
        $this->assertArrayHasKey('Content-Type', $headers);
        $this->assertArrayHasKey('X-Custom', $headers);
    }

    public function testStatus(): void
    {
        $response = new Response();
        
        $response->status(404);
        
        $this->assertEquals(404, $response->getStatus());
        $this->assertEquals('Not Found', $response->getStatusText());
    }

    public function testCookie(): void
    {
        $response = new Response();
        
        $response->cookie('session', 'abc123', time() + 3600, true);
        
        $this->assertCount(1, $response->cookies);
    }

    public function testRedirect(): void
    {
        $response = new Response();
        
        $response->redirect('https://example.com');
        
        $this->assertEquals('https://example.com', $response->getHeader('Location'));
    }

    public function testSend(): void
    {
        $response = new Response();
        
        $content = '<html><body>Test</body></html>';
        $response->contentType('text/html')->content($content)->send();
        
        $this->assertTrue($response->sent);
    }

    public function testSent(): void
    {
        $response = new Response();
        
        $this->assertFalse($response->sent);
        
        $response->send();
        
        $this->assertTrue($response->sent);
    }
}
