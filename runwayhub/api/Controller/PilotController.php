<?php

declare(strict_types=1);

namespace RunwayHub\Api\Controller;

use RunwayHub\Core\Controller as BaseController;
use RunwayHub\Core\Request;
use RunwayHub\Core\Response;
use RunwayHub\Core\Database;

class PilotController extends BaseController
{
    protected Request $request;
    protected Response $response;
    protected Database $db;

    public function __construct(Request $request, Response $response, Database $db)
    {
        $this->request = $request;
        $this->response = $response;
        $this->db = $db;
    }

    /**
     * Get all pilots
     */
    public function index(): Response
    {
        // Get all pilots from database
        $pilots = $this->db->fetchAll('SELECT * FROM pilots WHERE status = "active" ORDER BY surname');

        return $this->response->contentType('application/json')->content(json_encode([
            'success' => true,
            'data' => $pilots,
            'count' => count($pilots),
        ]))->send();
    }

    /**
     * Get pilot by ID or callsign
     */
    public function show(): Response
    {
        $pilotId = $this->request->getGet('id');
        
        // Support both ID and callsign
        $pilot = $this->db->fetchRow('SELECT * FROM pilots WHERE (id = ? OR callsign = ?)', [$pilotId, $pilotId]);
        
        if (!$pilot) {
            return $this->response->contentType('application/json')->content(json_encode([
                'success' => false,
                'error' => 'Pilot not found',
            ]))->send();
        }
        
        // Get recent flights for this pilot
        $recentFlights = $this->db->fetchAll(
            'SELECT * FROM flights WHERE pilot_id = ? ORDER BY departure_time DESC LIMIT 5',
            [$pilot['id']]
        );
        
        return $this->response->contentType('application/json')->content(json_encode([
            'success' => true,
            'data' => $pilot,
            'recentFlights' => $recentFlights,
        ]))->send();
    }

    /**
     * Create new pilot
     */
    public function create(): Response
    {
        $data = [
            'first_name' => $this->request->getGet('first_name'),
            'surname' => $this->request->getGet('surname'),
            'callsign' => $this->request->getGet('callsign'),
            'license_type' => $this->request->getGet('license_type'),
            'license_number' => $this->request->getGet('license_number'),
            'email' => $this->request->getGet('email'),
            'phone' => $this->request->getGet('phone'),
            'rating' => $this->request->getGet('rating') ?? '1',
            'status' => 'active',
            'hire_date' => $this->request->getGet('hire_date') ?? date('Y-m-d'),
        ];

        $pilotId = $this->db->fetchOne(
            "INSERT INTO pilots (first_name, surname, callsign, license_type, license_number, email, phone, rating, status, hire_date) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [$data['first_name'], $data['surname'], $data['callsign'], $data['license_type'], $data['license_number'], 
             $data['email'], $data['phone'], $data['rating'], $data['status'], $data['hire_date']]
        );

        // Generate login credentials
        $credentials = $this->generateCredentials($data['surname']);
        $data['password'] = $credentials['password'];
        $data['username'] = $credentials['username'];
        $data['reset_token'] = $credentials['reset_token'];
        
        // Store in database
        $this->db->execute(
            "UPDATE pilots SET password = ?, username = ?, reset_token = ? WHERE id = ?",
            [$data['password'], $data['username'], $data['reset_token'], $pilotId]
        );

        return $this->response->contentType('application/json')->content(json_encode([
            'success' => true,
            'pilotId' => $pilotId,
            'callsign' => $data['callsign'],
            'username' => $data['username'],
            'message' => 'Pilot created successfully',
        ]))->send();
    }

    /**
     * Update pilot
     */
    public function update(): Response
    {
        $pilotId = $this->request->getInt('pilot');
        $data = $this->request->getPost();

        // Validate
        if (empty($data)) {
            return $this->response->contentType('application/json')->content(json_encode([
                'success' => false,
                'error' => 'No data provided',
            ]))->send();
        }

        // Update pilot
        $fields = [];
        $params = [];
        foreach ($data as $key => $value) {
            if (!in_array($key, ['pilot', 'password'])) {
                $fields[] = "{$key} = ?";
                $params[] = $value;
            } elseif ($key === 'password') {
                $fields[] = "password = ?";
                $params[] = $this->passwordHash($value);
            }
        }

        $sql = "UPDATE pilots SET " . implode(', ', $fields) . " WHERE id = ?";
        $params[] = $pilotId;
        $this->db->execute($sql, $params);

        return $this->response->contentType('application/json')->content(json_encode([
            'success' => true,
            'pilotId' => $pilotId,
            'message' => 'Pilot updated successfully',
        ]))->send();
    }

    /**
     * Deactivate pilot
     */
    public function deactivate(): Response
    {
        $pilotId = $this->request->getInt('pilot');
        
        $this->db->execute("UPDATE pilots SET status = 'inactive' WHERE id = ?", [$pilotId]);
        
        return $this->response->contentType('application/json')->content(json_encode([
            'success' => true,
            'message' => 'Pilot deactivated successfully',
        ]))->send();
    }

    /**
     * Assign pilot to flight
     */
    public function assignToFlight(): Response
    {
        $flightNumber = $this->request->getGet('flight');
        $pilotId = $this->request->getInt('pilot');
        
        // Check pilot availability
        $nextFlight = $this->db->fetchRow(
            "SELECT departure_time FROM flights WHERE pilot_id = ? AND departure_time > ? ORDER BY departure_time ASC LIMIT 1",
            [$pilotId, date('Y-m-d H:i:s')]
        );
        
        if ($nextFlight && strtotime($nextFlight['departure_time']) < strtotime("+2 hours")) {
            return $this->response->contentType('application/json')->content(json_encode([
                'success' => false,
                'error' => "Pilot {$pilotId} is not available (next flight in " . 
                         (strtotime($nextFlight['departure_time']) - strtotime("+2 hours")) . " minutes)",
            ]))->send();
        }
        
        // Assign pilot to flight
        $this->db->execute(
            "UPDATE flights SET pilot_id = ? WHERE flight_number = ?",
            [$pilotId, $flightNumber]
        );
        
        // Record in pilot history
        $this->db->execute(
            "INSERT INTO pilot_history (pilot_id, flight_number, departure_time, notes) VALUES (?, ?, ?, ?)",
            [$pilotId, $flightNumber, date('Y-m-d H:i:s'), $this->request->getGet('notes') ?? '']
        );
        
        return $this->response->contentType('application/json')->content(json_encode([
            'success' => true,
            'flight' => $flightNumber,
            'pilot' => $pilotId,
            'message' => 'Pilot assigned successfully',
        ]))->send();
    }

    /**
     * Generate credentials for new pilot
     */
    private function generateCredentials(string $surname): array
    {
        // Generate secure password
        $password = password_hash('pilot' . $surname . '_' . time(), PASSWORD_DEFAULT);
        
        // Generate username
        $username = strtolower($surname . '.' . substr(mt_rand(1000, 9999), -3));
        
        // Generate reset token
        $reset_token = bin2hex(random_bytes(32));
        
        return [
            'password' => $password,
            'username' => $username,
            'reset_token' => $reset_token,
        ];
    }

    /**
     * Hash password
     */
    private function passwordHash(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
}
