<?php

namespace RunwayHub\Modules\Demo\Controllers;

use Exception;

/**
 * Feedback-Controller für Demo-System
 * 
 * Erstellt GitHub Issues aus Feedback-Meldungen
 * 
 * @package RunwayHub\Modules\Demo\Controllers
 */
class FeedbackController
{
    /**
     * GitHub API Token
     * @var string
     */
    private string $githubToken = '';

    /**
     * GitHub Owner
     * @var string
     */
    private string $githubOwner = 'chris1971nrw';

    /**
     * Repository
     * @var string
     */
    private string $githubRepo = 'runwayhub';

    /**
     * Issue Label für Demo-Feedback
     * @var string
     */
    private const ISSUE_LABEL = 'demo-feedback';

    /**
     * Konstruktor
     * 
     * @param string $githubToken GitHub API Token
     */
    public function __construct(string $githubToken = '')
    {
        $this->githubToken = $githubToken ?: getenv('GITHUB_TOKEN') ?: 'ghp_demo_token';
    }

    /**
     * Feedback-Eintrag verarbeiten
     * 
     * @param array $data Feedback-Daten
     * @param string|null $screenshotDatei Screenshot-Datei
     * @return array['success' => bool, 'issue_url' => string|null, 'message' => string]
     * @throws Exception
     */
    public function submit(array $data, ?string $screenshotDatei = null): array
    {
        try {
            // Validierung
            if (!isset($data['title']) || !isset($data['description'])) {
                throw new Exception('Titel und Beschreibung sind erforderlich.');
            }

            // Screenshot-Upload verarbeiten
            if ($screenshotDatei && is_file($screenshotDatei)) {
                $screenshotData = file_get_contents($screenshotDatei);
                $screenshotB64 = base64_encode($screenshotData);
                $filename = 'screenshot_' . time() . '.png';
                $base64Filename = 'data:image/png;base64,' . $screenshotB64;
            } else {
                $screenshotB64 = null;
            }

            // Issue erstellen
            $issueData = [
                'title' => $data['title'],
                'body' => $this->formatIssueBody($data),
                'labels' => [self::ISSUE_LABEL, 'demo'],
            ];

            if ($screenshotB64) {
                $issueData['body'] .= "\n\n**Screenshot:**\n" . $screenshotB64;
            }

            // GitHub API aufrufen
            $issueUrl = $this->createIssue($issueData);

            if ($issueUrl) {
                // E-Mail-Benachrichtigung (optional)
                $this->notifyUser($data['email'] ?? null, $issueUrl, $data['title']);

                return [
                    'success' => true,
                    'issue_url' => $issueUrl,
                    'message' => 'Ihr Feedback wurde erfolgreich erstellt! Wir werden uns schnellstmöglich melden.',
                    'issue_number' => $this->parseIssueNumber($issueUrl),
                ];
            }

            return [
                'success' => false,
                'issue_url' => null,
                'message' => 'Fehler beim Erstellen des Issues. Bitte versuchen Sie es erneut.',
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'issue_url' => null,
                'message' => 'Fehler beim Senden: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Issue-Body formatieren
     * 
     * @param array $data Feedback-Daten
     * @return string
     */
    private function formatIssueBody(array $data): string
    {
        $body = "## Beschreibung\n\n" . nl2br($data['description']) . "\n\n";

        // Reproduktionsschritte
        if (isset($data['steps'])) {
            $body .= "## Reproduktionsschritte\n\n" . nl2br($data['steps']) . "\n\n";
        }

        // Benutzerauthentifizierung
        if (isset($data['username'])) {
            $body .= "## Benutzer\n\n" . $data['username'] . "\n\n";
        }

        // Browser/System-Info
        if (isset($data['browser'])) {
            $body .= "## Browser/System\n\n" . $data['browser'] . "\n\n";
        }

        // Umgebung
        if (isset($data['environment'])) {
            $body .= "## Umgebung\n\n" . json_encode($data['environment'], JSON_PRETTY_PRINT) . "\n\n";
        }

        // E-Mail
        if (isset($data['email'])) {
            $body .= "## Kontakt\n\n" . "E-Mail: " . $data['email'] . "\n\n";
        }

        return $body;
    }

    /**
     * GitHub Issue erstellen
     * 
     * @param array $issueData Issue-Daten
     * @return string|null URL zum Issue
     * @throws Exception
     */
    private function createIssue(array $issueData): ?string
    {
        $url = "https://api.github.com/repos/{$this->githubOwner}/{$this->githubRepo}/issues";

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($issueData),
            CURLOPT_HTTPHEADER: [
                'Accept: application/vnd.github.v3+json',
                'Authorization: token ' . $this->githubToken,
                'Content-Type: application/json',
            ],
            CURLOPT_SSL_VERIFYPEER => true,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 201) {
            return json_decode($response, true)['html_url'] ?? null;
        } elseif ($httpCode === 401) {
            throw new Exception('GitHub Token ungültig.');
        } elseif ($httpCode === 403) {
            throw new Exception('GitHub API Rate Limit erreicht.');
        }

        return null;
    }

    /**
     * GitHub Issue Number extrahieren
     * 
     * @param string $url GitHub Issue URL
     * @return int|null
     */
    private function parseIssueNumber(string $url): ?int
    {
        $match = preg_match('/issues\/\d+$/', $url);
        
        if ($match) {
            return (int) preg_replace('/issues\//', '', $url);
        }

        return null;
    }

    /**
     * Benutzer benachrichtigen (E-Mail)
     * 
     * @param string|null $email E-Mail
     * @param string $issueUrl Issue URL
     * @param string $title Issue-Titel
     * @return void
     */
    private function notifyUser(?string $email, string $issueUrl, string $title): void
    {
        if (!$email) {
            return;
        }

        // E-Mail-Vorlage
        $subject = "Ihr Feedback wurde erstellt - GitHub Issue #{$this->parseIssueNumber($issueUrl)}";
        $body = "Hallo,\n\n";
        $body .= "Danke für Ihr Feedback! Ihr Issue wurde erstellt:\n\n";
        $body .= "URL: {$issueUrl}\n\n";
        $body .= "Titel: {$title}\n\n";
        $body .= "Wir werden uns schnellstmöglich melden.\n\n";
        $body .= "Mit freundlichen Grüßen\n";
        $body .= "RunwayHub Team";

        // E-Mail senden (hier nur als Placeholder - in Produktion wäre es eine echte E-Mail)
        // mail($email, $subject, $body);
    }
}
