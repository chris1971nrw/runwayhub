<?php

declare(strict_types=1);

namespace RunwayHub\Modules\Home\Controllers;

use RunwayHub\Core\Controller;
use RunwayHub\Core\Database;
use RunwayHub\Core\UpdateChecker;
use RunwayHub\Core\Response;

class DashboardController extends Controller
{
    public function __construct(Database $db)
    {
        parent::__construct($db);
    }

    /**
     * Show dashboard with statistics
     */
    public function index(): Response
    {
        // Admin-Update-Check (nur Admin)
        $isAdmin = isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'];
        
        $updateInfo = [];
        $updateAvailable = false;
        
        if ($isAdmin) {
            $checker = new UpdateChecker();
            $updateAvailable = $checker->check();
            $updateInfo = $checker->getUpdateInfo();
            
            // Clear cache if requested
            if (isset($_GET['clear_cache'])) {
                $checker->clearCache();
                $updateInfo = $checker->getUpdateInfo();
            }
        }
        $flightStats = [
            'total' => 0,
            'today' => 0,
            'on_time' => 0,
            'late' => 0,
            'cancelled' => 0,
        ];
        
        // Gesamtflüge
        $flightStats['total'] = (int) ($this->db->fetchOne('SELECT COUNT(*) FROM flights') ?? 0) ?? 0;
        
        // Flüge heute
        $flightStats['today'] = (int) ($this->db->fetchOne('SELECT COUNT(*) FROM flights WHERE DATE(scheduled_departure) = CURDATE()') ?? 0) ?? 0;
        
        // Pünktliche Flüge (Beispielwert)
        $flightStats['on_time'] = (int) ($this->db->fetchOne('SELECT COUNT(*) FROM flights WHERE status = \'on-time\'') ?? 0) ?? 0;
        
        // Späte Flüge
        $flightStats['late'] = (int) ($this->db->fetchOne('SELECT COUNT(*) FROM flights WHERE status = \'late\'') ?? 0) ?? 0;
        
        // Abgesagte Flüge
        $flightStats['cancelled'] = (int) ($this->db->fetchOne('SELECT COUNT(*) FROM flights WHERE status = \'cancelled\'') ?? 0) ?? 0;

        // Flottenstatistiken
        $fleetStats = [
            'total' => 0,
            'active' => 0,
            'maintenance' => 0,
            'stored' => 0,
        ];
        
        $fleetStats['total'] = (int) ($this->db->fetchOne('SELECT COUNT(*) FROM aircrafts') ?? 0) ?? 0;
        $fleetStats['active'] = (int) ($this->db->fetchOne('SELECT COUNT(*) FROM aircrafts WHERE status = \'active\'') ?? 0) ?? 0;
        $fleetStats['maintenance'] = (int) ($this->db->fetchOne('SELECT COUNT(*) FROM aircrafts WHERE status = \'maintenance\'') ?? 0) ?? 0;
        $fleetStats['stored'] = (int) ($this->db->fetchOne('SELECT COUNT(*) FROM aircrafts WHERE status = \'stored\'') ?? 0) ?? 0;

        // Pilotenstatistiken
        $pilotStats = [
            'total' => 0,
            'active' => 0,
            'rest' => 0,
        ];
        
        $pilotStats['total'] = (int) ($this->db->fetchOne('SELECT COUNT(*) FROM pilots') ?? 0) ?? 0;
        $pilotStats['active'] = (int) ($this->db->fetchOne('SELECT COUNT(*) FROM pilots WHERE status = \'active\'') ?? 0) ?? 0;
        $pilotStats['rest'] = (int) ($this->db->fetchOne('SELECT COUNT(*) FROM pilots WHERE status = \'rest\'') ?? 0) ?? 0;

        // Buchungen heute
        $bookingStats = [
            'today' => 0,
            'this_week' => 0,
            'this_month' => 0,
        ];
        
        $bookingStats['today'] = (int) ($this->db->fetchOne('SELECT COUNT(*) FROM bookings WHERE DATE(created_at) = CURDATE()') ?? 0) ?? 0;
        $bookingStats['this_week'] = (int) ($this->db->fetchOne('SELECT COUNT(*) FROM bookings WHERE DATE(created_at) BETWEEN DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND CURDATE()') ?? 0) ?? 0;
        $bookingStats['this_month'] = (int) ($this->db->fetchOne('SELECT COUNT(*) FROM bookings WHERE DATE(created_at) BETWEEN DATE_SUB(CURDATE(), INTERVAL 30 DAY) AND CURDATE()') ?? 0) ?? 0;

        // Airlines
        $airlines = [
            'total' => 0,
            'active' => 0,
            'partners' => 0,
        ];
        
        $airlines['total'] = (int) ($this->db->fetchOne('SELECT COUNT(*) FROM airlines') ?? 0) ?? 0;
        $airlines['active'] = (int) ($this->db->fetchOne('SELECT COUNT(*) FROM airlines WHERE status = \'active\'') ?? 0) ?? 0;
        $airlines['partners'] = (int) ($this->db->fetchOne('SELECT COUNT(*) FROM airlines WHERE type = \'partner\'') ?? 0) ?? 0;

        return new Response(view('dashboard', [
            'stats' => [
                'flights' => $flightStats,
                'fleet' => $fleetStats,
                'pilots' => $pilotStats,
                'bookings' => $bookingStats,
                'airlines' => $airlines,
            ],
            'update' => [
                'isAdmin' => $isAdmin,
                'updateAvailable' => $updateAvailable,
                'updateInfo' => $updateInfo,
            ],
        ]), 200);
    }

    /**
     * Show live flights
     */
    public function live(): Response
    {
        $flights = $this->db->select('flights', 'ORDER BY scheduled_departure DESC LIMIT 50');
        
        return new Response(json_encode([
            'success' => true,
            'data' => $flights,
            'timestamp' => time(),
        ]), 200);
    }

    /**
     * Show recent bookings
     */
    public function recent(): Response
    {
        $bookings = $this->db->select('bookings', 'ORDER BY created_at DESC LIMIT 20');
        
        return new Response(json_encode([
            'success' => true,
            'data' => $bookings,
        ]), 200);
    }

    /**
     * Show recent PIREPs
     */
    public function pireps(): Response
    {
        $pireps = $this->db->select('pireps', 'ORDER BY reported_at DESC LIMIT 20');
        
        return new Response(json_encode([
            'success' => true,
            'data' => $pireps,
        ]), 200);
    }
}
