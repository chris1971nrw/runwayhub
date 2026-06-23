--
Initial schema for RunwayHub project.
Creates tables for users, fleet, and sessions.
--

CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL UNIQUE,
    password_hash TEXT NOT NULL,
    role TEXT NOT NULL DEFAULT 'pilot' -- e.g., admin, pilot, ground_staff
);

CREATE TABLE IF NOT EXISTS fleet (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    model_name TEXT NOT NULL,
    status TEXT NOT NULL DEFAULT 'active',
    capacity INTEGER NOT NULL DEFAULT 0,
    description TEXT
);

\nCREATE TABLE IF NOT EXISTS flights (\n    id INTEGER PRIMARY KEY AUTOINCREMENT,\n    route TEXT NOT NULL,\n    date DATETIME,\n    status TEXT NOT NULL DEFAULT 'scheduled',\n    flight_number VARCHAR(10) UNIQUE\n);\n
