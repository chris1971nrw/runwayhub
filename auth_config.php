<?php
/**
 * Authentication & Security Configuration
 * This file handles session management, role verification, and core authentication logic.
 */

ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Configure session for persistence
ini_set('session.gc_maxlen', 3600 * 24 * 7);
ini_set('session.gc_probability', 1);

require_once "db_config.php";

/**
 * Role Constants
 */
define('ROLE_ADMIN', 'admin');
define('ROLE_PILOT', 'pilot');
define('ROLE_STAFF', 'ground_staff');

/**
 * Validates if the user is logged in and has a specific role.
 * 
 * @param string $requiredRole The required role (e.g., ROLE_ADMIN).
 * @return bool True if authenticated and authorized, false otherwise.
 */
function check_auth_status(string $requiredRole = null): bool {
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['error'] = "Please log in to access this page.";
        header('Location: login.php');
        exit;
    }

    $currentRole = strtolower(trim($_SESSION['role'] ?? ''));
    if ($requiredRole && $currentRole !== $requiredRole) {
        $_SESSION['error'] = "Access denied: You do not have the required privileges.";
        header('Location: index.php?reason=unauthorized');
        exit;
    }

    return true;
}

/**
 * Authenticates a user against the database.
 * 
 * @param string $username The raw username input.
 * @param_password The raw password input.
 * @return bool True if login is successful, false otherwise.
 */
function authenticate(string $username, string $password): bool {
    try {
        $db = getDB();
        $username = trim($username);

        // Query for user by username using the correct 'password' column
        $stmt = $db->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user) {
            $isValid = false;
            // Check if password is hashed or plain text (for backward compatibility)
            if (password_verify($password, $user['password'])) {
                $isValid = true;
            } elseif ($password === $user['password']) {
                $isValid = true; 
            }

            if ($isValid) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                return true;
            }
        }

        return false;
    } catch (PDOException $e) {
        error_log("Authentication Database Error: " . $e->getMessage());
        return false;
    }
}
