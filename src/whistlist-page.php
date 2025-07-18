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
    <title>Login page - ChARM</title>
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
        <nav id="navbar" class="flex max-md:fixed max-md:right-[-100%] max-md:top-16 max-md:p-5 transition-[right] max-md:w-[60vw] max-md:bg-custom_white  duration-300 ease-in-out z-10">
            <ul class="flex items-center md:space-x-6 max-md:flex-col max-md:items-start max-md:mt-4 max-md:p-5">
                <!-- Search Bar -->
                <form action="/search" method="GET" class="inline-block mt-2 w-[100%]">
                    <input type="text" name="q" placeholder="Search..." class="border rounded-lg px-2 py-1 text-custom_black">
                    <button type="submit" class="px-2 py-1 text-white bg-custom_black rounded-lg">Go</button>
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
                    <a href="#favorites">
                        <i class='bx bx-user text-3xl text-custom_black mt-2 hover:scale-110'></i>
                    </a>
                    <p class="tex-custom_black md:hidden">Account</p>

                </li>
            </ul>
        </nav>
    </header>
    <main>

        <section class="container flex flex-col items-center">
            <div class="container mx-auto mt-10">
                <h1 class="text-3xl text-custom_black font-bold mb-8 md:ml-8">MY WISHLIST</h1>
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3 md:ml-8">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex items-center mb-4">
                            <img src="asset/miku.jpg" alt="Yankee Candle Medium Jar - Turquoise Sky" class="w-24 h-24 rounded-lg">
                            <div class="ml-4">
                                <h3 class="text-xl text-custom_black font-bold">Miku</h3>
                                <p class="text-custom_brown text-sm">Libero magna mi suspendisse curabitur, lorem mi massa, ipsum pharetra posuere elementum.</p>
                            </div>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xl text-custom_black font-bold">Rp 55.000</span>
                            <div class="flex items-center">
                                <button class="bg-dark_orange hover:bg-custom_orange text-white font-bold py-2 px-4 rounded">Add to Cart</button>
                                <i class='bx bx-trash text-3xl font-bold mx-2'></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex items-center mb-4">
                            <img src="asset/miku.jpg" alt="Yankee Candle Medium Jar - Turquoise Sky" class="w-24 h-24 rounded-lg">
                            <div class="ml-4">
                                <h3 class="text-xl text-custom_black font-bold">Miku</h3>
                                <p class="text-custom_brown text-sm">Libero magna mi suspendisse curabitur, lorem mi massa, ipsum pharetra posuere elementum.</p>
                            </div>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xl text-custom_black font-bold">Rp 55.000</span>
                            <div class="flex items-center">
                                <button class="bg-dark_orange hover:bg-custom_orange text-white font-bold py-2 px-4 rounded">Add to Cart</button>
                                <i class='bx bx-trash text-3xl font-bold mx-2'></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex items-center mb-4">
                            <img src="asset/miku.jpg" alt="Yankee Candle Medium Jar - Turquoise Sky" class="w-24 h-24 rounded-lg">
                            <div class="ml-4">
                                <h3 class="text-xl text-custom_black font-bold">Miku</h3>
                                <p class="text-custom_brown text-sm">Libero magna mi suspendisse curabitur, lorem mi massa, ipsum pharetra posuere elementum.</p>
                            </div>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xl text-custom_black font-bold">Rp 55.000</span>
                            <div class="flex items-center">
                                <button class="bg-dark_orange hover:bg-custom_orange text-white font-bold py-2 px-4 rounded">Add to Cart</button>
                                <i class='bx bx-trash text-3xl font-bold mx-2'></i>
                            </div>
                        </div>
                    </div>
    
    
    
                
                
                    </div>
                </div>
            </div>
            
        </section>
    </main>
    <script src="script.js"></script>
</body>
  
</html>