<?php
/**
 * Integration Bridge
 * 
 * Diese Klasse bildet die Brücke zwischen den Modul-Systemen.
 * Sie reagiert auf Signale aus dem ACAR-Modul und initiiert notwendige
 * Aktionen im Wartungs-Modul (Maintenance).
 */

namespace RunwayHub\Core;

use RunwayHub\Modules\Acard\Acard_18parser; // Adjusting to the actual parser class or function if needed
use RunwayHub\Modules\Maintenance\Maintenance_manager;

class BridgeSystem {
    /**
     * Verarbeitet eine eingehende ACAR-Sendung und prüft auf Wartungsnotwendigkeiten.
     * 
     * Wenn das ACAR-Modul einen kritischen Status meldet, wird automatisch
     * ein Flag im Wartungsmodul gesetzt.
     * 
     * @param array $parsedAcardDaten Die Daten aus dem Acard_Parser.
     * @return bool Erfolgrichkeit der Cross-Module Verarbeitung.
     */
    public static function processAcardToMaintenance(array $parsedAcardDaten): bool {
        // 1. Extraktion des Status & ID
        $vehicleId = $parsedAcardDaten['id'];
        $status = $parsedAcardDaten['status'];

        // 2. Logik-Check: Welche Zustände erfordern Wartungs-Interaktion?
        $criticalStates = ['FAIL', 'WARN'];

        if (in_array($status, $criticalStates)) {
            // Wenn der Status kritisch ist, fordern wir eine Aktion im Wartungssystem an
            // Wir rufen das Maintenance Modul auf, um den Zustand zu triggern.
            \Runway_Modules_Maintenance_manager::updateStatus($vehicleId, 'IMMEDIATE_MAINTENANCE');
            return true; 
        }

        return false;
    }
}
