<?php

declare(strict_types=1);

namespace RunwayHub\Core\Middleware;

use RunwayHub\Core\Request;
use RunwayHub\Core\Response;
use RunwayHub\Core\Database;

class Auth
{
    /** @var Request */
    private Request $request;

    /** @var Response */
    private Response $response;

    /** @var Database */
    private Database $database;

    /**
     * Auth constructor
     */
    public function __construct(
        Request $request,
        Response $response,
        Database $database
    ) {
        $this->request = $request;
        $this->response = $response;
        $this->database = $database;
    }

    /**
     * Run middleware
     *
     * @return bool
     */
    public function run(): bool
    {
        // Check if authenticated
        if (!$this->isAuthenticated()) {
            // Redirect to login
            $this->response->redirect('/login', 302);
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
        // Simple check for session
        return isset($_SESSION['user_id']);
    }

    /**
     * Check if session exists
     *
     * @return bool
     */
    private function hasSession(): bool
    {
        return session_status() === PHP_SESSION_ACTIVE;
    }

    /**
     * Start session if not started
     */
    public function ensureSession(): void
    {
        if ($this->hasSession()) {
            return;
        }

        session_start();

        // Regenerate session ID for security
        if ($this->request->ip('REMOTE_ADDR')) {
            session_regenerate_id(true);
        }
    }

    /**
     * Get user ID
     *
     * @return int|null
     */
    public function getUserId(): ?int
    {
        if (!$this->isAuthenticated()) {
            return null;
        }

        return $_SESSION['user_id'] ?? null;
    }

    /**
     * Get user role
     *
     * @return string|null
     */
    public function getRole(): ?string
    {
        if (!$this->isAuthenticated()) {
            return null;
        }

        return $_SESSION['user_role'] ?? null;
    }

    /**
     * Get user name
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        if (!$this->isAuthenticated()) {
            return null;
        }

        return $_SESSION['user_name'] ?? null;
    }

    /**
     * Check if user has role
     *
     * @param string $role
     * @return bool
     */
    public function hasRole(string $role): bool
    {
        if (!$this->isAuthenticated()) {
            return false;
        }

        return $_SESSION['user_role'] === $role;
    }

    /**
     * Check if user is admin
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Check if user is staff
     *
     * @return bool
     */
    public function isStaff(): bool
    {
        return $this->hasRole('staff');
    }

    /**
     * Check if user is pilot
     *
     * @return bool
     */
    public function isPilot(): bool
    {
        return $this->hasRole('pilot');
    }
}
