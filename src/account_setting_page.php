<?php
session_start();

// Pastikan user sudah login
if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in']) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
require_once 'db_connection.php';

// Initialize variables with correct field names from database schema
$full_name = $phone = $date_of_birth = $gender = $address = $city = $postal_code = $country = $payment_method = $fav_waifu = "";
$success_message = $error_message = "";

// Load existing data
$sql = "SELECT p.*, u.email, u.username FROM profiles p 
        JOIN users u ON p.user_id = u.user_id 
        WHERE p.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $full_name = $row['full_name'] ?? '';
    $phone = $row['phone'] ?? '';
    $date_of_birth = $row['date_of_birth'] ?? '';
    $gender = $row['gender'] ?? '';
    $address = $row['address'] ?? '';
    $city = $row['city'] ?? '';
    $postal_code = $row['postal_code'] ?? '';
    $country = $row['country'] ?? 'Indonesia';
    $payment_method = $row['preferred_payment_method'] ?? '';
    $fav_waifu = $row['favorite_character'] ?? '';
    $email = $row['email'];
    $username = $row['username'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST['full_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $date_of_birth = $_POST['date_of_birth'] ?? null;
    $gender = $_POST['gender'] ?? '';
    $address = trim($_POST['alamat'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $postal_code = trim($_POST['postal_code'] ?? '');
    $country = $_POST['country'] ?? 'Indonesia';
    $payment_method = $_POST['payment_method'] ?? '';
    $fav_waifu = trim($_POST['fav_waifu'] ?? '');

    // Check if profile exists
    $check_sql = "SELECT id FROM profiles WHERE user_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $user_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        // Update existing profile
        $update_sql = "UPDATE profiles SET 
                      full_name = ?, phone = ?, date_of_birth = ?, gender = ?, 
                      address = ?, city = ?, postal_code = ?, country = ?, 
                      preferred_payment_method = ?, favorite_character = ?, 
                      updated_at = NOW() 
                      WHERE user_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ssssssssssi", 
            $full_name, $phone, $date_of_birth, $gender, 
            $address, $city, $postal_code, $country, 
            $payment_method, $fav_waifu, $user_id);
        
        if ($update_stmt->execute()) {
            echo "<script>alert('Informasi berhasil diperbarui.');</script>";
        } else {
            echo "<script>alert('Gagal memperbarui informasi.');</script>";
        }
    } else {
        // Insert new profile
        $insert_sql = "INSERT INTO profiles 
                      (user_id, full_name, phone, date_of_birth, gender, address, city, postal_code, country, preferred_payment_method, favorite_character, created_at, updated_at) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("issssssssss", 
            $user_id, $full_name, $phone, $date_of_birth, $gender, 
            $address, $city, $postal_code, $country, 
            $payment_method, $fav_waifu);
        
        if ($insert_stmt->execute()) {
            echo "<script>alert('Profile berhasil dibuat.');</script>";
        } else {
            echo "<script>alert('Gagal membuat profile.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Setting - ChARM</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.0.5/css/boxicons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&display=swap" rel="stylesheet">
    <style>
        /* CSS untuk tombol Logout */
        .fixed {
            position: fixed;
        }
        .bottom-4 {
            bottom: 1rem; /* Atur jarak dari bawah */
        }
        .right-4 {
            right: 1rem; /* Atur jarak dari kanan */
        }
    </style>
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
        <nav class="flex">
            <ul class="flex items-center space-x-6">
                <li><a href="main_page.php"><i class='bx bx-home-alt text-3xl text-custom_black'></i></a></li>
                <li><a href="chart-page.php"><i class='bx bx-shopping-bag text-3xl text-custom_black'></i></a></li>
                <li><a href="whistlist-page.php"><i class='bx bx-heart text-3xl text-custom_black'></i></a></li>
                <li><a href="account_setting_page.php"><i class='bx bx-user text-3xl text-custom_black'></i></a></li>
            </ul>
        </nav>
    </header>

    <!-- Main Container -->
    <section class="max-w-6xl mx-auto py-10">
        <h2 class="text-2xl font-bold mb-6 text-custom_black md:ml-4 ml-1">Account Setting</h2>
        
        <div class="max-w-3xl mx-auto my-10 p-8 bg-white rounded-lg shadow-md">
            <form action="" method="POST">
                <div class="mb-4">
                    <label for="nama" class="block text-custom_brown mb-2">Nama lengkap</label>
                    <input type="text" id="nama" name="full_name" value="<?php echo htmlspecialchars($full_name ?? '', ENT_QUOTES); ?>" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-custom_orange" placeholder="Nama lengkap" required>
                </div>
                
                <div class="mb-4">
                    <label for="alamat" class="block text-custom_brown mb-2">Alamat</label>
                    <input type="text" id="alamat" name="alamat" value="<?php echo htmlspecialchars($alamat ?? '', ENT_QUOTES); ?>" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-custom_orange" placeholder="Alamat" required>
                </div>
                
                <div class="mb-4">
                    <label for="metode" class="block text-custom_brown mb-2">Bank Info</label>
                    <select id="metode" name="payment_method" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-custom_orange" required>
                        <option value="">Pilih Bank Pembayaran</option>
                        <option value="BRI" <?php echo ($payment_method == 'BRI') ? 'selected' : ''; ?>>BRI</option>
                        <option value="BNI" <?php echo ($payment_method == 'BNI') ? 'selected' : ''; ?>>BNI</option>
                        <option value="BCA" <?php echo ($payment_method == 'BCA') ? 'selected' : ''; ?>>BCA</option>
                        <option value="Others" <?php echo ($payment_method == 'Others') ? 'selected' : ''; ?>>Others</option>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label for="note" class="block text-custom_brown mb-2">Favourite Character (for better recommendation)</label>
                    <input id="note" name="fav_waifu" value="<?php echo htmlspecialchars($fav_waifu ?? '', ENT_QUOTES); ?>" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-custom_orange" placeholder="Your Waifu">
                </div>

                <div class="mb-4">
                    <a href="recovery_password.php">
                        <p class="text-custom_brown hover:underline">Change password</p>
                    </a>
                </div>
                
                <button type="submit" class="w-full bg-custom_black text-white py-3 rounded-lg">Simpan Perubahan</button>
            </form>
        </div>
    </section>

    <!-- Logout Button -->
    <div class="fixed bottom-4 right-4">
        <form action="logout.php" method="POST">
            <button type="submit" class="text-custom_brown hover:underline px-4 py-2 rounded-lg">Logout</button>
        </form>
    </div>

    <script src="script.js"></script>
</body>
</html>
