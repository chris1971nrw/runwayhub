<?php

namespace RunwayHub\Core\OpenAIP;

use RunwayHub\Core\Database;

/**
 * Flughafen-Klasse für OpenAIP Integration
 *
 * Liest und verwaltet Flughafen-Daten aus OpenAIP API
 */
class Airport
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
     * @var array|null Caching für Flughafen-Daten
     */
    private ?array $cache = null;

    /**
     * @var int Cache TTL in Sekunden (300 = 5 Minuten)
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
     * Hole alle Flughäfen oder filtere nach Parametern
     *
     * @param array $filters Filter-Kriterien (z.B. 'country=DE', 'type=airfield')
     * @param int $limit Maximale Anzahl
     * @return array Array von Flughafen-Daten
     */
    public function list(string $filters = '', int $limit = 1000): array
    {
        $url = $this->baseUrl . '/api/airports';
        
        if ($filters) {
            $url .= '?' . http_build_query(['filter' => $filters, 'limit' => $limit]);
        }

        try {
            $data = $this->apiCall($url);
            
            // Datenbank-Cache
            $this->cacheAirports($data);
            
            return $data ?? [];
        } catch (\Exception $e) {
            // Offline-Fallback: aus Datenbank
            error_log("OpenAIP Airport API Error: " . $e->getMessage());
            return $this->getAirportsFromDb();
        }
    }

    /**
     * Hole Details für einen einzelnen Flughafen
     *
     * @param int|string $id Flughafen-ID
     * @return array|null Flughafen-Details oder null bei Fehler
     */
    public function get(int|string $id): ?array
    {
        $url = $this->baseUrl . '/api/airports/' . ($id instanceof \DateTime ? $id->format('Y-m-d') : $id);

        try {
            $data = $this->apiCall($url);
            
            // Datenbank-Cache
            $this->cacheAirport($data);
            
            return $data;
        } catch (\Exception $e) {
            // Offline-Fallback: aus Datenbank
            error_log("OpenAIP Airport ID " . $id . " Error: " . $e->getMessage());
            return $this->getAirportFromDb($id);
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
     * Cache Flughäfen in Datenbank
     *
     * @param array $airports Array von Flughafen-Daten
     * @return void
     */
    private function cacheAirports(array $airports): void
    {
        if (empty($airports)) {
            return;
        }

        $this->db->query(
            'INSERT INTO airports_openaip (id, name, city, country, iata, icao, latitude, longitude, elevation, timezone, 
                     weather_station, traffic_pattern, customs, frequency, glideslope, localizer, navaid, 
                     gps_waypoint, airport_code, elevation_feet, elevation_meters, type, latitude_d, latitude_m, latitude_s, 
                     longitude_d, longitude_m, longitude_s, city_id, country_id)
             VALUES (:id, :name, :city, :country, :iata, :icao, :latitude, :longitude, :elevation, :timezone,
                     :weather_station, :traffic_pattern, :customs, :frequency, :glideslope, :localizer, :navaid,
                     :gps_waypoint, :airport_code, :elevation_feet, :elevation_meters, :type, 
                     :latitude_d, :latitude_m, :latitude_s, :longitude_d, :longitude_m, :longitude_s,
                     :city_id, :country_id)
             ON CONFLICT(id) DO UPDATE SET
                 name = excluded.name,
                 city = excluded.city,
                 country = excluded.country,
                 iata = excluded.iata,
                 icao = excluded.icao,
                 latitude = excluded.latitude,
                 longitude = excluded.longitude,
                 elevation = excluded.elevation,
                 timezone = excluded.timezone,
                 weather_station = excluded.weather_station,
                 traffic_pattern = excluded.traffic_pattern,
                 customs = excluded.customs,
                 frequency = excluded.frequency,
                 glideslope = excluded.glideslope,
                 localizer = excluded.localizer,
                 navaid = excluded.navaid,
                 gps_waypoint = excluded.gps_waypoint,
                 airport_code = excluded.airport_code,
                 elevation_feet = excluded.elevation_feet,
                 elevation_meters = excluded.elevation_meters,
                 type = excluded.type,
                 last_updated = CURRENT_TIMESTAMP'
            , [
                'id' => $airports[0]['id'] ?? 0,
                'name' => $airports[0]['name'] ?? '',
                'city' => $airports[0]['city'] ?? '',
                'country' => $airports[0]['country'] ?? '',
                'iata' => $airports[0]['iata'] ?? '',
                'icao' => $airports[0]['icao'] ?? '',
                'latitude' => $airports[0]['latitude'] ?? 0.0,
                'longitude' => $airports[0]['longitude'] ?? 0.0,
                'elevation' => $airports[0]['elevation'] ?? 0,
                'timezone' => $airports[0]['timezone'] ?? '',
                'weather_station' => $airports[0]['weather_station'] ?? '',
                'traffic_pattern' => $airports[0]['traffic_pattern'] ?? '',
                'customs' => $airports[0]['customs'] ?? '',
                'frequency' => $airports[0]['frequency'] ?? '',
                'glideslope' => $airports[0]['glideslope'] ?? '',
                'localizer' => $airports[0]['localizer'] ?? '',
                'navaid' => $airports[0]['navaid'] ?? '',
                'gps_waypoint' => $airports[0]['gps_waypoint'] ?? '',
                'airport_code' => $airports[0]['airport_code'] ?? '',
                'elevation_feet' => $airports[0]['elevation_feet'] ?? 0,
                'elevation_meters' => $airports[0]['elevation_meters'] ?? 0,
                'type' => $airports[0]['type'] ?? '',
            ]
        );
    }

    /**
     * Cache einzelnen Flughafen in Datenbank
     *
     * @param array $airport Flughafen-Daten
     * @return void
     */
    private function cacheAirport(array $airport): void
    {
        if (empty($airport)) {
            return;
        }

        $this->db->query(
            'INSERT INTO airports_openaip (id, name, city, country, iata, icao, latitude, longitude, elevation, timezone,
                     weather_station, traffic_pattern, customs, frequency, glideslope, localizer, navaid,
                     gps_waypoint, airport_code, elevation_feet, elevation_meters, type, latitude_d, latitude_m, latitude_s,
                     longitude_d, longitude_m, longitude_s, city_id, country_id)
             VALUES (:id, :name, :city, :country, :iata, :icao, :latitude, :longitude, :elevation, :timezone,
                     :weather_station, :traffic_pattern, :customs, :frequency, :glideslope, :localizer, :navaid,
                     :gps_waypoint, :airport_code, :elevation_feet, :elevation_meters, :type, 
                     :latitude_d, :latitude_m, :latitude_s, :longitude_d, :longitude_m, :longitude_s,
                     :city_id, :country_id)
             ON CONFLICT(id) DO UPDATE SET
                 name = excluded.name,
                 city = excluded.city,
                 country = excluded.country,
                 iata = excluded.iata,
                 icao = excluded.icao,
                 latitude = excluded.latitude,
                 longitude = excluded.longitude,
                 elevation = excluded.elevation,
                 timezone = excluded.timezone,
                 weather_station = excluded.weather_station,
                 traffic_pattern = excluded.traffic_pattern,
                 customs = excluded.customs,
                 frequency = excluded.frequency,
                 glideslope = excluded.glideslope,
                 localizer = excluded.localizer,
                 navaid = excluded.navaid,
                 gps_waypoint = excluded.gps_waypoint,
                 airport_code = excluded.airport_code,
                 elevation_feet = excluded.elevation_feet,
                 elevation_meters = excluded.elevation_meters,
                 type = excluded.type,
                 last_updated = CURRENT_TIMESTAMP'
            , [
                'id' => $airport['id'] ?? 0,
                'name' => $airport['name'] ?? '',
                'city' => $airport['city'] ?? '',
                'country' => $airport['country'] ?? '',
                'iata' => $airport['iata'] ?? '',
                'icao' => $airport['icao'] ?? '',
                'latitude' => $airport['latitude'] ?? 0.0,
                'longitude' => $airport['longitude'] ?? 0.0,
                'elevation' => $airport['elevation'] ?? 0,
                'timezone' => $airport['timezone'] ?? '',
                'weather_station' => $airport['weather_station'] ?? '',
                'traffic_pattern' => $airport['traffic_pattern'] ?? '',
                'customs' => $airport['customs'] ?? '',
                'frequency' => $airport['frequency'] ?? '',
                'glideslope' => $airport['glideslope'] ?? '',
                'localizer' => $airport['localizer'] ?? '',
                'navaid' => $airport['navaid'] ?? '',
                'gps_waypoint' => $airport['gps_waypoint'] ?? '',
                'airport_code' => $airport['airport_code'] ?? '',
                'elevation_feet' => $airport['elevation_feet'] ?? 0,
                'elevation_meters' => $airport['elevation_meters'] ?? 0,
                'type' => $airport['type'] ?? '',
            ]
        );
    }

    /**
     * Hole Flughäfen aus lokaler Datenbank (Offline-Fallback)
     *
     * @return array
     */
    private function getAirportsFromDb(): array
    {
        $stmt = $this->db->query(
            'SELECT id, name, city, country, iata, icao, latitude, longitude, elevation, timezone,
                     weather_station, traffic_pattern, customs, frequency, glideslope, localizer, navaid,
                     gps_waypoint, airport_code, elevation_feet, elevation_meters, type
             FROM airports_openaip
             LIMIT 500'
        );
        
        return $stmt->fetchAll();
    }

    /**
     * Hole einzelnen Flughafen aus lokaler Datenbank
     *
     * @param int|string $id Flughafen-ID
     * @return array|null
     */
    private function getAirportFromDb($id): ?array
    {
        $stmt = $this->db->query(
            'SELECT id, name, city, country, iata, icao, latitude, longitude, elevation, timezone,
                     weather_station, traffic_pattern, customs, frequency, glideslope, localizer, navaid,
                     gps_waypoint, airport_code, elevation_feet, elevation_meters, type
             FROM airports_openaip
             WHERE id = :id',
            ['id' => $id]
        );
        
        return $stmt->fetch() ?: null;
    }

    /**
     * Synchronisiere alle Flughäfen aus OpenAIP
     *
     * @return int Anzahl synchronisierter Flughäfen
     */
    public function sync(): int
    {
        $allAirports = $this->list('', 10000);
        
        $synced = 0;
        foreach ($allAirports as $airport) {
            if ($this->cacheAirport($airport)) {
                $synced++;
            }
        }
        
        error_log("OpenAIP Sync: {$synced} Flughäfen synchronisiert.");
        return $synced;
    }

    /**
     * Prüfe, ob OpenAIP-Sync nötig ist
     *
     * @return bool
     */
    public function needsSync(): bool
    {
        $stmt = $this->db->query('SELECT COUNT(*) as count FROM airports_openaip');
        $count = $stmt->fetch()['count'] ?? 0;
        
        // Hole aktuelle Anzahl aus OpenAIP
        try {
            $openaipCount = count($this->list('', 1));
            return $count < $openaipCount;
        } catch (\Exception $e) {
            error_log("OpenAIP Sync Check Error: " . $e->getMessage());
            return true; // Bei Fehler synchronisieren
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
        
        // Datenbank-Cache optional löschen
        // $this->db->query('DELETE FROM airports_openaip WHERE synced_at < NOW() - INTERVAL 1 DAY');
    }
}
