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
    <title>Check out - ChARM</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.0.5/css/boxicons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&display=swap" rel="stylesheet">
</head>
<body class="font-lexend bg-gradient-to-r from-custom_orange via-bright_orange to-dark_cream">

    <!-- Header -->
    <header class="flex justify-between overflow-hidden items-center p-4">
        <div id="logo" class="">
            <a href="#" class="flex gap-2 items-center">
                <img class="max-h-9 w-auto ml-1 md:ml-10" src="asset/logo_Charm.png" alt="logo_Charm">
                <span class="text-lg font-semibold mt-2 text-custom_black">CHARM</span>
            </a>
        </div>

        <div class="md:hidden flex items-center gap-4">
            <!-- Menu Icon -->
            <button onclick="handleMenu()" class="flex items-center">
                <i class='bx bx-menu text-3xl text-custom_black'></i>
            </button>
        </div>
        <!-- navbar -->
        <nav id="navbar" class="flex max-md:fixed max-md:right-[-100%] max-md:top-16 max-md:p-5 transition-[right] max-sm:w-[70%] max-md:w-[60%] max-md:bg-custom_white  duration-300 ease-in-out z-10">
            <ul class="flex items-center md:space-x-6 max-md:flex-col max-md:items-start max-md:mt-4 max-md:p-5">
                <!-- Search Bar -->
                <form action="/search" method="GET" class="inline-block mt-2 w-[100%]">
                    <input type="text" name="q" placeholder="Search..." class="border rounded-lg px-2 py-1 text-custom_black">
                    <button type="submit" class="px-2 py-1 text-white bg-custom_black rounded-lg max-sm:mt-3">Go</button>
                </form>
                <!-- home-icon -->
                <li class="max-md:flex max-md:flex-row max-md:gap-3 max-md:items-center">
                    <a href="main_page.php">
                        <i class='bx bx-home-alt text-3xl text-custom_black mt-2 hover:scale-110'></i>
                    </a>
                    <p class="tex-custom_black md:hidden">Home</p>
                </li>
                <li class="max-md:flex max-md:flex-row max-md:gap-3 max-md:items-center">
                    <a href="chart-page.php">
                        <i class='bx bx-shopping-bag text-3xl text-custom_black mt-2 hover:scale-110'></i>
                    </a>
                    <p class="tex-custom_black md:hidden">Chart</p>
                </li>
                <!-- Heart Icon -->
                <li class="max-md:flex max-md:flex-row max-md:gap-3 max-md:items-center">
                    <a href="whistlist-page.php">
                        <i class='bx bx-heart text-3xl text-custom_black mt-2 hover:scale-110'></i>
                    </a>
                    <p class="tex-custom_black md:hidden">Wish List</p>

                </li>
                <li class="max-md:flex max-md:flex-row max-md:gap-3 max-md:items-center">
                    <a href="account_setting_page.php">
                        <i class='bx bx-user text-3xl text-custom_black mt-2 hover:scale-110'></i>
                    </a>
                    <p class="tex-custom_black md:hidden">Account</p>

                </li>
            </ul>
        </nav>
    </header>

        <!-- Main Container -->
    <section class="max-w-6xl mx-auto py-10">
        
        <div class="max-w-3xl mx-auto my-10 p-8 bg-white rounded-lg shadow-md">
        
            <!-- Title -->
            <h2 class="text-2xl font-bold mb-6 text-custom_black">Check out</h2>
            
            <!-- Form Start -->
            <form action="#">
                
                <!-- Full Name -->
                <div class="mb-4">
                    <label for="nama" class="block text-custom_brown mb-2">Nama lengkap</label>
                    <input type="text" id="nama" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400" placeholder="Nama lengkap">
                </div>
                
                <!-- Address -->
                <div class="mb-4">
                    <label for="alamat" class="block text-custom_brown mb-2">Alamat</label>
                    <input type="text" id="alamat" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400" placeholder="Alamat">
                </div>
                
                <!-- Payment Method -->
                <div class="mb-4">
                    <label for="metode" class="block text-custom_brown mb-2">Metode pembayaran</label>
                    <select id="metode" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400">
                        <option value="">Pilih Metode Pembayaran</option>
                        <option value="bank">Bank Transfer</option>
                        <option value="cod">Cash on Delivery (COD)</option>
                    </select>
                </div>
                
                <!-- Delivery Note -->
                <div class="mb-4">
                    <label for="note" class="block text-custom_brown mb-2">Delivery note</label>
                    <textarea id="note" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400" rows="4" placeholder="Add a note"></textarea>
                </div>
    
                <!-- Submit Button -->
                <button type="submit" class="w-full bg-custom_black text-white py-3 rounded-lg">Submit</button>
            
            </form>
        </div>

    </section>
    <script src="script.js"></script>
    <script type="text/javascript"
    src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="SB-Mid-client-PTOwdx_ZDMJ31gS1"></script>

</body>
  
</html>