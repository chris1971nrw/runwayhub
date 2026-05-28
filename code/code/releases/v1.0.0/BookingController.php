<?php

declare(strict_types=1);

namespace RunwayHub\Api\Controller;

use RunwayHub\Core\Controller;
use RunwayHub\Core\Request;
use RunwayHub\Core\Response;

class BookingController extends Controller
{
    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
    }

    public function getBookings(Response $response): Response
    {
        $response->contentType('application/json');
        $response->content(json_encode([
            'success' => true,
            'data' => [
                'bookings' => [
                    [
                        'booking_id' => 'BK001',
                        'flight' => 'LH1234',
                        'passenger' => 'John Doe',
                        'seat' => '12A',
                        'class' => 'Economy',
                        'price' => 350.00,
                        'status' => 'confirmed',
                    ],
                ],
            ],
        ]));
        $response->send();
        return $response;
    }

    public function getBooking(Response $response): Response
    {
        $bookingId = $this->request->get('booking_id');
        
        if (empty($bookingId)) {
            $response->status(400);
            $response->contentType('application/json');
            $response->content(json_encode([
                'success' => false,
                'error' => true,
                'message' => 'Missing booking_id parameter',
            ]));
            $response->send();
            return $response;
        }

        $response->contentType('application/json');
        $response->content(json_encode([
            'success' => true,
            'data' => [
                'booking' => [
                    'booking_id' => $bookingId,
                    'flight' => 'LH1234',
                    'passenger' => 'John Doe',
                    'seat' => '12A',
                    'class' => 'Economy',
                    'price' => 350.00,
                    'status' => 'confirmed',
                ],
            ],
        ]));
        $response->send();
        return $response;
    }
}
