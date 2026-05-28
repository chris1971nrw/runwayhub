<?php

declare(strict_types=1);

namespace RunwayHub\Api\Controller;

use RunwayHub\Core\Database;
use RunwayHub\Core\Response;
use Ramsey\Uuid\Uuid;

/**
 * Virtual Airline (VA) Controller
 * 
 * Handles VA creation, connection validation, and management
 */
class VAController
{
    private Database $db;
    
    public function __construct(Database $db)
    {
        $this->db = $db;
    }
    
    /**
     * Create new Virtual Airline
     * 
     * Endpoint: POST /api/va-create.php
     * 
     * @param array $data VA creation data
     * @return Response
     */
    public function createVA(array $data): Response
    {
        // Validate data
        $required = ['name', 'logo', 'colors', 'airlineName', 'website'];
        foreach ($required as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                return Response::error("Missing required field: {$field}", 400);
            }
        }
        
        // Validate colors
        $colors = $data['colors'] ?? [];
        if (!isset($colors['primary'], $colors['secondary'])) {
            return Response::error('Missing color definitions', 400);
        }
        
        // Generate unique VA ID
        $vaId = Uuid::uuid4()->toString();
        
        // Generate Owner Credentials (simulated for demo)
        $ownerCredentials = [
            'id' => Uuid::uuid4()->toString(),
            'username' => strtolower(str_replace(' ', '', $data['name'] . time())),
            'password' => Uuid::uuid4()->toString(), // In production: use password_hash
            'expires' => '+365 days'
        ];
        
        // Hash password
        $ownerCredentials['password'] = password_hash(
            $ownerCredentials['password'],
            PASSWORD_DEFAULT,
            ['cost' => 12]
        );
        
