<?php

declare(strict_types=1);

namespace RunwayHub\Core;

class View
{
    /** @var array */
    private array $data = [];

    /** @var string */
    private string $template = 'layout';

    /**
     * View constructor
     */
    public function __construct()
    {
        // Default layout
        $this->layout('runwayhub');
    }

    /**
     * Set layout
     *
     * @param string $layout
     * @return self
     */
    public function layout(string $layout): self
    {
        $this->template = $layout;
        return $this;
    }

    /**
     * Get layout
     */
    public function getLayout(): string
    {
        return $this->template;
    }

    /**
     * Set data
     *
     * @param string|array $key
     * @param mixed $value
     * @return self
     */
    public function set(string|array $key, mixed $value = null): self
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $this->data[$k] = $v;
            }
        } else {
            $this->data[$key] = $value;
        }

        return $this;
    }

    /**
     * Get data
     *
     * @param string|null $key
     * @param mixed $default
     * @return mixed
     */
    public function get(?string $key = null, mixed $default = null): mixed
    {
        if ($key === null) {
            return $this->data;
        }

        return $this->data[$key] ?? $default;
    }

    /**
     * Render view
     *
     * @return string
     * @throws \Exception
     */
    public function render(): string
    {
        // Get layout
        $layout = BASE_PATH . 'runwayhub/public/templates/layout.php';

        // If layout doesn't exist, return data as plain text
        if (!file_exists($layout)) {
            return json_encode($this->data, JSON_PRETTY_PRINT);
        }

        // Output buffering
        ob_start();

        // Include layout with data
        include $layout;

        // Get content
        $content = ob_get_clean();

        return $content;
    }

    /**
     * Check if data exists
     *
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        return isset($this->data[$key]);
    }

    /**
     * Clear all data
     *
     * @return self
     */
    public function clear(): self
    {
        $this->data = [];
        return $this;
    }

    /**
     * Get all data
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Set multiple data at once
     *
     * @param array $data
     * @return self
     */
    public function setData(array $data): self
    {
        $this->data = array_merge($this->data, $data);
        return $this;
    }
}
