<?php
/**
 * Core Module Manager
 * 
 * Diese Basisstruktur ermöglicht es uns, Features wie 'acard' (ACARS) und
 * 'maintenance' als separate, unabhängig skalierbare Plugins einzubinden.
 */

namespace RunwayHub\Core;

class ModuleManager {
    private static array $loadedModules = [];

    /**
     * Lädt ein Modul basierend auf seinem Namen.
     * 
     * @param string $moduleName Der Name des Moduls (z.B. 'acard' oder 'maintenance')
     * @return mixed Das geladene Modul-Objekt oder die entsprechenden Daten.
     */
    public static function load(string $name): mixed {
        if (!isset(self::$loadedModules[$name])) {
            $path = __DIR__ . "/modules/{$name}/Loader.php";
            
            if (file_exists($path)) {
                require_once $path;
                // Jeder Modul sollte eine eigene Autoload-Ebene oder Factory nutzen.
                // Der Manager dient hier als zentraler Einstiegspunkt im Core.
                self::$loadedModules[$name] = $name; // Platzhalter für die eigentliche Instanziierung
            }
        }
        return self::$loadedModules[$name] ?? null;
    }

    /**
     * Prüft, ob ein Feature aktiv und verfügbar ist (z.B. via Lizenzschlüssel).
     */
    public static function isEnabled(string $feature): bool {
        // Hier wird künftige Logik für lizenzbasierte Einschränkungen implementiert.
        return true; 
    }
}
