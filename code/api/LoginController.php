<?php

declare(strict_types=1);

namespace RunwayHub\Api;

use RunwayHub\Core\Database;
use RunwayHub\Core\Request;
use RunwayHub\Core\Response;
use RunwayHub\Core\Middleware\Auth;

class LoginController
{
    /** @var Database */
    private Database $db;

    /** @var string */
    private string $loginAction = '/login';

    public function __construct(Database $db, Request $request, Response $response)
    {
        $this->db = $db;
    }

    /**
     * Login für alle Benutzergruppen (Admin, VA-Inhaber, Pilot)
     */
    public function login(Request $request, Response $response): Response
    {
        $username = $request->input('username', '');
        $password = $request->input('password', '');

        // Benutzer aus der users-Tabelle laden
        $user = $this->db->fetch(
            "SELECT * FROM users WHERE username = ? AND status = 'active' AND role != 'user'",
            [$username]
        );

        if (!$user) {
            $response->statusCode(401);
            $response->json([
                'success' => false,
                'message' => 'Ungültige Zugangsdaten'
            ]);
            return $response;
        }

        // Passwort-Verifizierung
        if (password_verify($password, $user['password'])) {
            // Session erstellen
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['name'] = $user['name'] ?? $user['email'];

            if ($user['role'] === 'admin') {
                // Admin-Dashboard
                header('Location: /src/modules/Admin/Views/dashboard.html');
            } elseif ($user['role'] === 'va_owner') {
                // VA-Inhaber Dashboard
                header('Location: /src/modules/VAOwner/Views/dashboard.html');
            } elseif ($user['role'] === 'pilot') {
                // Pilot Dashboard
                header('Location: /src/modules/Pilot/Views/dashboard.html');
            } else {
                // Benutzer Dashboard
                header('Location: /src/modules/User/Views/dashboard.html');
            }
            exit;
        }

        $response->statusCode(401);
        $response->json([
            'success' => false,
            'message' => 'Ungültiges Passwort'
        ]);

        return $response;
    }

    /**
     * Logout für alle Benutzergruppen
     */
    public function logout(Request $request, Response $response): Response
    {
        session_destroy();
        header('Location: /api/login-pilot.php');
        exit;
    }
}
