<?php
session_start();
require_once 'auth_config.php';
require_once 'core_components.php';

$title = "Piloten-Handbuch";
?>
<?php include 'header.php'; ?>

<main class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="mb-4">
                <h1>Piloten-Handbuch</h1>
                <p class="text-muted">Offizielle Dokumentation und Regeln für die RunwayHub Gemeinschaft.</p>
            </div>
            <div class="card shadow-sm border-0 bg-white p-4">
                <?php
                $filePath = __DIR__ . '/docs/pilot_manual.md';
                if (file_exists($filePath)) {
                    $content = file_get_contents($filePath);

                    // Basic Markdown to HTML conversion
                    // Headers
                    $content = preg_replace('/^# (.*)$/m', '<h1>$1</h1>', $content);
                    $content = preg_replace('/^## (.*)$/m/m', '<h2>$1</h2>', $content);
                    $content = preg_replace('/^### (.*)$/m', '<h3>$1</h3>', $content);

                    // Bold
                    $content = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $content);

                    // Lists
                    $content = preg_replace('/^- (.*)$/m', '<li>$1</li>', $content);
                    
                    // Wrap list items in <ul> if they follow each other (simplified)
                    // This is a very basic implementation as requested.
                    $content = preg_replace('/(<li>.*<\/li>)+/s', function($matches) {
                        return '<ul>' . str_replace("\n", '', $matches[0]) . '</ul>';
                    }, $content);

                    // Newlines
                    $content = nl2br($content);

                    echo $content;
                } else {
                    echo '<p class="text-danger">Fehler: Das Handbuch konnte nicht geladen werden.</p>';
                }
                ?>
            </div>
        </div>
    </div>
</main>

<?php include 'footer.php'; ?>
