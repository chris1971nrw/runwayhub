<?php

declare(strict_types=1);

namespace RunwayHub\Api\Controller;

use RunwayHub\Core\Database;
use RunwayHub\Core\Response;
use Ramsey\Uuid\Uuid;

/**
 * Login Controller
 * 
 * Handles pilot authentication, callsign verification,
 * and redirects to airline portal after successful login.
 */
class LoginController
{
    private Database $db;
    private ?Database $usersDb = null;
    
    public function __construct(Database $db)
    {
        $this->db = $db;
    }
    
    /**
     * Initialize users database if needed
     */
    private function initializeUsersDb(): void
    {
        $schema = __DIR__ . '/../../database/users.sqlite.schema';
        
        if (!file_exists($schema)) {
            // Create basic users schema if not exists
            $usersSchema = <<<'SQL'
-- Users table for pilot authentication
CREATE TABLE IF NOT EXISTS users (
    id TEXT PRIMARY KEY,
    callsign TEXT UNIQUE NOT NULL,
    password_hash TEXT NOT NULL,
    airline TEXT,
    airline_url TEXT,
    group_id TEXT,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    active INTEGER DEFAULT 1
);

-- Users groups for pilot management
CREATE TABLE IF NOT EXISTS groups (
    id TEXT PRIMARY KEY,
    name TEXT UNIQUE NOT NULL,
    airline TEXT NOT NULL,
    permissions TEXT DEFAULT '[]'
);

-- Index for airline lookups
CREATE INDEX IF NOT EXISTS idx_users_airline ON users(airline);
CREATE INDEX IF NOT EXISTS idx_users_active ON users(active);
SQL;
            
            // Parse and execute schema
            $sqls = preg_split('/;/', $usersSchema);
            foreach ($sqls as $sql) {
                $sql = trim($sql);
                if (!empty($sql)) {
                    try {
                        $this->usersDb = new Database($schema ?: __DIR__ . '/../../database/users.sqlite');
                        $this->usersDb->query($sql);
                    } catch (\Exception $e) {
                        // Schema may already exist
                    }
                }
            }
        }
        
        if (!$this->usersDb) {
            $this->usersDb = new Database(__DIR__ . '/../../database/users.sqlite');
        }
    }
    
    /**
     * Login endpoint
     * 
     * Authenticate pilot with callsign/password
     * Redirect to airline portal after successful login
     * 
     * @param array $request Data from POST request
     * @return Response API response or redirect
     */
    public function login(array $request): Response
    {
        $this->initializeUsersDb();
        
        // Validate request
        if (!isset($request['callsign'], $request['password'])) {
            return Response::error('Missing callsign or password', 400);
        }
        
        $callsign = trim($request['callsign']);
        $password = $request['password'];
        
        // Check if user exists
        $stmt = $this->usersDb->prepare('SELECT * FROM users WHERE callsign = ? AND active = 1');
        $stmt->execute([$callsign]);
        
        $user = $stmt->fetch();
        
        if (!$user) {
            // Return generic error to prevent brute force
            return Response::error('Invalid credentials', 401);
        }
        
        // Verify password
        if (!password_verify($password, $user['password_hash'])) {
            return Response::error('Invalid credentials', 401);
        }
        
        // Generate session token
        $sessionToken = Uuid::uuid4()->toString();
        
        // Regenerate session (prevent session fixation)
        $sessionId = Uuid::uuid4()->toString();
        
        // Get user groups
        $stmt = $this->usersDb->prepare('SELECT id FROM groups WHERE name IN (
            SELECT g.name FROM groups g
            INNER JOIN group_members gm ON g.id = gm.group_id
            WHERE gm.user_id = ?
        )');
        $stmt->execute([$sessionToken]);
        $userGroups = $stmt->fetchAll();
        
        // Prepare redirect data
        $redirectData = [
            'success' => true,
            'user' => [
                'id' => $user['id'],
                'callsign' => $user['callsign'],
                'airline' => $user['airline']
            ],
            'sessionToken' => $sessionToken,
            'sessionId' => $sessionId,
            'groups' => array_column($userGroups, 'id')
        ];
        
        // Get airline redirect URL
        $airlineUrl = $user['airline_url'] ?? '';
        
        // Build redirect response
        if ($airlineUrl) {
            return Response::redirect($airlineUrl, $redirectData);
        }
        
        // Return API response with session data
        return Response::json($redirectData);
    }
    
