<?php
/**
 * Finance & Logistics Module
 * 
 * Dieses Modul ist als eigenständiges Plugin konzipiert. Es verwaltet die 
 * wirtschaftlichen Aspekte der Fluggesellschaft (Finanzen, Personal, Asset-Kosten)
 * unabhängig vom laufenden Flugbetrieb oder den technischen ACAR-Daten.
 */

namespace Runway_Modules_Finance;

class FinanceManager {
    
    /**
     * Berechnet die Gesamtkosten eines geplanten Flugasatzes.
     * 
     * Diese Funktion aggregiert die verschiedenen Kostenpunkte (Ground, Catering, Versicherung).
     * 
     * @param int $flight_id Die ID des betreffenden Fluges.
     * @return array {
     *    @var float $total_cost Gesamtpreis inklusive aller Gebühren.
     *    @var array $breakdown Detaillierte Aufschlüsselung der Kosten.
     * }
     */
    public static function calculateProjectedCosts(int $flight_id): array {
        // Initialisierung der Kostenkomponenten (Simulation-Logik)
        $costs = [
            'ground_fees' => 0.0,      // Landing, Parking, Handling
            'catering'     => 0.0,      // Food & Beverage für Passagiere/Crew
            'insurance'    => 0.0,      // Proportionale Versicherungsanteile
            'tolls_fees'   => 0.0       // Verschiedene Gebühren (Gate-Fees etc.)
        ];

        // Logik zur Zuweisung von Basiskosten basierend auf der Flugstrecke
        // ... hier würde die Logik für variable Kosten einfließen ...

        return [
            'total_cost' => array_sum($costs),
            'breakdown'  => $costs,
            'currency'   => 'EUR'
        ];
    }

    /**
     * Verwaltung der Personal-Kosten (Pilot & Crew).
     * 
     * Berechnet Gehalt, Zulagen und Per-Diem Erstattungen.
     * 
     * @param int $pilot_id Die ID des Piloten.
     * @param float $duration Flugdauer in Stunden.
     * @return array Details zur Abrechnung.
     */
    public static function getPayrollData(int $pilot_id, float $duration): array {
        $base_salary = 0.0; // Basisbreich aus dem User-Profil
        $bonus_rate = 25.0;  // Bonus pro Flugstunde

        return [
            'basic_pay' => $base_salary,
            'flight_bonus' => $duration * $bonus_rate,
            'total_payout' => $base_salary + ($duration * $bonus_rate)
        ];
    }
}
