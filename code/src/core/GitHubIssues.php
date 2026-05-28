<?php

declare(strict_types=1);

namespace RunwayHub\Core;

use GuzzleHttp\Client;

/**
 * GitHub Issues API
 * 
 * Erstellt Issues mit automatischem Logfile-Anhang.
 */
class GitHubIssues
{
    protected string $repository = 'chris1971nrw/runwayhub';
    protected Client $client;
    protected ?string $issueBody = null;
    protected ?string $attachment = null;
    protected ?string $title = null;
    protected ?string $labels = null;
    protected int $timeout = 30;

    public function __construct(?string $repository = null)
    {
        if ($repository) {
            $this->repository = $repository;
        }

        $this->client = new Client([
            'timeout' => $this->timeout,
            'headers' => [
                'User-Agent' => 'RunwayHub Issue Reporter',
            ],
        ]);
    }

    /**
     * Create issue with attachment
     * 
     * @param string $title Issue Titel
     * @param string $body Issue Beschreibung
     * @param string $logfile Pfad zum Logfile
     * @param array $labels Issue-Labels
     * @return array|bool
     */
    public function createIssue(string $title, string $body, string $logfile = null, array $labels = ['bug']): array|bool
    {
        // Logfile lesen
        $attachment = null;
        
        if ($logfile && file_exists($logfile)) {
            try {
                $attachment = file_get_contents($logfile);
            } catch (\Exception $e) {
                // Fehler beim Lesen ignorieren
            }
        }

        if ($attachment === false) {
            $attachment = null;
        }

        // Issue Body
        $timestamp = date('Y-m-d H:i:s');
        $gitInfo = `git rev-parse --short HEAD 2>/dev/null || echo 'unknown'`;
        $commitDate = `git log -1 --format="%ci" 2>/dev/null || echo 'unknown'`;

        $body = "### Issue-Beschreibung\n\n" . $body . "\n\n" .
                "### System-Info\n\n" .
                "- **Repository:** {$this->repository}\n" .
                "- **Commit:** {$gitInfo}\n" .
                "- **Commit-Datum:** {$commitDate}\n\n" .
                "### Logfile\n\n" .
                "```\n" . $attachment . "\n```\n\n" .
                "### Nächste Schritte\n\n" .
                "Bitte prüfen Sie den Logfile und geben Sie weitere Informationen.\n";

        // GitHub API
        try {
            $response = $this->client->post('https://api.github.com/repos/' . $this->repository . '/issues', [
                'json' => [
                    'title' => $title,
                    'body' => $body,
                    'labels' => $labels,
                ],
            ]);

            if ($response->getStatusCode() === 201) {
                $issueData = json_decode($response->getBody(), true);
                
                return [
                    'success' => true,
                    'issue_url' => $issueData['html_url'],
                    'issue_number' => $issueData['number'],
                    'title' => $issueData['title'],
                    'message' => 'Issue erstellt!',
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }

        return false;
    }

    /**
     * Get issue status
     * 
     * @param int $issueNumber
     * @return array
     */
    public function getIssueStatus(int $issueNumber): array
    {
        try {
            $response = $this->client->get('https://api.github.com/repos/' . $this->repository . '/issues/' . $issueNumber);

            if ($response->getStatusCode() === 200) {
                return json_decode($response->getBody(), true);
            }
        } catch (\Exception $e) {
            //
        }

        return ['success' => false, 'error' => 'Issue nicht gefunden'];
    }
}