        // Create VA in database
        try {
            $sql = <<<'SQL'
INSERT OR REPLACE INTO va (
    id, name, airline_name, airline_website, logo, colors,
    owner_credentials, created_at, updated_at
) VALUES (?, ?, ?, ?, ?, ?, ?, datetime('now'), datetime('now'))
SQL;
            
            $this->db->query($sql, [
                $vaId,
                $data['name'],
                $data['airlineName'],
                $data['website'],
                $data['logo'],
                json_encode($colors),
                json_encode($ownerCredentials)
            ]);
            
            // Create pilot group
            $groupSql = <<<'SQL'
INSERT OR IGNORE INTO groups (id, name, airline, permissions)
VALUES (?, ?, ?, ?)
SQL;
            
            $groupId = Uuid::uuid4()->toString();
            $this->db->query($groupSql, [
                $groupId,
                'pilots',
                $data['name'],
                json_encode(['*'])
            ]);
            
            return Response::json([
                'success' => true,
                'va' => [
                    'id' => $vaId,
                    'name' => $data['name'],
                    'airline_name' => $data['airlineName'],
                    'airline_website' => $data['website'],
                    'logo' => $data['logo'],
                    'colors' => $colors
                ],
                'owner_credentials' => $ownerCredentials,
                'group_id' => $groupId,
                'message' => 'Virtual airline created successfully'
            ], 201);
            
        } catch (\Exception $e) {
            return Response::error('VA creation failed', 500);
        }
    }
    
    /**
     * Connect existing Virtual Airline
     * 
     * Endpoint: POST /api/va-connect.php
     * 
     * @param array $data Connection data
     * @return Response
     */
    public function connectVA(array $data): Response
    {
        // Validate data
        if (!isset($data['ownerCredentials']['username'], $data['ownerCredentials']['password'])) {
            return Response::error('Missing owner credentials', 400);
        }
        
        $username = $data['ownerCredentials']['username'];
        $password = $data['ownerCredentials']['password'];
        
        // Get all VAs and check credentials (demo mode)
        $vAs = $this->db->query('SELECT * FROM va');
        
        $connectedVa = null;
        
        foreach ($vAs as $va) {
            $credentials = json_decode($va['owner_credentials'], true);
            
            if ($credentials['username'] === $username && 
                password_verify($password, $credentials['password'])) {
                
                // Match website to confirm
                if (strpos($data['website'] ?? '', $va['airline_website']) === 0 ||
                    strpos($va['airline_website'], $data['website'] ?? '') === 0) {
                    
                    $connectedVa = $va;
                    break;
                }
            }
        }
        
        if (!$connectedVa) {
            return Response::error('VA not found or invalid credentials', 404);
        }
        
        // Verify credentials in database
        $credentials = json_decode($connectedVa['owner_credentials'], true);
        if (!password_verify($password, $credentials['password'])) {
            return Response::error('Invalid owner credentials', 401);
        }
        
        // Create or update pilot group
        $groupSql = <<<'SQL'
INSERT OR IGNORE INTO groups (id, name, airline, permissions)
VALUES (?, ?, ?, ?)
SQL;
        
        $groupId = Uuid::uuid4()->toString();
        $this->db->query($groupSql, [
            $groupId,
            'pilots',
            $connectedVa['airline_name'],
            json_encode(['*'])
        ]);
        
        // Return connection data
        return Response::json([
            'success' => true,
            'va' => [
                'id' => $connectedVa['id'],
                'name' => $connectedVa['name'],
                'airline_name' => $connectedVa['airline_name'],
                'airline_website' => $connectedVa['airline_website']
            ],
            'group_id' => $groupId,
            'message' => 'Successfully connected to virtual airline',
            'next_steps' => [
                '1. Add pilot callsign to your Airline portal',
                '2. Complete profile setup',
                '3. Start receiving flight data'
            ]
        ]);
    }
    
    /**
     * Get list of all Virtual Airlines
     * 
     * @return Response
     */
    public function listVA(): Response
    {
        $vAs = $this->db->query('SELECT * FROM va');
        
        $formatted = array_map(function($va) {
            $colors = json_decode($va['colors'] ?? '[]', true);
            $owner = json_decode($va['owner_credentials'] ?? '{}', true);
            
            return [
                'id' => $va['id'],
                'name' => $va['name'],
                'airline_name' => $va['airline_name'],
                'airline_website' => $va['airline_website'],
                'logo' => $va['logo'],
                'colors' => $colors,
                'created_at' => $va['created_at'],
                'owner' => $owner
            ];
        }, $vAs);
        
        return Response::json(['va' => $formatted]);
    }
    
    /**
     * Get VA by ID
     * 
     * @param string $vaId VA identifier
     * @return Response
     */
    public function getVA(string $vaId): Response
    {
        $stmt = $this->db->prepare('SELECT * FROM va WHERE id = ?');
        $stmt->execute([$vaId]);
        $va = $stmt->fetch();
        
        if (!$va) {
            return Response::error('VA not found', 404);
        }
        
        $colors = json_decode($va['colors'] ?? '[]', true);
        $owner = json_decode($va['owner_credentials'] ?? '{}', true);
        
        return Response::json([
            'va' => [
                'id' => $va['id'],
                'name' => $va['name'],
                'airline_name' => $va['airline_name'],
                'airline_website' => $va['airline_website'],
                'logo' => $va['logo'],
                'colors' => $colors,
                'created_at' => $va['created_at']
            ]
        ]);
    }
    
    /**
     * Update VA details
     * 
     * @param string $vaId VA identifier
     * @param array $data Update data
     * @return Response
     */
    public function updateVA(string $vaId, array $data): Response
    {
        // Validate data
        if (!isset($data['name'])) {
            return Response::error('Name is required', 400);
        }
        
        $sql = <<<'SQL'
UPDATE va SET name = ?, airline_name = ?, airline_website = ?, logo = ?,
    colors = ?, updated_at = datetime('now')
WHERE id = ?
SQL;
        
        try {
            $this->db->query($sql, [
                $data['name'],
                $data['airlineName'] ?? null,
                $data['website'] ?? null,
                $data['logo'] ?? null,
                json_encode($data['colors'] ?? []),
                $vaId
            ]);
            
            return Response::json([
                'success' => true,
                'message' => 'VA updated successfully'
            ]);
            
        } catch (\Exception $e) {
            return Response::error('Update failed', 500);
        }
    }
    
    /**
     * Delete VA
     * 
     * @param string $vaId VA identifier
     * @return Response
     */
    public function deleteVA(string $vaId): Response
    {
        try {
            $this->db->query('DELETE FROM va WHERE id = ?', [$vaId]);
            
            return Response::json([
                'success' => true,
                'message' => 'VA deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            return Response::error('Delete failed', 500);
        }
    }
}
