<?php

namespace RunwayHub\Core\OpenAIP;

use RunwayHub\Core\Database;

/**
 * OpenAIP API Client
 *
 * Hauptklasse für die OpenAIP API Integration
 * Koordiniert alle API-Operationen und -Endpunkte
 */
class Client
{
    /**
     * @var string API-Key für OpenAIP
     */
    private string $apiKey;

    /**
     * @var string API-Base-URL
     */
    private string $baseUrl;

    /**
     * @var Database Datenbank-Instanz
     */
    private Database $db;

    /**
     * @var array Offene Sync-Jobs
     */
    private array $syncJobs = [];

    /**
     * @var bool Sync-Status
     */
    private bool $lastSyncSuccess = false;

    /**
     * @var int Letzte Sync-Zeitpunkt (Timestamp)
     */
    private int $lastSyncTime = 0;

    public function __construct()
    {
        $this->apiKey = getenv('OPENAIP_API_KEY') ?: '4eafd2e1740e235b937c362c0e3074f9';
        $this->baseUrl = 'https://www.openaip.net';
        $this->db = Database::getInstance();
    }

    /**
     * Initialisiere API-Verbindungen
     *
     * @return void
     */
    public function init(): void
    {
        // API-Verbindungen initialisieren
        curl_init();
    }

    /**
     * Hole alle Daten aus OpenAIP (Flughäfen, Wegpunkte, Luftwege, Navigationshilfen)
     *
     * @return array Array mit allen Daten
     */
    public function fetchAll(): array
    {
        $airports = new Airport();
        $waypoints = new Waypoint();
        $airways = new Airway();
        $navaids = new Navaid();

        $result = [
            'airports' => $airports->list('', 500),
            'waypoints' => $waypoints->list('', 500),
            'airways' => $airways->list('', 500),
            'navaids' => $navaids->list('', 500),
        ];

        return $result;
    }

    /**
     * Synchronisiere alle Daten aus OpenAIP
     *
     * @return array Sync-Ergebnis
     */
    public function sync(): array
    {
        $start = microtime(true);

        $result = [
            'airports' => 0,
            'waypoints' => 0,
            'airways' => 0,
            'navaids' => 0,
            'total' => 0,
            'duration' => 0,
        ];

        try {
            $airports = new Airport();
            $waypoints = new Waypoint();
            $airways = new Airway();
            $navaids = new Navaid();

            $result['airports'] = $airports->sync();
            $result['waypoints'] = $waypoints->sync();
            $result['airways'] = $airways->sync();
            $result['navaids'] = $navaids->sync();
            $result['total'] = $result['airports'] + $result['waypoints'] + $result['airways'] + $result['navaids'];

            $this->lastSyncSuccess = true;
            $this->lastSyncTime = time();

            error_log("OpenAIP Sync completed: {$result['total']} records synced in " . 
                      number_format((microtime(true) - $start) * 1000, 2) . " ms");
        } catch (\Exception $e) {
            error_log("OpenAIP Sync Error: " . $e->getMessage());
            $this->lastSyncSuccess = false;
            $result['error'] = $e->getMessage();
        }

        $result['duration'] = (microtime(true) - $start) * 1000;

        return $result;
    }

    /**
     * Prüfe, ob ein Sync nötig ist
     *
     * @return bool
     */
    public function needsSync(): bool
    {
        // Sync nur wenn älter als 24 Stunden
        if ($this->lastSyncTime > 0 && (time() - $this->lastSyncTime) < 86400) {
            return false;
        }

        $airports = new Airport();
        if ($airports->needsSync()) {
            return true;
        }

        $waypoints = new Waypoint();
        if ($waypoints->needsSync()) {
            return true;
        }

        $airways = new Airway();
        if ($airways->needsSync()) {
            return true;
        }

        $navaids = new Navaid();
        if ($navaids->needsSync()) {
            return true;
        }

        return false;
    }

    /**
     * Hole Flughafen-Daten
     *
     * @param int|string $id Flughafen-ID oder ICAO
     * @return array|null
     */
    public function getAirport(int|string $id): ?array
    {
        $airport = new Airport();
        return $airport->get($id);
    }

