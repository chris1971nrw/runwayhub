<?php

declare(strict_types=1);

namespace RunwayHub\Api\Controller;

use RunwayHub\Core\Controller as BaseController;
use RunwayHub\Core\Request;
use RunwayHub\Core\Response;
use RunwayHub\Core\Database;
use RunwayHub\Core\Middleware\Auth;

/**
 * Admin Controller
 * 
 * Handles admin-specific actions like password changes.
 */
class AdminController extends BaseController
{
    protected Request $request;
    protected Response $response;
    protected Database $db;
    protected ?Auth $auth;

    public function __construct(Request $request, Response $response, Database $db, ?Auth $auth = null)
    {
        parent::__construct($request, $response);
        $this->request = $request;
        $this->response = $response;
        $this->db = $db;
        $this->auth = $auth;
    }

    /**
     * Admin login
     * 
     * @return Response
     */
    public function login(): Response
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // Authenticate
        $auth = new Auth($this->request);

        if ($auth->login($username, $password)) {
            // Set admin in session
            $_SESSION['admin'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['isAdmin'] = $auth->isAdmin();

            return $this->response->contentType('application/json')->content(json_encode([
                'success' => true,
                'message' => 'Admin login successful',
                'isAdmin' => true,
                'username' => $username,
            ]))->send();
        }

        return $this->response->contentType('application/json')->content(json_encode([
            'success' => false,
            'error' => 'Invalid credentials',
        ]))->send();
    }

    /**
     * Admin logout
     * 
     * @return Response
     */
    public function logout(): Response
    {
        $auth = new Auth($this->request);
        $auth->logout();

        $_SESSION['admin'] = false;
        $_SESSION['username'] = null;
        $_SESSION['isAdmin'] = false;

        return $this->response->contentType('application/json')->content(json_encode([
            'success' => true,
            'message' => 'Admin logged out',
        ]))->send();
    }

    /**
     * Change password
     * 
     * @return Response
     */
    public function changePassword(): Response
    {
        // Check admin access
        if (!$this->auth || !$this->auth->isAdmin()) {
            return $this->response->contentType('application/json')->content(json_encode([
                'success' => false,
                'error' => 'Access denied. Admin privileges required.',
            ]))->send();
        }

        $currentPassword = $this->request->getPost('current_password');
        $newPassword = $this->request->getPost('new_password');
        $confirmPassword = $this->request->getPost('confirm_password');

        // Verify current password
        if (!password_verify($currentPassword, $this->auth->getPasswordHash())) {
            return $this->response->contentType('application/json')->content(json_encode([
                'success' => false,
                'error' => 'Current password is incorrect',
            ]))->send();
        }

        // Verify new password matches confirmation
        if ($newPassword !== $confirmPassword) {
            return $this->response->contentType('application/json')->content(json_encode([
                'success' => false,
                'error' => 'New passwords do not match',
            ]))->send();
        }

        // Minimum password length
        if (strlen($newPassword) < 8) {
            return $this->response->contentType('application/json')->content(json_encode([
                'success' => false,
                'error' => 'Password must be at least 8 characters',
            ]))->send();
        }

        // Get current admin
        $admin = $this->db->fetchRow('SELECT id, username FROM admins WHERE username = ?', [$this->auth->getUsername()]);

        if (!$admin) {
            return $this->response->contentType('application/json')->content(json_encode([
                'success' => false,
                'error' => 'Admin not found',
            ]))->send();
        }

        // Update password
        $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
        $this->db->execute("UPDATE admins SET password = ? WHERE id = ?", [$newPasswordHash, $admin['id']]);

        // Log action
        $this->logger->info("Password changed", [
            'username' => $this->auth->getUsername(),
            'action' => 'password_changed',
        ]);

        // Clear password hash from auth
        $this->auth->passwordHash = null;

        return $this->response->contentType('application/json')->content(json_encode([
            'success' => true,
            'message' => 'Password changed successfully',
        ]))->send();
    }

    /**
     * Update profile
     * 
     * @return Response
     */
    public function updateProfile(): Response
    {
        // Check admin access
        if (!$this->auth || !$this->auth->isAdmin()) {
            return $this->response->contentType('application/json')->content(json_encode([
                'success' => false,
                'error' => 'Access denied. Admin privileges required.',
            ]))->send();
        }

        $data = [
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone') ?? null,
        ];

        // Get current admin
        $admin = $this->db->fetchRow('SELECT id FROM admins WHERE username = ?', [$this->auth->getUsername()]);

        if (!$admin) {
            return $this->response->contentType('application/json')->content(json_encode([
                'success' => false,
                'error' => 'Admin not found',
            ]))->send();
        }

        // Update profile
        $fields = [];
        $params = [];
        foreach ($data as $key => $value) {
            if ($value !== null) {
                $fields[] = "{$key} = ?";
                $params[] = $value;
            }
        }

        if (empty($fields)) {
            return $this->response->contentType('application/json')->content(json_encode([
                'success' => false,
                'error' => 'No fields to update',
            ]))->send();
        }

        $sql = "UPDATE admins SET " . implode(', ', $fields) . " WHERE id = ?";
        $params[] = $admin['id'];
        $this->db->execute($sql, $params);

        // Log action
        $this->logger->info("Profile updated", [
            'username' => $this->auth->getUsername(),
            'action' => 'profile_updated',
        ]);

        return $this->response->contentType('application/json')->content(json_encode([
            'success' => true,
            'message' => 'Profile updated successfully',
        ]))->send();
    }

    /**
     * Get admin profile
     * 
     * @return Response
     */
    public function getProfile(): Response
    {
        // Check admin access
        if (!$this->auth || !$this->auth->isAdmin()) {
            return $this->response->contentType('application/json')->content(json_encode([
                'success' => false,
                'error' => 'Access denied. Admin privileges required.',
            ]))->send();
        }

        // Get admin info
        $admin = $this->db->fetchRow('
            SELECT id, username, email, phone, role, active, created_at
            FROM admins 
            WHERE username = ?
        ', [$this->auth->getUsername()]);

        if (!$admin) {
            return $this->response->contentType('application/json')->content(json_encode([
                'success' => false,
                'error' => 'Admin not found',
            ]))->send();
        }

        // Remove password from result
        unset($admin['password']);

        return $this->response->contentType('application/json')->content(json_encode([
            'success' => true,
            'data' => $admin,
        ]))->send();
    }

    /**
     * Get admin stats
     * 
     * @return Response
     */
    public function getStats(): Response
    {
        // Check admin access
        if (!$this->auth || !$this->auth->isAdmin()) {
            return $this->response->contentType('application/json')->content(json_encode([
                'success' => false,
                'error' => 'Access denied. Admin privileges required.',
            ]))->send();
        }

        // Get stats
        $stats = [
            'total_flights' => $this->db->fetchOne('SELECT COUNT(*) FROM flights'),
            'total_aircrafts' => $this->db->fetchOne('SELECT COUNT(*) FROM aircrafts'),
            'total_pilots' => $this->db->fetchOne('SELECT COUNT(*) FROM pilots'),
            'total_bookings' => $this->db->fetchOne('SELECT COUNT(*) FROM bookings'),
            'today_bookings' => $this->db->fetchOne('SELECT COUNT(*) FROM bookings WHERE DATE(created_at) = DATE("now")'),
        ];

        return $this->response->contentType('application/json')->content(json_encode([
            'success' => true,
            'data' => $stats,
        ]))->send();
    }
}
