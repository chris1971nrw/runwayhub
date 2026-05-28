<?php
/**
 * Cache Statistics for RunwayHub
 * Monitors cache usage and performance
 */

$cacheDir = __DIR__ . '/../..';
$files = array_count_values(array_map('basename', glob($cacheDir . '/*')));
$totalFiles = count($files);
$totalSize = 0;

foreach ($files as $file => $count) {
    $path = $cacheDir . '/' . $file;
    if (is_file($path)) {
        $size = filesize($path);
        $totalSize += $size;
    }
}

$humanSize = round($totalSize / 1024, 2) . ' KB';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cache Statistics - RunwayHub</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; }
        .stats { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; }
        .stat { background: #f5f5f5; padding: 15px; border-radius: 5px; text-align: center; }
        .stat-value { font-size: 24px; font-weight: bold; color: #333; }
        .stat-label { font-size: 14px; color: #666; }
        h1 { color: #333; }
    </style>
</head>
<body>
    <h1>📊 Cache Statistics</h1>
    
    <div class="stats">
        <div class="stat">
            <div class="stat-value"><?= $totalFiles ?></div>
            <div class="stat-label">Total Files</div>
        </div>
        <div class="stat">
            <div class="stat-value"><?= $humanSize ?></div>
            <div class="stat-label">Total Size</div>
        </div>
        <div class="stat">
            <div class="stat-value">✅</div>
            <div class="stat-label">Cache Status</div>
        </div>
        <div class="stat">
            <div class="stat-value"><?= date('H:i') ?></div>
            <div class="stat-label">Last Check</div>
        </div>
    </div>
    
    <p><em>Generated: <?=date('Y-m-d H:i:s')?></em></p>
</body>
</html>