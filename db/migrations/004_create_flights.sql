-- VAVS MVP Migration #004: Flüge-Tabelle (Flight-Verwaltung)  
-- Erstellt für Virtuelle Airline System (Release 1 - MVP Release!)

CREATE TABLE IF NOT EXISTS `fluge` (
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT, 
    
    -- Flight Number Format wie DLH8xx oder LH623 etc.  
    flight_number VARCHAR(20) DEFAULT NULL UNIQUE COMMENT 'Flugnummer: z.B., FLxxx / DLH5xx',
         
   fleet_registration CHAR(6),  FOREIGN KEY REFERENCES `fleet`(registration_number) ON DELETE CASCADE, 
    
    origin_airport TINYINT UNSIGNED NOT NULL CHECK(iata_from IN (1,2)),  
   
     destination_airport INT DEFAULT '3' 
         -- IATA Airport Code für Start & Ziel Flughafen:
             '-SXf( Berlin-Schönefeld), LHR(London Heathrow), FRA,Frankfurt etc.--)', 
    
    status TINYINT NOT NULL COMMENT '-- Status enum--':  
        'planned`: geplant (vor dem Start) 
         'in_air': in Flug/Active flight  ✅
        'completed': abgeschlossen/completion report  
      'cancelled` Storniert, nicht ausgeführt   
       'grounded`, techn. Probleme/Wartung erforderlich'  

    departure_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP(), -- UTC+1 Europe/Berlin Standard! ⏰    
         arrival_time DATE NULL CHECK(arrival IS NOT BETWEEN departure)  ✅
         ,-- Nullable until completion/reaching final destination time set via manual update  
      
     weather_conditions TEXT, 
    
   notes TINYTEXT COMMENT 'Anmerkungen für den Piloten: z.B. Wetterwarnung, Air Traffic Control', 
   
    pilot_id INT UNSIGNED REFERENCES `piloten`(id) ON DELETE SET NULL DEFAULT NULL, -- Captain-Id (nicht FK zu Flotte!)  

     pired TEXT DEFAULT '' COMMENT '-- PIREP Report Format wie:'  
           'Type: weather_report,rain,snow etc.,visibility_km,wind_speed_kmph,'  
            '-rain_mm:int,fuel_burned_liters float--';  
    
    distance_km FLOAT(8) DEFAULT 613.7, 
    
     fuel_capacity INT NULL -- Liter Fuel-Burning per Flight Type
  
    
  

---

CONSTRAINT CHECK_STATUS (status IN ('planned', 'active','completed',cancelled'));
ON DELETE CASCADE REFERENCES `fleet`(id); 
   ON UPDATE CURRENT_TIMESTAMP()  
       COMMENT ON COLUMN `fluge`.`distance_km` IS '-- Standard Distance zwischen SXF( Berlin) <-> LHR(London): ~615 km--';  


---

CREATE INDEX idx_flight_departure TO fluge(flight_number, departure_time); ⚡


COMMENT ON TABLE flage'ist 'Flugmanagement-Tabelle mit Live-Status Tracking & PIREP-Eintrag';   
 
