<?php

declare(strict_types=1);

namespace RunwayHub\Core\Middleware;

use RunwayHub\Core\Request;
use RunwayHub\Core\Response;

/**
 * Authentication Middleware
 * 
 * Provides admin authentication and authorization.
 */
class Auth
{
    protected ?string $username = null;
    protected ?string $passwordHash = null;
    protected bool $isAdmin = false;
    protected ?Request $request = null;

    /**
     * Constructor
     */
    public function __construct(Request $request = null)
    {
        $this->request = $request;
    }

    /**
     * Authenticate user
     * 
     * @param string $username
     * @param string $password
     * @return bool
     */
    public function login(string $username, string $password): bool
    {
        $this->username = $username;
        
        // Check admin credentials (hardcoded for now)
        if ($username === 'admin' && password_verify($password, $this->getAdminHash())) {
            $this->isAdmin = true;
            $this->passwordHash = $this->getAdminHash();
            
            // Log successful login
            $this->logLogin($username, 'success');
            
            return true;
        }

        $this->logLogin($username, 'failed');
        return false;
    }

    /**
     * Check if user is admin
     * 
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->isAdmin;
    }

    /**
     * Get username
     * 
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * Log out
     * 
     * @return void
     */
    public function logout(): void
    {
        $this->username = null;
        $this->isAdmin = false;
        $this->passwordHash = null;
        
        $this->logLogin($this->getUsername() ?? 'unknown', 'logout');
    }

    /**
     * Check permissions for action
     * 
     * @param string $action Action to check
     * @return bool
     */
    public function can(string $action): bool
    {
        // Admins can do everything
        if ($this->isAdmin) {
            return true;
        }

        // Non-admins have limited access
        $allowedActions = ['read', 'search'];
        
        return in_array($action, $allowedActions);
    }

    /**
     * Get admin password hash
     * 
     * @return string
     */
    private function getAdminHash(): string
    {
        // Default admin password hash (change this in production!)
        // Password: "admin123"
        return password_hash('admin123', PASSWORD_DEFAULT);
    }

    /**
     * Log login attempt
     * 
     * @param string $username
     * @param string $status success/failed/logout
     * @return void
     */
    private function logLogin(string $username, string $status): void
    {
        $logPath = __DIR__ . '/../../logs/login-activity.log';
        
        if (file_exists(__DIR__ . '/../../logs/login-activity.log')) {
            $now = date('Y-m-d H:i:s');
            $message = "{$now} User: {$username} Status: {$status}\n";
            file_put_contents($logPath, $message, FILE_APPEND);
        }
    }

    /**
     * Get admin user info
     * 
     * @return array|null
     */
    public function getAdminInfo(): ?array
    {
        return [
            'username' => $this->username,
            'isAdmin' => $this->isAdmin,
            'email' => 'admin@example.com',
            'name' => 'Administrator',
        ];
    }

    /**
     * Get permissions
     * 
     * @return array
     */
    public function getPermissions(): array
    {
        if ($this->isAdmin) {
            return [
                'airlines' => ['create', 'read', 'update', 'delete'],
                'flights' => ['create', 'read', 'update', 'delete'],
                'aircrafts' => ['create', 'read', 'update', 'delete'],
                'pilots' => ['create', 'read', 'update', 'delete'],
                'bookings' => ['create', 'read', 'update', 'delete'],
                'bookings' => ['create', 'read', 'update', 'delete'],
                'users' => ['create', 'read', 'update', 'delete'],
                'settings' => ['read', 'update'],
            ];
        }

        return [
            'airlines' => ['read'],
            'flights' => ['read'],
            'aircrafts' => ['read'],
            'pilots' => ['read'],
            'bookings' => ['read'],
        ];
    }
}
