<?php

namespace App\Api\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\DemoAirline;
use App\Entity\DemoUser;
use App\Entity\DemoAircraft;
use App\Entity\DemoFlight;
use App\Entity\DemoPIREP;
use App\Entity\DemoBooking;

class DemoController extends AbstractController
{
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Demo Airline Info
     */
    #[Route('/api/demo/airline', name: 'api_demo_airline', methods: ['GET'])]
    public function airline(): JsonResponse
    {
        $airline = $this->entityManager->getRepository(DemoAirline::class)->findOneBy([
            'name' => 'DemoFly'
        ]);

        if (!$airline) {
            return new JsonResponse(
                ['error' => 'Demo Airline nicht gefunden'],
                Response::HTTP_NOT_FOUND
            );
        }

        return new JsonResponse([
            'success' => true,
            'data' => [
                'id' => $airline->getId(),
                'name' => $airline->getName(),
                'code' => $airline->getCode(),
                'iata' => $airline->getIata(),
                'icao' => $airline->getIcao(),
                'callsign' => $airline->getCallsign(),
                'country' => $airline->getCountry(),
                'logo' => $airline->getLogo(),
                'color' => $airline->getColor(),
                'is_active' => $airline->getIsActive(),
                'created_at' => $airline->getCreatedAt()?->format('Y-m-d H:i:s'),
                'updated_at' => $airline->getUpdatedAt()?->format('Y-m-d H:i:s'),
            ],
            'timestamp' => time(),
        ]);
    }

    /**
     * Demo User List
     */
    #[Route('/api/demo/users', name: 'api_demo_users', methods: ['GET'])]
    public function users(): JsonResponse
    {
        $users = $this->entityManager->getRepository(DemoUser::class)->findBy([], ['role' => 'ASC']);

        $userList = [];
        foreach ($users as $user) {
            $userList[] = [
                'id' => $user->getId(),
                'username' => $user->getUsername(),
                'email' => $user->getEmail(),
                'full_name' => $user->getFullName(),
                'role' => $user->getRole(),
                'is_active' => $user->getIsActive(),
                'airline' => [
                    'id' => $user->getAirline()?->getId(),
                    'name' => $user->getAirline()?->getName(),
                    'code' => $user->getAirline()?->getCode(),
                ],
                'type_ratings' => $user->getTypeRating(),
                'callsign' => $user->getCallsign(),
                'phone' => $user->getPhone(),
                'avatar' => $user->getAvatar(),
            ];
        }

        return new JsonResponse([
            'success' => true,
            'data' => $userList,
            'count' => count($userList),
            'timestamp' => time(),
        ]);
    }

    /**
     * Demo Flotte
     */
    #[Route('/api/demo/aircraft', name: 'api_demo_aircraft', methods: ['GET'])]
    public function aircraft(): JsonResponse
    {
        $aircraft = $this->entityManager->getRepository(DemoAircraft::class)->findBy([], ['registration' => 'ASC']);

        $fleetList = [];
        foreach ($aircraft as $a) {
            $fleetList[] = [
                'id' => $a->getId(),
                'type' => $a->getType(),
                'model' => $a->getModel(),
                'registration' => $a->getRegistration(),
                'manufacturer' => $a->getManufacturer(),
                'year' => $a->getYear(),
                'seats' => $a->getSeats(),
                'engine' => $a->getEngine(),
                'fuel' => $a->getFuel(),
                'range' => $a->getRange(),
                'max_altitude' => $a->getMaxAltitude(),
                'status' => $a->getStatus(),
                'last_maintenance' => $a->getLastMaintenance()?->format('Y-m-d'),
                'maintenance_interval' => $a->getMaintenanceInterval(),
                'airline' => [
                    'id' => $a->getAirline()?->getId(),
                    'name' => $a->getAirline()?->getName(),
                    'code' => $a->getAirline()?->getCode(),
                ],
            ];
        }

        return new JsonResponse([
            'success' => true,
            'data' => $fleetList,
            'count' => count($fleetList),
            'timestamp' => time(),
        ]);
    }

    /**
     * Demo Flüge
     */
    #[Route('/api/demo/flights', name: 'api_demo_flights', methods: ['GET'])]
    public function flights(): JsonResponse
    {
        $flights = $this->entityManager->getRepository(DemoFlight::class)->findBy([], ['flight_number' => 'ASC']);

        $flightList = [];
        foreach ($flights as $f) {
            $flightList[] = [
                'id' => $f->getId(),
                'flight_number' => $f->getFlightNumber(),
                'aircraft' => [
                    'id' => $f->getAircraft()?->getId(),
                    'registration' => $f->getAircraft()?->getRegistration(),
                    'model' => $f->getAircraft()?->getModel(),
                ],
                'pilot' => [
                    'id' => $f->getPilot()?->getId(),
                    'username' => $f->getPilot()?->getUsername(),
                    'callsign' => $f->getPilot()?->getCallsign(),
                ],
                'airline' => [
                    'id' => $f->getAirline()?->getId(),
                    'name' => $f->getAirline()?->getName(),
                    'code' => $f->getAirline()?->getCode(),
                ],
                'airport_from' => $f->getAirportFrom(),
                'airport_to' => $f->getAirportTo(),
                'route' => $f->getRoute(),
                'distance' => $f->getDistance(),
                'duration' => $f->getDuration(),
                'scheduled_date' => $f->getScheduledDate()?->format('Y-m-d'),
                'scheduled_time' => $f->getScheduledTime(),
                'departure_time' => $f->getDepartureTime()?->format('H:i'),
                'arrival_time' => $f->getArrivalTime()?->format('H:i'),
                'eta' => $f->getEta()?->format('Y-m-d H:i'),
                'eot' => $f->getEot()?->format('Y-m-d H:i'),
                'status' => $f->getStatus(),
                'price_economy' => $f->getPriceEconomy(),
                'price_business' => $f->getPriceBusiness(),
                'price_first' => $f->getPriceFirst(),
                'available_seats' => $f->getAvailableSeats(),
                'sold_seats' => $f->getSoldSeats(),
            ];
        }

        return new JsonResponse([
            'success' => true,
            'data' => $flightList,
            'count' => count($flightList),
            'timestamp' => time(),
        ]);
    }

