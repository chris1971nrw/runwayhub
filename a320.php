<?php
session_start();
require_once 'auth_config.php';
require_once 'header.php';

// Statische Informationen für die Seite
$plane_name = "Airbus A320";
$capacity = 180;
$status = "aktiv";
?>
<main>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-header bg-primary text-white py-3">
                        <h2 class="mb-0"><?= $plane_name ?></h2>
                    </div>
                    <div class="card-body p-4">
                        <p>Willkommen zur Detailansicht des Flugzeugtyps <strong><?= $plane_name ?></strong>.</p>

                        <div class="row mt-4">
                            <div class="col-md-6">
                                <h4 class="mb-3 text-primary">Technische Daten</h4>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item"><strong>Modell:</strong> <?= $plane_name ?></li>
                                    <li class="list-group-item"><strong>Kapazität:</strong> <?= $capacity ?> Passagiere</li>
                                    <li class="list-group-item"><strong>Status:</strong> <span class="badge bg-success">Aktiv</span></li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h4 class="mb-3 text-success">Flugdetails</h4>
                                <p>Die <?= $plane_name ?> ist ein Rückgrat vieler moderner Fluggesellschaften. Sie bietet eine ideale Kombination aus Effizienz, Reichweite und Zuverlässigkeit für den täglichen Passagierbetrieb. In unserer Flotte wird sie speziell für hohe Auslastung und präzise Routenfahrten eingesetzt.</p>
                                <div class="alert alert-info">
                                    Diese Informationen dienen als Referenz für die operationellen Details innerhalb von RunwayHub.
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4 text-center" style="background: #f8f9fa;">
                        <h5>Navigation</h5>
                        <ul class="list-group list-group-flush mt-3">
                            <li class="list-group-item"><a href="index.php">🏠 Startseite</a></li>
                            <li class="list-group-item"><a href="admin_fleet.php" class="text-muted small">Nur für Admin</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'footer.php'; ?>
