<?php

if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in']) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product page - ChARM</title>
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
            <a href="main_page.php" class="flex gap-2 items-center">
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
                    <a href="whistlist-page.html">
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
        <!-- Product Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 px-4">
            
            <!-- Image Section -->
            <div class="relative w-full max-w-lg h-96 overflow-hidden rounded-lg shadow-lg">
                <!-- Images -->
                <div id="carousel" class="flex transition-transform duration-500 ease-in-out">
                    <div class="flex-none w-full h-full">
                        <img src="asset/gojo.png" alt="Image 1" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-none w-full h-full">
                        <img src="asset/gojo2.jpg" alt="Image 2" class="w-full h-full object-cover">
                    </div>
                </div>
        
                <!-- Previous Button -->
                <button id="prevBtn" class="absolute top-1/2 left-0 transform -translate-y-1/2 p-2  text-custom_black hover:translate-x-[-0.5rem]">
                    <i class='bx bx-chevron-left text-3xl '></i>
                </button>
        
                <!-- Next Button -->
                <button id="nextBtn" class="absolute top-1/2 right-0 transform -translate-y-1/2 p-2  text-custom_black hover:translate-x-[0.5rem]">
                    <i class='bx bx-chevron-right text-3xl'></i>
                </button>
            </div>
            
            <!-- Product Details Section -->
            <div>
                <!-- Title and Badge -->
                <div class="mb-4">
                    <h1 class="text-2xl font-bold">Gojo</h1>
                    <span class="bg-yellow-500 text-bright_cream text-sm px-2 py-1 rounded">Remaining 1</span>
                </div>

                <!-- Price -->
                <div class="text-3xl text-custom_black font-semibold mb-6">
                    Rp35.000/Hari
                </div>
                
                <!-- Action Buttons -->
                <div class="flex space-x-4 mb-6">
                    <button class="bg-custom_brown text-white px-4 py-2 rounded"><a href="checkout-page.php">Rent now</a></button>
                    <button class="bg-custom_white px-4 py-2 rounded"><a href="whistlist-page.html">Add to Wishlist</a></button>
                </div>

                <div class="">
                    <h3 class="text-custom_black font-semibold text-lg">Description</h3>
                    <p class="text-custom_brown">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Recusandae, ab magnam veritatis nostrum expedita quae repellat sint dolorem facilis minus quas nihil voluptates praesentium! Assumenda amet vel neque inventore itaque.</p>
                </div>
                
                <!-- Sub Options -->
                <div class="mt-5">
                    <h3 class="text-custom_black text-lg">See other costum</h3>
                    <div class="grid grid-cols-3 gap-8 p-5">
                        <div class="flex flex-col justify-items-start">
                            <div class="w-full bg-custom_white h-36 rounded-md flex items-center max-md:h-20 overflow-hidden">
                                <img src="asset/miku.jpg" alt="sub-option costum" class="rounded-md">
                            </div>
                            <div>
                                <p class="font-bold">Hatsune Miku</p>
                                <p class="text-sm left-0">Rp 50.000</p>
                            </div>
                        </div>
                        
                        <div class="flex flex-col justify-items-start">
                            <div class="w-full bg-custom_white h-36 rounded-md flex items-center max-md:h-20 overflow-hidden">
                                <img src="asset/nagaungu3.jpg" alt="sub-option costum" class="rounded-md">
                            </div>
                            <div>
                                <p class="font-bold">Naga Ungu</p>
                                <p class="text-sm left-0">Rp 35.0000</p>
                            </div>
                        </div>
                        
                        <div class="flex flex-col justify-items-start">
                            <div class="w-full bg-custom_white h-36 rounded-md flex items-center max-md:h-20 overflow-hidden">
                                <img src="asset/costume-image-4.jpg" alt="sub-option costum" class="rounded-md">
                            </div>
                            <div>
                                <p class="font-bold">Ino-Shika-Cho</p>
                                <p class="text-sm left-0">Rp 55.0000</p>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="script_product-page.js"></script>
</body>
  
</html>