<?php
session_start();

// Check admin authentication
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true || 
    !isset($_SESSION['admin_value']) || $_SESSION['admin_value'] != 1) {
    header("Location: ../login.php");
    exit();
}

require_once '../db_connection.php';

// Handle category actions
if (isset($_POST['action'])) {
    $action = $_POST['action'];
    
    if ($action == 'add' && isset($_POST['name'])) {
        $name = trim($_POST['name']);
        $description = trim($_POST['description']);
        $sort_order = (int)$_POST['sort_order'];
        
        if (!empty($name)) {
            $stmt = $conn->prepare("INSERT INTO categories (name, description, sort_order) VALUES (?, ?, ?)");
            $stmt->bind_param("ssi", $name, $description, $sort_order);
            if ($stmt->execute()) {
                $message = '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">Category added successfully!</div>';
            } else {
                $message = '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">Error adding category.</div>';
            }
        }
    } elseif ($action == 'toggle_status' && isset($_POST['category_id'])) {
        $category_id = (int)$_POST['category_id'];
        $new_status = (int)$_POST['new_status'];
        
        $stmt = $conn->prepare("UPDATE categories SET is_active = ? WHERE id = ?");
        $stmt->bind_param("ii", $new_status, $category_id);
        if ($stmt->execute()) {
            $status_text = $new_status ? 'activated' : 'deactivated';
            $message = '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">Category ' . $status_text . ' successfully!</div>';
        }
    } elseif ($action == 'update' && isset($_POST['category_id'])) {
        $category_id = (int)$_POST['category_id'];
        $name = trim($_POST['name']);
        $description = trim($_POST['description']);
        $sort_order = (int)$_POST['sort_order'];
        
        if (!empty($name)) {
            $stmt = $conn->prepare("UPDATE categories SET name = ?, description = ?, sort_order = ? WHERE id = ?");
            $stmt->bind_param("ssii", $name, $description, $sort_order, $category_id);
            if ($stmt->execute()) {
                $message = '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">Category updated successfully!</div>';
            }
        }
    }
}

// Get categories with costume count
$sql = "SELECT c.*, COUNT(cos.id) as costume_count 
        FROM categories c 
        LEFT JOIN costumes cos ON c.id = cos.category_id 
        GROUP BY c.id 
        ORDER BY c.sort_order, c.name";

$result = $conn->query($sql);
$categories = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories | ChARM Admin</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.0.5/css/boxicons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&display=swap" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .content-wrapper {
            flex: 1;
        }
        footer {
            margin-top: auto;
        }
    </style>
