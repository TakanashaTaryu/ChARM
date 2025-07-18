<?php
session_start();

// Koneksi database
$servername = "localhost";
$username_db = "admin";
$password_db = "admin";
$dbname = "charm_new";
$conn = new mysqli($servername, $username_db, $password_db, $dbname);

if ($conn->connect_error) {
    die("Koneksi Gagal: " . $conn->connect_error);
}

// Mendapatkan email dari URL (query parameter)
if (isset($_GET['email'])) {
    $email = $_GET['email']; // Email dari forget_password.php
} else {
    die("Email is required!");
}

$message = "";

// Jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newpassword = $_POST['new-password'];
    $confirmpassword = $_POST['confirm-password'];

    if ($newpassword == $confirmpassword) {
        // Hash password baru
        $hashed_password = password_hash($newpassword, PASSWORD_DEFAULT);

        // Update password di database
        $sql = "UPDATE users SET password = ? WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $hashed_password, $email);
        if ($stmt->execute()) {
            $message = "Password berhasil diubah! Silakan login dengan password baru Anda.";
            header("Location: login.php");
            exit();
        } else {
            $message = "Error updating password!";
        }
    } else {
        $message = "Passwords do not match!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recovery Password - ChARM</title>
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

    <!-- Recovery Password Section -->
    <section class="container h-auto min-h-[80vh] max-w-5xl mx-auto py-16 px-4 flex flex-col items-center">
        <div class="text-center md:w-1/2 space-y-4 mb-8">
            <form action="" method="POST" class="space-y-4">
                <!-- Recovery Password Header -->
                <div class="mt-6 text-center">
                    <p>Reset your password for <b><?php echo htmlspecialchars($email); ?></b></p>
                    <hr class="border-custom_black mt-2">
                </div>

                <!-- Display Message (Success/Failure) -->
                <?php if (!empty($message)): ?>
                    <div class="alert alert-info"><?= $message ?></div>
                <?php endif; ?>

                <!-- New Password Input -->
                <div>
                    <label for="new-password" class="block text-custom_black font-bold mb-2">New Password</label>
                    <input type="password" id="new-password" name="new-password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-custom_orange mt-2" placeholder="Enter new password" required>
                </div>

                <!-- Confirm Password Input -->
                <div>
                    <label for="confirm-password" class="block text-custom_black font-bold mb-2">Confirm Password</label>
                    <input type="password" id="confirm-password" name="confirm-password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-custom_orange mt-2" placeholder="Confirm new password" required>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" class="w-full bg-dark_orange text-custom_white px-4 py-2 rounded-lg hover:bg-bright_cream hover:text-custom_brown">
                        Reset Password
                    </button>
                </div>
            </form>
        </div>
    </section>
    
    <script src="script_login_page.js"></script>

</body>
</html>
