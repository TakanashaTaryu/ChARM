<?php
session_start();

// Include PHPMailer
require_once '../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$registrationSuccess = false;
$registrationMessage = '';
$registrationButton = '<button type="submit" class="w-full bg-dark_orange text-custom_white px-4 py-2 rounded-lg hover:bg-bright_cream transition-colors duration-300">Sign Up Now</button>';
$usernameExists = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once 'db_connection.php';

    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Check if user already exists
    $checkUser = "SELECT user_id FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($checkUser);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $usernameExists = true;
        $registrationMessage = "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4'><strong>Error:</strong> Username atau Email sudah terdaftar!</div>";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Generate verification code
        $verificationCode = sprintf("%06d", mt_rand(100000, 999999));
        $expiresAt = date('Y-m-d H:i:s', strtotime('+24 hours'));

        try {
            // Start transaction
            $conn->begin_transaction();

            // Insert new user (email_verified_at will be NULL)
            $sql = "INSERT INTO users (username, email, password, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $username, $email, $hashedPassword);
            $stmt->execute();
            $user_id = $conn->insert_id;
            
            // Create default profile entry
            $profile_sql = "INSERT INTO profiles (user_id, created_at, updated_at) VALUES (?, NOW(), NOW())";
            $profile_stmt = $conn->prepare($profile_sql);
            $profile_stmt->bind_param("i", $user_id);
            $profile_stmt->execute();
            
            // Insert verification code
            $otp_sql = "INSERT INTO otp_codes (email, otp_code, purpose, expires_at, created_at) VALUES (?, ?, 'email_verification', ?, NOW())";
            $otp_stmt = $conn->prepare($otp_sql);
            $otp_stmt->bind_param("sss", $email, $verificationCode, $expiresAt);
            $otp_stmt->execute();
            
            // Send verification email
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'mohfirmansyah315@gmail.com';
            $mail->Password = 'zbxg zggs gkep pzyv'; // Use the same app password from forget_password.php
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            
            $mail->setFrom('mohfirmansyah315@gmail.com', 'ChARM Support');
            $mail->addAddress($email, $username);
            
            $mail->isHTML(true);
            $mail->Subject = 'Verify Your ChARM Account';
            $mail->Body = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; background: linear-gradient(135deg, #ff6b35, #f7931e); padding: 20px; border-radius: 10px;'>
                <div style='background: white; padding: 30px; border-radius: 10px; text-align: center;'>
                    <h1 style='color: #333; margin-bottom: 20px;'>Welcome to ChARM!</h1>
                    <p style='color: #666; font-size: 16px; margin-bottom: 30px;'>Thank you for registering with ChARM. Please verify your email address to activate your account.</p>
                    
                    <div style='background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0;'>
                        <h2 style='color: #ff6b35; margin: 0 0 10px 0;'>Your Verification Code</h2>
                        <div style='font-size: 32px; font-weight: bold; color: #333; letter-spacing: 5px; font-family: monospace;'>$verificationCode</div>
                    </div>
                    
                    <p style='color: #666; font-size: 14px; margin-top: 20px;'>This code will expire in 24 hours.</p>
                    <p style='color: #666; font-size: 14px;'>If you didn't create an account with ChARM, please ignore this email.</p>
                    
                    <div style='margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee;'>
                        <p style='color: #999; font-size: 12px;'>© 2024 ChARM - Costume Rental Service</p>
                    </div>
                </div>
            </div>
            ";
            
            $mail->send();
            
            // Commit transaction
            $conn->commit();
            
            $registrationSuccess = true;
            $registrationMessage = "<div class='bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4'><strong>Success!</strong> Registration successful! Please check your email for verification code.</div>";
            $registrationButton = '<a href="verify_email.php?email=' . urlencode($email) . '" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors duration-300 inline-block text-center">Verify Email Now</a>';
            
        } catch (Exception $e) {
            $conn->rollback();
            $registrationMessage = "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4'><strong>Error:</strong> Registration failed. Please try again.</div>";
        }
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
                    <input type="text" id="username" name="username" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-custom_orange transition-all duration-300" placeholder="Enter your username" required>
                </div>
                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-custom_black font-bold mb-2">Email</label>
                    <input type="email" id="email" name="email" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-custom_orange transition-all duration-300" placeholder="Enter your email" required>
                </div>
                <!-- Password Input -->
                <div>
                    <label for="password" class="block text-custom_black font-bold mb-2">Password</label>
                    <input type="password" id="password" name="password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-custom_orange transition-all duration-300" placeholder="Enter your password" required>
                </div>

                <?php echo $registrationMessage; ?>

                <!-- Sign Up Button -->
                <div>
                    <?php echo $registrationButton; ?>
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
