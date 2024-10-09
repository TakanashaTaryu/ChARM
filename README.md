# ChARM (Cartoon &amp; Anime Rental Marketplace)

ChARM is a website containing a place to rent anime cosplay and other costumes

---

## Description

There are 2 accounts, namely admin, courier, and user

The admin's role is to manage user accounts and can also add and subtract costume items and can also view transaction status.
Users can register and log in and can choose and rent existing costumes, in the rental option they can choose to have the costumes delivered or picked up at the location. If you pick it up, it will issue the shop address. If you choose to deliver it to a certain place, it will enter the address where the user is located for delivery data.
Then the courier can receive orders from transactions that have been carried out and then update when the delivery is complete.

Additional Features: For rentals, available items can be displayed, then the user selects a product like a marketplace and can later checkout with several payment options.
There are two pick-up options, pick it up at the shop or send it to the respective place where you will then enter the address.

Revision: For the courier feature, WhatsApp API will be added to provide notifications to the courier for each order, then for costume components there will be a separate menu for full set costume rentals or just individual component rentals

---

## Requirements

- Tailwinds Framework
- PHP 8.1.10
- phpMyAdmin 5.2.1 or above
- composer 2.7.9 or above 

---

## Dev Installation Web
- install php
```C
you can install php from https://www.php.net/releases/ and follow the installer instruction
php must above 8.1.10
```
- install npm
```C
npm install
```
- install tailwindcss 
```C
npm install -D tailwindcss
```
- install composer
```C
you can install from https://getcomposer.org/download/ and follow the installer instruction
```
- Run the framework build or wacth using npm
```C
npm run build
```
or
```C
npm run watch
```

- Manage to main website with
```C
http://127.0.0.1:[your port]/src
```


---

## Dev Instalation Database

- If you using Laragon, go to C:\laragon\www\ChARM
```C
cd C:\laragon\www
```

- Please clone this repo and open it
```C
git clone https://github.com/yourusername/ChARM.git
cd ChARM
```

- Copy the '.env.example' to 'env'
```C
cp .env.example .env
```

- Open the .env file and update the following database configuration:
```C
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=charm_db
DB_USERNAME=admin
DB_PASSWORD=admin
```

- Run Laragon or any PHP dev enviroment

- Database instalas
```C
open charm/database/charm_db.sql
open database manager (xampp or laragon or any)
import charm_db.sql inside your database manager console
```

- Manage to website to test the database using
```C
http://localhost/charm/src
```
if it manage to Main Page, congrats you can running it



---

## Version

- Beta 1.9
  1. user now can register and login as user or admin (depends of their access)
  2. any user can forget password using email (with phpmailer as mail service)
  3. dev can manage user that can access admin page with using database manager
  4. user can fill their personal details in account setting
  5. 


---

## Academician

- [Mohammad Fiqri Firmansyah](https://github.com/TakanashaTaryu) [RYU]
  > As a BackEnd dev
- [Umar Zaki Gunawan](https://github.com/marzkigun27) [UZY]
  > As a FrontEnd dev
- [Dariele Zebada Sanuwu Gea](https://github.com/DrealGea) [DAZ]
  > As a FrontEnd dev

---

<div align="center">
  <p style="font-size: 20px; font-weight: 600; text-align: center;">WebDev 3 Academy 2024 <br> RYU DAZ UZY </p>
</div>
