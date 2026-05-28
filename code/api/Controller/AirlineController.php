<?php

declare(strict_types=1);

namespace RunwayHub\Api\Controller;

use RunwayHub\Core\Controller as BaseController;
use RunwayHub\Core\Request;
use RunwayHub\Core\Response;
use RunwayHub\Core\Database;
use RunwayHub\Core\Middleware\Auth;

/**
 * Airline Controller
 * 
 * Handles airline management with admin authentication.
 * Admins have full access to create, read, update, delete airlines.
 */
class AirlineController extends BaseController
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
     * Get all airlines
     * 
     * @return Response
     */
    public function index(): Response
    {
        // Check admin access
        if ($this->auth && !$this->auth->isAdmin()) {
            return $this->response->contentType('application/json')->content(json_encode([
                'success' => false,
                'error' => 'Access denied. Admin privileges required.',
            ]))->send();
        }

        // Get all airlines
        $airlines = $this->db->fetchAll('
            SELECT * FROM airlines 
            ORDER BY name ASC
        ');

        return $this->response->contentType('application/json')->content(json_encode([
            'success' => true,
            'data' => $airlines,
            'count' => count($airlines),
        ]))->send();
    }

    /**
     * Get airline by ID
     * 
     * @return Response
     */
    public function show(): Response
    {
        // Check admin access
        if ($this->auth && !$this->auth->isAdmin()) {
            return $this->response->contentType('application/json')->content(json_encode([
                'success' => false,
                'error' => 'Access denied. Admin privileges required.',
            ]))->send();
        }

        $airlineId = $this->request->getInt('id');
        
        // Get airline
        $airline = $this->db->fetchRow('SELECT * FROM airlines WHERE id = ?', [$airlineId]);

        if (!$airline) {
            return $this->response->contentType('application/json')->content(json_encode([
                'success' => false,
                'error' => 'Airline not found',
            ]))->send();
        }

        return $this->response->contentType('application/json')->content(json_encode([
            'success' => true,
            'data' => $airline,
        ]))->send();
    }

    /**
     * Create new airline
     * 
     * @return Response
     */
    public function create(): Response
    {
        // Check admin access
        if ($this->auth && !$this->auth->isAdmin()) {
            return $this->response->contentType('application/json')->content(json_encode([
                'success' => false,
                'error' => 'Access denied. Admin privileges required.',
            ]))->send();
        }

        $data = [
            'name' => $this->request->getGet('name'),
            'iata_code' => $this->request->getGet('iata_code'),
            'icao_code' => $this->request->getGet('icao_code'),
            'callsign' => $this->request->getGet('callsign'),
            'country' => $this->request->getGet('country'),
            'logo_url' => $this->request->getGet('logo_url') ?? null,
            'website' => $this->request->getGet('website') ?? '',
            'status' => $this->request->getGet('status') ?? 'active',
        ];

        // Insert airline
        $airlineId = $this->db->fetchOne(
            "INSERT INTO airlines (name, iata_code, icao_code, callsign, country, logo_url, website, status) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
            [
                $data['name'],
                $data['iata_code'],
                $data['icao_code'],
                $data['callsign'],
                $data['country'],
                $data['logo_url'],
                $data['website'],
                $data['status'],
            ]
        );

        // Log action
        $this->logger->info("Airline created", [
            'airline_id' => $airlineId,
            'name' => $data['name'],
            'admin' => $this->auth->getUsername() ?? 'system',
        ]);

        return $this->response->contentType('application/json')->content(json_encode([
            'success' => true,
            'airlineId' => $airlineId,
            'name' => $data['name'],
            'iata_code' => $data['iata_code'],
            'message' => 'Airline created successfully',
        ]))->send();
    }

    /**
     * Update airline
     * 
     * @return Response
     */
    public function update(): Response
    {
        // Check admin access
        if ($this->auth && !$this->auth->isAdmin()) {
            return $this->response->contentType('application/json')->content(json_encode([
                'success' => false,
                'error' => 'Access denied. Admin privileges required.',
            ]))->send();
        }

        $airlineId = $this->request->getInt('id');
        $data = $this->request->getPost();

        if (empty($data)) {
            return $this->response->contentType('application/json')->content(json_encode([
                'success' => false,
                'error' => 'No data provided',
            ]))->send();
        }

        // Update airline
        $fields = [];
        $params = [];
        foreach ($data as $key => $value) {
            if ($key !== 'id') {
                $fields[] = "{$key} = ?";
                $params[] = $value;
            }
        }

        $sql = "UPDATE airlines SET " . implode(', ', $fields) . " WHERE id = ?";
        $params[] = $airlineId;
        $this->db->execute($sql, $params);

        // Log action
        $this->logger->info("Airline updated", [
            'airline_id' => $airlineId,
            'admin' => $this->auth->getUsername() ?? 'system',
        ]);

        return $this->response->contentType('application/json')->content(json_encode([
            'success' => true,
            'airlineId' => $airlineId,
            'message' => 'Airline updated successfully',
        ]))->send();
    }

    /**
     * Delete airline
     * 
     * @return Response
     */
    public function delete(): Response
    {
        // Check admin access
        if ($this->auth && !$this->auth->isAdmin()) {
            return $this->response->contentType('application/json')->content(json_encode([
                'success' => false,
                'error' => 'Access denied. Admin privileges required.',
            ]))->send();
        }

        $airlineId = $this->request->getInt('id');
        
        // Check if airline is in use
        $inUse = $this->db->fetchOne("SELECT COUNT(*) as count FROM flights WHERE airline_id = ?", [$airlineId]);

        if ($inUse['count'] > 0) {
            return $this->response->contentType('application/json')->content(json_encode([
                'success' => false,
                'error' => "Airline cannot be deleted (airline is currently in use)",
            ]))->send();
        }

        // Delete airline
        $this->db->execute("DELETE FROM airlines WHERE id = ?", [$airlineId]);

        // Log action
        $this->logger->info("Airline deleted", [
            'airline_id' => $airlineId,
            'admin' => $this->auth->getUsername() ?? 'system',
        ]);

        return $this->response->contentType('application/json')->content(json_encode([
            'success' => true,
            'airlineId' => $airlineId,
            'message' => 'Airline deleted successfully',
        ]))->send();
    }

    /**
     * Get airlines by country
     * 
     * @return Response
     */
    public function getByCountry(): Response
    {
        // Check admin access
        if ($this->auth && !$this->auth->isAdmin()) {
            return $this->response->contentType('application/json')->content(json_encode([
                'success' => false,
                'error' => 'Access denied. Admin privileges required.',
            ]))->send();
        }

        $country = $this->request->getGet('country');
        
        if (!$country) {
            return $this->response->contentType('application/json')->content(json_encode([
                'success' => false,
                'error' => 'Country required',
            ]))->send();
        }

        $airlines = $this->db->fetchAll('
            SELECT * FROM airlines 
            WHERE country = ? 
            ORDER BY name ASC
        ', [$country]);

        return $this->response->contentType('application/json')->content(json_encode([
            'success' => true,
            'data' => $airlines,
            'country' => $country,
            'count' => count($airlines),
        ]))->send();
    }

    /**
     * Get airlines by IATA code
     * 
     * @return Response
     */
    public function getByIata(): Response
    {
        // Check admin access
        if ($this->auth && !$this->auth->isAdmin()) {
            return $this->response->contentType('application/json')->content(json_encode([
                'success' => false,
                'error' => 'Access denied. Admin privileges required.',
            ]))->send();
        }

        $iata = $this->request->getGet('iata');
        
        $airline = $this->db->fetchRow('SELECT * FROM airlines WHERE iata_code = ?', [$iata]);

        if (!$airline) {
            return $this->response->contentType('application/json')->content(json_encode([
                'success' => false,
                'error' => 'Airline not found',
            ]))->send();
        }

        return $this->response->contentType('application/json')->content(json_encode([
            'success' => true,
            'data' => $airline,
        ]))->send();
    }
}
