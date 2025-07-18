<?php
session_start();

// Include PHPMailer for resending codes
require_once '../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$verificationMessage = '';
$email = $_GET['email'] ?? '';
$showResendButton = true;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once 'db_connection.php';
    
    if (isset($_POST['verify_code'])) {
        $email = trim($_POST['email']);
        $enteredCode = trim($_POST['verification_code']);
        
        // Check if code is valid
        $sql = "SELECT id FROM otp_codes WHERE email = ? AND otp_code = ? AND purpose = 'email_verification' AND expires_at > NOW() AND is_used = 0";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $email, $enteredCode);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            try {
                $conn->begin_transaction();
                
                // Mark email as verified
                $update_user = "UPDATE users SET email_verified_at = NOW() WHERE email = ?";
                $stmt_user = $conn->prepare($update_user);
                $stmt_user->bind_param("s", $email);
                $stmt_user->execute();
                
                // Mark OTP as used
                $update_otp = "UPDATE otp_codes SET is_used = 1 WHERE email = ? AND purpose = 'email_verification'";
                $stmt_otp = $conn->prepare($update_otp);
                $stmt_otp->bind_param("s", $email);
                $stmt_otp->execute();
                
                $conn->commit();
                
                $verificationMessage = "<div class='bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4'><strong>Success!</strong> Your email has been verified! You can now login to your account.</div>";
                $showResendButton = false;
                
            } catch (Exception $e) {
                $conn->rollback();
                $verificationMessage = "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4'><strong>Error:</strong> Verification failed. Please try again.</div>";
            }
        } else {
            $verificationMessage = "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4'><strong>Error:</strong> Invalid or expired verification code.</div>";
        }
    }
    
    if (isset($_POST['resend_code'])) {
        $email = trim($_POST['email']);
        
        // Generate new verification code
        $verificationCode = sprintf("%06d", mt_rand(100000, 999999));
        $expiresAt = date('Y-m-d H:i:s', strtotime('+24 hours'));
        
        try {
            // Delete old codes
            $delete_sql = "DELETE FROM otp_codes WHERE email = ? AND purpose = 'email_verification'";
            $stmt_delete = $conn->prepare($delete_sql);
            $stmt_delete->bind_param("s", $email);
            $stmt_delete->execute();
            
            // Insert new code
            $otp_sql = "INSERT INTO otp_codes (email, otp_code, purpose, expires_at, created_at) VALUES (?, ?, 'email_verification', ?, NOW())";
            $otp_stmt = $conn->prepare($otp_sql);
            $otp_stmt->bind_param("sss", $email, $verificationCode, $expiresAt);
            $otp_stmt->execute();
            
            // Send new verification email
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'mohfirmansyah315@gmail.com';
            $mail->Password = 'zbxg zggs gkep pzyv';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            
            $mail->setFrom('mohfirmansyah315@gmail.com', 'ChARM Support');
            $mail->addAddress($email);
            
            $mail->isHTML(true);
            $mail->Subject = 'New Verification Code - ChARM';
            $mail->Body = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; background: linear-gradient(135deg, #ff6b35, #f7931e); padding: 20px; border-radius: 10px;'>
                <div style='background: white; padding: 30px; border-radius: 10px; text-align: center;'>
                    <h1 style='color: #333; margin-bottom: 20px;'>New Verification Code</h1>
                    <p style='color: #666; font-size: 16px; margin-bottom: 30px;'>Here's your new verification code for ChARM account activation.</p>
                    
                    <div style='background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0;'>
                        <h2 style='color: #ff6b35; margin: 0 0 10px 0;'>Your New Verification Code</h2>
                        <div style='font-size: 32px; font-weight: bold; color: #333; letter-spacing: 5px; font-family: monospace;'>$verificationCode</div>
                    </div>
                    
                    <p style='color: #666; font-size: 14px; margin-top: 20px;'>This code will expire in 24 hours.</p>
                </div>
            </div>
            ";
            
            $mail->send();
            
            $verificationMessage = "<div class='bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded-lg mb-4'><strong>Code Sent!</strong> A new verification code has been sent to your email.</div>";
            
        } catch (Exception $e) {
            $verificationMessage = "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4'><strong>Error:</strong> Failed to send verification code. Please try again.</div>";
        }
    }
    
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification - ChARM</title>
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

    <!-- Verification Section -->
    <section class="container h-auto min-h-[80vh] max-w-2xl mx-auto py-16 px-4">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="text-center mb-8">
                <i class='bx bx-envelope text-6xl text-custom_orange mb-4'></i>
                <h1 class="text-3xl font-bold text-custom_black mb-2">Verify Your Email</h1>
                <p class="text-gray-600">We've sent a verification code to <strong><?php echo htmlspecialchars($email); ?></strong></p>
            </div>
            
            <?php echo $verificationMessage; ?>
            
            <?php if ($showResendButton): ?>
            <form method="POST" class="space-y-6">
                <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
                
                <div>
                    <label for="verification_code" class="block text-custom_black font-bold mb-2">Enter Verification Code</label>
                    <input type="text" id="verification_code" name="verification_code" 
                           class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-custom_orange transition-all duration-300 text-center text-2xl font-mono tracking-widest" 
                           placeholder="000000" maxlength="6" pattern="[0-9]{6}" required>
                </div>
                
                <div class="space-y-3">
                    <button type="submit" name="verify_code" 
                            class="w-full bg-dark_orange text-white px-4 py-3 rounded-lg hover:bg-bright_cream hover:text-custom_brown transition-colors duration-300 font-semibold">
                        Verify Email
                    </button>
                    
                    <button type="submit" name="resend_code" 
                            class="w-full bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors duration-300">
                        Resend Code
                    </button>
                </div>
            </form>
            <?php else: ?>
            <div class="text-center">
                <a href="login.php" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors duration-300 inline-block font-semibold">
                    Go to Login
                </a>
            </div>
            <?php endif; ?>
            
            <div class="text-center mt-6">
                <p class="text-gray-600 text-sm">Didn't receive the code? Check your spam folder or click resend.</p>
            </div>
        </div>
    </section>

</body>
</html>