<?php

declare(strict_types=1);

namespace RunwayHub\Core;

use GuzzleHttp\Client;

/**
 * GitHub Release Checker
 * 
 * Prüft auf neue Releases und Updates.
 */
class GitHubRelease
{
    protected string $repository = 'chris1971nrw/runwayhub';
    protected int $version = 1;
    protected ?string $latestVersion = null;
    protected ?string $downloadUrl = null;
    protected bool $hasUpdate = false;
    protected string $lastCheck = '';

    /**
     * Constructor
     * 
     * @param string|null $repository GitHub-Repository (optional)
     * @param int $version Aktuelle Version (optional)
     */
    public function __construct(?string $repository = null, int $version = 1)
    {
        if ($repository) {
            $this->repository = $repository;
        }
        
        // Version aus APP_VERSION holen
        if (defined('APP_VERSION')) {
            $this->version = APP_VERSION;
        }
    }

    /**
     * Check for updates
     * 
     * @return bool
     */
    public function checkForUpdates(): bool
    {
        // Cache-Key
        $cacheKey = 'github_release_' . $this->repository;
        $cacheDir = __DIR__ . '/../../cache';
        
        // Cache-Verzeichnis erstellen
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0755, true);
        }

        $cacheFile = $cacheDir . '/' . $cacheKey . '.json';

        // Check ob cache existiert
        if (file_exists($cacheFile)) {
            $fileTime = filemtime($cacheFile);
            $cacheAge = time() - $fileTime;
            
            // Cache 1 Stunde alt
            if ($cacheAge < 3600) {
                $cacheData = json_decode(file_get_contents($cacheFile), true);
                $this->latestVersion = $cacheData['tag_name'] ?? null;
                $this->downloadUrl = $cacheData['assets'][0]['browser_download_url'] ?? null;
                $this->lastCheck = date('Y-m-d H:i:s', $fileTime);
                
                $this->hasUpdate = version_compare($this->latestVersion, $this->version, '>');
                
                return $this->hasUpdate;
            }
        }

        // GitHub API aufrufen
        try {
            $client = new Client([
                'timeout' => 10,
                'headers' => [
                    'User-Agent' => 'RunwayHub',
                ],
            ]);

            $response = $client->get('https://api.github.com/repos/' . $this->repository . '/releases/latest', [
                'connect_timeout' => 5,
                'timeout' => 10,
            ]);

            if ($response->getStatusCode() === 200) {
                $data = json_decode($response->getBody(), true);
                
                // Extract version number
                $tag = $data['tag_name'] ?? null;
                
                if ($tag) {
                    // Remove 'v' prefix if present
                    $versionPrefix = $tag[0] === 'v' ? 0 : 1;
                    $this->latestVersion = substr($tag, $versionPrefix);
                    
                    // Save to cache
                    file_put_contents($cacheFile, json_encode($data), LOCK_EX);
                    
                    // Check for update
                    $this->hasUpdate = version_compare($this->latestVersion, $this->version, '>');
                }
            }
        } catch (\Exception $e) {
            // Log error
            $this->logger->error('GitHub API Error', ['message' => $e->getMessage()]);
        }

        $this->lastCheck = date('Y-m-d H:i:s');
        return $this->hasUpdate;
    }

    /**
     * Get latest version
     * 
     * @return string|null
     */
    public function getLatestVersion(): ?string
    {
        return $this->latestVersion;
    }

    /**
     * Get download URL
     * 
     * @return string|null
     */
    public function getDownloadUrl(): ?string
    {
        return $this->downloadUrl;
    }

    /**
     * Get update info
     * 
     * @return array
     */
    public function getUpdateInfo(): array
    {
        return [
            'current_version' => $this->version,
            'latest_version' => $this->latestVersion,
            'update_available' => $this->hasUpdate,
            'download_url' => $this->downloadUrl,
            'last_check' => $this->lastCheck,
        ];
    }

    /**
     * Force update (ignore cache)
     * 
     * @return bool
     */
    public function forceUpdate(): bool
    {
        // Remove cache
        $cacheFile = $cacheDir . '/github_release_' . $this->repository . '.json';
        
        if (file_exists($cacheFile)) {
            unlink($cacheFile);
        }

        return $this->checkForUpdates();
    }

    /**
     * Clear cache
     * 
     * @return void
     */
    public function clearCache(): void
    {
        $cacheFile = $cacheDir . '/github_release_' . $this->repository . '.json';
        if (file_exists($cacheFile)) {
            unlink($cacheFile);
        }
    }

    /**
     * Get cache TTL
     * 
     * @return int
     */
    public function getCacheTTL(): int
    {
        return 3600; // 1 hour
    }
}