    /**
     * Reset alle Demo-Daten
     */
    #[Route('/api/demo/reset', name: 'api_demo_reset', methods: ['POST'])]
    public function reset(): JsonResponse
    {
        try {
            // Lösche alle Demo-Daten
            $this->entityManager->getRepository(DemoAirline::class)->deleteAll();
            $this->entityManager->getRepository(DemoUser::class)->deleteAll();
            $this->entityManager->getRepository(DemoAircraft::class)->deleteAll();
            $this->entityManager->getRepository(DemoFlight::class)->deleteAll();
            $this->entityManager->getRepository(DemoPIREP::class)->deleteAll();
            $this->entityManager->getRepository(DemoBooking::class)->deleteAll();

            // Installiere neue Demo-Daten
            $this->entityManager->getRepository(DemoAirline::class)->up();
            $this->entityManager->getRepository(DemoUser::class)->up();
            $this->entityManager->getRepository(DemoAircraft::class)->up();
            $this->entityManager->getRepository(DemoFlight::class)->up();
            $this->entityManager->getRepository(DemoPIREP::class)->up();
            $this->entityManager->getRepository(DemoBooking::class)->up();

            return new JsonResponse([
                'success' => true,
                'message' => 'Demo-Daten wurden erfolgreich zurückgesetzt.',
                'timestamp' => time(),
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'error' => 'Fehler beim Zurücksetzen der Demo-Daten: ' . $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Demo PIREPs
     */
    #[Route('/api/demo/pireps', name: 'api_demo_pireps', methods: ['GET'])]
    public function pireps(): JsonResponse
    {
        $pireps = $this->entityManager->getRepository(DemoPIREP::class)->findBy([], ['created_at' => 'DESC']);

        $pirepList = [];
        foreach ($pireps as $p) {
            $pirepList[] = [
                'id' => $p->getId(),
                'flight_number' => $p->getFlightNumber(),
                'aircraft' => [
                    'registration' => $p->getAircraft()?->getRegistration(),
                    'model' => $p->getAircraft()?->getModel(),
                ],
                'pilot' => [
                    'id' => $p->getPilot()?->getId(),
                    'username' => $p->getPilot()?->getUsername(),
                    'callsign' => $p->getPilot()?->getCallsign(),
                ],
                'altitude' => $p->getAltitude(),
                'speed' => $p->getSpeed(),
                'fuel' => $p->getFuel(),
                'temperature' => $p->getTemperature(),
                'weather_conditions' => $p->getWeatherConditions(),
                'visibility' => $p->getVisibility(),
                'wind_speed' => $p->getWindSpeed(),
                'wind_direction' => $p->getWindDirection(),
                'turbulence' => $p->getTurbulence(),
                'icing' => $p->getIcing(),
                'comments' => $p->getComments(),
                'is_public' => $p->getIsPublic(),
                'status' => $p->getStatus(),
                'created_at' => $p->getCreatedAt()?->format('Y-m-d H:i:s'),
            ];
        }

        return new JsonResponse([
            'success' => true,
            'data' => $pirepList,
            'count' => count($pirepList),
            'timestamp' => time(),
        ]);
    }

    /**
     * Demo Bookings
     */
    #[Route('/api/demo/bookings', name: 'api_demo_bookings', methods: ['GET'])]
    public function bookings(): JsonResponse
    {
        $bookings = $this->entityManager->getRepository(DemoBooking::class)->findBy([], ['created_at' => 'DESC']);

        $bookingList = [];
        foreach ($bookings as $b) {
            $bookingList[] = [
                'id' => $b->getId(),
                'booking_number' => $b->getBookingNumber(),
                'flight' => [
                    'flight_number' => $b->getFlight()?->getFlightNumber(),
                    'airport_from' => $b->getFlight()?->getAirportFrom(),
                    'airport_to' => $b->getFlight()?->getAirportTo(),
                ],
                'passenger' => [
                    'name' => $b->getPassengerName(),
                    'email' => $b->getPassengerEmail(),
                    'type' => $b->getPassengerType(),
                ],
                'class' => $b->getClass(),
                'price' => $b->getPrice(),
                'tax' => $b->getTax(),
                'total' => $b->getTotal(),
                'payment_method' => $b->getPaymentMethod(),
                'payment_status' => $b->getPaymentStatus(),
                'status' => $b->getStatus(),
                'created_at' => $b->getCreatedAt()?->format('Y-m-d H:i:s'),
            ];
        }

        return new JsonResponse([
            'success' => true,
            'data' => $bookingList,
            'count' => count($bookingList),
            'timestamp' => time(),
        ]);
    }
}
