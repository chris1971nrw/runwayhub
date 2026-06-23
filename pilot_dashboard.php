<?php
session_start();
require_once 'auth_config.php';
require_once 'core_components.php';

// Prüfung, ob der User ein Pilot ist (oder Admin mit Piloten-Funktionen)
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['pilot', 'admin'])) {
    header('Location: dashboard.php?error=denied');
    exit;
}

$title = "Piloten_Dashboard";
// Überprüfung auf spezifischen Status, falls nötig
?>
<main>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-3 col-lg-2 mb-4">
                <?php 
                    // Anpassung der Sidebar für Piloten (wenn nötig)
                    // Da wir ein Modul nutzen, bleibt die Struktur konsistent.
                    include 'sidebar_admin.php'; 
                ?>
            </div>

            <div class="col-md-9 col-lg-10">
                <div class="bg-light p-4 rounded mb-4 shadow-sm border">
                    <h2>Piloten-Kontrollzentrum</h2>
                    <p class="text-muted">Willkommen zur täglichen Briefing. Hier finden Sie alle Details zu Ihrer heutigen Flotte.</p>
                </div>

                <div class="row">
                    <div class="col-md-8">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-primary text-white">
                                <h3 class="mb-0">Aktive Flugzeuge & Status</h3>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Flugzeug</th>
                                            <th>Kapazität</th>
                                            <th>Status</th>
                                            <th>Aktion</th>
                                        </tr>
                                    </thead>>
                                    <tbody>
                                        <?php 
                                        $db = getDB();\n                                        $flights = $db->query("SELECT * FROM flights ORDER BY date ASC")->fetchAll();\n                                        \n                                        if (empty($flights)) {\n                                            echo '<tr><td colspan="4" class="text-center">No upcoming missions.</td></tr>';\n                                        } else {\n                                            foreach ($flights as $f):\n                                            ?>\n                                            <tr>\n                                                <td>
                                                    <strong><?= htmlspecialchars($f['route']) ?></strong> 
                                                    <br/><small class=\"text-muted\">Flight: <?= htmlspecialchars($f['flight_number'] ?? 'N/A') ?></small>
                                                </td>\n                                                <td><?= date('Y-m-d H:i', strtotime($f['date'])) ?></td>\n                                                <td><?= renderStatusBadge($f['status']) ?></td>\r\
                                                <td>\r\
                                                    <a href="details.php?id=<?= $f['id'] ?>" class="btn btn-sm btn-info">Details</a>\r\
                                                </td>\r\
                                            </tr>\r\
                                            <?php endforeach; ?>\r\
                                        }
                                    </tbody>>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card shadow-sm">
                            <div class="card-header bg-warning text-dark">
                                <h3 class="mb-0">Tages-Briefing</h3>
                            </div>
                            <div class="card-body">
                                <p>Wählen Sie ein Flugzeug aus, um die spezifischen Checklisten zu laden.</p>
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <a href="air_status.php" class="text-decoration-none">Aktuelles Wetterwarnung</a>
                                    </li>
                                    <li class="list-group-item">
                                        <a href="fuel_management.php" class="text-decoration-none">Betankungsplanung</a>
                                    </li>
                                    <li class="list-group-item">
                                        <a href="gate_assignment.php" class="text-decoration-none">Gate-Zuweisung</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'footer.php'; ?>
