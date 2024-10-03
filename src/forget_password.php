<?php
session_start();

$servername = "localhost"; 
$username = "admin";
$password = "admin";
$dbname = "charm_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

function generateOTP($length = 6) {
    return rand(pow(10, $length-1), pow(10, $length) - 1);
}

$message = "";

// Jika tombol Send OTP ditekan
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['send_otp'])) {
    $email = $_POST['email'];

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $otp = generateOTP();
        $created_at = date('Y-m-d H:i:s');

        $sql = "INSERT INTO otp_codes (email, otp_code, created_at) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $email, $otp, $created_at);
        $stmt->execute();

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'tatsuarieyu@gmail.com'; // Ganti dengan email Anda
            $mail->Password   = 'ogpi egzo tznr vawk';    // Ganti dengan password aplikasi
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Pengirim
            $mail->setFrom('tatsuarieyu@gmail.com', 'ChARM Support');
            // Penerima
            $mail->addAddress($email);

            // Isi email
            $mail->isHTML(true);
            $mail->Subject = 'Your OTP Code';
            $mail->Body    = 'Here is your OTP code: <b>' . $otp . '</b>';
            $mail->AltBody = 'Here is your OTP code: ' . $otp;

            $mail->send();
            $message = 'OTP has been sent to your email!';

            $_SESSION['otp'] = $otp;
            $_SESSION['email'] = $email;
        } catch (Exception $e) {
            $message = "Error in sending email: {$mail->ErrorInfo}";
        }
    } else {
        $message = "Email does not exist!";
    }
}

// Jika tombol Verify OTP ditekan
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['verify_otp'])) {
    $entered_otp = $_POST['otp'];

    if ($entered_otp == $_SESSION['otp']) {
        // Hapus OTP dari database setelah berhasil diverifikasi
        $email = $_SESSION['email'];
        $sql = "DELETE FROM otp_codes WHERE email = ? AND otp_code = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $email, $entered_otp);
        $stmt->execute();

        // OTP verified successfully, proceed to password recovery
        unset($_SESSION['otp']); // Hapus sesi OTP setelah verifikasi
        header("Location: recovery_password.html");
        exit();
    } else {
        $message = "Invalid OTP!";
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
</body>
</html>
