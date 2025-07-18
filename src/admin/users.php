<?php
session_start();

// Check admin authentication
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true || 
    !isset($_SESSION['admin_value']) || $_SESSION['admin_value'] != 1) {
    header("Location: ../login.php");
    exit();
}

require_once '../db_connection.php';

// Handle user status updates
if (isset($_POST['action']) && isset($_POST['user_id'])) {
    $user_id = (int)$_POST['user_id'];
    $action = $_POST['action'];
    
    if ($action == 'suspend') {
        $conn->query("UPDATE users SET status = 'suspended' WHERE user_id = $user_id");
        $message = '<div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">User suspended successfully!</div>';
    } elseif ($action == 'activate') {
        $conn->query("UPDATE users SET status = 'active' WHERE user_id = $user_id");
        $message = '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">User activated successfully!</div>';
    } elseif ($action == 'make_admin') {
        $conn->query("UPDATE users SET admin_value = 1 WHERE user_id = $user_id");
        $message = '<div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-4">User promoted to admin successfully!</div>';
    } elseif ($action == 'remove_admin') {
        $conn->query("UPDATE users SET admin_value = 0 WHERE user_id = $user_id");
        $message = '<div class="bg-orange-100 border border-orange-400 text-orange-700 px-4 py-3 rounded mb-4">Admin privileges removed successfully!</div>';
    }
}

// Get users with rental statistics
$sql = "SELECT u.*, 
        COUNT(DISTINCT rd.id) as total_rentals,
        COALESCE(SUM(CASE WHEN rd.status = 'active' THEN 1 ELSE 0 END), 0) as active_rentals,
        COALESCE(MAX(rd.created_at), 'Never') as last_rental
        FROM users u 
        LEFT JOIN rent_details rd ON u.user_id = rd.user_id 
        GROUP BY u.user_id 
        ORDER BY u.created_at DESC";

$result = $conn->query($sql);
$users = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users | ChARM Admin</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.0.5/css/boxicons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&display=swap" rel="stylesheet">
</head>
<body class="font-[lexend] bg-gradient-to-r from-orange-600 to-[#ea8b24]">
    <header class="flex justify-between items-center p-4 bg-orange-950">
        <a href="./adminpage.php" class="flex gap-2 items-center">
            <img class="max-h-9 w-auto ml-1 md:ml-10" src="../asset/logo_Charm.png" alt="logo_Charm">
            <span class="text-lg font-semibold mt-2 text-white">CHARM Admin - Users</span>
        </a>
        <nav class="text-white flex justify-end items-center w-[92%] mx-auto text-lg">
            <div>
                <ul class="flex items-center gap-[4vw]">
                    <li><a class="hover:text-orange-500" href="./adminpage.php">Dashboard</a></li>
                    <li><a class="hover:text-orange-500" href="./costumes.php">Costumes</a></li>
                    <li><a class="hover:text-orange-500" href="./addcos.php">Add Costume</a></li>
                    <li><a class="hover:text-orange-500" href="./categories.php">Categories</a></li>
                    <li><a class="hover:text-orange-500 font-bold" href="./users.php">Users</a></li>
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
                <h1 class="text-3xl font-bold text-gray-800">Manage Users</h1>
                <div class="text-sm text-gray-600">
                    Total Users: <?php echo count($users); ?>
                </div>
            </div>
            
            <?php if (isset($message)) echo $message; ?>
            
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rentals</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Rental</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($user['username']); ?></div>
                                    <div class="text-sm text-gray-500"><?php echo htmlspecialchars($user['email']); ?></div>
                                    <?php if ($user['first_name'] || $user['last_name']): ?>
                                    <div class="text-xs text-gray-400"><?php echo htmlspecialchars(trim($user['first_name'] . ' ' . $user['last_name'])); ?></div>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if ($user['admin_value'] == 2): ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">Super Admin</span>
                                <?php elseif ($user['admin_value'] == 1): ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Admin</span>
                                <?php else: ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">User</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if ($user['status'] == 'active'): ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                <?php elseif ($user['status'] == 'suspended'): ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Suspended</span>
                                <?php else: ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div>Total: <?php echo $user['total_rentals']; ?></div>
                                <div class="text-xs text-orange-600">Active: <?php echo $user['active_rentals']; ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php echo $user['last_rental'] == 'Never' ? 'Never' : date('M j, Y', strtotime($user['last_rental'])); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php echo date('M j, Y', strtotime($user['created_at'])); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <?php if ($user['user_id'] != $_SESSION['user_id']): // Don't allow actions on self ?>
                                <div class="flex space-x-2">
                                    <?php if ($user['status'] == 'active'): ?>
                                    <form method="POST" class="inline">
                                        <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                                        <input type="hidden" name="action" value="suspend">
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Suspend this user?')">Suspend</button>
                                    </form>
                                    <?php else: ?>
                                    <form method="POST" class="inline">
                                        <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                                        <input type="hidden" name="action" value="activate">
                                        <button type="submit" class="text-green-600 hover:text-green-900">Activate</button>
                                    </form>
                                    <?php endif; ?>
                                    
                                    <?php if ($user['admin_value'] == 0): ?>
                                    <form method="POST" class="inline">
                                        <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                                        <input type="hidden" name="action" value="make_admin">
                                        <button type="submit" class="text-blue-600 hover:text-blue-900" onclick="return confirm('Make this user an admin?')">Make Admin</button>
                                    </form>
                                    <?php elseif ($user['admin_value'] == 1): ?>
                                    <form method="POST" class="inline">
                                        <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                                        <input type="hidden" name="action" value="remove_admin">
                                        <button type="submit" class="text-orange-600 hover:text-orange-900" onclick="return confirm('Remove admin privileges?')">Remove Admin</button>
                                    </form>
                                    <?php endif; ?>
                                </div>
                                <?php else: ?>
                                <span class="text-gray-400">Current User</span>
                                <?php endif; ?>
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