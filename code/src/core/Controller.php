<?php

declare(strict_types=1);

namespace RunwayHub\Core;

use Exception;

abstract class Controller
{
    /** @var Database */
    protected Database $database;

    /** @var Request */
    protected Request $request;

    /** @var Response */
    protected Response $response;

    /** @var View|null */
    protected ?View $view = null;

    /** @var string|null */
    protected ?string $template = null;

    /** @var array */
    protected array $data = [];

    /**
     * Controller constructor
     */
    public function __construct(
        Database $database,
        Request $request,
        Response $response
    ) {
        $this->database = $database;
        $this->request = $request;
        $this->response = $response;
        $this->view = new View();
    }

    /**
     * Set template
     */
    public function setTemplate(string $template): self
    {
        $this->template = $template;
        return $this;
    }

    /**
     * Get template
     */
    public function getTemplate(): ?string
    {
        return $this->template;
    }

    /**
     * Set data to view
     */
    public function setData(string|array $key, mixed $value = null): self
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
     * Get view instance
     */
    public function getView(): View
    {
        return $this->view;
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
     * Get database instance
     */
    public function getDatabase(): Database
    {
        return $this->database;
    }

    /**
     * Render view
     *
     * @throws Exception
     */
    public function render(?string $template = null): Response
    {
        if ($template === null) {
            $template = $this->template;
        }

        if ($template === null) {
            throw new Exception('No template specified');
        }

        // Load template
        $path = BASE_PATH . 'runwayhub/public/templates/' . $template . '.php';

        if (!file_exists($path)) {
            throw new Exception("Template not found: {$template}");
        }

        // Render
        ob_start();
        include $path;
        $content = ob_get_clean();

        // Set content type
        $this->response->contentType('text/html');
        $this->response->content($content);

        // Send response
        $this->response->send();

        return $this->response;
    }

    /**
     * Redirect to URL
     */
    public function redirect(string $url, int $status = 302): Response
    {
        $this->response->header('Location', $url);
        $this->response->status($status);
        $this->response->send();

        return $this->response;
    }

    /**
     * Return JSON response
     */
    public function json(array $data, int $status = 200): Response
    {
        $this->response->contentType('application/json');
        $this->response->content(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        $this->response->status($status);
        $this->response->send();

        return $this->response;
    }

    /**
     * Show error message
     */
    public function error(string $message, int $code = 500): Response
    {
        $this->response->status($code);
        $this->response->contentType('application/json');
        $this->response->content(json_encode([
            'error' => true,
            'message' => $message,
        ]));
        $this->response->send();

        return $this->response;
    }
}
