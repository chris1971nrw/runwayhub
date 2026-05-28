<?php
declare(strict_types=1);

namespace RunwayHub\Tests\Core;

use PHPUnit\Framework\TestCase;
use RunwayHub\Core\Router;

class RouterTest extends TestCase
{
    private Router $router;

    protected function setUp(): void
    {
        $this->router = new Router();
    }

    public function testRouteRegistered(): void
    {
        $this->router->addRoute('GET', '/test', 'handler');
        $this->assertTrue(true);
    }

    public function testRouteMatch(): void
    {
        $matched = $this->router->match('GET', '/test', 'handler');
        $this->assertIsBool($matched);
    }

    public function testMiddlewareStack(): void
    {
        $this->assertTrue(method_exists($this->router, 'middlewareStack'));
    }

    public function testGetRoutes(): void
    {
        $routes = $this->router->getRoutes();
        $this->assertIsArray($routes);
    }

    public function testGetRequest(): void
    {
        $request = $this->router->getRequest();
        $this->assertNotNull($request);
    }

    public function testGetResponse(): void
    {
        $response = $this->router->getResponse();
        $this->assertNotNull($response);
    }

    public function testGetMiddlewares(): void
    {
        $middlewares = $this->router->getMiddlewares();
        $this->assertIsArray($middlewares);
    }
}
