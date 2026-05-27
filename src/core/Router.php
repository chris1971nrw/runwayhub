<?php

/**
 * RunwayHub Router
 * Simple PSR-7 compatible router for REST API
 */

namespace RunwayHub\Core;

class Router
{
    protected array $routes = [];
    protected ?string $basePath;

    public function __construct(?string $basePath = '/api')
    {
        $this->basePath = rtrim($basePath ?? '/', '/');
    }

    public function get(string $path, callable $handler): static
    {
        $path = $this->normalizePath($path);
        $this->routes[$path]['GET'] = $handler;
        return $this;
    }

    public function post(string $path, callable $handler): static
    {
        $path = $this->normalizePath($path);
        $this->routes[$path]['POST'] = $handler;
        return $this;
    }

    public function put(string $path, callable $handler): static
    {
        $path = $this->normalizePath($path);
        $this->routes[$path]['PUT'] = $handler;
        return $this;
    }

    public function patch(string $path, callable $handler): static
    {
        $path = $this->normalizePath($path);
        $this->routes[$path]['PATCH'] = $handler;
        return $this;
    }

    public function delete(string $path, callable $handler): static
    {
        $path = $this->normalizePath($path);
        $this->routes[$path]['DELETE'] = $handler;
        return $this;
    }

    public function handle(array $method, array $path, array $params = []): array
    {
        foreach ($this->routes as $route => $handlers) {
            if ($this->matches($path, $route)) {
                $method = ($method ?? 'GET') ?? 'GET';
                if (isset($handlers[$method])) {
                    return call_user_func($handlers[$method], $params);
                }
            }
        }
        return ['error' => 'Not Found', 'code' => 404];
    }

    private function normalizePath(string $path): string
    {
        return '/' . trim($path, '/');
    }

    private function matches(string $path, string $route): bool
    {
        return $path === $route;
    }
}
