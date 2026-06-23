<?php
session_start();
require_once 'auth_config.php';
require_once 'core_components.php';

// Nur Admins dürfen die Flottenverwaltung sehen/nutzen
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: dashboard.php?error=denied');
    exit;
}

$message = '';
$message_type = 'success';
$edit_mode = false;
$editing_id = null;
$aircrafts_to_edit = null;

// Bearbeitungs-Modus vorbereiten (Initialer Check)
if (isset($_GET['edit'])) {
    $edit_mode = true;
    $editing_id = (int)$_GET['edit'];
    $db = getDB();
    if ($db) {
        $stmt = $db->prepare("SELECT * FROM items_aircraft WHERE id = ?");
        $stmt->execute([$editing_id]);
        $aircrafts_to_edit = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

// Aktionen verarbeiten (Update/Add/Delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = getDB();
    
    if ($db) {
        if (isset($_POST['delete_aircraft'])) {
            $id = (int)$_POST['id'];
            $stmt = $db->prepare("DELETE FROM fleet WHERE id = ?");
            $stmt->execute([$id]);
            $message = "Flugzeug wurde gelöscht.";
        }
        
        if (isset($_POST['update_aircraft'])) {
            $id = (int)$_POST['id'];
            $model = trim($_POST['model']);
            $capacity = (int)$_POST['capacity'];
            $stmt = $db->prepare("UPDATE fleet SET model_name = ?, capacity = ? WHERE id = ?");
            $stmt->execute([$model, $capacity, $id]);
            $message = "Flugzeug erfolgreich aktualisiert.";
        }
        
        if (isset($_POST['add_aircraft'])) {
            $model = trim($_POST['model']);
            $capacity = (int)$_POST['capacity'];
            if (!empty($model)) {
                $stmt = $db->prepare("INSERT INTO fleet (model_name, capacity) VALUES (?, ?)");
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
                <div class="bg-light p-4 rounded mb-4 shadow-sm border">
                    <h2>Flottenverwaltung</h2>
                    <?php if ($message): ?>
                        <div class="alert alert-<?= $message_type ?>"><?= $message ?></div>
                    <?php endif; ?>
                </div>

                <div class="row">
                    <div class="col-md-8">
                        <div class="card shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <h3 class="mb-0">Aktuelle Flotte</h3>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Modell</th>
                                            <th>Kapazität</th>
                                            <th>Status</th>
                                            <th>Aktionen</th>
                                        </tr>
                                    </thead>>
                                    <tbody>
                                        <?= renderTableBody(getAircraftData()) ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card shadow-sm">
                            <div class="card-header bg-success text-white">
                                <h3 class="mb-0"><?= $edit_mode ? "Flugzeug bearbeiten" : "Neues Flugzeug" ?></h3>
                            </div>
                            <div class="card-body">
                                <?php if ($edit_mode && $aircrafts_to_edit): ?>
                                    <p class="text-muted">Bearbeite die Daten für das ausgewählte Flugzeug.</p>
                                    <form method="POST">
                                        <input type="hidden" name="id" value="<?= $aircrafts_to_edit['id'] ?>">
                                        <div class="mb-3">
                                            <label class="form-label">Flugzeugmodell</label>
                                            <input type="text" name="model" class="form-control" value="<?= htmlspecialchars($aircrafts_to_edit['model']) ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Kapazität (Passagiere)</label>
                                            <input type="number" name="capacity" class="form-control" value="<?= (int)$aircrafts_to_edit['capacity'] ?>" required>
                                        </div>
                                        <button type="submit" name="update_aircraft" class="btn btn-primary w-100">Änderungen speichern</button>
                                        <a href="admin_fleet.php" class="btn btn-link mt-2">Abbrechen</a>
                                    </form>
                                <?php else: ?>
                                    <form method="POST">
                                        <div class="mb-3">
                                            <label class="form-label">Flugzeugmodell</label>
                                            <input type="text" name="model" class="form-control" required placeholder="z.B. Airbus A320">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Kapazität (Passagiere)</label>
                                            <input type="number" name="capacity" class="form-control" required>
                                        </div>
                                        <button type="submit" name="add_aircraft" class="btn btn-success w-100">Zum Flottenbestand hinzufügen</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'footer.php'; ?>
