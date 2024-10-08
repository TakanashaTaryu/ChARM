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

---

## Dev Installation Web
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
or
```C
http://localhost/
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

- Manage to website to test the database using
```C
http://localhost/charm/src
```
if it manage to Main Page, congrats you can running it

---

## Version

- Beta 1.2 Changelog
1. add full schematic database
2. user can register and login
3. admin can access admin page via login user (username & password = admin)
4. user can change password using email OTP that user input when register (email from tatsuarieyu@gmail.com)
5. index.php have some issue with script.js (it didnt detect location of script.js soo theres <script></script> in .php


---

## Academician

- [Mohammad Fiqri Firmansyah](https://github.com/TakanashaTaryu) [RYU]
  > As a FrontEnd dev
- [Umar Zaki Gunawan](https://github.com/marzkigun27) [UZY]
  > As a FrontEnd dev
- [Dariele Zebada Sanuwu Gea](https://github.com/DrealGea) [DAZ]
  > As a FrontEnd dev

---

<div align="center">
  <p style="font-size: 20px; font-weight: 600; text-align: center;">WebDev 3 Academy 2024 <br> RYU DAZ UZY </p>
</div>