    /**
     * Logout endpoint
     * 
     * Invalidate session and clear data
     * 
     * @param array $request Contains session token
     * @return Response
     */
    public function logout(array $request): Response
    {
        $sessionToken = $request['sessionToken'] ?? null;
        
        if ($sessionToken) {
            // Invalidate session in database
            try {
                $this->usersDb->query('UPDATE users SET active = 0 WHERE id = ?', [$sessionToken]);
            } catch (\Exception $e) {
                // Ignore if no active column
            }
        }
        
        return Response::json(['success' => true, 'message' => 'Logged out successfully']);
    }
    
    /**
     * Register pilot endpoint
     * 
     * Create new pilot account
     * 
     * @param array $data Registration data
     * @return Response
     */
    public function register(array $data): Response
    {
        $this->initializeUsersDb();
        
        // Validate data
        if (!isset($data['callsign'], $data['password'], $data['email'])) {
            return Response::error('Missing required fields', 400);
        }
        
        $callsign = trim($data['callsign']);
        $password = $data['password'];
        $email = filter_var($data['email'], FILTER_VALIDATE_EMAIL);
        $airline = $data['airline'] ?? null;
        $airlineUrl = $data['airline_url'] ?? '';
        
        // Check if callsign already exists
        $stmt = $this->usersDb->prepare('SELECT id FROM users WHERE callsign = ?');
        $stmt->execute([$callsign]);
        
        if ($stmt->fetch()) {
            return Response::error('Callsign already in use', 409);
        }
        
        // Hash password
        $passwordHash = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);
        
        // Generate user ID
        $userId = Uuid::uuid4()->toString();
        
        // Insert user
        try {
            $sql = <<<'SQL'
INSERT INTO users (id, callsign, password_hash, airline, airline_url, created_at, updated_at)
VALUES (?, ?, ?, ?, ?, datetime('now'), datetime('now'))
SQL;
            
            $this->usersDb->query($sql, [
                $userId,
                $callsign,
                $passwordHash,
                $airline,
                $airlineUrl
            ]);
            
            // Create default group for pilot
            $groupId = Uuid::uuid4()->toString();
            $this->usersDb->query(
                'INSERT OR IGNORE INTO groups (id, name, airline) VALUES (?, ?, ?)',
                [$groupId, 'pilots', $airline]
            );
            
            // Add user to group
            $this->usersDb->query(
                'INSERT OR IGNORE INTO group_members (user_id, group_id) VALUES (?, ?)',
                [$userId, $groupId]
            );
            
            return Response::json([
                'success' => true,
                'user' => [
                    'id' => $userId,
                    'callsign' => $callsign,
                    'email' => $email
                ],
                'message' => 'Account created successfully'
            ], 201);
            
        } catch (\Exception $e) {
            return Response::error('Registration failed', 500);
        }
    }
    
    /**
     * Verify callsign endpoint
     * 
     * Check if callsign exists without revealing password requirements
     * 
     * @param array $request Contains callsign
     * @return Response
     */
    public function verifyCallsign(array $request): Response
    {
        $callsign = trim($request['callsign'] ?? '');
        
        if (empty($callsign)) {
            return Response::error('Invalid callsign', 400);
        }
        
        $stmt = $this->usersDb->prepare('SELECT callsign, airline FROM users WHERE callsign = ? AND active = 1');
        $stmt->execute([$callsign]);
        $user = $stmt->fetch();
        
        if (!$user) {
            return Response::json([
                'valid' => false,
                'message' => 'Callsign not found'
            ]);
        }
        
        return Response::json([
            'valid' => true,
            'callsign' => $user['callsign'],
            'airline' => $user['airline']
        ]);
    }
    
    /**
     * Get user profile
     * 
     * @param array $request Contains session token
     * @return Response
     */
    public function profile(array $request): Response
    {
        $sessionToken = $request['sessionToken'] ?? null;
        
        if (!$sessionToken) {
            return Response::error('Missing session token', 401);
        }
        
        $stmt = $this->usersDb->prepare('SELECT * FROM users WHERE id = ? AND active = 1');
        $stmt->execute([$sessionToken]);
        $user = $stmt->fetch();
        
        if (!$user) {
            return Response::error('Invalid session', 401);
        }
        
        return Response::json([
            'id' => $user['id'],
            'callsign' => $user['callsign'],
            'email' => $user['email'] ?? null,
            'airline' => $user['airline'],
            'airline_url' => $user['airline_url'],
            'created_at' => $user['created_at'],
            'updated_at' => $user['updated_at']
        ]);
    }
}
