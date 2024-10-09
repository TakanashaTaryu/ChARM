<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username_db = "admin";
    $password_db = "admin";
    $dbname = "charm_db";

    include 'db_connection.php';


    $conn = new mysqli($servername, $username_db, $password_db, $dbname);

    //Cek koneksi
    if ($conn->connect_error) {
        die("Koneksi Gagal: " . $conn->connect_error);
    }
}

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
    <title>Home Page - ChARM</title>
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
                    <input type="text" name="q" placeholder="Search Costume..." class="border rounded-lg px-2 py-1 text-custom_black">
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
    <!-- Carousel Section -->
    <section class="flex justify-center mt-6 px-4">
        <div class="relative w-[90%] h-[50vh] overflow-hidden rounded-lg shadow-lg">
            
            <!-- Carousel Container -->
            <div id="carousel" class="relative w-full h-full overflow-hidden">
                <!-- Carousel Images -->
                <div class="flex w-full h-full transition-transform duration-500 ease-in-out" style="transform: translateX(0);">
                    <div class="relative flex-none w-full h-full">
                        <img src="asset\costume-scrol1.png" alt="Image 1" class="w-full h-full object-cover rounded-lg">
                        <div class="absolute bottom-0 left-0 w-full h-[20%] bg-gradient-to-b from-transparent to-black opacity-30"></div>
                    </div>
                    <div class="relative flex-none w-full h-full">
                        <img src="asset\costume-scrol2.png" alt="Image 2" class="w-full h-full object-cover rounded-lg">
                        <div class="absolute bottom-0 left-0 w-full h-[20%] bg-gradient-to-b from-transparent to-black opacity-30"></div>
                    </div>
                    <div class="relative flex-none w-full h-full">
                        <img src="asset\costume-scrol3.png" alt="Image 3" class="w-full h-full object-cover rounded-lg">
                        <div class="absolute bottom-0 left-0 w-full h-[20%] bg-gradient-to-b from-transparent to-black opacity-30"></div>
                    </div>
                </div>
            </div>
    
            <!-- Previous Button -->
            <button id="prevBtn" class="absolute top-1/2 left-0 transform -translate-y-1/2 p-2 text-custom_black hover:translate-x-[-0.5rem]">
                <i class='bx bx-chevron-left text-3xl'></i>
            </button>
    
            <!-- Next Button -->
            <button id="nextBtn" class="absolute top-1/2 right-0 transform -translate-y-1/2 p-2 text-custom_black hover:translate-x-[0.5rem]">
                <i class='bx bx-chevron-right text-3xl'></i>
            </button>
        </div>
    </section>
    

    <!-- Label Filter Section -->
    <section class="flex mt-6 px-4">
        <div class="flex flex-wrap space-x-2 my-8 md:ml-10 ">
            <button class="btn bg-dark_cream  text-custom_brown px-4 py-2 my-2 rounded-md ">Anime</button>
            <button class="btn bg-dark_cream text-custom_brown px-4 py-2 my-2 rounded-md">Game</button>
            <button class="btn bg-dark_cream text-custom_brown px-4 py-2 my-2 rounded-md">Furry</button>
            <button class="btn bg-dark_cream text-custom_brown px-4 py-2 my-2 rounded-md">Film</button>
            <button class="btn bg-dark_cream text-custom_brown px-4 py-2 my-2 rounded-md">Original</button>
            <button class="btn bg-dark_cream text-custom_brown px-4 py-2 my-2 rounded-md">Parts</button>
        </div>
    </section>
    <script>
        const buttons = document.querySelectorAll('.btn');
        // Remove class from all buttons
        buttons.forEach(btn => btn.classList.remove('bg-dark_cream', 'text-custom_brown'));
        buttons.forEach(btn => btn.classList.add('bg-dark_cream', 'text-custom_brown'));
        
        buttons.forEach(button => {
            button.addEventListener('click', function () {
                buttons.forEach(btn => btn.classList.remove('bg-custom_orange', 'text-custom_white'));
                // Add 'active' class to the clicked button
                this.classList.add('bg-custom_orange', 'text-custom_white');
            });
        });
    </script>

    <!-- Product Grid Section -->
    <section class="mt-6 px-4 flex justify-center">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 w-full max-w-6xl">

            <!-- Product Card 1 -->
        <div id="anime" class="product-category" href="product_page(nagaungu).php">
            <div class="bg-white shadow-md rounded-md overflow-hidden" href="product_page(nagaungu).php">
                <!-- Image Section -->
                <div class="bg-gray-300 h-40 relative">
                    <a href="product_page(nagaungu).php">
                    <img src="asset/stok1.png" alt="Description" class="w-full h-full object-cover">
                    </a>
                </div>
                <!-- Text Content -->
                <div class="p-4">
                    <a class="bg-green-500 text-white px-2 py-1 rounded-full text-sm">Available</a>
                            <p class="font-bold max-md:text-sm ">Naga Ungu</p>
                    <p class="font-semibold">Rp 20.000/hari</p>
                </div>
            </div>
        </div>


            
        <div id="anime" class="product-category" href="product_page(nagaungu).php">
            <div class="bg-white shadow-md rounded-md overflow-hidden" href="product_page(miku).php">
                <!-- Image Section -->
                <div class="bg-gray-300 h-40 relative">
                    <a href="product_page(miku).php">
                    <img src="asset/miku.jpg" alt="Description" class="w-full h-full object-cover">
                    </a>
                </div>
                <!-- Text Content -->
                <div class="p-4">
                    <span class="bg-red-700 text-white px-2 py-1 rounded-full text-sm">Out of Stock</span>
                            <p class="font-bold max-md:text-sm ">Hatsune Miku</p>
                    <p class="font-semibold">Rp 20.000/hari</p>
                </div>
            </div>
        </div>


        <div id="anime" class="product-category" href="product_page(nagaungu).php">
            <div class="bg-white shadow-md rounded-md overflow-hidden" href="product_page(gojo).php">
                <!-- Image Section -->
                <div class="bg-gray-300 h-40 relative">
                    <a href="product_page(gojo).php">
                    <img src="asset/gojo.png" alt="Description" class="w-full h-full object-cover">
                    </a>
                </div>
                <!-- Text Content -->
                <div class="p-4">
                    <span class=" bg-yellow-500 text-white px-2 py-1 rounded-full text-sm">Remaining 1</span>
                            <p class="font-bold max-md:text-sm ">Gojo Satoru</p>
                    <p class="font-semibold">Rp 20.000/hari</p>
                </div>
            </div>
        </div>


            <!-- Product Card 2 -->
        <div id="anime" class="product-category">
            <div class="bg-white shadow-md rounded-md overflow-hidden">
                <div class="bg-gray-300 h-40">
                    <img src="asset/stok2.png" alt="Description" class="w-full h-full object-cover">
                </div>
                <div class="p-4">
                    <span class="bg-red-700 text-white px-2 py-1 rounded-full text-sm">Out of Stock</span>
                    <p class="font-bold max-md:text-sm ">Kucing Cream</p>
                    <p class="font-semibold">Rp 15.000/hari</p>
                </div>
            </div>
        </div>

            <!-- Product Card 3 -->
        <div id="anime" class="product-category">
            <div class="bg-white shadow-md rounded-md overflow-hidden">
                <div class="bg-gray-300 h-40">
                    <img src="asset/stok3.png" alt="Description" class="w-full h-full object-cover">
                </div>
                <div class="p-4">
                    <a class="bg-green-500 text-white px-2 py-1 rounded-full text-sm">Available</a>

                    <p class="font-bold max-md:text-sm ">Wolf Hijau</p>
                    <p class="font-semibold">Rp 25.000/hari</p>
                </div>
            </div>
        </div>

            <!-- Product Card 4 -->
        <div id="anime" class="product-category">
            <div class="bg-white shadow-md rounded-md overflow-hidden">
                <div class="bg-gray-300 h-40">
                    <img src="asset/stok4.png" alt="Description" class="w-full h-full object-cover">
                </div>
                <div class="p-4">
                    <a class="bg-green-500 text-white px-2 py-1 rounded-full text-sm">Available</a>

                    <p class="font-bold max-md:text-sm ">Devil Rabbit</p>
                    <p class="font-semibold">Rp 25.000/hari</p>
                </div>
            </div>
        </div>

            <!-- Product Card 5 -->
        <div id="anime" class="product-category">
            <div class="bg-white shadow-md rounded-md overflow-hidden">
                <div class="bg-gray-300 h-40">
                    <img src="asset/stok5.png" alt="Description" class="w-full h-full object-cover">
                </div>
                <div class="p-4">
                    <span class=" bg-yellow-500 text-white px-2 py-1 rounded-full text-sm">Remaining 1</span>
                    <p class="font-bold max-md:text-sm ">Nezuko</p>
                    <p class="font-semibold">Rp 25.000/hari</p>
                </div>
            </div>
        </div>

            <!-- Product Card 6 -->
        <div id="anime" class="product-category">
            <div class="bg-white shadow-md rounded-md overflow-hidden">
                <div class="bg-gray-300 h-40">
                    <img src="asset/stok6.png" alt="Description" class="w-full h-full object-cover">
                </div>
                <div class="p-4">
                    <a class="bg-green-500 text-white px-2 py-1 rounded-full text-sm">Available</a>

                    <p class="font-bold max-md:text-sm ">Zhongli Genshin</p>
                    <p class="font-semibold">Rp 25.000/hari</p>
                </div>
            </div>
        </div>
        </div>
    </section>

    <script src="script.js"></script>
</body>
  
</html>