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
    <title>Add Costume | ChARM</title>
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
    <section class="container flex flex-col items-center mt-10">
        <div class="text-white text-center text-3xl">
            <h1 class="font-bold"><b>Costume Details</b></h1>
            <p>Enter costume information</p>
        </div>
    </section>
    <section class="flex flex-col justify-between items-center my-10">
        <div class="text-white flex justify-between items-center my-5">
            <div class="mx-5">
                <form action="">
                    <label>Name :</label>
                    <p><input class="text-black" type="text" name="cosname" placeholder="" /></p>
                </form>
            </div>
            <div class="mx-5">
                <p>Kind :</p>
                <p>
                    <button class="mx-1 bg-black rounded p-2 hover:bg-white hover:text-black">Male</button>
                    <button class="mx-1 bg-black rounded p-2 hover:bg-white hover:text-black">Female</button>
                    <button class="mx-1 bg-black rounded p-2 hover:bg-white hover:text-black">Others</button>
                </p>
            </div>
            <div class="mx-5">
                <p>Category :</p>
                <p>
                    <button class="mx-1 bg-black rounded p-2 hover:bg-white hover:text-black">Halloween</button>
                    <button class="mx-1 bg-black rounded p-2 hover:bg-white hover:text-black">Fantasy</button>
                    <button class="mx-1 bg-black rounded p-2 hover:bg-white hover:text-black">Sci-fi</button>
                    <button class="mx-1 bg-black rounded p-2 hover:bg-white hover:text-black">Historical</button>
                </p>
            </div>
        </div>
        <div class="text-white flex justify-between items-center my-5">
            <div class="mx-5">
                <p>Type :</p>
                <p>
                    <button class="mx-1 bg-black rounded p-2 hover:bg-white hover:text-black">Fullset</button>
                    <button class="mx-1 bg-black rounded p-2 hover:bg-white hover:text-black">Costume Only</button>
                    <button class="mx-1 bg-black rounded p-2 hover:bg-white hover:text-black">Prop Only</button>
                </p>
            </div>
            <div class="mx-5">
                <p>Size :</p>
                <p>
                    <button class="mx-1 bg-black rounded p-2 hover:bg-white hover:text-black">S</button>
                    <button class="mx-1 bg-black rounded p-2 hover:bg-white hover:text-black">M</button>
                    <button class="mx-1 bg-black rounded p-2 hover:bg-white hover:text-black">L</button>
                    <button class="mx-1 bg-black rounded p-2 hover:bg-white hover:text-black">XL</button>
                </p>
            </div>
            <div class="mx-5">
                <form action="">
                    <label>Price :</label>
                    <p><input class="text-black" type="number" name="cosprice" placeholder="" /></p>
                </form>
            </div>
        </div>
        <div class="text-white flex justify-between items-center my-5">
            <div class="mx-5">
                <form action="">
                    <label>Description :</label>
                    <p><input class="text-black" type="text" name="cosdesc" placeholder="" /></p>
                </form>
            </div>
            <div class="mx-5">
                <form action="">
                    <label>Photo :</label>
                    <p><input class="text-white" type="file" name="cosphoto" placeholder="" /></p>
                </form>
            </div>
        </div>
        <div class="flex justify-between items-center my-5">
            <p class="mx-5">
                <button class="mx-2 bg-orange-200 text-black rounded p-2 hover:bg-orange-700 hover:text-white">Clear Form</button>
                <button class="mx-2 bg-orange-700 text-white rounded p-2 hover:bg-orange-200 hover:text-black">Add Costume</button>
            </p>
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