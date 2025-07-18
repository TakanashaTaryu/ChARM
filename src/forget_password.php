<?php
session_start();
require_once 'db_connection.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

function generateOTP($length = 6) {
    return rand(pow(10, $length-1), pow(10, $length) - 1);
}

$message = "";

// Send OTP
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['send_otp'])) {
    $email = trim($_POST['email']);

    // Check if email exists and account is active
    $sql = "SELECT user_id FROM users WHERE email = ? AND status = 'active'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $otp = generateOTP();
        $expires_at = date('Y-m-d H:i:s', strtotime('+15 minutes')); // OTP expires in 15 minutes

        // Delete any existing OTP for this email
        $delete_sql = "DELETE FROM otp_codes WHERE email = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("s", $email);
        $delete_stmt->execute();

        // Insert new OTP with expiration
        $sql = "INSERT INTO otp_codes (email, otp_code, expires_at, created_at) VALUES (?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $email, $otp, $expires_at);
        $stmt->execute();

        // Send email (existing email code)
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'mohfirmansyah315@gmail.com';
            $mail->Password   = 'zbxg zggs gkep pzyv';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('mohfirmansyah315@gmail.com', 'ChARM Support');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Your OTP Code - ChARM';
            $mail->Body    = 'Password change action detected. If this was not you, please ignore this email. Your OTP code is: <b>' . $otp . '</b><br>This code will expire in 15 minutes.';
            $mail->AltBody = 'Password change action detected. If this was not you, please ignore this email. Your OTP code is: ' . $otp . '. This code will expire in 15 minutes.';

            $mail->send();
            $message = 'OTP has been sent to your email! It will expire in 15 minutes.';

            $_SESSION['reset_email'] = $email;
        } catch (Exception $e) {
            $message = "Error in sending email: {$mail->ErrorInfo}";
        }
    } else {
        $message = "Email does not exist or account is inactive!";
    }
}

// Verify OTP
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['verify_otp'])) {
    $entered_otp = trim($_POST['otp']);
    $email = $_SESSION['reset_email'] ?? '';

    if ($email) {
        // Check OTP and expiration
        $sql = "SELECT id FROM otp_codes WHERE email = ? AND otp_code = ? AND expires_at > NOW()";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $email, $entered_otp);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // OTP verified successfully
            $delete_sql = "DELETE FROM otp_codes WHERE email = ?";
            $delete_stmt = $conn->prepare($delete_sql);
            $delete_stmt->bind_param("s", $email);
            $delete_stmt->execute();

            unset($_SESSION['reset_email']);
            header("Location: recovery_password.php?email=" . urlencode($email));
            exit();
        } else {
            $message = "Invalid or expired OTP!";
        }
    } else {
        $message = "Session expired. Please request a new OTP.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forget Password - ChARM</title>
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
            <form action="forget_password.php" method="POST" class="space-y-4">
                <!-- Forget Password Header -->
                <div class="mt-6 text-center">
                    <p>Forget Your Password? We can help out!</p>
                    <hr class="border-custom_black mt-2">
                </div>

                <!-- Display Message (Success/Failure) -->
                <?php if (!empty($message)): ?>
                    <div class="alert alert-info"><?= $message ?></div>
                <?php endif; ?>

                <!-- Email Input -->
                <?php if (!isset($_SESSION['otp'])): ?>
                <div>
                    <label for="email" class="block text-custom_black font-bold mb-2">Email</label>
                    <input type="email" id="email" name="email" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-custom_orange mt-2" placeholder="Enter your email" required>
                </div>

                <!-- Send OTP Button -->
                <div>
                    <button type="submit" name="send_otp" class="w-full bg-dark_orange text-custom_white px-4 py-2 rounded-lg hover:bg-bright_cream hover:text-custom_brown">
                        Send OTP
                    </button>
                </div>
                <?php endif; ?>

                <!-- OTP Input (Shown after Send OTP) -->
                <?php if (isset($_SESSION['otp'])): ?>
                <div>
                    <label for="otp" class="block text-custom_black font-bold mb-2">Enter OTP</label>
                    <input type="text" id="otp" name="otp" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-custom_orange mt-2" placeholder="Enter the OTP" required>
                </div>

                <!-- Verify OTP Button -->
                <div>
                    <button type="submit" name="verify_otp" class="w-full bg-dark_orange text-custom_white px-4 py-2 rounded-lg hover:bg-bright_cream hover:text-custom_brown">
                        Verify OTP
                    </button>
                </div>
                <?php endif; ?>
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
    <script src="script_login-page.js"></script>
    <script>
const carousel = document.getElementById('carousel');
const images = carousel.children;
const totalImages = images.length;
let currentIndex = 0;
const intervalTime = 3000; // 3 seconds for auto slide

function nextSlide() {
    currentIndex = (currentIndex + 1) % totalImages;
    updateCarousel();
}

// Function to move to the previous image
function prevSlide() {
    currentIndex = (currentIndex - 1 + totalImages) % totalImages;
    updateCarousel();
}

// Update carousel position
function updateCarousel() {
    const offset = -currentIndex * 100; // Adjust to move images horizontally
    carousel.style.transform = `translateX(${offset}%)`;
}

// Auto-slide functionality
let autoSlide = setInterval(nextSlide, intervalTime);

// Event listeners for manual control
document.getElementById('nextBtn').addEventListener('click', () => {
    clearInterval(autoSlide); // Stop auto-slide on manual click
    nextSlide();
    autoSlide = setInterval(nextSlide, intervalTime); // Restart auto-slide after interaction
});

document.getElementById('prevBtn').addEventListener('click', () => {
    clearInterval(autoSlide); // Stop auto-slide on manual click
    prevSlide();
    autoSlide = setInterval(nextSlide, intervalTime); // Restart auto-slide after interaction
});</script>
</body>
</html>
