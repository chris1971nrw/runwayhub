import sqlite3
import os

def init_db():
    # Connect to or create the database file
    conn = sqlite3.connect('database.db')
    cursor = conn.cursor()

    # Create Users table
    # role: 'admin' or 'pilot'
    cursor.execute('''
        CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT NOT NULL UNIQUE,
            password TEXT NOT NULL,
            role TEXT NOT NULL CHECK(role IN ('admin', 'pilot'))
        )
    ''')

    # Create Flights table
    cursor.execute('''
        CREATE TABLE IF NOT EXISTS flights (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER,
            aircraft_model TEXT,
            flight_date DATE,
            duration_minutes INTEGER,
            notes TEXT,
            FOREIGN KEY(user_id) REFERENCES users(id)
        )
    ''')

    # Seed initial data if empty
    cursor.execute("SELECT COUNT(*) FROM users")
    if cursor.fetchone()[0] == 0:
        # Passwords are plain for now to simplify, will update to hashes later
        users_data = [
            (1, 'admin', 'admin123', 'admin'),
            (2, 'pilot1', 'pilot456', 'pilot')
        ]
        cursor.executemany("INSERT INTO users (id, username, password, role) VALUES (?, ?, ?, ?)", users_data)
        # Remove the ID from list since we'll use autoincrement normally, 
        # but for seeding it helps to have fixed IDs. Actually just don't include ID in insert or make them unique.
    
    conn.commit()
    conn.close()
    print("Database initialized successfully.")

if __name__ == "__main__":
    init_db()
