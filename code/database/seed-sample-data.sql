-- Seed Sample Data for RunwayHub
-- This script populates the database with sample data for testing

-- Insert sample airlines
INSERT INTO airlines (id, name, iata, icao, website, status, logo_url, notes) VALUES
(1, 'Lufthansa', 'LH', 'DLH', 'https://www.lufthansa.com', 'active', NULL, 'Germany flag'),
(2, 'Air France', 'AF', 'AFR', 'https://www.airfrance.com', 'active', NULL, 'France flag'),
(3, 'KLM Royal Dutch Airlines', 'KL', 'KLM', 'https://www.klm.com', 'active', NULL, 'Netherlands flag'),
(4, 'British Airways', 'BA', 'BAW', 'https://www.britishairways.com', 'active', NULL, 'UK flag'),
(5, 'Turkish Airlines', 'TK', 'THY', 'https://www.turkishairlines.com', 'active', NULL, 'Turkey flag'),
(6, 'Emirates', 'EK', 'UAE', 'https://www.emirates.com', 'active', NULL, 'UAE flag'),
(7, 'Qatar Airways', 'QR', 'QTR', 'https://www.qatarairways.com', 'active', NULL, 'Qatar flag'),
(8, 'Singapore Airlines', 'SQ', 'SIA', 'https://www.singaporeair.com', 'active', NULL, 'Singapore flag');

-- Insert sample aircrafts
INSERT INTO aircrafts (id, registration, type, manufacturer, model, capacity, range, max_altitude, max_speed, status, purchase_date, next_maintenance, last_maintenance, notes) VALUES
(1, 'D-AIMA', 'Boeing 737-800', 'Boeing', '737-800', 162, 2950, 41200, 470, 'active', '2015-06-15', '2026-08-01', '2025-12-15 14:30:00', 'Regular A320neo fleet'),
(2, 'D-AIMB', 'Airbus A320neo', 'Airbus', 'A320neo', 180, 6500, 37100, 487, 'active', '2018-03-20', '2026-09-15', '2026-01-10 10:00:00', 'New efficient fleet'),
(3, 'D-AIMC', 'Airbus A319', 'Airbus', 'A319', 120, 3500, 37100, 487, 'active', '2012-11-08', '2026-07-20', '2025-11-05 16:45:00', 'Legacy fleet'),
(4, 'D-AIMD', 'Boeing 747-400', 'Boeing', '747-400', 416, 7925, 43000, 575, 'active', '2008-09-12', '2026-10-01', '2025-10-02 09:30:00', 'Flagship jumbo jet'),
(5, 'D-AIME', 'Airbus A350-900', 'Airbus', 'A350-900', 325, 15000, 43100, 487, 'active', '2020-01-15', '2026-11-01', '2026-03-15 11:20:00', 'Long-haul flagship');

-- Insert sample pilots
INSERT INTO pilots (id, first_name, surname, callsign, license_type, license_number, email, phone, rating, status, hire_date, password, username, reset_token) VALUES
(1, 'Hans', 'Müller', 'HAL92', 'ATPL', 'DE-P1234567', 'hans.mueller@lh.com', '+49 170 1234567', 'multi', 'active', '2015-06-01', '$2y$10$rXsP7e3KvN9vQ2rZ8wL7u.4jK3mN5oP6qR7sT8uV9wX0yZ1aB2cD3', 'hal92', NULL),
(2, 'Anna', 'Schmidt', 'ASH13', 'ATPL', 'DE-P2345678', 'anna.schmidt@lh.com', '+49 170 2345678', 'multi', 'active', '2016-08-15', '$2y$10$sYtQ8f4LwO0wR3sA9xM8v.5kL4nO6pQ7rS8tU9vW0xY1zA2bC3dE4', 'ash13', NULL),
(3, 'Michael', 'Weber', 'WEH56', 'ATPL', 'DE-P3456789', 'michael.weber@af.com', '+49 170 3456789', 'multi', 'active', '2017-03-20', '$2y$10$tZuR9g5MxP1wS4tB0yN9w.6lM5oP8qR9sT0uV1wX2yZ3aB4cD5eF6', 'weh56', NULL),
(4, 'Sophie', 'Nowak', 'NOW78', 'ATPL', 'DE-P4567890', 'sophie.nowak@kl.com', '+49 170 4567890', 'multi', 'active', '2018-05-10', '$2y$10$uAvS0h6NyQ2xT5uC1zO0x.7mN6pQ9rS0tU1vW2xY3zA4bC5dE6fG7', 'now78', NULL),
(5, 'Thomas', 'Schulz', 'SCH99', 'ATPL', 'DE-P5678901', 'thomas.schulz@ba.com', '+49 170 5678901', 'multi', 'active', '2019-09-01', '$2y$10$vBwT1i7OzR3yU6vD2aP1y.8nO7qR0sT1uV2wX3yZ4aB5cD6eF7gH8', 'sch99', NULL);

