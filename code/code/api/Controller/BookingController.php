<?php

declare(strict_types=1);

namespace RunwayHub\Api\Controller;

use RunwayHub\Core\Controller as BaseController;
use RunwayHub\Core\Request;
use RunwayHub\Core\Response;
use RunwayHub\Core\Database;

class BookingController extends BaseController
{
    protected Request $request;
    protected Response $response;
    protected Database $db;
    protected FlightTrackingService $acarsService;

    public function __construct(Request $request, Response $response, Database $db, FlightTrackingService $acarsService = null)
    {
        $this->request = $request;
        $this->response = $response;
        $this->db = $db;
        $this->acarsService = $acarsService;
    }

    /**
     * Get all bookings
     */
    public function index(): Response
    {
        $page = $this->request->getInt('page') ?? 1;
        $limit = $this->request->getInt('limit') ?? 10;
        
        // Get bookings with pagination
        $bookings = $this->db->fetchAll(
            "SELECT * FROM bookings ORDER BY created_at DESC LIMIT ? OFFSET ?",
            [$limit, ($page - 1) * $limit]
        );
        
        $totalCount = $this->db->fetchOne("SELECT COUNT(*) as count FROM bookings")->count;
        
        return $this->response->contentType('application/json')->content(json_encode([
            'success' => true,
            'data' => $bookings,
            'pagination' => [
                'page' => $page,
                'limit' => $limit,
                'total' => $totalCount,
                'pages' => (int)ceil($totalCount / $limit),
            ],
        ]))->send();
    }

    /**
     * Get booking by ID
     */
    public function show(): Response
    {
        $bookingId = $this->request->getInt('id');
        
        $booking = $this->db->fetchRow('SELECT * FROM bookings WHERE id = ?', [$bookingId]);
        
        if (!$booking) {
            return $this->response->contentType('application/json')->content(json_encode([
                'success' => false,
                'error' => 'Booking not found',
            ]))->send();
        }
        
        // Get flight and passenger details
        $booking['flight'] = $this->db->fetchRow('SELECT * FROM flights WHERE flight_number = ?', [$booking['flight_number']]);
        $booking['passengers'] = $this->db->fetchAll("SELECT * FROM passengers WHERE booking_id = ?", [$bookingId]);
        
        // ACARS Integration - Real-time flight status
        if ($this->acarsService && $booking['flight']) {
            $acarsData = $this->acarsService->getFlightStatus($booking['flight']['flight_number']);
            $booking['flight']['acarsStatus'] = $acarsData;
        }
        
        return $this->response->contentType('application/json')->content(json_encode([
            'success' => true,
            'data' => $booking,
        ]))->send();
    }

    /**
     * Create new booking
     */
    public function create(): Response
    {
        // Validate request
        $flightNumber = $this->request->getGet('flight');
        $passengerEmail = $this->request->getGet('email');
        $passengerName = $this->request->getGet('name');
        $passengerSeats = $this->request->getPost('seats');
        
        // Get flight details
        $flight = $this->db->fetchRow('SELECT * FROM flights WHERE flight_number = ?', [$flightNumber]);
        
        if (!$flight) {
            return $this->response->contentType('application/json')->content(json_encode([
                'success' => false,
                'error' => 'Flight not found',
            ]))->send();
        }
        
        // Check seat availability
        if ($passengerSeats) {
            $seats = is_array($passengerSeats) ? $passengerSeats : explode(',', $passengerSeats);
            
            foreach ($seats as $seat) {
                $available = $this->db->fetchOne(
                    "SELECT COUNT(*) as count FROM seats WHERE flight_number = ? AND seat_number = ? AND booking_id IS NULL",
                    [$flight['flight_number'], $seat]
                );
                
                if ($available['count'] === 0) {
                    return $this->response->contentType('application/json')->content(json_encode([
                        'success' => false,
                        'error' => "Seat {$seat} is not available for flight {$flight['flight_number']}",
                    ]))->send();
                }
            }
        }
        
        // Create booking
        $bookingId = $this->db->fetchOne(
            "INSERT INTO bookings (flight_number, passenger_email, passenger_name, seats, status, created_at) 
             VALUES (?, ?, ?, ?, 'confirmed', ?)",
            [$flightNumber, $passengerEmail, $passengerName, $passengerSeats ?? 'All', date('Y-m-d H:i:s')]
        );
        
        // Create passenger record
        if ($passengerName) {
            $this->db->execute(
                "INSERT INTO passengers (booking_id, name, email, phone) VALUES (?, ?, ?, ?)",
                [$bookingId, $passengerName, $passengerEmail, $this->request->getGet('phone') ?? '']
            );
        }
        
        // Log booking
        $this->logger->info("Booking created", [
            'booking_id' => $bookingId,
            'flight' => $flightNumber,
            'passenger' => $passengerName,
        ]);
        
        // Send confirmation email (if enabled)
        if (getenv('SMTP_ENABLED')) {
            $this->sendConfirmationEmail($bookingId, $passengerEmail, $passengerName);
        }
        
        return $this->response->contentType('application/json')->content(json_encode([
            'success' => true,
            'bookingId' => $bookingId,
            'flight' => $flight['flight_number'],
            'message' => 'Booking created successfully',
        ]))->send();
    }

