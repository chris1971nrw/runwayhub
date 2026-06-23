<?php
/**
 * Maintenance Module - Logistics & Status Tracking
 * 
 * Dieses Modul verwaltet den Zustand der Flugzeuge (Maintenance Cycles,
 * Service Timer und Zustandsänderungen).
 */

namespace Runway_Modules\Maintenance;

class MaintenanceManager {
    /**
     * Berechnet die verbleibende Zeit bis zur nächsten Wartung.
     * 
     * @param float $totalHours Gesamthstunden des Flugzeugs.
     * @param float $nextDueHour Der Schwellenwert für den nächsten Service.
     * @return array ['remaining' => float, 'status' => string]
     */
    public static function calculateServiceStatus(float $totalHours, float $nextDueHour): array {
        $remaining = $nextDueHour - $totalHours;
        
        if ($remaining <= 0) {
            return [
                'remaining' => 0.0,
                'status' => 'CRITICAL_MAINTENANCE_REQUIRED'
            ];
        } elseif ($remaining < 50) { // Warnzone bei unter 50 Stunden
            return [
                'remaining' => $remaining,
                'status' => 'WARNING_LOW_SERVICE_MARGIN'
            ];
        } else {
            return [
                'remaining' => $remaining,
                'status' => 'READY'
            ];
        }
    }

    /**
     * Verarbeitet eine Wartungsaktion.
     * 
     * @param int $aircraftId ID des Flugzeugs zum Update.
     * @param float $hoursToAdd Stunden, die während der Inspektion/Triebwerksprüfung festgestellt wurden.
     * @return array Die aktualisierten Daten nach dem Loggen.
     */
    public static function registerService(int $aircraftId, float $hoursAdd): array {
        // Hier würde in einer echten DB-Anbindung der SQL-Update laufen.
        // Der Kern des Moduls berechnet den neuen Stand und loggt das Datum.
        return [
            'status' => 'UPDATE_SUCCESS',
            'new_calculation' => 'Logic placeholder'
        ];
    }
}
