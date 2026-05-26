<?php

namespace RunwayHub\Core\OpenAIP;

use RunwayHub\Core\Database;

/**
 * Luftweg-Klasse für OpenAIP Integration
 *
 * Liest und verwaltet Luftweg-Daten aus OpenAIP API
 */
class Airway
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
     * @var array|null Caching für Luftweg-Daten
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
     * Hole alle Luftwege
     *
     * @param string $filters Filter-Kriterien
     * @param int $limit Maximale Anzahl
     * @return array Array von Luftweg-Daten
     */
    public function list(string $filters = '', int $limit = 1000): array
    {
        $url = $this->baseUrl . '/api/airways';
        
        if ($filters) {
            $url .= '?' . http_build_query(['filter' => $filters, 'limit' => $limit]);
        }

        try {
            $data = $this->apiCall($url);
            
            // Datenbank-Cache
            $this->cacheAirways($data);
            
            return $data ?? [];
        } catch (\Exception $e) {
            error_log("OpenAIP Airways API Error: " . $e->getMessage());
            return $this->getAirwaysFromDb();
        }
    }

    /**
     * Hole Details für einen einzelnen Luftweg
     *
     * @param int|string $id Luftweg-ID
     * @return array|null Luftweg-Details oder null bei Fehler
     */
    public function get(int|string $id): ?array
    {
        $url = $this->baseUrl . '/api/airways/' . $id;

        try {
            $data = $this->apiCall($url);
            
            // Datenbank-Cache
            $this->cacheAirway($data);
            
            return $data;
        } catch (\Exception $e) {
            error_log("OpenAIP Airway ID " . $id . " Error: " . $e->getMessage());
            return $this->getAirwayFromDb($id);
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
     * Cache Luftwege in Datenbank
     *
     * @param array $airways Array von Luftweg-Daten
     * @return void
     */
    private function cacheAirways(array $airways): void
    {
        if (empty($airways)) {
            return;
        }

        $this->db->query(
            'INSERT INTO airways_openaip (id, name, type, latitude, longitude, 
                     description, country, airport1, airport2)
             VALUES (:id, :name, :type, :latitude, :longitude, :description,
                     :country, :airport1, :airport2)
             ON CONFLICT(id) DO UPDATE SET
                 name = excluded.name,
                 type = excluded.type,
                 latitude = excluded.latitude,
                 longitude = excluded.longitude,
                 description = excluded.description,
                 country = excluded.country,
                 airport1 = excluded.airport1,
                 airport2 = excluded.airport2,
                 last_updated = CURRENT_TIMESTAMP'
            , [
                'id' => $airways[0]['id'] ?? 0,
                'name' => $airways[0]['name'] ?? '',
                'type' => $airways[0]['type'] ?? '',
                'latitude' => $airways[0]['latitude'] ?? 0.0,
                'longitude' => $airways[0]['longitude'] ?? 0.0,
                'description' => $airways[0]['description'] ?? '',
                'country' => $airways[0]['country'] ?? '',
                'airport1' => $airways[0]['airport1'] ?? '',
                'airport2' => $airways[0]['airport2'] ?? '',
            ]
        );
    }

    /**
     * Cache einzelnen Luftweg in Datenbank
     *
     * @param array $airway Luftweg-Daten
     * @return void
     */
    private function cacheAirway(array $airway): void
    {
        if (empty($airway)) {
            return;
        }

        $this->db->query(
            'INSERT INTO airways_openaip (id, name, type, latitude, longitude, 
                     description, country, airport1, airport2)
             VALUES (:id, :name, :type, :latitude, :longitude, :description,
                     :country, :airport1, :airport2)
             ON CONFLICT(id) DO UPDATE SET
                 name = excluded.name,
                 type = excluded.type,
                 latitude = excluded.latitude,
                 longitude = excluded.longitude,
                 description = excluded.description,
                 country = excluded.country,
                 airport1 = excluded.airport1,
                 airport2 = excluded.airport2,
                 last_updated = CURRENT_TIMESTAMP'
            , [
                'id' => $airway['id'] ?? 0,
                'name' => $airway['name'] ?? '',
                'type' => $airway['type'] ?? '',
                'latitude' => $airway['latitude'] ?? 0.0,
                'longitude' => $airway['longitude'] ?? 0.0,
                'description' => $airway['description'] ?? '',
                'country' => $airway['country'] ?? '',
                'airport1' => $airway['airport1'] ?? '',
                'airport2' => $airway['airport2'] ?? '',
            ]
        );
    }

    /**
     * Hole Luftwege aus lokaler Datenbank (Offline-Fallback)
     *
     * @return array
     */
    private function getAirwaysFromDb(): array
    {
        $stmt = $this->db->query(
            'SELECT id, name, type, latitude, longitude,
                     description, country, airport1, airport2
             FROM airways_openaip
             LIMIT 500'
        );
        
        return $stmt->fetchAll();
    }

    /**
     * Hole einzelnen Luftweg aus lokaler Datenbank
     *
     * @param int|string $id Luftweg-ID
     * @return array|null
     */
    private function getAirwayFromDb($id): ?array
    {
        $stmt = $this->db->query(
            'SELECT id, name, type, latitude, longitude,
                     description, country, airport1, airport2
             FROM airways_openaip
             WHERE id = :id',
            ['id' => $id]
        );
        
        return $stmt->fetch() ?: null;
    }

    /**
     * Synchronisiere alle Luftwege aus OpenAIP
     *
     * @return int Anzahl synchronisierter Luftwege
     */
    public function sync(): int
    {
        $allAirways = $this->list('', 10000);
        
        $synced = 0;
        foreach ($allAirways as $airway) {
            if ($this->cacheAirway($airway)) {
                $synced++;
            }
        }
        
        error_log("OpenAIP Sync: {$synced} Luftwege synchronisiert.");
        return $synced;
    }

    /**
     * Prüfe, ob OpenAIP-Sync nötig ist
     *
     * @return bool
     */
    public function needsSync(): bool
    {
        $stmt = $this->db->query('SELECT COUNT(*) as count FROM airways_openaip');
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
