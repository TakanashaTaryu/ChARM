<?php
session_start();

// Pastikan user sudah login
if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in']) {
    header("Location: index.php");
    exit();
}

// Ambil user_id dari session
$user_id = $_SESSION['user_id'];

// Koneksi ke database
$servername = "localhost";
$username_db = "admin";
$password_db = "admin";
$dbname = "charm_db";

$conn = new mysqli($servername, $username_db, $password_db, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi Gagal: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Godzilla | ChARM</title>
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
        <section class="flex justify-between items-center p-4 my-3 mx-3">
            <div class="flex items-center">
                <img class="max-h-52 rounded-full w-auto ml-1 md:ml-10" src="./asset/godzilla.jpeg" alt="toga">
                <div>
                    <p class="text-3xl font-semibold mt-2 ml-5 text-white ">Godzilla</p>
                    <ul class="flex justify-normal items-start">
                        <li>
                            <p class="bg-blue-600 rounded max-w-max text-white ml-5 my-2 px-2">Sci-fi</p>
                        </li>
                        <li>
                            <p class="bg-blue-600 rounded max-w-max text-white ml-5 my-2 px-2">Halloween</p>
                        </li>
                        <li>
                            <p class="bg-blue-600 rounded max-w-max text-white ml-2 my-2 px-2">Other</p>
                        </li>
                    </ul>
                </div>
            </div>
            <p class="text-white flex justify-end items-center mx-auto text-lg">
                <div class="flex items-center">
                    <ul class="flex items-center">
                        <li>
                            <p class="bg-orange-800 text-white px-4 py-2 rounded mx-5">Status : Available</p>
                        </li>
                        <li>
                            <button class="bg-white text-black px-4 py-2 rounded hover:bg-black hover:text-white">Change Status</button>
                        </li>
                    </ul>
                </div>
            </p>
        </section>
        <section class="flex justify-normal items-center p-4 my-3 mx-3">
            <div class="ml-6">
                <div class="rounded-t bg-orange-300">
                    <img class="max-h-96 w-auto rounded-t" src="./asset/godzilla2.jpg" alt="">
                </div>
                <div class="bg-orange-800 rounded-b p-5 text-white">
                    <p>Size XL</p>
                    <p class="text-xl"><b>Rp 150.000/day</b></p>
                </div>
                <div class="text-center my-3">
                    <button class="bg-black text-white px-4 py-2 rounded hover:bg-white hover:text-black mr-1">Delete Costume</button>
                    <button class="bg-white text-black px-4 py-2 rounded hover:bg-black hover:text-white ml-1">Edit Costume</button>
                </div>
            </div>
            <div class="container max-w-[100vh] min-h-[80vh] justify-start items-start ml-12">
                <div>
                    <p class="text-3xl mb-5"><b>Description</b></p>
                </div>
                <div>
                    <p class="mb-5">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum euismod lectus vel elit accumsan dignissim. Cras sagittis lectus leo, ac ullamcorper erat sollicitudin eu.</p>
                </div>
                <div class="border-2 border-orange-950 bg-orange-300 justify-start">
                    <ul class="flex justify-normal items-center">
                        <li>
                            <img class="max-h-28 w-auto m-1 rounded md:m-10" src="./asset/godzilla.jpeg" alt="">
                        </li>
                        <li>
                            <p class="text-3xl"><b>Costume Only</b></p>
                            <p>Contains costume only</p>
                        </li>
                    </ul>
                </div>
            </div>
        </section>
        <section class="flex justify-normal items-center my-5 bg-orange-950 h-auto w-auto">
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