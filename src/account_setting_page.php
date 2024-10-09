<?php
session_start();

// Pastikan user sudah login
if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in']) {
    header("Location: index.php");
    exit();
}

// Ambil user_id dari session
$user_id = $_SESSION['user_id'];

// Koneksi ke database
$servername = "localhost";
$username_db = "admin";
$password_db = "admin";
$dbname = "charm_db";

$conn = new mysqli($servername, $username_db, $password_db, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi Gagal: " . $conn->connect_error);
}

// Inisialisasi variabel untuk mencegah kesalahan 'undefined variable'
$full_name = '';
$alamat = '';
$payment_method = '';
$fav_waifu = '';

// Ambil data pengguna dari database
$sql = "SELECT profiles.full_name, profiles.alamat, profiles.payment_method, profiles.fav_waifu 
        FROM profiles 
        JOIN users ON profiles.user_id = users.user_id 
        WHERE users.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // Mengisi variabel dengan data yang ada di database
    $full_name = $row['full_name'] ?? '';
    $alamat = $row['alamat'] ?? '';
    $payment_method = $row['payment_method'] ?? '';
    $fav_waifu = $row['fav_waifu'] ?? '';
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $full_name = $_POST['full_name'] ?? '';
    $alamat = $_POST['alamat'] ?? '';
    $payment_method = $_POST['payment_method'] ?? '';
    $fav_waifu = $_POST['fav_waifu'] ?? '';

    // Check jika user_id sudah ada di database atau belum
    $check_sql = "SELECT * FROM profiles WHERE user_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $user_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        // Jika user_id sudah ada, lakukan UPDATE
        $update_sql = "UPDATE profiles SET full_name=?, alamat=?, payment_method=?, fav_waifu=?, updated_at=NOW() WHERE user_id=?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ssssi", $full_name, $alamat, $payment_method, $fav_waifu, $user_id);
        
        if ($update_stmt->execute()) {
            echo "<script>alert('Informasi berhasil diperbarui.');</script>";
        } else {
            echo "<script>alert('Terjadi kesalahan saat memperbarui informasi.');</script>";
        }
    } else {
        // Jika user_id belum ada, lakukan INSERT
        $insert_sql = "INSERT INTO profiles (user_id, full_name, alamat, payment_method, fav_waifu, updated_at) VALUES (?, ?, ?, ?, ?, NOW())";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("issss", $user_id, $full_name, $alamat, $payment_method, $fav_waifu);
        
        if ($insert_stmt->execute()) {
            echo "<script>alert('Informasi berhasil ditambahkan.');</script>";
        } else {
            echo "<script>alert('Terjadi kesalahan saat menambahkan informasi.');</script>";
        }
    }

    // Tutup statement
    $check_stmt->close();
    if (isset($update_stmt)) $update_stmt->close();
    if (isset($insert_stmt)) $insert_stmt->close();
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
                <li><a href="chart-page.html"><i class='bx bx-shopping-bag text-3xl text-custom_black'></i></a></li>
                <li><a href="whistlist-page.html"><i class='bx bx-heart text-3xl text-custom_black'></i></a></li>
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
            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg">Logout</button>
        </form>
    </div>

    <script src="script.js"></script>
</body>
</html>
