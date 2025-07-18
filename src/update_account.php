<?php
session_start();

if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in']) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

require_once 'db_connection.php';

// Initialize variables
$full_name = $alamat = $payment_method = $fav_waifu = $phone = "";
$success_message = $error_message = "";

// Load existing data with correct field names
$sql = "SELECT full_name, address, preferred_payment_method, favorite_character, phone FROM profiles WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $full_name = $row['full_name'] ?? '';
    $alamat = $row['address'] ?? '';
    $payment_method = $row['preferred_payment_method'] ?? '';
    $fav_waifu = $row['favorite_character'] ?? '';
    $phone = $row['phone'] ?? '';
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST["nama"] ?? '');
    $alamat = trim($_POST["alamat"] ?? '');
    $payment_method = $_POST["metode"] ?? '';
    $fav_waifu = trim($_POST["note"] ?? '');
    $phone = trim($_POST["phone"] ?? '');

    // Check if profile exists
    $check_sql = "SELECT id FROM profiles WHERE user_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $user_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        // Update existing profile
        $update_sql = "UPDATE profiles SET 
                      full_name = ?, address = ?, preferred_payment_method = ?, 
                      favorite_character = ?, phone = ?, updated_at = NOW() 
                      WHERE user_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("sssssi", $full_name, $alamat, $payment_method, $fav_waifu, $phone, $user_id);
        
        if ($update_stmt->execute()) {
            $success_message = "Account updated successfully!";
        } else {
            $error_message = "Failed to update account: " . $conn->error;
        }
    } else {
        // Insert new profile
        $insert_sql = "INSERT INTO profiles (user_id, full_name, address, preferred_payment_method, favorite_character, phone, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("isssss", $user_id, $full_name, $alamat, $payment_method, $fav_waifu, $phone);
        
        if ($insert_stmt->execute()) {
            $success_message = "Profile created successfully!";
        } else {
            $error_message = "Failed to create profile: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Account - ChARM</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.0.5/css/boxicons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&display=swap" rel="stylesheet">
</head>
<body class="font-lexend bg-gradient-to-r from-custom_orange via-bright_orange to-dark_cream">
    <section class="max-w-6xl mx-auto py-10">
        <h2 class="text-2xl font-bold mb-6 text-custom_black md:ml-4 ml-1">Update Account Information</h2>
        
        <div class="max-w-3xl mx-auto my-10 p-8 bg-white rounded-lg shadow-md">
            <?php if ($success_message): ?>
                <p class="bg-green-200 text-green-800 p-2 rounded"><?php echo $success_message; ?></p>
            <?php elseif ($error_message): ?>
                <p class="bg-red-200 text-red-800 p-2 rounded"><?php echo $error_message; ?></p>
            <?php endif; ?>
            
            <form action="update_account.php" method="POST">
                <div class="mb-4">
                    <label for="nama" class="block text-custom_brown mb-2">Nama lengkap</label>
                    <input type="text" id="nama" name="nama" value="<?php echo htmlspecialchars($full_name); ?>" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-custom_orange" placeholder="Nama lengkap">
                </div>
                
                <div class="mb-4">
                    <label for="alamat" class="block text-custom_brown mb-2">Alamat</label>
                    <input type="text" id="alamat" name="alamat" value="<?php echo htmlspecialchars($alamat); ?>" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-custom_orange" placeholder="Alamat">
                </div>
                
                <div class="mb-4">
                    <label for="metode" class="block text-custom_brown mb-2">Bank Info</label>
                    <select id="metode" name="metode" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-custom_orange">
                        <option value="">Pilih Bank Pembayaran</option>
                        <option value="BRI" <?php echo ($payment_method == "BRI") ? "selected" : ""; ?>>BRI</option>
                        <option value="BNI" <?php echo ($payment_method == "BNI") ? "selected" : ""; ?>>BNI</option>
                        <option value="BCA" <?php echo ($payment_method == "BCA") ? "selected" : ""; ?>>BCA</option>
                        <option value="Others" <?php echo ($payment_method == "Others") ? "selected" : ""; ?>>Others</option>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label for="note" class="block text-custom_brown mb-2">Favourite Character (for better recommendation)</label>
                    <input id="note" name="note" value="<?php echo htmlspecialchars($fav_waifu); ?>" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-custom_orange" placeholder="Your Waifu">
                </div>
    
                <button type="submit" class="w-full bg-custom_black text-white py-3 rounded-lg">Save Info</button>
            </form>
        </div>
    </section>
</body>
</html>