    /**
     * Hole alle Flughäfen
     *
     * @param string $filters Filter
     * @param int $limit Limit
     * @return array
     */
    public function getAirports(string $filters = '', int $limit = 1000): array
    {
        $airport = new Airport();
        return $airport->list($filters, $limit);
    }

    /**
     * Hole Wegpunkt-Daten
     *
     * @param int|string $id Wegpunkt-ID
     * @return array|null
     */
    public function getWaypoint(int|string $id): ?array
    {
        $waypoint = new Waypoint();
        return $waypoint->get($id);
    }

    /**
     * Hole alle Wegpunkte
     *
     * @param string $filters Filter
     * @param int $limit Limit
     * @return array
     */
    public function getWaypoints(string $filters = '', int $limit = 1000): array
    {
        $waypoint = new Waypoint();
        return $waypoint->list($filters, $limit);
    }

    /**
     * Hole Luftweg-Daten
     *
     * @param int|string $id Luftweg-ID
     * @return array|null
     */
    public function getAirway(int|string $id): ?array
    {
        $airway = new Airway();
        return $airway->get($id);
    }

    /**
     * Hole alle Luftwege
     *
     * @param string $filters Filter
     * @param int $limit Limit
     * @return array
     */
    public function getAirways(string $filters = '', int $limit = 1000): array
    {
        $airway = new Airway();
        return $airway->list($filters, $limit);
    }

    /**
     * Hole Navigationshilfe-Daten
     *
     * @param int|string $id Navaid-ID
     * @return array|null
     */
    public function getNavaid(int|string $id): ?array
    {
        $navaid = new Navaid();
        return $navaid->get($id);
    }

    /**
     * Hole alle Navigationshilfen
     *
     * @param string $filters Filter
     * @param int $limit Limit
     * @return array
     */
    public function getNavaids(string $filters = '', int $limit = 1000): array
    {
        $navaid = new Navaid();
        return $navaid->list($filters, $limit);
    }

    /**
     * Hole Luftraum-Daten (falls verfügbar)
     *
     * @param int|string $id Luftraum-ID
     * @return array|null
     */
    public function getAirspace(int|string $id): ?array
    {
        // Implementierung falls OpenAIP Lufträume unterstützt
        error_log("Airspace API not implemented yet");
        return null;
    }

    /**
     * Hole alle Lufträume (falls verfügbar)
     *
     * @param string $filters Filter
     * @param int $limit Limit
     * @return array
     */
    public function getAirspaces(string $filters = '', int $limit = 1000): array
    {
        error_log("Airspace API not implemented yet");
        return [];
    }

    /**
     * Lösche Sync-Cache
     *
     * @return void
     */
    public function clearCache(): void
    {
        $airports = new Airport();
        $waypoints = new Waypoint();
        $airways = new Airway();
        $navaids = new Navaid();

        $airports->clearCache();
        $waypoints->clearCache();
        $airways->clearCache();
        $navaids->clearCache();

        // Datenbank-Cache optional löschen
        // $this->db->query('DELETE FROM airports_openaip WHERE synced_at < NOW() - INTERVAL 1 DAY');
        // $this->db->query('DELETE FROM waypoints_openaip WHERE synced_at < NOW() - INTERVAL 1 DAY');
        // $this->db->query('DELETE FROM airways_openaip WHERE synced_at < NOW() - INTERVAL 1 DAY');
        // $this->db->query('DELETE FROM navaids_openaip WHERE synced_at < NOW() - INTERVAL 1 DAY');
    }

    /**
     * Hole Sync-Status
     *
     * @return array
     */
    public function getSyncStatus(): array
    {
        return [
            'last_sync' => $this->lastSyncTime,
            'last_sync_success' => $this->lastSyncSuccess,
            'last_sync_duration' => $this->lastSyncTime ? 0 : null,
        ];
    }

    /**
     * Hole OpenAIP API Version/Status
     *
     * @return array
     */
    public function apiInfo(): array
    {
        $url = $this->baseUrl . '/api/info';
        
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Accept: application/json',
            ]);

            $response = curl_exec($ch);
            curl_close($ch);

            if ($response) {
                return json_decode($response, true) ?: [];
            }
        } catch (\Exception $e) {
            error_log("OpenAIP API Info Error: " . $e->getMessage());
        }

        return [];
    }
}
