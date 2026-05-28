<?php

declare(strict_types=1);

namespace RunwayHub\Core;

class Response
{
    /** @var string */
    private string $statusText;

    /** @var int */
    private int $status = 200;

    /** @var string */
    private string $contentType = 'text/html';

    /** @var string */
    private string $content = '';

    /** @var array */
    private array $headers = [];

    /** @var bool */
    private bool $sent = false;

    /** @var array */
    private array $cookies = [];

    /**
     * Response constructor
     */
    public function __construct()
    {
        $this->statusText = $this->getStatusLabel($this->status);
    }

    /**
     * Get status label
     */
    private function getStatusLabel(int $status): string
    {
        $texts = [
            200 => 'OK',
            201 => 'Created',
            204 => 'No Content',
            301 => 'Moved Permanently',
            302 => 'Found',
            304 => 'Not Modified',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not Found',
            500 => 'Internal Server Error',
        ];

        return $texts[$status] ?? 'Unknown';
    }

    /**
     * Set content type
     *
     * @param string $type
     * @return self
     */
    public function contentType(string $type): self
    {
        $this->contentType = $type;
        $this->header('Content-Type', $type . '; charset=UTF-8');
        return $this;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return self
     */
    public function content(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Set headers
     *
     * @param string|array $key
     * @param mixed $value
     * @return self
     */
    public function header(string|array $key, mixed $value = null): self
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $this->headers[$k] = $v;
            }
        } else {
            $this->headers[$key] = $value;
        }

        return $this;
    }

    /**
     * Get headers
     *
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Get content
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Get content type
     */
    public function getContentType(): string
    {
        return $this->contentType;
    }

    /**
     * Get status
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * Get status text
     */
    public function getStatusText(): string
    {
        return $this->statusText;
    }

    /**
     * Set status
     *
     * @param int $status
     * @return self
     */
    public function status(int $status): self
    {
        $this->status = $status;
        $this->statusText = $this->getStatusText($status);
        return $this;
    }

    /**
     * Add cookie
     *
     * @param string $name
     * @param string $value
     * @param int|null $expire
     * @param bool $path
     * @param string|null $domain
     * @param bool $secure
     * @param bool $httponly
     * @param string|null $sameSite
     * @return self
     */
    public function cookie(
        string $name,
        string $value,
        ?int $expire = null,
        bool $path = false,
        ?string $domain = null,
        bool $secure = false,
        bool $httponly = false,
        ?string $sameSite = null
    ): self {
        $this->cookies[] = [
            'name' => $name,
            'value' => $value,
            'expire' => $expire,
            'path' => $path,
            'domain' => $domain,
            'secure' => $secure,
            'httponly' => $httponly,
            'samesite' => $sameSite,
        ];

        if ($expire !== null) {
            setcookie($name, $value, $expire, $path, $domain ?: $this->getHost(), $secure, $httponly);
        } elseif (isset($_COOKIE[$name])) {
            setcookie($name, '', time() - 3600, $path, $domain ?: $this->getHost(), $secure, $httponly);
        }

        return $this;
    }

    /**
     * Get host
     */
    private function getHost(): string
    {
        return isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
    }

    /**
     * Send response
     *
     * @return self
     */
    public function send(): self
    {
        if ($this->sent) {
            return $this;
        }

        // Set headers
        foreach ($this->headers as $key => $value) {
            header("$key: $value");
        }

        // Set status code
        http_response_code($this->status);

        // Set cookies
        foreach ($this->cookies as $cookie) {
            $name = $cookie['name'];
            $value = $cookie['value'];
            $expire = $cookie['expire'] ?? null;
            $path = $cookie['path'] ?? '/';
            $domain = $cookie['domain'] ?? null;
            $secure = $cookie['secure'] ?? false;
            $httponly = $cookie['httponly'] ?? false;
            $sameSite = $cookie['samesite'] ?? 'Lax';

            if ($expire !== null) {
                setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
            }
        }

        // Send content
        echo $this->content;

        // Flush output
        flush();
        ob_flush();

        $this->sent = true;

        return $this;
    }

    /**
     * Get full headers including status
     *
     * @return array
     */
    public function getFullHeaders(): array
    {
        $headers = [];

        foreach ($this->headers as $key => $value) {
            $headers[$key] = $value;
        }

        return $headers;
    }

    /**
     * Redirect to URL
     *
     * @param string $url
     * @param int $status
     * @return self
     */
    public function redirect(string $url, int $status = 302): self
    {
        $this->status($status);
        $this->header('Location', $url);
        $this->content('');
        return $this;
    }
}
