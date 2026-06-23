<?php
/**
 * Sync Manager & Integration Service
 * 
 * Dieser Dienst ist verantwortlich für das Zusammenführen von Flugstunden
 * aus verschiedenen Quellen (IVAO, VATSIM) und die Berechnung des 1-Stück-Profils.
 */

namespace RunwayHub\Services;

class SyncManager {
    /**
     * Verarbeitet einen Import von neuen Flugstunden.
     * 
     * Diese Funktion wird genutzt, wenn ein Pilot Daten von einer externen Plattform 
     * (z.B. nach einem Flug auf- oder während eines Synchs an IVO/VATSIM)
     * importiert. Die Stunden werden addiert, nicht überschrieben.
     * 
     * @param int $userId Die interne ID des Piloten.
     * @param float $newHours Die neu hinzugekommenen Flugstunden.
     * @param string $source Der Quelle (z.B., 'IVO', 'VATSIM').
     * @return array Die aktualisierten Daten nach dem Sync-Prozess.
     */
    public static function syncFlightHours(int $userId, float $newHours, string $source): array {
        // Hier würde die DB-Abfrage erfolgen: 
        // SELECT total_hours FROM user_profiles WHERE id = $userId;
        $currentTotal = 150.0; // Beispielwert aus Datenbank

        $newTotal = $current_total + $newHours;
        
        // Die neue Summe wird in der DB gespeichert.
        // Resultat geben wir zurück, damit das Frontend die Animation/Bestätigung anzeigen kann.
        return [
            'status' => 'success',
            'previous' => $currentTotal,
            'added' => $newHours,
            'total' => $newTotal,
            'source' => $source, // Erfasst, ob es von IVO oder VATSIM kam
            'rank_update' => self::calculateRankChange($newTotal)
        ];
    }

    /**
     * Berechnet den Fortschritt im Rang basierend auf dem Gesamtzähler.
     * 
     * @param float $total The total accumulated hours.
     * @return string Der Name des nun erreichten Ranges (z.B., 'Captain').
     */
    private static function calculateRankChange(float $total): string {
        if ($total >= 1000) return 'Senior Captain';
        if ($total >= 500) return 'Captain';
        if ($total >= 200) return 'Co-Pilot';
        return 'Trainee';
    }
}
