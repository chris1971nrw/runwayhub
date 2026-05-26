<?php

namespace RunwayHub\Core\OpenAIP;

use RunwayHub\Core\Database;

/**
 * Navigationshilfe-Klasse für OpenAIP Integration
 *
 * Liest und verwaltet Navigationshilfe-Daten (VOR, NDB, ILS, etc.) aus OpenAIP API
 */
class Navaid
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
     * @var array|null Caching für Navigationshilfe-Daten
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
     * Hole alle Navigationshilfen
     *
     * @param string $filters Filter-Kriterien (z.B. 'type=vor', 'type=ndb')
     * @param int $limit Maximale Anzahl
     * @return array Array von Navigationshilfe-Daten
     */
    public function list(string $filters = '', int $limit = 1000): array
    {
        $url = $this->baseUrl . '/api/navaids';
        
        if ($filters) {
            $url .= '?' . http_build_query(['filter' => $filters, 'limit' => $limit]);
        }

        try {
            $data = $this->apiCall($url);
            
            // Datenbank-Cache
            $this->cacheNavaids($data);
            
            return $data ?? [];
        } catch (\Exception $e) {
            error_log("OpenAIP Navaids API Error: " . $e->getMessage());
            return $this->getNavaidsFromDb();
        }
    }

    /**
     * Hole Details für eine einzelne Navigationshilfe
     *
     * @param int|string $id Navigationshilfe-ID
     * @return array|null Navigationshilfe-Details oder null bei Fehler
     */
    public function get(int|string $id): ?array
    {
        $url = $this->baseUrl . '/api/navaids/' . $id;

        try {
            $data = $this->apiCall($url);
            
            // Datenbank-Cache
            $this->cacheNavaid($data);
            
            return $data;
        } catch (\Exception $e) {
            error_log("OpenAIP Navaid ID " . $id . " Error: " . $e->getMessage());
            return $this->getNavaidFromDb($id);
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
     * Cache Navigationshilfen in Datenbank
     *
     * @param array $navaids Array von Navigationshilfe-Daten
     * @return void
     */
    private function cacheNavaids(array $navaids): void
    {
        if (empty($navaids)) {
            return;
        }

        $this->db->query(
            'INSERT INTO navaids_openaip (id, name, type, latitude, longitude, 
                     description, country, frequency, power, range)
             VALUES (:id, :name, :type, :latitude, :longitude, :description,
                     :country, :frequency, :power, :range)
             ON CONFLICT(id) DO UPDATE SET
                 name = excluded.name,
                 type = excluded.type,
                 latitude = excluded.latitude,
                 longitude = excluded.longitude,
                 description = excluded.description,
                 country = excluded.country,
                 frequency = excluded.frequency,
                 power = excluded.power,
                 range = excluded.range,
                 last_updated = CURRENT_TIMESTAMP'
            , [
                'id' => $navaids[0]['id'] ?? 0,
                'name' => $navaids[0]['name'] ?? '',
                'type' => $navaids[0]['type'] ?? '',
                'latitude' => $navaids[0]['latitude'] ?? 0.0,
                'longitude' => $navaids[0]['longitude'] ?? 0.0,
                'description' => $navaids[0]['description'] ?? '',
                'country' => $navaids[0]['country'] ?? '',
                'frequency' => $navaids[0]['frequency'] ?? '',
                'power' => $navaids[0]['power'] ?? '',
                'range' => $navaids[0]['range'] ?? '',
            ]
        );
    }

    /**
     * Cache einzelne Navigationshilfe in Datenbank
     *
     * @param array $navaid Navigationshilfe-Daten
     * @return void
     */
    private function cacheNavaid(array $navaid): void
    {
        if (empty($navaid)) {
            return;
        }

        $this->db->query(
            'INSERT INTO navaids_openaip (id, name, type, latitude, longitude, 
                     description, country, frequency, power, range)
             VALUES (:id, :name, :type, :latitude, :longitude, :description,
                     :country, :frequency, :power, :range)
             ON CONFLICT(id) DO UPDATE SET
                 name = excluded.name,
                 type = excluded.type,
                 latitude = excluded.latitude,
                 longitude = excluded.longitude,
                 description = excluded.description,
                 country = excluded.country,
                 frequency = excluded.frequency,
                 power = excluded.power,
                 range = excluded.range,
                 last_updated = CURRENT_TIMESTAMP'
            , [
                'id' => $navaid['id'] ?? 0,
                'name' => $navaid['name'] ?? '',
                'type' => $navaid['type'] ?? '',
                'latitude' => $navaid['latitude'] ?? 0.0,
                'longitude' => $navaid['longitude'] ?? 0.0,
                'description' => $navaid['description'] ?? '',
                'country' => $navaid['country'] ?? '',
                'frequency' => $navaid['frequency'] ?? '',
                'power' => $navaid['power'] ?? '',
                'range' => $navaid['range'] ?? '',
            ]
        );
    }

    /**
     * Hole Navigationshilfen aus lokaler Datenbank (Offline-Fallback)
     *
     * @return array
     */
    private function getNavaidsFromDb(): array
    {
        $stmt = $this->db->query(
            'SELECT id, name, type, latitude, longitude,
                     description, country, frequency, power, range
             FROM navaids_openaip
             LIMIT 500'
        );
        
        return $stmt->fetchAll();
    }

    /**
     * Hole einzelne Navigationshilfe aus lokaler Datenbank
     *
     * @param int|string $id Navigationshilfe-ID
     * @return array|null
     */
    private function getNavaidFromDb($id): ?array
    {
        $stmt = $this->db->query(
            'SELECT id, name, type, latitude, longitude,
                     description, country, frequency, power, range
             FROM navaids_openaip
             WHERE id = :id',
            ['id' => $id]
        );
        
        return $stmt->fetch() ?: null;
    }

    /**
     * Synchronisiere alle Navigationshilfen aus OpenAIP
     *
     * @return int Anzahl synchronisierter Navigationshilfen
     */
    public function sync(): int
    {
        $allNavaids = $this->list('', 10000);
        
        $synced = 0;
        foreach ($allNavaids as $navaid) {
            if ($this->cacheNavaid($navaid)) {
                $synced++;
            }
        }
        
        error_log("OpenAIP Sync: {$synced} Navigationshilfen synchronisiert.");
        return $synced;
    }

    /**
     * Prüfe, ob OpenAIP-Sync nötig ist
     *
     * @return bool
     */
    public function needsSync(): bool
    {
        $stmt = $this->db->query('SELECT COUNT(*) as count FROM navaids_openaip');
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
