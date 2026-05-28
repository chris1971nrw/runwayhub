<?php

declare(strict_types=1);

namespace RunwayHub\Core;

use RunwayHub\Core\Database;

/**
 * Update Checker
 * 
 * Integriert GitHub Releases für Versions-Checks.
 */
class UpdateChecker
{
    protected string $repository = 'chris1971nrw/runwayhub';
    protected string $currentVersion = '1.0.0';
    protected ?string $latestVersion = null;
    protected bool $hasUpdate = false;
    protected ?string $downloadUrl = null;
    protected ?string $releaseNotes = null;
    protected int $cacheTTL = 3600; // 1 hour
    protected string $lastCheck = '';

    public function __construct(?string $repository = null)
    {
        if ($repository) {
            $this->repository = $repository;
        }

        // Version aus APP_VERSION holen
        if (defined('APP_VERSION')) {
            $this->currentVersion = APP_VERSION;
        }
    }

    /**
     * Check for updates
     * 
     * @return bool
     */
    public function check(): bool
    {
        // Cache
        $cacheFile = __DIR__ . '/../../cache/github_update.json';
        if (file_exists($cacheFile)) {
            $age = time() - filemtime($cacheFile);
            if ($age < $this->cacheTTL) {
                $data = json_decode(file_get_contents($cacheFile), true);
                $this->latestVersion = $data['latest_version'] ?? null;
                $this->downloadUrl = $data['download_url'] ?? null;
                $this->releaseNotes = $data['body'] ?? null;
                $this->hasUpdate = version_compare($this->latestVersion, $this->currentVersion, '>');
                $this->lastCheck = $data['last_check'] ?? date('Y-m-d H:i:s');
                return $this->hasUpdate;
            }
        }

        // API request
        try {
            $client = new \GuzzleHttp\Client([
                'timeout' => 10,
                'headers' => ['User-Agent' => 'RunwayHub'],
            ]);

            $response = $client->get('https://api.github.com/repos/' . $this->repository . '/releases/latest', [
                'connect_timeout' => 5,
            ]);

            if ($response->getStatusCode() === 200) {
                $data = json_decode($response->getBody(), true);
                $this->latestVersion = substr($data['tag_name'] ?? '', 1);
                $this->releaseNotes = $data['body'] ?? null;
                
                // Cache
                file_put_contents($cacheFile, json_encode([
                    'latest_version' => $this->latestVersion,
                    'download_url' => $data['assets'][0]['browser_download_url'] ?? null,
                    'body' => $data['body'] ?? null,
                    'last_check' => date('Y-m-d H:i:s'),
                ]));

                $this->hasUpdate = version_compare($this->latestVersion, $this->currentVersion, '>');
                $this->downloadUrl = $data['assets'][0]['browser_download_url'] ?? null;
            }
        } catch (\Exception $e) {
            // Log error
        }

        $this->lastCheck = date('Y-m-d H:i:s');
        return $this->hasUpdate;
    }

    /**
     * Get update info
     * 
     * @return array
     */
    public function getUpdateInfo(): array
    {
        return [
            'current_version' => $this->currentVersion,
            'latest_version' => $this->latestVersion,
            'update_available' => $this->hasUpdate,
            'download_url' => $this->downloadUrl,
            'release_notes' => $this->releaseNotes,
            'last_check' => $this->lastCheck,
        ];
    }

    /**
     * Force update (ignore cache)
     * 
     * @return bool
     */
    public function forceCheck(): bool
    {
        $cacheFile = __DIR__ . '/../../cache/github_update.json';
        if (file_exists($cacheFile)) {
            unlink($cacheFile);
        }
        return $this->check();
    }

    /**
     * Clear cache
     * 
     * @return void
     */
    public function clearCache(): void
    {
        $cacheFile = __DIR__ . '/../../cache/github_update.json';
        if (file_exists($cacheFile)) {
            unlink($cacheFile);
        }
    }
}
