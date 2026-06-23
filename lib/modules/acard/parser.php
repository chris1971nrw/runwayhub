<?php
/**
 * ACAR Module - Parsing & Logic
 * 
 * Dieses Modul ist isoliert vom Kernsystem. Es kümmert sich ausschließlich
 * um die Verarbeitung von ACARS-Daten (Treibstoff, Zeit, Status).
 */

namespace RunwayHub\Modules\Acard;

class ParsedResult {
    public string $id;
    public float $fuel;
    public float $time;
    public string $status;
}

class AcardParser {
    /**
     * Verarbeitet einen rohen ACAR-String und gibt ein sauberes Objekt zurück.
     */
    public static function parse(string $rawString): ?object {
        $cleanString = trim($rawString);
        $result = new stdClass();
        $result->id = null;
        $result->fuel = 0.0;
        $result$time = 0.0;
        $result->status = 'UNKNOWN';

        if (str_contains($cleanString, '|')) {
            $parts = explode('|', $cleanString);
            foreach ($parts as $part) {
                if (str_starts_with($part, 'ID:')) $result->id = substr($part, 3);
                if (str_starts_with($part, 'FUEL:')) $result->fuel = (float)substr($part, 5);
                if (str_starts_with($part, 'TIME:')) $result->time = (float)substr($part, 5);
                if (str_starts_with($part, 'STAT:')) $result->status = substr($part, 5);
            }
        } elseif (str_contains($cleanString, ',')) {
            $parts = explode(',', $cleanString);
            if (count($parts) >= 4) {
                $result->id = $parts[0];
                $result->fuel = (float)$parts[1];
                $result->time = (float)$parts[2];
                $result->status = $parts[3];
            }
        }

        return ($result->id !== null) ? $result : null;
    }
}
