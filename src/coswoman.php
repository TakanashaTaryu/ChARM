<?php
session_start();

// Pastikan user sudah login
if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in']) {
    header("Location: index.php");
    exit();
}

// Ambil user_id dari session
$user_id = $_SESSION['user_id'];

require_once 'db_connection.php';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Costumes | ChARM</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.0.5/css/boxicons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&display=swap" rel="stylesheet">
</head>
<body class="font-[lexend] bg-gradient-to-r from-orange-600 to-[#ea8b24]">
    <header class="flex justify-between items-center p-4 bg-orange-950">
        <a href="#" class="flex gap-2 items-center">
            <img class="max-h-9 w-auto ml-1 md:ml-10" src="./asset/logo_Charm.png" alt="logo_Charm">
            <span class="text-lg font-semibold mt-2 text-white">CHARM Admin Page</span>
        </a>
        <nav class="text-white flex justify-end items-center w-[92%] mx-auto text-lg">
            <div>
                <ul class="flex items-center gap-[4vw]">
                    <li>
                        <a class="hover:text-orange-500" href="./adminpage.php">Home</a>
                    </li>
                    <li>
                        <a class="hover:text-orange-500" href="./costumes.php">Costumes</a>
                    </li>
                    <li>
                        <a class="hover:text-orange-500" href="./addcos.php">Add Costumes</a>
                    </li>
                    <li>
                        <a class="hover:text-orange-500" href="./statcos.php">Status</a>
                    </li>
                    <li>
                        <button class="bg-white text-black px-4 py-2 rounded-full hover:bg-black hover:text-white" href="./index.php"><a href="./index.php">Log Out </a></button>

                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <section class="container flex justify-start">
        <div class="bg-orange-700  text-orange-200 min-h-[100vh]">
            <p class="mx-5 mt-5"><a href="./costumes.php">All Costumes</a></p>
            <hr class="m-2">
            <p class="mx-5"><a href="./coswoman.php">Woman Costumes</a></p>
            <hr class="m-2">
            <p class="mx-5"><a href="./cosman.php">Man Costumes</a></p>
            <hr class="m-2">
            <p class="mx-5"><a href="./cosothers.php">Other Costumes</a></p>
            <hr class="m-2">
        </div>
        <div class="flex flex-col">
            <div class="text-white text-left text-3xl my-5 mx-7">
                <h1 class="font-bold"><b>Woman Costumes</b></h1>
            </div>
            <div class="mx-5">
                <a href="./cosinfo1.php">
                    <button class="bg-orange-200 rounded p-5 m-5">
                        <p><img class="max-h-64 w-auto m-1 rounded" src="./asset/toga1.jpg" alt=""></p>
                        <p>Himiko Toga</p>
                        <p class="font-bold">Available</p>
                    </button>
                    </a>
                    <a href="./cosinfo2.php">
                        <button class="bg-orange-200 rounded p-5 m-5">
                            <p><img class="max-h-64 w-auto m-1 rounded" src="./asset/miku.jpg" alt=""></p>
                            <p>Hatsune Miku</p>
                            <p class="font-bold">Rented</p>
                        </button>
                    </a>
            </div>
        </div>
    </section>
    <section class="flex justify-normal items-center mb-5 bg-orange-950 h-auto w-auto">
        <div>
            <img class="max-h-24 w-auto m-1 rounded-full md:m-10" src="./asset/Pas foto formal fixed.jpg" alt="foto">
        </div>
        <div class="text-white">
            <h1><b>DAZ</b></h1>
            <div class="bg-blue-600 rounded max-w-max">Admin</div>
            <p>Welcome to ChARM Admin Panel</p>
        </div>
    </section>
    <footer class="container flex flex-col items-center m-10">
        <p class="text-white text-center text-lg">2023 ChARM Rentcos Company</p>
    </footer>
</body>
</html>