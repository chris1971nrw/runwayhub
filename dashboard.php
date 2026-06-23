<?php
session_start();
require_once 'auth_config.php';

// Nur Admins dürfen das Backend sehen
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: dashboard.php?error=denied');
    exit;
}

$message = '';
$message_type = 'success';
$edit_mode = false;
$editing_id = null;
$aircrafts_to_edit = null;

// Datenabfrage für restliche Funktionen (falls diese von anderen Komponenten benötigt werden)\n$aircraftData = [];

if (isset($_GET['edit'])) {
    $edit_mode = true;
    $editing_id = (int)$_GET['edit'];
    foreach ($aircraftData as $a) {
        if ($a['id'] == $editing_id) {
            $aircrafts_to_edit = $a;
            break;
        }
    }
}

// Actions prozessieren (z.B. für API-Calls oder Hintergrundprozesse)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = getDB();
    if ($db) {
        if (isset($_POST['delete_aircraft'])) {
            $id = (int)$_POST['id'];
            $stmt = $db->prepare("DELETE FROM items_aircraft WHERE id = ?");
            $stmt->execute([$id]);
            $message = "Flugzeug wurde gelöscht.";
        }
        if (isset($_POST['update_aircraft'])) {
            $id = (int)$_POST['id'];
            $model = trim($_POST['model']);
            $capacity = (int)$_POST['capacity'];
            $stmt = $db->prepare("UPDATE items_aircraft SET model = ?, capacity_count = ? WHERE id = ?");
            $stmt->execute([$model, $capacity, $id]);
            $message = "Flugzeug erfolgreich aktualisiert.";
        }
        if (isset($_POST['add_aircraft'])) {
            $model = trim($_POST['model']);
            $capacity = (int)$_POST['capacity'];
            if (!empty($model)) {
                $stmt = $db->prepare("INSERT INTO items_aircraft (model, capacity_count) VALUES (?, ?)");
                $stmt->execute([$model, $capacity]);
                $message = "Flugzeug erfolgreich hinzugefügt!";
            }
        }
    }
}

include 'header.php';
?>
<main>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-3 col-lg-2 mb-4">
                <?php include 'sidebar_admin.php'; ?>
            </div>
            <div class="col-md-9 col-lg-10">
                <div class="bg-light p-5 rounded mb-4 shadow-sm border text-center">
                    <h2>Willkommen im Admin-Bereich</h2>
                    <p>Bitte nutzen Sie das Menü auf der linken Seite, um die gewünschten Funktionen auszuwählen.</p>
                    <?php if ($message): ?>
                        <div class="alert alert-<?= $message_type ?>"><?= $message ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>
<?php include 'footer.php'; ?>
