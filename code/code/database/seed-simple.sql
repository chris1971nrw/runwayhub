-- Simple Seed Data for RunwayHub
-- Basic data for testing and demonstration

-- Insert sample aircrafts
INSERT INTO aircrafts (registration, type, manufacturer, model, capacity, range, status) VALUES
('D-AIMA', 'Boeing 737-800', 'Boeing', '737-800', 162, 2950, 'active'),
('D-AIMB', 'Airbus A320neo', 'Airbus', 'A320neo', 180, 6500, 'active'),
('D-AIMC', 'Airbus A319', 'Airbus', 'A319', 120, 3500, 'active'),
('D-AIMD', 'Boeing 747-400', 'Boeing', '747-400', 416, 7925, 'active'),
('D-AIME', 'Airbus A350-900', 'Airbus', 'A350-900', 325, 15000, 'active');

-- Insert sample pilots
INSERT INTO pilots (first_name, surname, callsign, license_type, email, phone, status, username) VALUES
('Hans', 'Müller', 'HAL92', 'ATPL', 'hans.mueller@lh.com', '+49 170 1234567', 'active', 'hal92'),
('Anna', 'Schmidt', 'ASH13', 'ATPL', 'anna.schmidt@lh.com', '+49 170 2345678', 'active', 'ash13'),
('Michael', 'Weber', 'WEH56', 'ATPL', 'michael.weber@af.com', '+49 170 3456789', 'active', 'weh56'),
('Sophie', 'Nowak', 'NOW78', 'ATPL', 'sophie.nowak@kl.com', '+49 170 4567890', 'active', 'now78'),
('Thomas', 'Schulz', 'SCH99', 'ATPL', 'thomas.schulz@ba.com', '+49 170 5678901', 'active', 'sch99');

-- Insert sample flights
INSERT INTO flights (flight_number, origin, destination, departure_time, arrival_time, status, aircraft_registration, pilot_id, gate, terminal) VALUES
('LH4001', 'FRA', 'MUC', '2026-05-28 06:00:00', '2026-05-28 06:45:00', 'departed', 'D-AIMA', 1, 'A1', '1'),
('LH4512', 'FRA', 'HAM', '2026-05-28 07:30:00', '2026-05-28 08:15:00', 'boarding', 'D-AIMB', 2, 'B2', '2'),
('AF1234', 'FRA', 'CDG', '2026-05-28 10:00:00', '2026-05-28 11:45:00', 'on-time', 'D-AIMC', 3, 'C3', '3'),
('KL5678', 'FRA', 'AMS', '2026-05-28 12:00:00', '2026-05-28 12:40:00', 'on-time', 'D-AIMD', 4, 'D4', '4'),
('EK9012', 'FRA', 'DXB', '2026-05-29 22:00:00', '2026-06-01 02:30:00', 'scheduled', 'D-AIME', 5, 'E5', '5'),
('LH700', 'FRA', 'JFK', '2026-06-01 18:00:00', '2026-06-02 11:00:00', 'scheduled', 'D-AIMA', 1, 'F6', '6'),
('BA117', 'FRA', 'LHR', '2026-05-28 20:30:00', '2026-05-28 22:00:00', 'on-time', 'D-AIMB', 2, 'G7', '7');

-- Insert sample bookings
INSERT INTO bookings (flight_number, passenger_email, passenger_name, passenger_seats, passenger_phone, booking_reference, status, total_price, payment_method) VALUES
('LH4001', 'john.doe@email.com', 'John Doe', '12A', '+49 170 9876543', 'LH4001A12', 'confirmed', 150.00, 'credit_card'),
('LH4512', 'jane.smith@email.com', 'Jane Smith', '05C', '+49 170 8765432', 'LH4512C05', 'confirmed', 135.00, 'credit_card'),
('AF1234', 'paul.wilson@email.com', 'Paul Wilson', '14F', '+49 170 7654321', 'AF1234F14', 'confirmed', 280.00, 'paypal'),
('KL5678', 'mary.johnson@email.com', 'Mary Johnson', '08D', '+49 170 6543210', 'KL5678D08', 'confirmed', 165.00, 'bank_transfer'),
('EK9012', 'robert.brown@email.com', 'Robert Brown', '10E', '+49 170 5432109', 'EK9012E10', 'confirmed', 850.00, 'credit_card'),
('LH700', 'alice.jones@email.com', 'Alice Jones', '20A', '+49 170 4321098', 'LH700A20', 'confirmed', 950.00, 'credit_card'),
('BA117', 'charlie.davis@email.com', 'Charlie Davis', '15G', '+49 170 3210987', 'BA117G15', 'confirmed', 420.00, 'paypal');

-- Insert sample passengers
INSERT INTO passengers (first_name, surname, email, phone, seat, seat_class) VALUES
('John', 'Doe', 'john.doe@email.com', '+49 170 9876543', '12A', 'economy'),
('Jane', 'Smith', 'jane.smith@email.com', '+49 170 8765432', '05C', 'economy'),
('Paul', 'Wilson', 'paul.wilson@email.com', '+49 170 7654321', '14F', 'business'),
('Mary', 'Johnson', 'mary.johnson@email.com', '+49 170 6543210', '08D', 'economy'),
('Robert', 'Brown', 'robert.brown@email.com', '+49 170 5432109', '10E', 'first'),
('Alice', 'Jones', 'alice.jones@email.com', '+49 170 4321098', '20A', 'economy'),
('Charlie', 'Davis', 'charlie.davis@email.com', '+49 170 3210987', '15G', 'business');

-- Insert sample seat configurations
INSERT INTO seats (flight_number, seat_number, row, seat_class, status, price) VALUES
('LH4001', '12A', 12, 'economy', 'occupied', 150.00),
('LH4001', '12B', 12, 'economy', 'available', 150.00),
('LH4512', '05C', 5, 'economy', 'occupied', 135.00),
('AF1234', '14F', 14, 'business', 'occupied', 280.00),
('KL5678', '08D', 8, 'economy', 'occupied', 165.00),
('EK9012', '10E', 10, 'first', 'occupied', 850.00),
('LH700', '20A', 20, 'economy', 'occupied', 950.00),
('BA117', '15G', 15, 'business', 'occupied', 420.00);

-- Insert sample maintenance records
INSERT INTO maintenance (aircraft_registration, date, type, description, cost) VALUES
('D-AIMA', '2025-12-15', 'scheduled', 'Annual inspection and maintenance', 5000.00),
('D-AIMA', '2026-05-15', 'routine', 'Oil change and filter replacement', 500.00),
('D-AIMB', '2026-01-10', 'routine', 'Tire inspection and replacement', 800.00),
('D-AIMC', '2025-11-05', 'emergency', 'Engine troubleshooting', 3000.00),
('D-AIMD', '2026-02-20', 'scheduled', 'Cockpit update and software patch', 1200.00);

-- Insert sample pilot history
INSERT INTO pilot_history (pilot_id, flight_number, departure_time, status, notes) VALUES
(1, 'LH4001', '2026-05-28 06:00:00', 'completed', 'On time'),
(2, 'LH4512', '2026-05-28 07:30:00', 'completed', 'Early departure'),
(3, 'AF1234', '2026-05-28 10:00:00', 'completed', 'Punctual'),
(4, 'KL5678', '2026-05-28 12:00:00', 'completed', 'Smooth landing'),
(5, 'EK9012', '2026-05-29 22:00:00', 'scheduled', 'Long-haul flight');
