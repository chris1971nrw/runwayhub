<?php

declare(strict_types=1);

namespace RunwayHub\Core\Middleware;

use RunwayHub\Core\Request;
use RunwayHub\Core\Response;

class Guest
{
    /** @var Request */
    private Request $request;

    /** @var Response */
    private Response $response;

    /**
     * Guest constructor
     */
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * Run middleware
     *
     * @return bool
     */
    public function run(): bool
    {
        // Check if user is authenticated
        if ($this->isAuthenticated()) {
            // Redirect to dashboard or last visited page
            $this->response->redirect('/dashboard', 302);
            return true;
        }

        return false;
    }

    /**
     * Check if user is authenticated
     *
     * @return bool
     */
    private function isAuthenticated(): bool
    {
        return isset($_SESSION['user_id']);
    }
}
