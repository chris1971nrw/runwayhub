<?php
session_start();
require_once 'auth_config.php';
require_once 'core_components.php';

// Nur Admins dürfen die Flügeverwaltung sehen/nutzen
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: dashboard.php?error=denied');
    exit;
}

$message = '';
$message_type = 'success';
$edit_mode = false;
$editing_id = null;
$flight_to_edit = null;

// Bearbeitungs-Modus vorbereiten (Initialer Check)
if (isset($_GET['edit'])) {
    $edit_mode = true;
    $editing_id = (int)$_GET['edit'];
    $db = getDB();
    if ($db) {
        $stmt = $db->prepare("SELECT * FROM flights WHERE id = ?");
        $stmt->execute([$editing_id]);
        $flight_to_edit = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

// Aktionen verarbeiten (Update/Add/Delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = getDB();
    
    if ($db) {
        if (isset($_POST['delete_flight'])) {
            $id = (int)$_POST['id'];
            $stmt = $db->prepare("DELETE FROM flights WHERE id = ?");
            $stmt->execute([$id]);
            $message = "Flug wurde gelöscht.";
        }
        
        if (isset($_POST['update_flight'])) {
            $id = (int)$_POST['id'];
            $route = trim($_POST['route']);
            $date = $_POST['date'];
            $status = $_POST['status'];
            
            $stmt = $db->prepare("UPDATE flights SET route = ?, date = ?, status = ? WHERE id = ?");
            $stmt->execute([$route, $date, $status, $id]);
            $message = "Flug erfolgreich aktualisiert.";
        }
        
        if (isset($_POST['add_flight'])) {
            $route = trim($_POST['route']);
            $date = $_POST['date'];
            $status = $_POST['status'];
            if (!empty($route)) {
                $db = getDB();

                # Identify ICAO codes and distances (basic grouping)
                preg_match_all('/[A-Z]{4}/', $route, $matches);
                $icao_codes = $matches[0];

                # Determine routes to process
                $routes_to_process = [];
                if (str_contains($route, '->')) {
                    $parts = explode('->', $route);
                    $parts = array_map('trim', $parts);
                    if (count($parts) >= 2) {
                        $routes_to_process[] = $parts[0] . ' -> ' . $parts[1];
                        // Add automatic return flight if it doesn't exist in the current request?
                        // For now, just process what is entered.
                    }
                } else {
                    $routes_to_process[] = $route;
                }

                $results = [];
                foreach ($routes_to_process as $r) {
                    # Generate unique Flight Number (e.g., RW0123)
                    $max_val = 0;
                    $stmt_max = $db->query("SELECT MAX(CAST(SUBSTRING(flight_number, 3) AS UNSIGNED)) FROM flights");
                    $res = $stmt_max->fetch();
                    $max_val = (int)($res[0] ?? 0);
                    $next_num = $max_val + 1;
                    $f_num = 'RW' . str_pad($next_num, 4, '0', STR_PAD_LEFT);

                    $stmt = $db->prepare("INSERT INTO flights (route, date, status, flight_number) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$r, $date, $status, $f_num]);
                    $results[] = "Flug hinzugefügt: $f_num";
                }
                $message = implode(' & ', $results);
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
                    <h2>Flugmanagement</h2>
                    <?php if ($message): ?>
                        <div class="alert alert-<?= $message_type ?>"><?= $message ?></div>
                    <?php endif; ?>
                </div>

                <div class="row">
                    <div class="col-md-8">
                        <div class="card shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <h3 class="mb-0">Aktuelle Flüge</h3>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Route</th>
                                            <th>Datum</th>
                                            <th>Status</th>
                                            <th>Aktionen</th>
                                        </tr>
                                    </thead>>
                                    <tbody>
                                        <?php
                                        $db = getDB();
                                        if ($db) {
                                            $stmt = $db->query("SELECT * FROM flights ORDER BY date ASC");
                                            $flights = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                            foreach ($flights as $flight) {
                                                echo '<tr class="align-middle">';
                                                echo '<td>' . htmlspecialchars($flight['route']) . '</td>';
                                                echo '<td>' . (date('d.m.Y H:i', strtotime($flight['date'])) ?: $flight['date']) . '</td>';
                                                echo '<td>' . renderStatusBadge($flight['status'] ?? 'scheduled') . '</td>';
                                                echo '<td class="text-end">';
                                                echo '<a href="?edit=' . $flight['id'] . '" class="btn btn-sm btn-outline-primary">Bearbeiten</a>';
                                                echo '<form method="POST" style="display:inline;" onsubmit="return confirm(\'Wirklich löschen?\');">';
                                                echo '<input type="hidden" name="id" value="' . $flight['id'] . '">';
                                                echo '<button type="submit" name="delete_flight" class="btn btn-sm btn-outline-danger">Löschen</button>';
                                                echo '</form>';
                                                echo '</td>';
                                                echo '</tr>';
                                            }
                                        } else {
                                            echo '<tr style="border-bottom: 2px solid #eee;">\n                                                <td colspan="4" class="text-center text-muted py-5">Noch keine Flüge im System eingetragen.</td>\n                                            </tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card shadow-sm">
                            <div class="card-header bg-success text-white">
                                <h3 class="mb-0"><?= $edit_mode ? "Flug bearbeiten" : "Neuen Flug hinzufügen" ?></h3>
                            </div>
                            <div class="card-body">
                                <?php if ($edit_mode && $flight_to_edit): ?>
                                    <p class="text-muted">Bearbeite die Daten für den ausgewählten Flug.</p>
                                    <form method="POST">
                                        <input type="hidden" name="id" value="<?= $flight_to_edit['id'] ?>">
                                        <div class="mb-3">
                                            <label class="form-label">Route</label>
                                            <input type="text" name="route" class="form-control" value="<?= htmlspecialchars($flight_to_edit['route']) ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Datum</label>
                                            <input type="datetime-local" name="date" class="form-control" value="<?= date('Y-m-d\TH:i', strtotime($flight_to_edit['date'])) ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Status</label>
                                            <select name="status" class="form-select">
                                                <option value="scheduled" <?= $flight_to_edit['status'] == 'scheduled' ? 'selected' : '' ?>>Geplant (Scheduled)</option>
                                                <option value="active" <?= $flight_to_edit['status'] == 'active' ? 'selected' : '' ?>>Aktiv</option>
                                                <option value="pending" <?= $flight_to_edit['status'] == 'pending' ? 'selected' : '' ?>>Ausstehend (Pending)</option>
                                            </select>
                                        </div>
                                        <button type="submit" name="update_flight" class="btn btn-primary w-100">Änderungen speichern</button>
                                        <a href="admin_flights.php" class="btn btn-link mt-2">Abbrechen</a>
                                    </form>
                                <?php else: ?>
                                    <form method="POST">
                                        <div class="mb-3">
                                            <label class="form-label">Route</label>
                                            <input type="text" name="route" class="form-control" required placeholder="z.B. London zu Berlin">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Datum</label>
                                            <input type="datetime-local" name="date" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Status</label>
                                            <select name="status" class="form-select">
                                                <option value="scheduled" selected>Geplant (Scheduled)</option>
                                                <option value="active">Aktiv</option>
                                                <option value="pending">Ausstehend (Pending)</option>
                                            </select>
                                        </div>
                                        <button type="submit" name="add_flight" class="btn btn-primary w-100 py-2">Zum System hinzufügen</button>
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