-- Insert sample airports (from migration)

-- Insert sample flights
INSERT INTO flights (id, flight_number, origin, destination, departure_time, arrival_time, status, aircraft_registration, pilot_id, gate, terminal, baggage, notes) VALUES
(1, 'LH4001', 'FRA', 'MUC', '2026-05-28 06:00:00', '2026-05-28 06:45:00', 'departed', 'D-AIMA', 1, 'A1', '1', '02', 'Domestic flight'),
(2, 'LH4512', 'FRA', 'HAM', '2026-05-28 07:30:00', '2026-05-28 08:15:00', 'boarding', 'D-AIMB', 2, 'B2', '2', '03', 'Morning business flight'),
(3, 'AF1234', 'FRA', 'CDG', '2026-05-28 10:00:00', '2026-05-28 11:45:00', 'on-time', 'D-AIMC', 3, 'C3', '3', '04', 'International flight'),
(4, 'KL5678', 'FRA', 'AMS', '2026-05-28 12:00:00', '2026-05-28 12:40:00', 'on-time', 'D-AIMD', 4, 'D4', '4', '05', 'Short haul'),
(5, 'EK9012', 'FRA', 'DXB', '2026-05-29 22:00:00', '2026-06-01 02:30:00', 'scheduled', 'D-AIME', 5, 'E5', '5', '06', 'Long-haul to Dubai'),
(6, 'LH700', 'FRA', 'JFK', '2026-06-01 18:00:00', '2026-06-02 11:00:00', 'scheduled', 'D-AIMA', 1, 'F6', '6', '07', 'Transatlantic flight'),
(7, 'BA117', 'FRA', 'LHR', '2026-05-28 20:30:00', '2026-05-28 22:00:00', 'on-time', 'D-AIMB', 2, 'G7', '7', '08', 'Evening flight to London');

-- Insert sample bookings
INSERT INTO bookings (id, flight_number, passenger_email, passenger_name, passenger_seats, passenger_phone, booking_reference, status, total_price, payment_method) VALUES
(1, 'LH4001', 'john.doe@email.com', 'John Doe', '12A', '+49 170 9876543', 'LH4001A12', 'confirmed', 150.00, 'credit_card'),
(2, 'LH4512', 'jane.smith@email.com', 'Jane Smith', '05C', '+49 170 8765432', 'LH4512C05', 'confirmed', 135.00, 'credit_card'),
(3, 'AF1234', 'paul.wilson@email.com', 'Paul Wilson', '14F', '+49 170 7654321', 'AF1234F14', 'confirmed', 280.00, 'paypal'),
(4, 'KL5678', 'mary.johnson@email.com', 'Mary Johnson', '08D', '+49 170 6543210', 'KL5678D08', 'confirmed', 165.00, 'bank_transfer'),
(5, 'EK9012', 'robert.brown@email.com', 'Robert Brown', '10E', '+49 170 5432109', 'EK9012E10', 'confirmed', 850.00, 'credit_card');

