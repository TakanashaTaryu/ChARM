<?php
// config.php
$servername = "localhost";
$username_db = "admin";
$password_db = "admin";
$dbname = "charm_db";

// Konfigurasi Midtrans
$midtrans_client_key = "SB-Mid-client-PTOwdx_ZDMJ31gS1";
$midtrans_server_key = "SB-Mid-server-p6MgCKt5insW_SC-ZwWTCM9_";

// index.php
session_start();
include 'db_connection.php';
include 'functions.php';

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'] ?? 1; // Assuming user is logged in
    $total = $_POST['total'];
    $rent_start = $_POST['rent_start'];
    $rent_done = $_POST['rent_done'];
    
    // Insert into rent_details table
    $sql = "INSERT INTO rent_details (user_id, total, rent_start, rent_done) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("idss", $user_id, $total, $rent_start, $rent_done);
    
    if ($stmt->execute()) {
        $rent_id = $stmt->insert_id;
    
        // Generate unique order ID
        $order_id = "ORDER-" . time() . "-" . $rent_id;
        
        // Insert into payment_details table

$sql = "INSERT INTO payment_details (rent_id, user_id, amount, payment_code) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiis", $rent_id, $user_id, $total, $order_id);
$stmt->execute();
$payment_id = $stmt->insert_id;

        
        if ($stmt->execute()) {
            $payment_id = $stmt->insert_id;
            
            // Get Snap Token
            $snap_token = getSnapToken($order_id, $total);
            
            if ($snap_token) {
                // Update payment_details with Snap Token
                $sql = "UPDATE payment_details SET payment_url_API = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("si", $snap_token, $payment_id);
                $stmt->execute();
                
                $_SESSION['snap_token'] = $snap_token;
                $_SESSION['order_id'] = $order_id;
            }
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Midtrans</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?php echo $midtrans_client_key; ?>"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <h1 class="text-2xl font-bold mb-6 text-center text-gray-800">Halaman Pembayaran</h1>
        
        <?php if (!isset($_SESSION['snap_token'])): ?>
        <form method="post" class="space-y-4">
            <div>
                <label for="total" class="block text-sm font-medium text-gray-700">Total Pembayaran:</label>
                <input type="number" id="total" name="total" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>
            
            <div>
                <label for="rent_start" class="block text-sm font-medium text-gray-700">Tanggal Mulai Sewa:</label>
                <input type="datetime-local" id="rent_start" name="rent_start" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>
            
            <div>
                <label for="rent_done" class="block text-sm font-medium text-gray-700">Tanggal Selesai Sewa:</label>
                <input type="datetime-local" id="rent_done" name="rent_done" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>
            
            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Proses Pembayaran
            </button>
        </form>
        <?php else: ?>
        <div class="space-y-4">
            <p class="text-gray-700">Order ID: <span class="font-semibold"><?php echo $_SESSION['order_id']; ?></span></p>
            <button id="pay-button" class="w-full bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Bayar Sekarang
            </button>
        </div>
        
        <script type="text/javascript">
            document.getElementById('pay-button').onclick = function() {
                snap.pay('<?php echo $_SESSION['snap_token']; ?>', {
                    onSuccess: function(result) {
                        alert("Pembayaran berhasil!");
                        console.log(result);
                        // Here you can redirect to a success page or update the order status
                    },
                    onPending: function(result) {
                        alert("Pembayaran tertunda!");
                        console.log(result);
                    },
                    onError: function(result) {
                        alert("Pembayaran gagal!");
                        console.log(result);
                    },
                    onClose: function() {
                        alert('Anda menutup popup tanpa menyelesaikan pembayaran');
                    }
                });
            };
        </script>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
// Bersihkan session setelah pembayaran selesai atau halaman di-refresh
unset($_SESSION['snap_token']);
unset($_SESSION['order_id']);
?>
