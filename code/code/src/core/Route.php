<?php

declare(strict_types=1);

namespace RunwayHub\Core;

/**
 * Route class
 *
 * Represents a URL route with method, path, handler, and options.
 */
class Route
{
    /** @var array HTTP methods allowed */
    public array $methods;

    /** @var string URL path */
    public string $path;

    /** @var string|array|callable Handler or controller@method */
    public $handler;

    /** @var array Route options */
    public array $options = [];

    /** @var bool Route name */
    public ?string $name = null;

    /** @var array Middleware stack */
    public array $middleware = [];

    /**
     * Route constructor
     *
     * @param array $methods HTTP methods
     * @param string $path URL path
     * @param string|array $handler Handler
     * @param array $options Options
     */
    public function __construct(
        array $methods,
        string $path,
        $handler,
        array $options = []
    ) {
        $this->methods = $methods;
        $this->path = $path;
        $this->handler = $handler;
        $this->options = $options;
    }

    /**
     * Set route name
     *
     * @param string $name Route name
     * @return self
     */
    public function name(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Add middleware
     *
     * @param string $middleware Middleware class name
     * @return self
     */
    public function middleware(string $middleware): self
    {
        $this->middleware[] = $middleware;
        return $this;
    }

    /**
     * Get route info
     *
     * @return array
     */
    public function getRouteInfo(): array
    {
        return [
            'methods' => $this->methods,
            'path' => $this->path,
            'handler' => $this->handler,
            'options' => $this->options,
            'name' => $this->name,
            'middleware' => $this->middleware,
        ];
    }

    /**
     * Check if method is allowed
     *
     * @param string $method HTTP method
     * @return bool
     */
    public function methodAllowed(string $method): bool
    {
        return in_array($method, $this->methods, true);
    }

    /**
     * Get path pattern with regex modifiers
     *
     * @return string
     */
    public function getPathPattern(): string
    {
        return preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $this->path);
    }
}