    /**
     * Update booking
     */
    public function update(): Response
    {
        $bookingId = $this->request->getInt('id');
        $data = $this->request->getPost();
        
        // Validate
        if (empty($data)) {
            return $this->response->contentType('application/json')->content(json_encode([
                'success' => false,
                'error' => 'No data provided',
            ]))->send();
        }
        
        // Update booking
        $fields = [];
        $params = [];
        foreach ($data as $key => $value) {
            if (!in_array($key, ['id'])) {
                $fields[] = "{$key} = ?";
                $params[] = $value;
            }
        }
        
        $sql = "UPDATE bookings SET " . implode(', ', $fields) . " WHERE id = ?";
        $params[] = $bookingId;
        $this->db->execute($sql, $params);
        
        // If flight changed, update ACARS
        $newFlight = $data['flight_number'] ?? null;
        if ($newFlight) {
            $flight = $this->db->fetchRow('SELECT * FROM flights WHERE flight_number = ?', [$newFlight]);
            if ($this->acarsService && $flight) {
                $acarsData = $this->acarsService->getFlightStatus($newFlight);
                $data['acarsData'] = $acarsData;
            }
        }
        
        return $this->response->contentType('application/json')->content(json_encode([
            'success' => true,
            'bookingId' => $bookingId,
            'message' => 'Booking updated successfully',
        ]))->send();
    }

    /**
     * Cancel booking
     */
    public function cancel(): Response
    {
        $bookingId = $this->request->getInt('id');
        
        // Get booking
        $booking = $this->db->fetchRow('SELECT * FROM bookings WHERE id = ?', [$bookingId]);
        
        if (!$booking) {
            return $this->response->contentType('application/json')->content(json_encode([
                'success' => false,
                'error' => 'Booking not found',
            ]))->send();
        }
        
        // Update status
        $this->db->execute("UPDATE bookings SET status = 'cancelled', cancelled_at = ? WHERE id = ?",
            [date('Y-m-d H:i:s'), $bookingId]);
        
        // If booking has a flight, update flight passenger count
        if ($booking['flight_number']) {
            $this->db->execute(
                "UPDATE passengers SET booking_id = NULL WHERE booking_id = ?",
                [$bookingId]
            );
        }
        
        // Send cancellation email
        if ($booking['passenger_email']) {
            $this->sendCancellationEmail($booking['passenger_email'], $booking['passenger_name'], $booking['flight_number']);
        }
        
        return $this->response->contentType('application/json')->content(json_encode([
            'success' => true,
            'bookingId' => $bookingId,
            'message' => 'Booking cancelled successfully',
        ]))->send();
    }

    /**
     * Check seat availability
     */
    public function checkAvailability(): Response
    {
        $flightNumber = $this->request->getGet('flight');
        $seats = $this->request->getPost('seats');
        
        $flight = $this->db->fetchRow('SELECT * FROM flights WHERE flight_number = ?', [$flightNumber]);
        
        if (!$flight) {
            return $this->response->contentType('application/json')->content(json_encode([
                'success' => false,
                'error' => 'Flight not found',
            ]))->send();
        }
        
        // Check seat availability
        $availableSeats = [];
        if ($seats) {
            foreach ($seats as $seat => $class) {
                $seatClass = is_string($seat) ? $seat : 'economy';
                $available = $this->db->fetchOne(
                    "SELECT COUNT(*) as count FROM seats WHERE flight_number = ? AND seat_number = ? AND class = ? AND booking_id IS NULL",
                    [$flightNumber, $seat, $class]
                );
                
                if ($available['count'] > 0) {
                    $availableSeats[$seat] = [
                        'class' => $class,
                        'available' => true,
                    ];
                }
            }
        }
        
        return $this->response->contentType('application/json')->content(json_encode([
            'success' => true,
            'flight' => $flightNumber,
            'availableSeats' => $availableSeats,
            'totalCount' => count($availableSeats),
        ]))->send();
    }

    /**
     * Send confirmation email
     */
    private function sendConfirmationEmail(int $bookingId, string $email, string $name): void
    {
        // Get booking details
        $booking = $this->db->fetchRow('SELECT * FROM bookings WHERE id = ?', [$bookingId]);
        $flight = $this->db->fetchRow('SELECT * FROM flights WHERE flight_number = ?', [$booking['flight_number']]);
        
        if (!$flight) {
            return;
        }
        
        // TODO: Implement email sending
        // $this->mailer->send($email, 'Booking Confirmation', $template)
        $this->logger->info("Booking confirmation email prepared", [
            'email' => $email,
            'booking' => $bookingId,
            'flight' => $flight['flight_number'],
        ]);
    }

    /**
     * Send cancellation email
     */
    private function sendCancellationEmail(string $email, string $name, string $flightNumber): void
    {
        // TODO: Implement email sending
        $this->logger->info("Booking cancellation email prepared", [
            'email' => $email,
            'passenger' => $name,
            'flight' => $flightNumber,
        ]);
    }
}
