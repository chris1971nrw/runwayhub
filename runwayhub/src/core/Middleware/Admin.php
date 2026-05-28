<?php

declare(strict_types=1);

namespace RunwayHub\Core\Middleware;

use RunwayHub\Core\Request;
use RunwayHub\Core\Response;

class Admin extends Auth
{
    /**
     * Run middleware
     *
     * @return bool
     */
    public function run(): bool
    {
        // Ensure session
        $this->ensureSession();

        // Check if admin
        if (!$this->isAdmin()) {
            $this->response->redirect('/dashboard', 302);
            return true;
        }

        return false;
    }
}
