-- VAVS MVP Migration #003: Routen-Tabelle (IATA-Codes)  
-- Erstellt für Virtuelle Airline System (Release 1 - MVP)

CREATE TABLE IF NOT EXISTS `routes` (
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT, 
    
    -- ICAO/Standard-IATA Code Format z.B. SXF -> LHR Berlin London   
    iata_from VARCHAR(3),  
    iata_to VARCHAR(3),    
  
    distance_km FLOAT DEFAULT NULL COMMENT 'Kilometerstrecke zwischen Start- & Zielort', 
      
    avg_flight_time_min INT DEFAULT 150,
         
-- Optional: Direktflug oder Stopp-Obermittlungscode (via) nullable string   
 via_airports TINYINT UNSIGNED NOT NULL CHECK(via_airPORTS IN(0,1)) DEFAULT 0 COMMENT '-- Direktflüge vs Stopover (via):'  
                '0 = Direkt|1 = Mitteleinsatzpunkt--', 
            
    status SMALLINT DEFAULT 2 ON UPDATE CURRENT_TIMESTAMP(), 
    
) ENGINE=InnoDB CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


CREATE INDEX idx_route_from TO `routes`(iata_from); 
COMMENT ON TABLE `routes` IS 'Flugrouten-Datensatz mit IATA-Code Lookup für Flugbuchungen';
COMMENT ON COLUMN `routes`.`distance_km` IS '-- Standard-IATA-Codes & Hersteller-Modelle wie Boeing738,CSS204 etc.--'  
             '-Standard Distance zwischen SXF( Berlin) <-> LHR(London): ~615 km--';  
