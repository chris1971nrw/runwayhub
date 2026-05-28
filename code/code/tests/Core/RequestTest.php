<?php

declare(strict_types=1);

namespace RunwayHub\Tests\Core;

use PHPUnit\Framework\TestCase;
use RunwayHub\Core\Request;

class RequestTest extends TestCase
{
    public function testGetMethod(): void
    {
        $request = new Request();
        
        // Mock $_SERVER['REQUEST_METHOD']
        $_SERVER['REQUEST_METHOD'] = 'GET';
        
        $this->assertEquals('GET', $request->getMethod());
    }

    public function testGetPath(): void
    {
        $request = new Request();
        
        $_SERVER['REQUEST_URI'] = '/dashboard';
        
        $this->assertEquals('/dashboard', $request->getPath());
    }

    public function testGetHeader(): void
    {
        $request = new Request();
        
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0';
        
        $this->assertEquals('Mozilla/5.0', $request->getHeader('User-Agent'));
    }

    public function testGetCookie(): void
    {
        $request = new Request();
        
        $_COOKIE['session_id'] = 'abc123';
        
        $this->assertEquals('abc123', $request->getCookie('session_id'));
    }

    public function testGetServer(): void
    {
        $request = new Request();
        
        $this->assertEquals('localhost', $request->getServer('HTTP_HOST'));
    }

    public function testGetContent(): void
    {
        $request = new Request();
        
        $_POST['name'] = 'test';
        
        $this->assertEquals(['name' => 'test'], $request->getContent());
    }

    public function testIsAjax(): void
    {
        $request = new Request();
        
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        
        $this->assertTrue($request->isAjax());
    }

    public function testIsMobile(): void
    {
        $request = new Request();
        
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X)';
        
        $this->assertTrue($request->isMobile());
    }

    public function testIsValid(): void
    {
        $request = new Request();
        
        $this->assertTrue($request->isValid());
    }
}
