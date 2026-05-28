<?php

declare(strict_types=1);

namespace RunwayHub\Api\Controller;

use RunwayHub\Core\Controller as BaseController;
use RunwayHub\Core\Request;
use RunwayHub\Core\Response;

class AircraftController extends BaseController
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
     * Get all aircrafts
     */
    public function index(): Response
    {
        // Get all aircrafts from database
        $aircrafts = $this->db->fetchAll('SELECT * FROM aircrafts ORDER BY type');

        return $this->response->contentType('application/json')->content(json_encode([
            'success' => true,
            'data' => $aircrafts,
            'count' => count($aircrafts),
        ]))->send();
    }

    /**
     * Get aircraft by registration
     */
    public function show(): Response
    {
        $registration = $this->request->getGet('registration');
        
        $aircraft = $this->db->fetchRow('SELECT * FROM aircrafts WHERE registration = ?', [$registration]);
        
        if (!$aircraft) {
            return $this->response->contentType('application/json')->content(json_encode([
                'success' => false,
                'error' => 'Aircraft not found',
            ]))->send();
        }
        
        // Get current flight for this aircraft
        $currentFlight = $this->db->fetchRow('SELECT * FROM flights WHERE aircraft_registration = ? ORDER BY departure_time DESC LIMIT 1', [$registration]);
        
        return $this->response->contentType('application/json')->content(json_encode([
            'success' => true,
            'data' => array_merge($aircraft, ['currentFlight' => $currentFlight]),
        ]))->send();
    }

    /**
     * Create new aircraft
     */
    public function create(): Response
    {
        $data = [
            'type' => $this->request->getGet('type'),
            'registration' => $this->request->getGet('registration'),
            'manufacturer' => $this->request->getGet('manufacturer'),
            'capacity' => $this->request->getInt('capacity'),
            'range' => $this->request->getInt('range'),
            'status' => $this->request->getGet('status') ?? 'active',
            'purchase_date' => $this->request->getGet('purchase_date') ?? date('Y-m-d'),
        ];

        $aircraftId = $this->db->fetchOne(
            "INSERT INTO aircrafts (type, registration, manufacturer, capacity, range, status, purchase_date) VALUES (?, ?, ?, ?, ?, ?, ?)",
            [$data['type'], $data['registration'], $data['manufacturer'], $data['capacity'], $data['range'], $data['status'], $data['purchase_date']]
        );

        $this->logger->info("Aircraft {$data['registration']} created", ['aircraft_id' => $aircraftId]);

        return $this->response->contentType('application/json')->content(json_encode([
            'success' => true,
            'aircraftId' => $aircraftId,
            'registration' => $data['registration'],
            'message' => 'Aircraft created successfully',
        ]))->send();
    }

    /**
     * Update aircraft
     */
    public function update(): Response
    {
        $registration = $this->request->getGet('registration');
        $data = $this->request->getPost();

        // Update aircraft
        $fields = [];
        foreach ($data as $key => $value) {
            if (!in_array($key, ['registration'])) {
                $fields[] = "{$key} = ?";
                $this->params[] = $value;
            }
        }

        $sql = "UPDATE aircrafts SET " . implode(', ', $fields) . " WHERE registration = ?";
        $this->db->execute($sql, array_merge($this->params, [$registration]));

        return $this->response->contentType('application/json')->content(json_encode([
            'success' => true,
            'registration' => $registration,
            'message' => 'Aircraft updated successfully',
        ]))->send();
    }

    /**
     * Delete aircraft
     */
    public function delete(): Response
    {
        $registration = $this->request->getGet('registration');
        
        // Check if aircraft is in use
        $inUse = $this->db->fetchOne("SELECT COUNT(*) as count FROM flights WHERE aircraft_registration = ?", [$registration]);
        
        if ($inUse['count'] > 0) {
            return $this->response->contentType('application/json')->content(json_encode([
                'success' => false,
                'error' => "Aircraft {$registration} is currently in use",
            ]))->send();
        }
        
        $this->db->execute("DELETE FROM aircrafts WHERE registration = ?", [$registration]);
        
        return $this->response->contentType('application/json')->content(json_encode([
            'success' => true,
            'registration' => $registration,
            'message' => 'Aircraft deleted successfully',
        ]))->send();
    }

    /**
     * Maintain aircraft
     */
    public function maintain(): Response
    {
        $registration = $this->request->getGet('registration');
        $maintenanceDate = $this->request->getGet('date');
        $maintenanceType = $this->request->getGet('type');
        
        // Insert maintenance record
        $id = $this->db->fetchOne(
            "INSERT INTO maintenance (aircraft_registration, date, type, notes) VALUES (?, ?, ?, ?)",
            [$registration, $maintenanceDate, $maintenanceType, $this->request->getGet('notes') ?? '']
        );

        // Update aircraft maintenance date
        $this->db->execute("UPDATE aircrafts SET next_maintenance = ?, last_maintenance = ? WHERE registration = ?",
            [strtotime($maintenanceDate), $maintenanceDate, $registration]);
        
        return $this->response->contentType('application/json')->content(json_encode([
            'success' => true,
            'aircraft' => $registration,
            'maintenance' => $maintenanceType,
            'message' => 'Maintenance scheduled successfully',
        ]))->send();
    }
}
