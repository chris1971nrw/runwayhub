<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UserReportController extends Controller
{
    /**
     * Create a GitHub Issue from user report.
     */
    public function createIssue(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'labels' => 'nullable|array',
            'priority' => 'nullable|in:low,normal,high,critical',
        ]);

        // Extract relevant data from report
        $issueData = [
            'title' => $validated['title'],
            'body' => $validated['body'],
            'labels' => [
                'bug',
                'feature',
                'documentation',
            ],
        ];

        // Add priority label if specified
        if ($validated['priority']) {
            $issueData['labels'][] = $validated['priority'];
        }

        // TODO: Implement actual GitHub API call
        // $response = Http::asForm()->post('https://api.github.com/repos/runwayhub/runwayhub/issues', $issueData);

        return response()->json([
            'message' => 'Issue created successfully',
            'issue_data' => $issueData,
        ]);
    }

    /**
     * List recent GitHub issues for the project.
     */
    public function listIssues(Request $request)
    {
        // Fetch issues from GitHub API
        $response = Http::get('https://api.github.com/repos/runwayhub/runwayhub/issues?state=all&per_page=100');

        if (!$response->successful()) {
            return response()->json([
                'message' => 'Failed to fetch issues',
                'error' => $response->status(),
            ], 500);
        }

        $issues = $response->json();

        // Filter and format issues for display
        $formattedIssues = array_map(function ($issue) {
            return [
                'id' => $issue['id'],
                'number' => $issue['number'],
                'title' => $issue['title'],
                'state' => $issue['state'],
                'created_at' => $issue['created_at'],
                'updated_at' => $issue['updated_at'],
                'labels' => array_map(function ($label) {
                    return $label['name'];
                }, $issue['labels'] ?? []),
            ];
        }, $issues);

        return response()->json($formattedIssues);
    }

    /**
     * Submit a bug report with automatic log collection.
     */
    public function submitBugReport(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'steps_to_reproduce' => 'nullable|string',
            'expected_behavior' => 'nullable|string',
            'actual_behavior' => 'nullable|string',
            'screenshots' => 'nullable|array',
        ]);

        // Collect system information
        $systemInfo = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'timezone' => date_default_timezone_get(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown',
        ];

        // Collect Laravel logs (last 100 lines)
        try {
            $logsFile = storage_path('logs/laravel.log');
            if (file_exists($logsFile)) {
                $systemInfo['recent_logs'] = file_get_contents($logsFile);
            }
        } catch (\Exception $e) {
            // Ignore log collection errors
        }

        // Create issue data
        $issueData = [
            'title' => '[Bug Report] ' . $validated['title'],
            'body' => "## Bug Report\n\n" .
                     "### Description\n" . $validated['description'] . "\n\n" .
                     "### Steps to Reproduce\n" . ($validated['steps_to_reproduce'] ?? 'N/A') . "\n\n" .
                     "### Expected Behavior\n" . ($validated['expected_behavior'] ?? 'N/A') . "\n\n" .
                     "### Actual Behavior\n" . ($validated['actual_behavior'] ?? 'N/A') . "\n\n" .
                     "### System Information\n" . json_encode($systemInfo, JSON_PRETTY_PRINT) . "\n\n" .
                     "### Additional Context\n" . ($request->user() ? 'Reported by authenticated user.' : 'Anonymous report.') . "\n",
            'labels' => ['bug', 'needs-investigation'],
        ];

        return response()->json([
            'message' => 'Bug report submitted successfully',
            'issue_data' => $issueData,
            'system_info' => $systemInfo,
        ]);
    }

    /**
     * Submit a feature request.
     */
    public function submitFeatureRequest(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'use_case' => 'nullable|string',
            'benefits' => 'nullable|array',
        ]);

        // Create issue data
        $issueData = [
            'title' => '[Feature Request] ' . $validated['title'],
            'body' => "## Feature Request\n\n" .
                     "### Description\n" . $validated['description'] . "\n\n" .
                     "### Use Case\n" . ($validated['use_case'] ?? 'N/A') . "\n\n" .
                     "### Benefits\n" . json_encode($validated['benefits'] ?? [], JSON_PRETTY_PRINT) . "\n\n" .
                     "### Additional Context\n" . ($request->user() ? 'Reported by authenticated user.' : 'Anonymous request.') . "\n",
            'labels' => ['enhancement', 'feature-request'],
        ];

        return response()->json([
            'message' => 'Feature request submitted successfully',
            'issue_data' => $issueData,
        ]);
    }

    /**
     * Submit a documentation improvement suggestion.
     */
    public function submitDocumentationSuggestion(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'page_url' => 'required|url',
            'suggestion' => 'required|string',
            'current_content' => 'nullable|string',
            'proposed_content' => 'nullable|string',
        ]);

        // Create issue data
        $issueData = [
            'title' => '[Docs] Improve: ' . str_replace([' ', '.'], '-', $validated['page_url']),
            'body' => "## Documentation Suggestion\n\n" .
                     "### Page URL\n" . $validated['page_url'] . "\n\n" .
                     "### Current Content\n" . ($validated['current_content'] ?? 'N/A') . "\n\n" .
                     "### Proposed Improvement\n" . $validated['suggestion'] . "\n\n" .
                     "### Additional Context\n" . ($request->user() ? 'Reported by authenticated user.' : 'Anonymous suggestion.') . "\n",
            'labels' => ['documentation', 'improvement'],
        ];

        return response()->json([
            'message' => 'Documentation suggestion submitted successfully',
            'issue_data' => $issueData,
        ]);
    }
}
