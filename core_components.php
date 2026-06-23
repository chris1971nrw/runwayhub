<?php
/**
 * Core Component Library for RunwayHub
 * This file contains reusable logic and UI components used across the entire platform
 * (Admin, Pilot, and Public Frontend)
 */

// --- Database Helpers ---
// --- Database Helpers ---\n/**\n * Note: The getDB() function is defined in db_config.php.\n * This section remains for organizational purposes but no local declaration is made to avoid conflicts.\n */\n
/**
 * Fetch data from the aircraft table.
 * Provides standard fallback logic if the database query fails.
 */
function getAircraftData() {
    $db = getDB();
    if ($db) {
        try {
            // Use the correct 'fleet' table and select necessary columns
            $stmt = $db->query("SELECT id, model_name as model, status, 100 as capacity FROM aircraft");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            // Log error or handle specifically if needed
        }
    }
    
    // Default fallback data for development and offline mode
    return [
        ['id' => 1, 'model' => 'Airbus A320', 'capacity' => 180, 'status' => 'aktiv'],
        ['id' => 2, 'model' => 'Boeing 737', 'capacity' => 160, 'status' => 'aktiv']
    ];
}

// --- UI Components (HTML Generation) ---

/**
 * Generates a standard Status Badge based on the status string.
 */
function renderStatusBadge($status = 'aktiv') {
    $s = strtolower((string)$status);
    $cssClass = 'success'; // Default for "aktiv"

    if ($s === 'wartung') {
        $cssClass = 'warning';
    } elseif (str_contains($s, 'nicht') || str_contains($s, 'aus')) {
        $cssClass = 'danger';
    }

    return '<span class="badge bg-' . $cssClass . '">' . htmlspecialchars($status) . '</span>';
}

/**
 * Generates a consistent info card.
 */
function renderInfoCard($title, $content, $type = 'primary') {
    $themeClass = '';
    if ($type === 'success') $themeClass = 'bg-success text-white';
    elseif ($type === 'info') $themeClass = 'bg-info text-white';
    else $themeClass = 'bg-primary text-white';

    return '
        <div class="card shadow-sm mb-3">
            <div class="card-header '.$themeClass.'">
                <h3 class="mb-0">' . htmlspecialchars($title) . '</h3>
            </div>
            <div class="card-body p-4">
                ' . $content . '
            </div>
        </div>';
}

/**
 * Helper to generate the table body for aircraft list.
 * This resolve the "undefined function" error in admin_fleet.php
 */
function renderTableBody($data) {
    if (!is_array($data)) return '<p>Keine Daten verfügbar.</p>';
    $html = '';
    foreach ($data as $item) {
        $html .= '<tr class="align-middle">';
        $html .= '<td>' . htmlspecialchars($item['model'] ?? 'N/A') . '</td>';
        $html .= '<td>' . (int)($item['capacity'] ?? 0) . ' Passagiere</td>';
        $html .= '<td>' . renderStatusBadge($item['status'] ?? 'aktiv') . '</td>';
        $html .= '<td class="text-end">
                    <a href="?edit=' . ($item['id'] ?? 0) . '" class="btn btn-sm btn-outline-primary">Bearbeiten</a>
                    <form method="POST" style="display:inline;" onsubmit="return confirm(\'Wirklich löschen?\');">
                        <input type="hidden" name="id" value="' . ($item['id'] ?? 0) . '">
                        <button type="submit" name="delete_aircraft" class="btn btn-sm btn-outline-danger">Löschen</button>
                    </form>
                  </td>';
        $html .= '</tr>';
    }
    return $html;
}
?>
