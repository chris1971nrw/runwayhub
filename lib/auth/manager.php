<?php
/**
 * Authentication & Authorization Core
 * 
 * Dieser Service verarbeitet die Validierung von Zugriffsrechten basierend auf 
 * dem hinterlegten Pilot-Rank (Trainee, Co-Pilot, Captain, Senior Captain, Flight Instructor).
 */

namespace RunwayHub\Auth;

class AuthManager {
    /**
     * Überprüft, ob ein Benutzer über den entsprechenden Rang verfügt.
     * 
     * @param string $rank Der vom System hinterlegte Rank (aus user_profiles)
     * @param string $requiredRank Der mindestens erforderliche Rank für die Aktion.
     * @return bool
     */
    public static function canAccess(string $rank, string $requiredRank): bool {
        $hierarchy = [
            'Trainee' => 0,
            'Co-Pilot' => 1,
            'Captain' => 2,
            'Senior Captain' => 3,
            'Flight Instructor' => 4,
            'Admin' => 5
        ];

        $userPower = $hierarchy[$rank] ?? 0;
        $requiredPower = $hierarchy[$requiredRank] ?? 99;

        return $userPower >= $requiredPower;
    }

    /**
     * Validiert eine spezifische Feature-Anfrage.
     * 
     * Diese Methode wird von den Modulen (ACAR, Finance, etc.) genutzt,
     * um zu entscheiden, ob ein Nutzer die Aktion ausführen darf.
     * 
     * @param string $rank Der aktuelle Rank des Logged-in-Users.
     * @param string $featureKey Der Name des Features (z.B., 'acard', 'finance_pro').
     * @return bool
     */
    public static function authorize(string $rank, string $featureKey): bool {
        $features = [
            'basic_flight' => 'Co-Pilot',      // Trainees können erst einmal nur lernen
            'acard'         => 'Captain',       // Erhöhte Verantwortung für Daten-Interpretation
            'finance'       => 'Senior Captain', // Finanzverwaltung für erfahrene Offiziere
            'instruction'   => 'Flight Instructor' // Nur für Lehrer
        ];

        $required = $features[$featureKey] ?? 'Captain';
        return self::canAccess($rank, $required);
    }
}
