<?php

namespace RunwayHub\Core\OpenAIP;

use RunwayHub\Core\Database;

/**
 * Wegpunkt-Klasse für OpenAIP Integration
 *
 * Liest und verwaltet Wegpunkt-Daten aus OpenAIP API
 */
class Waypoint
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
     * @var array|null Caching für Wegpunkt-Daten
     */
    private ?array $cache = null;

    /**
     * @var int Cache TTL in Sekunden
     */
    private int $cacheTtl = 300;

    /**
     * @var Database Datenbank-Instanz
     */
    private Database $db;

    public function __construct()
    {
        $this->apiKey = getenv('OPENAIP_API_KEY') ?: '4eafd2e1740e235b937c362c0e3074f9';
        $this->baseUrl = 'https://www.openaip.net';
        $this->db = Database::getInstance();
    }

    /**
     * Hole alle Wegpunkte
     *
     * @param string $filters Filter-Kriterien
     * @param int $limit Maximale Anzahl
     * @return array Array von Wegpunkt-Daten
     */
    public function list(string $filters = '', int $limit = 1000): array
    {
        $url = $this->baseUrl . '/api/waypoints';
        
        if ($filters) {
            $url .= '?' . http_build_query(['filter' => $filters, 'limit' => $limit]);
        }

        try {
            $data = $this->apiCall($url);
            
            // Datenbank-Cache
            $this->cacheWaypoints($data);
            
            return $data ?? [];
        } catch (\Exception $e) {
            error_log("OpenAIP Waypoints API Error: " . $e->getMessage());
            return $this->getWaypointsFromDb();
        }
    }

    /**
     * Hole Details für einen einzelnen Wegpunkt
     *
     * @param int|string $id Wegpunkt-ID
     * @return array|null Wegpunkt-Details oder null bei Fehler
     */
    public function get(int|string $id): ?array
    {
        $url = $this->baseUrl . '/api/waypoints/' . $id;

        try {
            $data = $this->apiCall($url);
            
            // Datenbank-Cache
            $this->cacheWaypoint($data);
            
            return $data;
        } catch (\Exception $e) {
            error_log("OpenAIP Waypoint ID " . $id . " Error: " . $e->getMessage());
            return $this->getWaypointFromDb($id);
        }
    }

    /**
     * API-Aufruf mit Header
     *
     * @param string $url
     * @param string $method HTTP-Method
     * @return array|null JSON-Daten oder null bei Fehler
     */
    private function apiCall(string $url, string $method = 'GET'): ?array
    {
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        
        // API-Key Header
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->apiKey,
            'Accept: application/json',
            'User-Agent: RunwayHub/1.0'
        ]);

        curl_setopt($ch, CURLOPT_HTTPREQUEST, true);

        if ($method !== 'GET') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if ($httpCode === 0) {
            throw new \RuntimeException('cURL Error: ' . curl_error($ch));
        }

        if ($httpCode !== 200) {
            error_log("OpenAIP API HTTP Error {$httpCode}: " . $url);
            return null;
        }

        curl_close($ch);

        return json_decode($response, true);
    }

    /**
     * Cache Wegpunkte in Datenbank
     *
     * @param array $waypoints Array von Wegpunkt-Daten
     * @return void
     */
    private function cacheWaypoints(array $waypoints): void
    {
        if (empty($waypoints)) {
            return;
        }

        $this->db->query(
            'INSERT INTO waypoints_openaip (id, name, type, latitude, longitude, country, 
                     description, navaid, airport, elevation, frequency)
             VALUES (:id, :name, :type, :latitude, :longitude, :country, :description,
                     :navaid, :airport, :elevation, :frequency)
             ON CONFLICT(id) DO UPDATE SET
                 name = excluded.name,
                 type = excluded.type,
                 latitude = excluded.latitude,
                 longitude = excluded.longitude,
                 country = excluded.country,
                 description = excluded.description,
                 navaid = excluded.navaid,
                 airport = excluded.airport,
                 elevation = excluded.elevation,
                 frequency = excluded.frequency,
                 last_updated = CURRENT_TIMESTAMP'
            , [
                'id' => $waypoints[0]['id'] ?? 0,
                'name' => $waypoints[0]['name'] ?? '',
                'type' => $waypoints[0]['type'] ?? '',
                'latitude' => $waypoints[0]['latitude'] ?? 0.0,
                'longitude' => $waypoints[0]['longitude'] ?? 0.0,
                'country' => $waypoints[0]['country'] ?? '',
                'description' => $waypoints[0]['description'] ?? '',
                'navaid' => $waypoints[0]['navaid'] ?? '',
                'airport' => $waypoints[0]['airport'] ?? '',
                'elevation' => $waypoints[0]['elevation'] ?? 0,
                'frequency' => $waypoints[0]['frequency'] ?? '',
            ]
        );
    }

    /**
     * Cache einzelnen Wegpunkt in Datenbank
     *
     * @param array $waypoint Wegpunkt-Daten
     * @return void
     */
    private function cacheWaypoint(array $waypoint): void
    {
        if (empty($waypoint)) {
            return;
        }

        $this->db->query(
            'INSERT INTO waypoints_openaip (id, name, type, latitude, longitude, country, 
                     description, navaid, airport, elevation, frequency)
             VALUES (:id, :name, :type, :latitude, :longitude, :country, :description,
                     :navaid, :airport, :elevation, :frequency)
             ON CONFLICT(id) DO UPDATE SET
                 name = excluded.name,
                 type = excluded.type,
                 latitude = excluded.latitude,
                 longitude = excluded.longitude,
                 country = excluded.country,
                 description = excluded.description,
                 navaid = excluded.navaid,
                 airport = excluded.airport,
                 elevation = excluded.elevation,
                 frequency = excluded.frequency,
                 last_updated = CURRENT_TIMESTAMP'
            , [
                'id' => $waypoint['id'] ?? 0,
                'name' => $waypoint['name'] ?? '',
                'type' => $waypoint['type'] ?? '',
                'latitude' => $waypoint['latitude'] ?? 0.0,
                'longitude' => $waypoint['longitude'] ?? 0.0,
                'country' => $waypoint['country'] ?? '',
                'description' => $waypoint['description'] ?? '',
                'navaid' => $waypoint['navaid'] ?? '',
                'airport' => $waypoint['airport'] ?? '',
                'elevation' => $waypoint['elevation'] ?? 0,
                'frequency' => $waypoint['frequency'] ?? '',
            ]
        );
    }

    /**
     * Hole Wegpunkte aus lokaler Datenbank (Offline-Fallback)
     *
     * @return array
     */
    private function getWaypointsFromDb(): array
    {
        $stmt = $this->db->query(
            'SELECT id, name, type, latitude, longitude, country,
                     description, navaid, airport, elevation, frequency
             FROM waypoints_openaip
             LIMIT 500'
        );
        
        return $stmt->fetchAll();
    }

    /**
     * Hole einzelnen Wegpunkt aus lokaler Datenbank
     *
     * @param int|string $id Wegpunkt-ID
     * @return array|null
     */
    private function getWaypointFromDb($id): ?array
    {
        $stmt = $this->db->query(
            'SELECT id, name, type, latitude, longitude, country,
                     description, navaid, airport, elevation, frequency
             FROM waypoints_openaip
             WHERE id = :id',
            ['id' => $id]
        );
        
        return $stmt->fetch() ?: null;
    }

    /**
     * Synchronisiere alle Wegpunkte aus OpenAIP
     *
     * @return int Anzahl synchronisierter Wegpunkte
     */
    public function sync(): int
    {
        $allWaypoints = $this->list('', 10000);
        
        $synced = 0;
        foreach ($allWaypoints as $waypoint) {
            if ($this->cacheWaypoint($waypoint)) {
                $synced++;
            }
        }
        
        error_log("OpenAIP Sync: {$synced} Wegpunkte synchronisiert.");
        return $synced;
    }

    /**
     * Prüfe, ob OpenAIP-Sync nötig ist
     *
     * @return bool
     */
    public function needsSync(): bool
    {
        $stmt = $this->db->query('SELECT COUNT(*) as count FROM waypoints_openaip');
        $count = $stmt->fetch()['count'] ?? 0;
        
        try {
            $openaipCount = count($this->list('', 1));
            return $count < $openaipCount;
        } catch (\Exception $e) {
            error_log("OpenAIP Sync Check Error: " . $e->getMessage());
            return true;
        }
    }

    /**
     * Lösche lokalen Cache
     *
     * @return void
     */
    public function clearCache(): void
    {
        $this->cache = null;
    }
}
