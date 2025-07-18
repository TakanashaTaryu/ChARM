<?php
session_start();

// Check admin authentication
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true || 
    !isset($_SESSION['admin_value']) || $_SESSION['admin_value'] != 1) {
    header("Location: ../login.php");
    exit();
}

require_once '../db_connection.php';

// Handle delete action
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $costume_id = (int)$_GET['id'];
    
    $conn->begin_transaction();
    try {
        // Delete from stocks first (foreign key constraint)
        $conn->query("DELETE FROM stocks WHERE costume_id = $costume_id");
        // Delete costume
        $conn->query("DELETE FROM costumes WHERE id = $costume_id");
        
        $conn->commit();
        header("Location: costumes.php?msg=deleted");
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        $error = "Error deleting costume: " . $e->getMessage();
    }
}

// Get costumes with category and stock info
$sql = "SELECT c.*, cat.name as category_name, s.quantity_available, s.quantity_total 
        FROM costumes c 
        LEFT JOIN categories cat ON c.category_id = cat.id 
        LEFT JOIN stocks s ON c.id = s.costume_id 
        ORDER BY c.created_at DESC";

$result = $conn->query($sql);
$costumes = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $costumes[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Costumes | ChARM Admin</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.0.5/css/boxicons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&display=swap" rel="stylesheet">
</head>
<body class="font-[lexend] bg-gradient-to-r from-orange-600 to-[#ea8b24]">
    <header class="flex justify-between items-center p-4 bg-orange-950">
        <a href="./adminpage.php" class="flex gap-2 items-center">
            <img class="max-h-9 w-auto ml-1 md:ml-10" src="../asset/logo_Charm.png" alt="logo_Charm">
            <span class="text-lg font-semibold mt-2 text-white">CHARM Admin - Costumes</span>
        </a>
        <nav class="text-white flex justify-end items-center w-[92%] mx-auto text-lg">
            <div>
                <ul class="flex items-center gap-[4vw]">
                    <li><a class="hover:text-orange-500" href="./adminpage.php">Dashboard</a></li>
                    <li><a class="hover:text-orange-500 font-bold" href="./costumes.php">Costumes</a></li>
                    <li><a class="hover:text-orange-500" href="./addcos.php">Add Costume</a></li>
                    <li><a class="hover:text-orange-500" href="./categories.php">Categories</a></li>
                    <li><a class="hover:text-orange-500" href="./users.php">Users</a></li>
                    <li><a class="hover:text-orange-500" href="./rentals.php">Rentals</a></li>
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
                <h1 class="text-3xl font-bold text-gray-800">Manage Costumes</h1>
                <a href="./addcos.php" class="bg-orange-600 text-white px-6 py-3 rounded-lg hover:bg-orange-700 transition">
                    <i class="bx bx-plus mr-2"></i>Add New Costume
                </a>
            </div>
            
            <?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    Costume deleted successfully!
                </div>
            <?php endif; ?>
            
            <?php if (isset($error)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <div class="overflow-x-auto">
                <table class="w-full table-auto">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-3 text-left">Image</th>
                            <th class="px-4 py-3 text-left">Name</th>
                            <th class="px-4 py-3 text-left">Category</th>
                            <th class="px-4 py-3 text-left">Price/Day</th>
                            <th class="px-4 py-3 text-left">Size</th>
                            <th class="px-4 py-3 text-left">Stock</th>
                            <th class="px-4 py-3 text-left">Status</th>
                            <th class="px-4 py-3 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($costumes as $costume): ?>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <?php if ($costume['image1']): ?>
                                    <img src="../<?php echo htmlspecialchars($costume['image1']); ?>" alt="<?php echo htmlspecialchars($costume['name']); ?>" class="w-16 h-16 object-cover rounded">
                                <?php else: ?>
                                    <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                                        <i class="bx bx-image text-gray-400 text-2xl"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 font-semibold"><?php echo htmlspecialchars($costume['name']); ?></td>
                            <td class="px-4 py-3"><?php echo htmlspecialchars($costume['category_name'] ?? 'N/A'); ?></td>
                            <td class="px-4 py-3">IDR <?php echo number_format($costume['price_per_day'], 0, ',', '.'); ?></td>
                            <td class="px-4 py-3"><?php echo htmlspecialchars($costume['size']); ?></td>
                            <td class="px-4 py-3">
                                <span class="<?php echo ($costume['quantity_available'] > 0) ? 'text-green-600' : 'text-red-600'; ?>">
                                    <?php echo $costume['quantity_available']; ?>/<?php echo $costume['quantity_total']; ?>
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded text-sm font-semibold <?php echo ($costume['status'] == 'available') ? 'bg-green-600 text-white' : 'bg-red-600 text-white'; ?>">
                                    <?php echo ucfirst($costume['status']); ?>
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex space-x-2">
                                    <a href="edit_costume.php?id=<?php echo $costume['id']; ?>" class="text-blue-600 hover:text-blue-800">
                                        <i class="bx bx-edit text-lg"></i>
                                    </a>
                                    <a href="costumes.php?action=delete&id=<?php echo $costume['id']; ?>" 
                                       onclick="return confirm('Are you sure you want to delete this costume?')" 
                                       class="text-red-600 hover:text-red-800">
                                        <i class="bx bx-trash text-lg"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <?php if (empty($costumes)): ?>
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                                No costumes found. <a href="./addcos.php" class="text-orange-600 hover:underline">Add your first costume</a>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <footer class="container mx-auto text-center py-8">
        <p class="text-white text-lg">© 2025 ChARM Costume Rental - Admin Panel</p>
    </footer>
</body>
</html>

<?php $conn->close(); ?>