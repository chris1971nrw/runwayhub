<?php
/**
 * ACARS Simulator
 * 
 * Simuliert den Empfang von ACARS-Datenpaketen zur Validierung 
 * der System-Integrität und des Parsers.
 * 
 * Basierend auf Standard-Telemetrierapporten:
 * - Fuel Status (Remaining, Rate)
 * - Engine Analytics (Temp, Pressure)
 * - Automated Alerting (Maintenance flags)
 */

class ACARS_Simulator {
    /**
     * Generiert eine simulierte ACARS-Meldung.
     * 
     * @param string $aircraftId Die RH-Kennung (z.B. "RH0124")
     * @param float $fuelRemaining Aktueller Treibstoffbestand
     * @param int $minutes Flugeinheit in Minuten
     * @param string $status Statuscode (z.B. "OK", "WARN", "FAIL")
     * 
     * @return string Ein formatiertes Acard-Paket für den Parser: 
     *         "ID:RH0124|FUEL:580.5|TIME:90|STAT:OK"
     */
    public static function generateReport(string $aircraftId, float $fuelRemaining, int $minutes, string $status = 'OK'): string {
        // Validierung der Kennung (Protokoll-konform)
        if (!preg_match('/^RH\d{4}$/', $aircraftId)) {
            throw new Exception("Invalid Aircraft ID format. Must be RHxxxx");
        }

        return "ID:{$aircraft_id}|FUEL:{$fuel_remaining}|TIME:{$minutes}|STAT:{$status}";
    }

    /**
     * Erzeugt eine Liste von Test-Szenarien für die Automatisierungstests.
     */
    public static function getTestSequences(): array {
        return [
            [
                'desc' => 'Normaler Flug (Standard)',
                'data' => ['RH0124', 580.0, 90, 'OK']
            ],
            [
                'desc' => 'Treibstoffwarnung (Low Fuel)',
                'data' => ['RH0780', 120.5, 45, 'WARN']
            ],
            [
                'desc' => 'Technischer Defekt (Engine Alert)',
                'data' => ['RH0321',-5.0, 120, 'FAIL'] // Negativer/unmöglicher Wert provoziert Logikprüfung
            ]
        ];
    }
}

// Beispiel für eine Testschleife im Parser:
/*
foreach (ACARS_Simulator::getTestSequences() as $case) {
    $raw = ACARS_Simulator::generateReport($case['0'], $case['1'], $case['2'], $case['3']);
    echo "Simulating: {$case['desc']} -> Raw Data: $raw\n";
    $parsed = AcarParser::parse($raw);
    print_r($parsed);
}
*/
