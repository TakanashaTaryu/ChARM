<?php

$registrationSuccess = false;
$registrationMessage = '';
$registrationButton = '<button type="submit" class="w-full bg-dark_orange text-custom_white px-4 py-2 rounded-lg hover:bg-bright_cream">Sign Up Now</button>';
$usernameExists = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username_db = "admin";
    $password_db = "admin";
    $dbname = "charm_db";

    include 'db_connection.php';

    $conn = new mysqli($servername, $username_db, $password_db, $dbname);

    if ($conn->connect_error) {
        die("Koneksi Gagal: " . $conn->connect_error);
    }

    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $checkUser = "SELECT * FROM users WHERE username='$username' OR email='$email'";
    $result = $conn->query($checkUser);

    if ($result->num_rows > 0) {
        $usernameExists = true;
        $registrationMessage = "<p class='text-red-500'>Username atau Email sudah terdaftar!</p>";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (username, email, password, created_at, updated_at) 
                VALUES ('$username', '$email', '$hashedPassword', NOW(), NOW())";

        if ($conn->query($sql) === TRUE) {
            $registrationSuccess = true;
            $registrationButton = '<button class="w-full bg-dark_orange text-custom_white px-4 py-2 rounded-lg hover:bg-bright_cream"><a href="login.php">Registration Successful, Login Now</a></button>';
        } else {
            $registrationMessage = "<p class='text-red-500'>Error: " . $sql . "<br>" . $conn->error . "</p>";
        }
    }

    // Tutup koneksi
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register page - ChARM</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.0.5/css/boxicons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&display=swap" rel="stylesheet">
</head>
<body class="font-lexend bg-gradient-to-r from-custom_orange via-bright_orange to-dark_cream">

    <!-- Header -->
    <header class="flex justify-between items-center p-4">
        <a href="index.php" class="flex gap-2 items-center">
            <img class="max-h-9 w-auto ml-1 md:ml-10" src="asset/logo_Charm.png" alt="logo_Charm">
            <span class="text-lg text-custom_black font-semibold mt-2">CHARM</span>
        </a>
    </header>

    <!-- Welcome Section -->
    <section class="container h-auto min-h-[80vh] max-w-5xl mx-auto py-16 px-4 flex flex-col md:flex-row items-center justify-between">
        <div class="text-center md:text-left md:w-1/2 space-y-4">
            <form action="register.php" method="POST" class="space-y-4">
                <!-- Username Input -->
                <div>
                    <label for="username" class="block text-custom_black font-bold mb-2">Username</label>
                    <input type="text" id="username" name="username" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-custom_orange" placeholder="Enter your username" required>
                </div>
                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-custom_black font-bold mb-2">Email</label>
                    <input type="email" id="email" name="email" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-custom_orange" placeholder="Enter your email" required>
                </div>
                <!-- Password Input -->
                <div>
                    <label for="password" class="block text-custom_black font-bold mb-2">Password</label>
                    <input type="password" id="password" name="password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-custom_orange" placeholder="Enter your password" required>
                </div>

                <?php
                // Tampilkan pesan feedback jika ada
                echo $registrationMessage;
                ?>

                <!-- Sign Up Button -->
                <div>
                    <?php 
                        echo $registrationButton;
                    ?>
                </div>

                <div class="text-center text-custom_black">
                    Already got an account? <span class="text-custom_black hover:underline"><a href="login.php">Sign in here!</a></span>
                </div>
            </form>
        </div>

        <!-- Carousel Images Section -->
        <div class="relative w-full max-w-xs h-64 overflow-hidden rounded-lg shadow-lg">
            <div id="carousel" class="flex transition-transform duration-500 ease-in-out">
                <div class="flex-none w-full h-full">
                    <img src="asset/costum-image-1.jpg" alt="Image 1" class="w-full h-full object-cover">
                </div>
                <div class="flex-none w-full h-full">
                    <img src="asset/costum-image-2.jpg" alt="Image 2" class="w-full h-full object-cover">
                </div>
                <div class="flex-none w-full h-full">
                    <img src="asset/costume-image-4.jpg" alt="Image 3" class="w-full h-full object-cover">
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
    
    <script src="../srcscript_login-page.js"></script>
    <script src="../src/script.js"></script>
</body>
</html>