-- Insert sample passengers
INSERT INTO passengers (id, booking_id, first_name, surname, email, phone, seat, seat_class, checked_bags) VALUES
(1, 1, 'John', 'Doe', 'john.doe@email.com', '+49 170 9876543', '12A', 'economy', 2),
(2, 1, 'Emma', 'Doe', 'emma.doe@email.com', '+49 170 9876544', '12B', 'economy', 1),
(3, 2, 'Jane', 'Smith', 'jane.smith@email.com', '+49 170 8765432', '05C', 'economy', 0),
(4, 3, 'Paul', 'Wilson', 'paul.wilson@email.com', '+49 170 7654321', '14F', 'business', 3),
(5, 4, 'Mary', 'Johnson', 'mary.johnson@email.com', '+49 170 6543210', '08D', 'economy', 2),
(6, 5, 'Robert', 'Brown', 'robert.brown@email.com', '+49 170 5432109', '10E', 'first', 4);

-- Insert sample seat configurations
INSERT INTO seats (id, flight_number, seat_number, row, seat_class, status, price) VALUES
(1, 'LH4001', '12A', 12, 'economy', 'available', 150.00),
(2, 'LH4001', '12B', 12, 'economy', 'occupied', 150.00),
(3, 'LH4512', '05C', 5, 'economy', 'occupied', 135.00),
(4, 'AF1234', '14F', 14, 'business', 'occupied', 280.00),
(5, 'KL5678', '08D', 8, 'economy', 'occupied', 165.00);

-- Insert sample maintenance records
INSERT INTO maintenance (id, aircraft_registration, date, type, description, cost) VALUES
(1, 'D-AIMA', '2025-12-15', 'scheduled', 'Annual inspection and maintenance', 5000.00),
(2, 'D-AIMA', '2026-05-15', 'routine', 'Oil change and filter replacement', 500.00),
(3, 'D-AIMB', '2026-01-10', 'routine', 'Tire inspection and replacement', 800.00),
(4, 'D-AIMC', '2025-11-05', 'emergency', 'Engine troubleshooting', 3000.00);

-- Insert sample pilot history
INSERT INTO pilot_history (id, pilot_id, flight_number, departure_time, actual_departure, status, notes) VALUES
(1, 1, 'LH4001', '2026-05-28 06:00:00', '2026-05-28 05:55:00', 'completed', 'On time'),
(2, 2, 'LH4512', '2026-05-28 07:30:00', '2026-05-28 07:28:00', 'completed', 'Early departure'),
(3, 3, 'AF1234', '2026-05-28 10:00:00', '2026-05-28 09:58:00', 'completed', 'Punctual');

-- Insert sample bookings with status
INSERT INTO bookings (id, flight_number, passenger_email, passenger_name, passenger_seats, passenger_phone, booking_reference, status, total_price, payment_method) VALUES
(6, 'LH700', 'alice.jones@email.com', 'Alice Jones', '20A', '+49 170 4321098', 'LH700A20', 'confirmed', 950.00, 'credit_card'),
(7, 'BA117', 'charlie.davis@email.com', 'Charlie Davis', '15G', '+49 170 3210987', 'BA117G15', 'confirmed', 420.00, 'paypal'),
(8, 'LH4001', 'david.miller@email.com', 'David Miller', '18C', '+49 170 2109876', 'LH4001C18', 'cancelled', 150.00, 'credit_card');

-- Insert sample passengers for new bookings
INSERT INTO passengers (id, booking_id, first_name, surname, email, phone, seat, seat_class, checked_bags) VALUES
(7, 6, 'Alice', 'Jones', 'alice.jones@email.com', '+49 170 4321098', '20A', 'economy', 2),
(8, 7, 'Charlie', 'Davis', 'charlie.davis@email.com', '+49 170 3210987', '15G', 'business', 3),
(9, 8, 'David', 'Miller', 'david.miller@email.com', '+49 170 2109876', '18C', 'economy', 1);
