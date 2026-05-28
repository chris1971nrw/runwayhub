<?php
declare(strict_types=1);

namespace RunwayHub\Modules\Airlines\Controllers;

use RunwayHub\Core\Controller as BaseController;
use RunwayHub\Core\Response;

class AdminController extends BaseController
{
    private string $locale;

    public function __construct(Database $db)
    {
        parent::__construct($db);
        $this->locale = getLocale();
    }

    public function index(): Response
    {
        // Dashboard-Statistiken
        $stats = [
            'total_flights' => 0,
            'total_aircrafts' => 0,
            'total_pilots' => 0,
            'today_bookings' => 0,
        ];

        // Flugstatistik
        $stats['total_flights'] = (int) ($this->db->query('SELECT COUNT(*) FROM flights') ?? [0])[0] ?? 0;

        // Flotte
        $stats['total_aircrafts'] = (int) ($this->db->query('SELECT COUNT(*) FROM aircrafts') ?? [0])[0] ?? 0;

        // Piloten
        $stats['total_pilots'] = (int) ($this->db->query('SELECT COUNT(*) FROM pilots') ?? [0])[0] ?? 0;

        // Buchungen heute
        $stats['today_bookings'] = (int) ($this->db->query('SELECT COUNT(*) FROM bookings WHERE DATE(created_at) = CURDATE()') ?? [0])[0] ?? 0;

        // Übersetzungen laden
        $translations = require __DIR__ . '/../../../i18n/' . $this->locale . '/messages.php';

        return new Response(view(
            'dashboard',
            [
                'stats' => $stats,
                'translations' => $translations['admin'] ?? [],
                'messages' => $translations['messages'] ?? [],
            ]
        ), 200);
    }

    public function users(): Response
    {
        $users = $this->db->select('users');
        $translations = require __DIR__ . '/../../../i18n/' . $this->locale . '/messages.php';

        return new Response(json_encode([
            'success' => true,
            'data' => $users,
            'messages' => $translations['messages'] ?? [],
        ]), 200);
    }

    public function flights(): Response
    {
        $flights = $this->db->select('flights', 'ORDER BY scheduled_departure DESC');
        $translations = require __DIR__ . '/../../../i18n/' . $this->locale . '/messages.php';

        return new Response(json_encode([
            'success' => true,
            'data' => $flights,
            'messages' => $translations['messages'] ?? [],
        ]), 200);
    }
}
