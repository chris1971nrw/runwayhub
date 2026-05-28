<?php
/**
 * Sitemap Validator for RunwayHub
 * Validates all URLs in sitemap and checks for broken links
 */

// List of URLs to check
$urls = [
    '/',
    '/landing',
    '/login',
    '/dashboard.php',
    '/flights.php',
    '/aircrafts.php',
    '/pilots.php',
    '/bookings.php',
    '/admin.php',
    '/va-admin.php',
    '/va-connect.php',
    '/va-gruenden.php',
    '/weather-widget.html',
    '/flight-board',
    '/api-status',
    '/api',
    '/api-docs',
    '/privacy-policy.html',
    '/terms.html',
    '/404.html',
    '/blog/index.html',
    '/search.php'
];

// Check each URL
$output = [];
$broken = [];
$valid = [];

foreach ($urls as $url) {
    $checkUrl = 'https://chris1971nrw.github.io/runwayhub' . $url;
    $response = @file_get_contents($checkUrl);
    $status = 'OK';
    
    if ($response === false) {
        $status = '404 - Not Found';
        $broken[] = $checkUrl;
    } elseif (strpos($response, '404') !== false || strpos($response, 'not found') !== false) {
        $status = '404';
        $broken[] = $checkUrl;
    }
    
    if ($status === 'OK') {
        $valid[] = $checkUrl;
    }
    
    $output[] = sprintf('- %s: %s', $url, $status);
}

// Output results
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sitemap Validation - RunwayHub</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; }
        .valid { color: green; }
        .broken { color: red; }
        .stats { background: #f5f5f5; padding: 10px; border-radius: 5px; margin-bottom: 20px; }
        h1 { color: #333; }
    </style>
</head>
<body>
    <h1>🔍 Sitemap Validation Report</h1>
    <div class="stats">
        <p><strong>Total URLs:</strong> <?=count($urls)?></p>
        <p><strong>Valid URLs:</strong> <span class="valid"><?=count($valid)?></span></p>
        <p><strong>Broken Links:</strong> <span class="broken"><?=count($broken)?></span></p>
        <p><strong>Success Rate:</strong> <?=number_format((count($valid)/count($urls))*100, 1)?>%</p>
    </div>
    
    <h2>Validation Results</h2>
    <ul>
        <?php foreach ($output as $line): ?>
            <li><?=htmlspecialchars($line)?></li>
        <?php endforeach; ?>
    </ul>
    
    <?php if (!empty($broken)): ?>
    <h2>Broken Links</h2>
    <ul>
        <?php foreach ($broken as $url): ?>
            <li class="broken"><?=htmlspecialchars($url)?></li>
        <?php endforeach; ?>
    </ul>
    <?php endif; ?>
    
    <p><em>Generated: <?=date('Y-m-d H:i:s')?></em></p>
</body>
</html>