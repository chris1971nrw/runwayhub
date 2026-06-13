-- VAVS MVP Migration #002: Flotte (Fleet) - Flugzeugverwaltung  
-- Erstellt für Virtuelle Airline System (Release 1 - MVP Release!)

CREATE TABLE IF NOT EXISTS `fleet` (  
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,  
    
    -- Typische IATA-Codes & Hersteller-Modelle wie Boeing738,CSS204 etc.
    model_type VARCHAR(50), 
    
    registration_number CHAR(6) UNIQUE NOT NULL COMMENT 'ICAO Format D-AIMC HL24xx',  
        
      capacity INT DEFAULT 189 CHECK(capacity BETWEEN 1 AND 500),  
    
   max_takeoff_weight DECIMAL(9,0), -- kg (Take-Off Weight Max),  
       
     fuel_capacity_liters FLOAT(4) COMMENT 'Tankinhalt in Litern für Fuel-Calculation', 
    
    maintenance_due_date DATE NULL DEFAULT CURRENT_DATE(),
      
       status TINYINT NOT NULL DEFAULT 1 COMMENT '-- Status: active|under_maintenance|retired--'  

        manufacturer VARCHAR(50),  
    
      serial_number CHAR(32) DEFAULT NULL

 ) ENGINE=InnoDB CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


CREATE INDEX idx_fleet_registration_hashed_01 ON fleet(registration_number); 
