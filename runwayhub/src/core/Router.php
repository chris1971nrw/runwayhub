<?php

declare(strict_types=1);

namespace RunwayHub\Core;

class Router
{
    /** @var Request */
    private Request $request;

    /** @var Response */
    private Response $response;

    /** @var array */
    private array $routes = [];

    /** @var array */
    private array $middlewares = [];

    /**
     * Router constructor
     */
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * Get route
     *
     * @param string $path URL path
     * @param string|array $handler Controller or controller@method
     * @param array $options Route options
     * @return Route
     */
    public function get(string $path, $handler, array $options = []): Route
    {
        return $this->addRoute(['GET', 'HEAD'], $path, $handler, $options);
    }

    /**
     * Post route
     *
     * @param string $path URL path
     * @param string|array $handler Controller or controller@method
     * @param array $options Route options
     * @return Route
     */
    public function post(string $path, $handler, array $options = []): Route
    {
        return $this->addRoute(['POST'], $path, $handler, $options);
    }

    /**
     * Put route
     *
     * @param string $path URL path
     * @param string|array $handler Controller or controller@method
     * @param array $options Route options
     * @return Route
     */
    public function put(string $path, $handler, array $options = []): Route
    {
        return $this->addRoute(['PUT'], $path, $handler, $options);
    }

    /**
     * Patch route
     *
     * @param string $path URL path
     * @param string|array $handler Controller or controller@method
     * @param array $options Route options
     * @return Route
     */
    public function patch(string $path, $handler, array $options = []): Route
    {
        return $this->addRoute(['PATCH'], $path, $handler, $options);
    }

    /**
     * Delete route
     *
     * @param string $path URL path
     * @param string|array $handler Controller or controller@method
     * @param array $options Route options
     * @return Route
     */
    public function delete(string $path, $handler, array $options = []): Route
    {
        return $this->addRoute(['DELETE'], $path, $handler, $options);
    }

    /**
     * Add route
     *
     * @param array $methods HTTP methods
     * @param string $path URL path
     * @param string|array $handler Controller or controller@method
     * @param array $options Route options
     * @return Route
     */
    private function addRoute(array $methods, string $path, $handler, array $options = []): Route
    {
        $route = new Route(
            $methods,
            $path,
            $handler,
            $options
        );

        $this->routes[$path][] = $route;

        return $route;
    }

    /**
     * Route request
     *
     * @return Response
     */
    public function dispatch(): Response
    {
        $method = $this->request->getMethod();
        $path = $this->request->getPath();

        // Parse path
        $pattern = $this->getPattern($path);
        $params = $this->getParams($path, $pattern);

        // Find matching route
        foreach ($this->routes as $routePath => $routes) {
            if ($this->match($pattern, $routePath)) {
                $route = $routes[0];

                // Check method
                if (in_array($method, $route->methods)) {
                    $this->handleRoute($route, $params);
                    return $this->response;
                }
            }
        }

        // No route found
        $this->response->status(404);
        $this->response->contentType('application/json');
        $this->response->content(json_encode([
            'error' => true,
            'message' => 'Not Found',
        ]));
        $this->response->send();

        return $this->response;
    }

    /**
     * Get route pattern
     */
    private function getPattern(string $path): string
    {
        return preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $path);
    }

    /**
     * Get params from path
     */
    private function getParams(string $path, string $pattern): array
    {
        $matches = [];
        preg_match('{' . $pattern . '}', $path, $matches);

        if (isset($matches[0]) && is_array($matches)) {
            array_shift($matches);
            return array_filter($matches);
        }

        return [];
    }

    /**
     * Check if path matches pattern
     */
    private function match(string $pattern, string $path): bool
    {
        return preg_match('{' . $pattern . '}', $path) === 1;
    }

    /**
     * Handle route
     */
    private function handleRoute(Route $route, array $params): void
    {
        // Run middlewares
        foreach ($this->middlewares as $middleware) {
            if ($middleware->run($this->request, $this->response)) {
                break;
            }
        }

        // Dispatch controller
        if (is_string($route->handler) && strpos($route->handler, '@') !== false) {
            [$controller, $action] = explode('@', $route->handler);
            $controller = 'RunwayHub\\' . $controller;
            $action = $action;

            if (class_exists($controller)) {
                /** @var Controller */
                $instance = new $controller(
                    $this->request,
                    $this->response,
                    $this->request
                );

                if (method_exists($instance, $action)) {
                    call_user_func_array([$instance, $action], $params);
                }
            }
        } elseif (is_string($route->handler)) {
            // Direct controller
            $controller = 'RunwayHub\\' . $route->handler;
            if (class_exists($controller)) {
                $this->response->status(500);
                $this->response->contentType('text/html');
                $this->response->content('Controller not found: ' . $controller);
                $this->response->send();
            }
        }
    }

    /**
     * Add middleware
     */
    public function middleware(string $middleware): self
    {
        $this->middlewares[] = new $middleware($this->request, $this->response);
        return $this;
    }

    /**
     * Get all routes
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * Get request instance
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * Get response instance
     */
    public function getResponse(): Response
    {
        return $this->response;
    }

    /**
     * Get middlewares
     */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }
}
