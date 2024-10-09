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
    <title>Chart page - ChARM</title>
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
        <h1 class="text-3xl font-bold text-custom_black mb-4 ml-2 sm:ml-4">Shopping Cart</h1>
        <div class="container mx-auto px-4 py-8 flex flex-col sm:flex-row sm:justify-between">
    
            <div class="flex flex-col ">
                <div class="bg-white rounded-lg shadow-md p-6 flex flex-col sm:flex-row sm:justify-between">
                    <div class="h-52 sm:h-10 w-auto max-sm:overflow-hidden">
                        <img src="asset/godzilla.jpeg" alt="StepSoft Socks" class="w-full h-48 object-cover rounded-lg mb-4">
                    </div>
                    <div class="sm:w-80 ml-4">
                        <h2 class="text-lg font-bold text-custom_black mb-2">Godzilla</h2>
                        <p class="text-dark_orange b-4 text-sm">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Ipsam odit quibusdam eaque vitae voluptate esse maiores modi placeat quia molestias sit possimus provident labore nulla, quidem reiciendis aliquam ab corporis!</p>
                        <div class="flex max-sm:flex-col sm:items-center sm:justify-between">
                            <form action="#">
                                <div class="mb-4">
                                    <label for="durasi" class="block text-dark_brown my-2">Renting time</label>
                                    <select id="durasi" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-custom_orange">
                                        <option value="">Chose Renting Time</option>
                                        <option value="1">1 day</option>
                                        <option value="2">2 day</option>
                                        <option value="3">3 day</option>
                                        <option value="3">1 week</option>
                                    </select>
                                </div>
                            </form>
                            <span class="text-custom_black font-bold">Rp 35.000</span>
                        </div>
                        <button class="bg-bright_orange hover:bg-gray-300 text-custom_white font-bold py-2 px-4 rounded mt-4">
                            Remove
                        </button>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6 mt-6 flex flex-col sm:flex-row sm:justify-between">
                    <div class="h-52 sm:h-10 w-auto max-sm:overflow-hidden">
                        <img src="asset/gojo.png" alt="costum Image" class="w-full h-48 object-cover rounded-lg mb-4">
                    </div>
                    <div class="sm:w-80 ml-4">
                        <h2 class="text-lg font-bold text-custom_black mb-2">Gojo</h2>
                        <p class="text-dark_orange b-4 text-sm">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Ipsam odit quibusdam eaque vitae voluptate esse maiores modi placeat quia molestias sit possimus provident labore nulla, quidem reiciendis aliquam ab corporis!</p>
                        <div class="flex max-sm:flex-col sm:items-center sm:justify-between">
                            <form action="#">
                                <div class="mb-4">
                                    <label for="durasi" class="block text-dark_brown my-2">Renting time</label>
                                    <select id="durasi" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-custom_orange">
                                        <option value="">Chose Renting Time</option>
                                        <option value="1">1 day</option>
                                        <option value="2">2 day</option>
                                        <option value="3">3 day</option>
                                        <option value="3">1 week</option>
                                    </select>
                                </div>
                            </form>
                            <span class="text-custom_black font-bold">Rp 50.000</span>
                        </div>
                        <button class="bg-bright_orange hover:bg-gray-300 text-custom_white font-bold py-2 px-4 rounded mt-4">
                            Remove
                        </button>
                    </div>
                </div>
            </div>
    
            <div class="bg-white rounded-lg shadow-md p-6 mt-6">
                <h2 class="text-xl font-bold text-custom_black mb-4">Order Summary</h2>
                <div class="flex items-center justify-between mb-4">
                    <span class="text-custom_brown">Subtotal</span>
                    <span class="text-dark_brown font-bold">Rp 85.000</span>
                </div>
                <div class="flex items-center justify-between mb-4">
                    <span class="text-custom_brown">Shipping</span>
                    <span class="text-dark_brown font-bold">FREE</span>
                </div>
                <div class="flex items-center justify-between mb-4">
                    <span class="text-custom_brown">Coupon Code</span>
                    <button class="bg-dark_cream hover:bg-gray-300 text-custom_orange font-bold py-2 px-4 rounded ml-2">
                        Add coupon code
                    </button>
                </div>
                <hr class="border-gray-300 mb-4">
                <div class="flex items-center justify-between">
                    <span class="text-xl font-bold text-custom_brown">Total</span>
                    <span class="text-xl font-bold text-dark_brown">Rp 85.000</span>
                </div>
                <button class="bg-dark_cream hover:bg-gray-300 text-custom_orange font-bold py-3 px-6 rounded mt-6">
                    <a href="checkout-page.php">Checkout</a>
                </button>
            </div>
        </div>
    </section>

</body>
  
</html>