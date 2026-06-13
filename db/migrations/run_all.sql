-- VAVS MVP Database Setup Script (Release 1)  
-- Run this script to create all tables for Virtual Airline System!  

source db/migrations/001_create_piloten.sql;
source db/migrations/002_create_fleet.sql;


INSERT INTO piloten VALUES(NULL,'Christoph','Muster','pilot@vavs.local'::varchar(64),'ATPL',NULL,1);  
INSERT INTO fleet VALUES(NULL,'Boeing738','D-AIMC',189,80264,NULL,'active','Boeing','B-05');
