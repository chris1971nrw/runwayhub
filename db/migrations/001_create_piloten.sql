-- VAVS MVP Migration #001: Piloten-Tabelle erstellen  
-- Erstellt für Virtuelle Airline System (Release 1 - MVP)  

CREATE TABLE IF NOT EXISTS `piloten` (  
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,  
    
    -- Pilot-Basisdaten  
    first_name VARCHAR(50), 
    last_name VARCHAR(50),
    
    email VARCHAR(128) UNIQUE NOT NULL COMMENT 'E-Mail für Login & Kommunikation',
    password_hash BINARY 72 COMMENT 'Bcrypt Password Hash + Salt (Salt nicht separat gespeichert)', 
    
    -- Lizenzstatus  
    license_type ENUM('PPL','CPL','ATPL'), 
    flight_hours DECIMAL(9,4),  
    
    status TINYINT NOT NULL DEFAULT 1 COMMENT '-- Status:0=deaktiviert|1=aktives Mitglied--'
) ENGINE=InnoDB CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

COMMENT ON TABLE `piloten` IS 'Haupt-Piloten-Tabelle für Crew-Management';  
