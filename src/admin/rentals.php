<?php
session_start();

// Check admin authentication
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true || 
    !isset($_SESSION['admin_value']) || $_SESSION['admin_value'] != 1) {
    header("Location: ../login.php");
    exit();
}

require_once '../db_connection.php';

// Handle rental status updates
if (isset($_POST['action']) && isset($_POST['rental_id'])) {
    $rental_id = (int)$_POST['rental_id'];
    $action = $_POST['action'];
    
    if ($action == 'confirm') {
        $conn->query("UPDATE rent_details SET status = 'confirmed' WHERE id = $rental_id");
        $message = '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">Rental confirmed successfully!</div>';
    } elseif ($action == 'complete') {
        $conn->query("UPDATE rent_details SET status = 'completed' WHERE id = $rental_id");
        $message = '<div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-4">Rental marked as completed!</div>';
    } elseif ($action == 'cancel') {
        $conn->query("UPDATE rent_details SET status = 'cancelled' WHERE id = $rental_id");
        $message = '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">Rental cancelled successfully!</div>';
    }
}

// Get rentals with user and item details
$sql = "SELECT rd.*, u.username, u.email,
        COUNT(ri.id) as total_items,
        GROUP_CONCAT(c.name SEPARATOR ', ') as costume_names
        FROM rent_details rd 
        JOIN users u ON rd.user_id = u.user_id 
        LEFT JOIN rent_items ri ON rd.id = ri.rent_id
        LEFT JOIN costumes c ON ri.product_id = c.id
        GROUP BY rd.id 
        ORDER BY rd.created_at DESC";

$result = $conn->query($sql);
$rentals = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $rentals[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Rentals | ChARM Admin</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.0.5/css/boxicons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&display=swap" rel="stylesheet">
</head>
<body class="font-[lexend] bg-gradient-to-r from-orange-600 to-[#ea8b24]">
    <header class="flex justify-between items-center p-4 bg-orange-950">
        <a href="./adminpage.php" class="flex gap-2 items-center">
            <img class="max-h-9 w-auto ml-1 md:ml-10" src="../asset/logo_Charm.png" alt="logo_Charm">
            <span class="text-lg font-semibold mt-2 text-white">CHARM Admin - Rentals</span>
        </a>
        <nav class="text-white flex justify-end items-center w-[92%] mx-auto text-lg">
            <div>
                <ul class="flex items-center gap-[4vw]">
                    <li><a class="hover:text-orange-500" href="./adminpage.php">Dashboard</a></li>
                    <li><a class="hover:text-orange-500" href="./costumes.php">Costumes</a></li>
                    <li><a class="hover:text-orange-500" href="./addcos.php">Add Costume</a></li>
                    <li><a class="hover:text-orange-500" href="./categories.php">Categories</a></li>
                    <li><a class="hover:text-orange-500" href="./users.php">Users</a></li>
                    <li><a class="hover:text-orange-500 font-bold" href="./rentals.php">Rentals</a></li>
                    <li>
                        <a href="../logout.php" class="bg-white text-black px-4 py-2 rounded-full hover:bg-black hover:text-white">Logout</a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <div class="container mx-auto mt-10 px-4">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-800">Manage Rentals</h1>
                <div class="text-sm text-gray-600">
                    Total Rentals: <?php echo count($rentals); ?>
                </div>
            </div>
            
            <?php if (isset($message)) echo $message; ?>
            
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order #</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rental Period</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($rentals as $rental): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <?php echo htmlspecialchars($rental['order_number']); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($rental['username']); ?></div>
                                    <div class="text-sm text-gray-500"><?php echo htmlspecialchars($rental['email']); ?></div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900"><?php echo $rental['total_items']; ?> item(s)</div>
                                <div class="text-xs text-gray-500 max-w-xs truncate" title="<?php echo htmlspecialchars($rental['costume_names']); ?>">
                                    <?php echo htmlspecialchars($rental['costume_names'] ?: 'No items'); ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div><?php echo date('M j, Y', strtotime($rental['rent_start'])); ?></div>
                                <div class="text-xs text-gray-500">to <?php echo date('M j, Y', strtotime($rental['rent_end'])); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                $<?php echo number_format($rental['total_amount'], 2); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php 
                                $status_colors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'confirmed' => 'bg-blue-100 text-blue-800',
                                    'active' => 'bg-green-100 text-green-800',
                                    'completed' => 'bg-gray-100 text-gray-800',
                                    'cancelled' => 'bg-red-100 text-red-800',
                                    'overdue' => 'bg-red-100 text-red-800'
                                ];
                                $color_class = $status_colors[$rental['status']] ?? 'bg-gray-100 text-gray-800';
                                ?>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $color_class; ?>">
                                    <?php echo ucfirst($rental['status']); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php 
                                $payment_colors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'paid' => 'bg-green-100 text-green-800',
                                    'failed' => 'bg-red-100 text-red-800',
                                    'refunded' => 'bg-blue-100 text-blue-800',
                                    'partial_refund' => 'bg-orange-100 text-orange-800'
                                ];
                                $payment_color = $payment_colors[$rental['payment_status']] ?? 'bg-gray-100 text-gray-800';
                                ?>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $payment_color; ?>">
                                    <?php echo ucfirst(str_replace('_', ' ', $rental['payment_status'])); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex flex-col space-y-1">
                                    <?php if ($rental['status'] == 'pending'): ?>
                                    <form method="POST" class="inline">
                                        <input type="hidden" name="rental_id" value="<?php echo $rental['id']; ?>">
                                        <input type="hidden" name="action" value="confirm">
                                        <button type="submit" class="text-green-600 hover:text-green-900 text-xs">Confirm</button>
                                    </form>
                                    <form method="POST" class="inline">
                                        <input type="hidden" name="rental_id" value="<?php echo $rental['id']; ?>">
                                        <input type="hidden" name="action" value="cancel">
                                        <button type="submit" class="text-red-600 hover:text-red-900 text-xs" onclick="return confirm('Cancel this rental?')">Cancel</button>
                                    </form>
                                    <?php elseif ($rental['status'] == 'confirmed' || $rental['status'] == 'active'): ?>
                                    <form method="POST" class="inline">
                                        <input type="hidden" name="rental_id" value="<?php echo $rental['id']; ?>">
                                        <input type="hidden" name="action" value="complete">
                                        <button type="submit" class="text-blue-600 hover:text-blue-900 text-xs" onclick="return confirm('Mark as completed?')">Complete</button>
                                    </form>
                                    <?php else: ?>
                                    <span class="text-gray-400 text-xs">No actions</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <footer class="mt-20 bg-orange-950 text-center py-6">
        <p class="text-white text-lg">© 2025 ChARM Costume Rental - Admin Panel</p>
    </footer>
</body>
</html>