<?php

declare(strict_types=1);

namespace RunwayHub\Api\Controller;

use RunwayHub\Core\Controller as BaseController;
use RunwayHub\Core\Request;
use RunwayHub\Core\Response;
use RunwayHub\Core\Database;
use RunwayHub\Core\Middleware\Auth;
use RunwayHub\Core\GitHubIssues;

/**
 * Issues Controller
 * 
 * Handhabt GitHub Issues Erstellung mit Logfile-Anhang.
 */
class IssuesController extends BaseController
{
    protected Request $request;
    protected Response $response;
    protected Database $db;
    protected ?Auth $auth;
    protected ?GitHubIssues $githubIssues;

    public function __construct(Request $request, Response $response, Database $db, ?Auth $auth = null)
    {
        parent::__construct($request, $response);
        $this->request = $request;
        $this->response = $response;
        $this->db = $db;
        $this->auth = $auth;
        $this->githubIssues = new GitHubIssues('chris1971nrw/runwayhub');
    }

    /**
     * Submit issue with logfile attachment
     * 
     * @return Response
     */
    public function submit(): Response
    {
        // Check admin access
        if (!$this->auth || !$this->auth->isAdmin()) {
            return $this->response->contentType('application/json')->content(json_encode([
                'success' => false,
                'error' => 'Access denied. Admin privileges required.',
            ]))->send();
        }

        // Get issue description
        $description = $this->request->getPost('description');
        $admin_email = $this->request->getPost('admin_email') ?? 'admin@example.com';
        $admin_name = $this->request->getPost('admin_name') ?? 'Administrator';

        if (empty($description)) {
            return $this->response->contentType('application/json')->content(json_encode([
                'success' => false,
                'error' => 'Description required.',
            ]))->send();
        }

        // Get log files
        $logfilePaths = [
            '/home/christoph/.openclaw/workspace-runwayhub/runwayhub/logs/',
            '/app/logs/',
        ];

        $logs = [];
        foreach ($logfilePaths as $path) {
            if (file_exists($path)) {
                foreach (scandir($path) as $file) {
                    if (is_file($path . $file)) {
                        $logs[$file] = file_get_contents($path . $file);
                    }
                }
            }
        }

        // Create issue
        $issueData = [
            'title' => 'Bug Report: ' . $description,
            'body' => "## Issue Description\n\n" . $description . "\n\n" .
                     "## Reporte\n\n" .
                     "- **Benutzer:** " . $admin_name . "\n" .
                     "- **E-Mail:** " . $admin_email . "\n\n" .
                     "## Logfiles\n\n" .
                     "```json\n" . json_encode($logs, JSON_PRETTY_PRINT) . "\n```\n\n" .
                     "## Nächste Schritte\n\n" .
                     "Bitte prüfen Sie den Logfile und geben Sie weitere Informationen.\n",
            'labels' => ['bug', 'admin-report'],
        ];

        try {
            $response = $this->githubIssues->createIssue(
                $issueData['title'],
                $issueData['body'],
                null,
                $issueData['labels']
            );
        } catch (\Exception $e) {
            return $this->response->contentType('application/json')->content(json_encode([
                'success' => false,
                'error' => $e->getMessage(),
            ]))->send();
        }

        return $this->response->contentType('application/json')->content(json_encode($response))->send();
    }

    /**
     * Get issue status
     * 
     * @return Response
     */
    public function status(): Response
    {
        // Check admin access
        if (!$this->auth || !$this->auth->isAdmin()) {
            return $this->response->contentType('application/json')->content(json_encode([
                'success' => false,
                'error' => 'Access denied. Admin privileges required.',
            ]))->send();
        }

        $issueNumber = $this->request->getInt('issue_number');

        if (!$issueNumber) {
            return $this->response->contentType('application/json')->content(json_encode([
                'success' => false,
                'error' => 'Issue number required.',
            ]))->send();
        }

        $issueStatus = $this->githubIssues->getIssueStatus($issueNumber);

        return $this->response->contentType('application/json')->content(json_encode($issueStatus))->send();
    }
}
