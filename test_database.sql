CREATE DATABASE IF NOT EXISTS runwayhub_test;

USE runwayhub_test;

-- Test pilot table (ICAO callsign unique constraint)  
DROP TABLE IF EXISTS pilots;  
CREATE TABLE pilots (  
    id INT AUTO_INCREMENT PRIMARY KEY,  
    callsign VARCHAR(10) UNIQUE NOT NULL,  
    name VARCHAR(100),  
    email VARCHAR(255) UNIQUE NOT NULL,  
    password_hash VARCHAR(255)  
);

INSERT INTO pilots (id, callsign, email, name) VALUES  
    (1, 'RUNWAY_ADMIN', 'admin@runwayhub.run', 'Admin Test');
