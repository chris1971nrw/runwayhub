<?php

declare(strict_types=1);

namespace RunwayHub\Tests\Core;

use PHPUnit\Framework\TestCase;
use RunwayHub\Core\Route;

class RouteTest extends TestCase
{
    public function testConstructor(): void
    {
        $route = new Route(['GET', 'HEAD'], '/test', 'HomeController@index');
        
        $this->assertEquals(['GET', 'HEAD'], $route->methods);
        $this->assertEquals('/test', $route->path);
        $this->assertEquals('HomeController@index', $route->handler);
        $this->assertEquals([], $route->options);
    }

    public function testName(): void
    {
        $route = new Route(['GET'], '/home', 'HomeController@index');
        $route->name('home');
        
        $this->assertEquals('home', $route->name);
    }

    public function testAddMiddleware(): void
    {
        $route = new Route(['GET'], '/admin', 'AdminController@dashboard');
        $route->middleware(\RunwayHub\Core\Middleware\Auth::class);
        
        $this->assertCount(1, $route->middleware);
        $this->assertEquals(\RunwayHub\Core\Middleware\Auth::class, $route->middleware[0]);
    }

    public function testMethodAllowed(): void
    {
        $route = new Route(['GET', 'HEAD'], '/test', 'HomeController@index');
        
        $this->assertTrue($route->methodAllowed('GET'));
        $this->assertTrue($route->methodAllowed('HEAD'));
        $this->assertFalse($route->methodAllowed('POST'));
    }

    public function testGetPathPattern(): void
    {
        $route = new Route(['GET'], '/{id}', 'AircraftController@show');
        $pattern = $route->getPathPattern();
        
        $this->assertEquals('/(?P<id>[^/]+)', $pattern);
    }

    public function testGetRouteInfo(): void
    {
        $route = new Route(['GET', 'POST'], '/test', 'HomeController@index', ['middleware' => 'test']);
        $route->name('test');
        $route->middleware(\RunwayHub\Core\Middleware\Auth::class);
        
        $info = $route->getRouteInfo();
        
        $this->assertEquals(['GET', 'POST'], $info['methods']);
        $this->assertEquals('/test', $info['path']);
        $this->assertEquals('HomeController@index', $info['handler']);
        $this->assertEquals(['middleware' => 'test'], $info['options']);
        $this->assertEquals('test', $info['name']);
        $this->assertCount(1, $info['middleware']);
    }
}
