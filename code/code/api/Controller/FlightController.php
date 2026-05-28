<?php

declare(strict_types=1);

namespace RunwayHub\Api\Controller;

use RunwayHub\Core\Controller as BaseController;
use RunwayHub\Core\Request;
use RunwayHub\Core\Response;
use RunwayHub\Services\FlightTrackingService;

class FlightController extends BaseController
{
    protected FlightTrackingService $acarsService;

    public function __construct(Request $request, Response $response, FlightTrackingService $acarsService)
    {
        parent::__construct($request, $response);
        $this->acarsService = $acarsService;
    }

    /**
     * Get flight by flight number
     */
    public function getFlight(): Response
    {
        $flightNumber = $this->request->getGet('number') ?? 'LH456';
        
        // Query database
        $flight = $this->db->fetchRow('SELECT * FROM flights WHERE flight_number = ?', [$flightNumber]);
        
        if (!$flight) {
            return $this->response->contentType('application/json')->content(json_encode([
                'success' => false,
                'error' => 'Flight not found',
            ]))->send();
        }
        
        // ACARS Integration - Real-time status
        if ($this->acarsService) {
            $acarsData = $this->acarsService->getFlightStatus($flight['number']);
            $flight['acarsStatus'] = $acarsData;
        }
        
        $this->response->contentType('application/json')->content(json_encode([
            'success' => true,
            'data' => $flight,
        ]))->send();
        
        return $this->response;
    }

    /**
     * Get all flights
     */
    public function getAll(): Response
    {
        // Get all flights from database
        $flights = $this->db->fetchAll('SELECT * FROM flights ORDER BY departure_time');
        
        $this->response->contentType('application/json')->content(json_encode([
            'success' => true,
            'data' => $flights,
        ]))->send();
        
        return $this->response;
    }

    /**
     * Create new flight
     */
    public function createFlight(): Response
    {
        // Get flight data
        $flightNumber = $this->request->getGet('number');
        $origin = $this->request->getGet('origin');
        $destination = $this->request->getGet('destination');
        $departureTime = $this->request->getGet('departure');
        $aircraft = $this->request->getGet('aircraft') ?? null;
        
        // Insert into database
        $flightId = $this->db->fetchOne(
            "INSERT INTO flights (flight_number, origin, destination, departure_time, aircraft) VALUES (?, ?, ?, ?, ?)",
            [$flightNumber, $origin, $destination, $departureTime, $aircraft]
        );
        
        // Log flight creation
        $this->logger->info("Flight {$flightNumber} created", [
            'flight_id' => $flightId,
            'origin' => $origin,
            'destination' => $destination,
        ]);
        
        return $this->response->contentType('application/json')->content(json_encode([
            'success' => true,
            'flightNumber' => $flightNumber,
            'message' => 'Flight created successfully',
        ]))->send();
    }

    /**
     * Update flight status
     */
    public function updateStatus(): Response
    {
        $flightNumber = $this->request->getGet('number');
        $status = $this->request->getGet('status');
        
        // Update status in database
        $this->db->execute(
            "UPDATE flights SET status = ?, eta = ? WHERE flight_number = ?",
            [$status, $this->request->getGet('eta') ?? null, $flightNumber]
        );
        
        // Update ACARS if available
        if ($this->acarsService) {
            $acarsStatus = ['status' => $status, 'timestamp' => time()];
            $this->acarsService->updateFlightStatus($flightNumber, $acarsStatus);
        }
        
        return $this->response->contentType('application/json')->content(json_encode([
            'success' => true,
            'flightNumber' => $flightNumber,
            'status' => $status,
        ]))->send();
    }

    /**
     * Delete flight
     */
    public function deleteFlight(): Response
    {
        $flightNumber = $this->request->getGet('number');
        
        // Delete flight from database
        $this->db->execute("DELETE FROM flights WHERE flight_number = ?", [$flightNumber]);
        
        return $this->response->contentType('application/json')->content(json_encode([
            'success' => true,
            'message' => "Flight {$flightNumber} deleted",
        ]))->send();
    }

    /**
     * Get flight history
     */
    public function getHistory(): Response
    {
        $flightNumber = $this->request->getGet('number');
        $days = $this->request->getInt('days') ?? 30;
        
        // Get flight history
        $history = $this->db->fetchAll(
            "SELECT * FROM flight_history WHERE flight_number = ? AND created_at >= ? ORDER BY created_at DESC",
            [$flightNumber, date('Y-m-d', strtotime("-{$days} days"))]
        );
        
        return $this->response->contentType('application/json')->content(json_encode([
            'success' => true,
            'flightNumber' => $flightNumber,
            'history' => $history,
        ]))->send();
    }
}