</head>
<body class="font-[lexend] bg-gradient-to-r from-orange-600 to-[#ea8b24]">
    <header class="flex justify-between items-center p-4 bg-orange-950">
        <a href="./adminpage.php" class="flex gap-2 items-center">
            <img class="max-h-9 w-auto ml-1 md:ml-10" src="../asset/logo_Charm.png" alt="logo_Charm">
            <span class="text-lg font-semibold mt-2 text-white">CHARM Admin - Categories</span>
        </a>
        <nav class="text-white flex justify-end items-center w-[92%] mx-auto text-lg">
            <div>
                <ul class="flex items-center gap-[4vw]">
                    <li><a class="hover:text-orange-500" href="./adminpage.php">Dashboard</a></li>
                    <li><a class="hover:text-orange-500" href="./costumes.php">Costumes</a></li>
                    <li><a class="hover:text-orange-500" href="./addcos.php">Add Costume</a></li>
                    <li><a class="hover:text-orange-500 font-bold" href="./categories.php">Categories</a></li>
                    <li><a class="hover:text-orange-500" href="./users.php">Users</a></li>
                    <li><a class="hover:text-orange-500" href="./rentals.php">Rentals</a></li>
                    <li>
                        <a href="../logout.php" class="bg-white text-black px-4 py-2 rounded-full hover:bg-black hover:text-white">Logout</a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <div class="content-wrapper">
        <div class="container mx-auto mt-10 px-4">
            <div class="bg-white rounded-lg shadow-lg p-8">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-3xl font-bold text-gray-800">Manage Categories</h1>
                    <button onclick="toggleAddForm()" class="bg-orange-600 text-white px-6 py-3 rounded-lg hover:bg-orange-700 transition">
                        <i class="bx bx-plus mr-2"></i>Add New Category
                    </button>
                </div>
                
                <?php if (isset($message)) echo $message; ?>
                
                <!-- Add Category Form -->
                <div id="addCategoryForm" class="hidden mb-6 p-6 bg-gray-50 rounded-lg">
                    <h3 class="text-xl font-semibold mb-4">Add New Category</h3>
                    <form method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <input type="hidden" name="action" value="add">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Category Name *</label>
                            <input type="text" name="name" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:border-orange-500">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Sort Order</label>
                            <input type="number" name="sort_order" value="0" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:border-orange-500">
                        </div>
                        <div class="md:col-span-3">
                            <label class="block text-gray-700 font-semibold mb-2">Description</label>
                            <textarea name="description" rows="3" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:border-orange-500"></textarea>
                        </div>
                        <div class="md:col-span-3">
                            <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition mr-2">Add Category</button>
                            <button type="button" onclick="toggleAddForm()" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition">Cancel</button>
                        </div>
                    </form>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sort Order</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Costumes</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($categories as $category): ?>
                            <tr id="category-<?php echo $category['id']; ?>">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($category['name']); ?></div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 max-w-xs truncate" title="<?php echo htmlspecialchars($category['description']); ?>">
                                        <?php echo htmlspecialchars($category['description'] ?: 'No description'); ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo $category['sort_order']; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">
                                        <?php echo $category['costume_count']; ?> costumes
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if ($category['is_active']): ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                    <?php else: ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo date('M j, Y', strtotime($category['created_at'])); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <button onclick="editCategory(<?php echo $category['id']; ?>)" class="text-blue-600 hover:text-blue-900">Edit</button>
                                        
                                        <form method="POST" class="inline">
                                            <input type="hidden" name="action" value="toggle_status">
                                            <input type="hidden" name="category_id" value="<?php echo $category['id']; ?>">
                                            <input type="hidden" name="new_status" value="<?php echo $category['is_active'] ? 0 : 1; ?>">
                                            <button type="submit" class="<?php echo $category['is_active'] ? 'text-red-600 hover:text-red-900' : 'text-green-600 hover:text-green-900'; ?>">
                                                <?php echo $category['is_active'] ? 'Deactivate' : 'Activate'; ?>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            
                            <!-- Edit Form Row (Hidden by default) -->
                            <tr id="edit-form-<?php echo $category['id']; ?>" class="hidden">
                                <td colspan="7" class="px-6 py-4 bg-gray-50">
                                    <form method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <input type="hidden" name="action" value="update">
                                        <input type="hidden" name="category_id" value="<?php echo $category['id']; ?>">
                                        <div>
                                            <label class="block text-gray-700 font-semibold mb-2">Category Name *</label>
                                            <input type="text" name="name" value="<?php echo htmlspecialchars($category['name']); ?>" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:border-orange-500">
                                        </div>
                                        <div>
                                            <label class="block text-gray-700 font-semibold mb-2">Sort Order</label>
                                            <input type="number" name="sort_order" value="<?php echo $category['sort_order']; ?>" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:border-orange-500">
                                        </div>
                                        <div class="md:col-span-3">
                                            <label class="block text-gray-700 font-semibold mb-2">Description</label>
                                            <textarea name="description" rows="3" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:border-orange-500"><?php echo htmlspecialchars($category['description']); ?></textarea>
                                        </div>
                                        <div class="md:col-span-3">
                                            <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition mr-2">Update</button>
                                            <button type="button" onclick="cancelEdit(<?php echo $category['id']; ?>)" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition">Cancel</button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-orange-950 text-center py-6 mt-8">
        <p class="text-white text-lg">© 2025 ChARM Costume Rental - Admin Panel</p>
    </footer>

    <script>
        function toggleAddForm() {
            const form = document.getElementById('addCategoryForm');
            form.classList.toggle('hidden');
        }
        
        function editCategory(categoryId) {
            const editForm = document.getElementById('edit-form-' + categoryId);
            editForm.classList.remove('hidden');
        }
        
        function cancelEdit(categoryId) {
            const editForm = document.getElementById('edit-form-' + categoryId);
            editForm.classList.add('hidden');
        }
    </script>
</body>
</html>