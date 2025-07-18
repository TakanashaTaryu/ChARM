<?php
session_start();

$loginSuccess = false;
$loginMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once 'db_connection.php';

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Updated query to match actual database schema
    $sql = "SELECT user_id, username, password, admin_value, status, email_verified_at, email FROM users WHERE username = ? AND status = 'active'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            // Check if email is verified
            if (is_null($user['email_verified_at'])) {
                $loginMessage = "<div class='bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded-lg mb-4'>";
                $loginMessage .= "<strong>Email Not Verified!</strong> Please verify your email before logging in. ";
                $loginMessage .= "<a href='verify_email.php?email=" . urlencode($user['email']) . "' class='underline font-semibold hover:text-yellow-800'>Verify Now</a>";
                $loginMessage .= "</div>";
            } else {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['is_logged_in'] = true;
                $_SESSION['admin_value'] = $user['admin_value'];

                if ($user['admin_value'] == 1) {
                    header("Location: admin/adminpage.php");
                    exit();
                } else {
                    header("Location: main_page.php");
                    exit();
                }
            }
        } else {
            $loginMessage = "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4'><strong>Error:</strong> Password salah!</div>";
        }
    } else {
        $loginMessage = "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4'><strong>Error:</strong> Username tidak ditemukan atau akun tidak aktif!</div>";
    }

    $stmt->close();
    $conn->close();
}
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
    <header class="flex justify-between items-center p-4">
        <a href="index.php" class="flex gap-2 items-center">
            <img class="max-h-9 w-auto ml-1 md:ml-10" src="asset/logo_Charm.png" alt="logo_Charm">
            <span class="text-lg text-custom_black font-semibold mt-2">CHARM</span>
        </a>
    </header>

    <!-- Welcome Section -->
    <section class="container h-auto min-h-[80vh] max-w-5xl mx-auto py-16 px-4 flex flex-col md:flex-row items-center justify-between">
        <div class="text-center md:text-left md:w-1/2 space-y-4">
            <!-- Form login -->
            <form id="loginForm" action="login.php" method="POST" class="space-y-4">
                <!-- Username Input -->
                <div>
                    <label for="username" class="block text-custom_black font-bold mb-2">Username</label>
                    <input type="text" id="username" name="username" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-custom_orange transition-all duration-300" placeholder="Enter your username" required>
                </div>
                <!-- Password Input -->
                <div>
                    <label for="password" class="block text-custom_black font-bold mb-2">Password</label>
                    <input type="password" id="password" name="password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-custom_orange transition-all duration-300" placeholder="Enter your password" required>
                </div>

                <?php echo $loginMessage; ?>

                <!-- Login Button -->
                <div>
                    <button type="submit" class="w-full bg-dark_orange text-custom_white px-4 py-2 rounded-lg hover:bg-bright_cream hover:text-custom_brown transition-colors duration-300">
                        Login
                    </button>
                </div>
            </form>

            <!-- Additional Links -->
            <div class="mt-4 text-center">
                <a href="forget_password.php" class="text-custom_black hover:underline">Forgot password?</a>
            </div>
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
    <script src="script_login_page.js"></script>
</body>
</html>
