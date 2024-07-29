# models.py
import sqlite3

class Database:
    def __init__(self, db_name):
        self.conn = sqlite3.connect(db_name)
        self.cursor = self.conn.cursor()

    def create_tables(self):
        self.cursor.execute('''
            CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY,
                username TEXT NOT NULL,
                email TEXT NOT NULL,
                password TEXT NOT NULL
            )
        ''')

        self.cursor.execute('''
            CREATE TABLE IF NOT EXISTS costumes (
                id INTEGER PRIMARY KEY,
                name TEXT NOT NULL,
                description TEXT,
                price REAL NOT NULL
            )
        ''')

        self.cursor.execute('''
            CREATE TABLE IF NOT EXISTS rentals (
                id INTEGER PRIMARY KEY,
                user_id INTEGER NOT NULL,
                costume_id INTEGER NOT NULL,
                rental_date DATE NOT NULL,
                return_date DATE NOT NULL,
                FOREIGN KEY (user_id) REFERENCES users (id),
                FOREIGN KEY (costume_id) REFERENCES costumes (id)
            )
        ''')

        self.conn.commit()

    def insert_user(self, username, email, password):
        self.cursor.execute('INSERT INTO users (username, email, password) VALUES (?, ?, ?)', (username, email, password))
        self.conn.commit()

    def insert_costume(self, name, description, price):
        self.cursor.execute('INSERT INTO costumes (name, description, price) VALUES (?, ?, ?)', (name, description, price))
        self.conn.commit()

    def insert_rental(self, user_id, costume_id, rental_date, return_date):
        self.cursor.execute('INSERT INTO rentals (user_id, costume_id, rental_date, return_date) VALUES (?, ?, ?, ?)', (user_id, costume_id, rental_date, return_date))
        self.conn.commit()

    def get_users(self):
        self.cursor.execute('SELECT * FROM users')
        return self.cursor.fetchall()

    def get_costumes(self):
        self.cursor.execute('SELECT * FROM costumes')
        return self.cursor.fetchall()

    def get_rentals(self):
        self.cursor.execute('SELECT * FROM rentals')
        return self.cursor.fetchall()