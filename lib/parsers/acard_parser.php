<?php
/**
 * ACAR Data Parser
 * 
 * Extracts key metrics from raw ACARS (Automatic Communications Reporting System) strings.
 * Used for both manual entry and future automated client integration.
 */

class AcarParser {
    /**
     * Parses a raw ACAR string and returns an associative array of extracted data.
     * 
     * @param string $rawString The raw incoming ACAR message.
     * @return array|null Returns null if the format is invalid or unreadable.
     */
    public static function parse(string $rawString): ?array {
        // Basic cleaning of the input
        $cleanString = trim($rawString);
        
        // Example expected format logic: 
        // A standard raw string might look like "ID:RH0124|FUEL:500.5|TIME:120|STAT:OK"
        // Or a more stripped version: "RH0124,500.5,120,OK"
        
        $data = [
            'aircraft_id' => null,
            'fuel_consumed' => 0.0,
            'flight_time' => 0.0,
            'status' => 'UNKNOWN'
        ];

        // Logic to detect format and extract data
        if (strpos($cleanString, '|') !== false) {
            $parts = explode('|', $clean_string);
            foreach ($parts as $part) {
                if (stripos($part, 'ID:') === 0) $data['aircraft_id'] = substr($part, 3);
                if (stripos($part, 'FUEL:') === 0) $data['fuel_consumed'] = (float)substr($part, 5);
                if (stripos($part, 'TIME:') === 0) $data['flight_time'] = (float)substr($part, 5);
                if (stripos($part, 'STAT:') === 0) $data['status'] = substr($part, 5);
            }
        } else {
            // Fallback for comma-separated or other common variations
            $parts = explode(',', $cleanString);
            if (count($parts) >= 4) {
                $data['aircraft_id'] = $parts[0];
                $data['fuel_consumed'] = (float)$parts[1];
                $data['flight_time'] = (float)$parts[2];
                $data['status'] = $parts[3];
            }
        }

        // Validate core fields
        if ($data['aircraft_id'] && $data['fuel_consumed'] >= 0) {
            return $data;
        }

        return null;
    }
}

// Example usage (Internal Test):
// $raw = "ID:RH0124|FUEL:580.0|TIME:90|STAT:OK";
// $parsed = AcarParser::parse($raw);
// print_r($parsed);
