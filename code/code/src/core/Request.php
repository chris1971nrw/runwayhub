<?php

declare(strict_types=1);

namespace RunwayHub\Core;

class Request
{
    /** @var string */
    private string $method;

    /** @var string */
    private string $uri;

    /** @var string */
    private string $path;

    /** @var array */
    private array $params = [];

    /** @var array */
    private array $get = [];

    /** @var array */
    private array $post = [];

    /** @var array */
    private array $files = [];

    /** @var array */
    private array $headers = [];

    /** @var array */
    private array $cookies = [];

    /** @var string|null */
    private ?string $contentType = null;

    /**
     * Request constructor
     */
    public function __construct()
    {
        // Parse URI
        $this->parseUri($_SERVER['REQUEST_URI'] ?? '/');

        // Get method
        $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        // Get headers
        $this->headers = getallheaders();
        if (empty($this->headers)) {
            $this->headers = [];
            foreach ($_SERVER as $key => $value) {
                if (preg_match('/HTTP_\w+_HEADER/i', $key)) {
                    $this->headers[str_replace(' ', '', strtolower($key))] = $value;
                }
            }
        }

        // Get content type
        if (isset($this->headers['CONTENT_TYPE'])) {
            $this->contentType = explode(';', $this->headers['CONTENT_TYPE'])[0] ?? null;
        }

        // Get GET params
        $this->get = $_GET;

        // Get POST params
        $this->post = $_POST;

        // Get files
        $this->files = $_FILES;

        // Get cookies
        $this->cookies = $_COOKIE;
    }

    /**
     * Parse URI
     */
    private function parseUri(string $uri): void
    {
        $this->uri = $uri;

        // Remove query string
        $parts = explode('?', $uri, 2);
        $this->path = $parts[0];

        // Parse path
        if (strpos($this->path, '/') === 0) {
            $segments = explode('/', $this->path);
            array_shift($segments); // Remove empty string from leading /
            
            // Build params
            $params = [];
            foreach ($segments as $index => $segment) {
                if (preg_match('/^{(\w+)}$/', $segment, $match)) {
                    $params[$match[1]] = true;
                } elseif (str_contains($segment, '.')) {
                    // File extension
                    continue;
                }
            }
            
            $this->params = $params;
        }
    }

    /**
     * Get method
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Get URI
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * Get path
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Get path with query string
     */
    public function getUriWithQuery(): string
    {
        return $this->uri;
    }

    /**
     * Get GET params
     *
     * @param string|null $key
     * @param mixed $default
     * @return mixed
     */
    public function get(?string $key = null, mixed $default = null): mixed
    {
        if ($key === null) {
            return $this->get;
        }

        return $this->get[$key] ?? $default;
    }

    /**
     * Get POST params
     *
     * @param string|null $key
     * @param mixed $default
     * @return mixed
     */
    public function post(?string $key = null, mixed $default = null): mixed
    {
        if ($key === null) {
            return $this->post;
        }

        return $this->post[$key] ?? $default;
    }

    /**
     * Get files
     *
     * @param string|null $key
     * @param mixed $default
     * @return mixed
     */
    public function file(?string $key = null, mixed $default = null): mixed
    {
        if ($key === null) {
            return $this->files;
        }

        return $this->files[$key] ?? $default;
    }

    /**
     * Get headers
     *
     * @param string|null $key
     * @param mixed $default
     * @return mixed
     */
    public function header(?string $key = null, mixed $default = null): mixed
    {
        if ($key === null) {
            return $this->headers;
        }

        return $this->headers[$key] ?? $default;
    }

    /**
     * Get cookies
     *
     * @param string|null $key
     * @param mixed $default
     * @return mixed
     */
    public function cookie(?string $key = null, mixed $default = null): mixed
    {
        if ($key === null) {
            return $this->cookies;
        }

        return $this->cookies[$key] ?? $default;
    }

    /**
     * Get content type
     */
    public function getContentType(): ?string
    {
        return $this->contentType;
    }

    /**
     * Get input (GET or POST depending on method)
     *
     * @param string|null $key
     * @param mixed $default
     * @return mixed
     */
    public function input(?string $key = null, mixed $default = null): mixed
    {
        $data = $this->method === 'POST' ? $this->post : $this->get;

        if ($key === null) {
            return $data;
        }

        return $data[$key] ?? $default;
    }

    /**
     * Check if request has method
     *
     * @param string $method
     * @return bool
     */
    public function is(string $method): bool
    {
        return $this->method === strtoupper($method);
    }

    /**
     * Get IP address
     *
     * @return string|null
     */
    public function ip(?string $client = 'REMOTE_ADDR'): ?string
    {
        $ip = $_SERVER[$client] ?? null;

        if ($ip === null) {
            return null;
        }

        // Remove port if present
        if (str_contains($ip, ':')) {
            $ip = explode(':', $ip)[0];
        }

        return filter_var($ip, FILTER_VALIDATE_IP) ? $ip : null;
    }

    /**
     * Get user agent
     *
     * @return string|null
     */
    public function userAgent(): ?string
    {
        return $_SERVER['HTTP_USER_AGENT'] ?? null;
    }

    /**
     * Get host
     *
     * @return string|null
     */
    public function host(): ?string
    {
        return $_SERVER['HTTP_HOST'] ?? null;
    }

    /**
     * Get scheme (http or https)
     *
     * @return string
     */
    public function scheme(): string
    {
        $scheme = $_SERVER['HTTPS'] ?? '';
        return $scheme === 'on' ? 'https' : 'http';
    }

    /**
     * Get base URL
     *
     * @return string
     */
    public function baseUrl(): string
    {
        return $this->scheme() . '://' . $this->host();
    }

    /**
     * Get full URL
     *
     * @param string $path
     * @param array $params
     * @return string
     */
    public function url(string $path = '', array $params = []): string
    {
        $url = $this->baseUrl() . $path;

        if (!empty($params)) {
            $query = http_build_query($params);
            if (empty($query)) {
                return $url;
            }

            $separator = str_contains($url, '?') ? '&' : '?';
            return $url . $separator . $query;
        }

        return $url;
    }

    /**
     * Get full URL to current page
     *
     * @param string|null $param
     * @param mixed $value
     * @return string
     */
    public function currentUrl(?string $param = null, mixed $value = null): string
    {
        $url = $this->url();

        if ($param !== null) {
            $query = http_build_query([$param => $value]);
            if (empty($query)) {
                return $url;
            }

            $separator = str_contains($url, '?') ? '&' : '?';
            return $url . $separator . $query;
        }

        return $url;
    }

    /**
     * Get param value from path
     *
     * @param string|null $key
     * @return mixed
     */
    public function param(?string $key = null): mixed
    {
        if ($key === null) {
            return $this->params;
        }

        return $this->params[$key] ?? null;
    }
}
