# main.py
from models import Database

def main():
    db = Database('charm.db')
    db.create_tables()

    while True:
        print('1. Insert user')
        print('2. Insert costume')
        print('3. Insert rental')
        print('4. Get users')
        print('5. Get costumes')
        print('6. Get rentals')
        print('7. Search costumes')
        print('8. Exit')
        choice = input('Choose an option: ')

        if choice == '1':
            username = input('Enter username: ')
            email = input('Enter email: ')
            password = input('Enter password: ')
            db.insert_user(username, email, password)
        elif choice == '2':
            name = input('Enter costume name: ')
            description = input('Enter costume description: ')
            price = float(input('Enter costume price: '))
            db.insert_costume(name, description, price)
        elif choice == '3':
            user_id = int(input('Enter user ID: '))
            costume_id = int(input('Enter costume ID: '))
            rental_date = input('Enter rental date (YYYY-MM-DD): ')
            return_date = input('Enter return date (YYYY-MM-DD): ')
            db.insert_rental(user_id, costume_id, rental_date, return_date)
        elif choice == '4':
            users = db.get_users()
            for user in users:
                print(user)
        elif choice == '5':
            costumes = db.get_costumes()
            for costume in costumes:
                print(costume)
        elif choice == '6':
            rentals = db.get_rentals()
            for rental in rentals:
                print(rental)
        elif choice == '7':
            query = input('Enter search query: ')
            costumes = db.search_costumes(query)
            for costume in costumes:
                print(costume)
        elif choice == '8':
            break
        else:
            print('Invalid choice')

if __name__ == '__main__':
    main()