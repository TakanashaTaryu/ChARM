<?php
session_start();

// Check admin authentication
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true || 
    !isset($_SESSION['admin_value']) || $_SESSION['admin_value'] != 1) {
    header("Location: ../login.php");
    exit();
}

require_once '../db_connection.php';

$message = '';
$user_id = $_SESSION['user_id'];

// Get categories for dropdown
$categories_result = $conn->query("SELECT id, name FROM categories WHERE is_active = 1 ORDER BY name");
$categories = [];
while ($row = $categories_result->fetch_assoc()) {
    $categories[] = $row;
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $category_id = (int)$_POST['category_id'];
    $price_per_day = (float)$_POST['price_per_day'];
    $size = $_POST['size'];
    $condition_rating = (int)$_POST['condition_rating'];
    $quantity = (int)$_POST['quantity'];
    
    // Handle image upload
    $image1 = null;
    if (isset($_FILES['image1']) && $_FILES['image1']['error'] == 0) {
        $upload_dir = '../uploads/costumes/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $file_extension = pathinfo($_FILES['image1']['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $file_extension;
        $upload_path = $upload_dir . $filename;
        
        if (move_uploaded_file($_FILES['image1']['tmp_name'], $upload_path)) {
            $image1 = 'uploads/costumes/' . $filename;
        }
    }
    
    // Validate required fields
    if (empty($name) || empty($description) || $category_id <= 0 || $price_per_day <= 0) {
        $message = '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">Please fill all required fields.</div>';
    } else {
        // Begin transaction
        $conn->begin_transaction();
        
        try {
            // Insert costume
            $stmt = $conn->prepare("INSERT INTO costumes (name, description, category_id, price_per_day, image1, size, condition_rating, owner_id, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'available')");
            $stmt->bind_param("ssidssis", $name, $description, $category_id, $price_per_day, $image1, $size, $condition_rating, $user_id);
            $stmt->execute();
            
            $costume_id = $conn->insert_id;
            
            // Insert stock record
            $stmt = $conn->prepare("INSERT INTO stocks (costume_id, quantity_total, quantity_available) VALUES (?, ?, ?)");
            $stmt->bind_param("iii", $costume_id, $quantity, $quantity);
            $stmt->execute();
            
            $conn->commit();
            $message = '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">Costume added successfully!</div>';
            
            // Clear form data
            $_POST = [];
            
        } catch (Exception $e) {
            $conn->rollback();
            $message = '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">Error adding costume: ' . $e->getMessage() . '</div>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Costume | ChARM Admin</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&display=swap" rel="stylesheet">
</head>
<body class="font-[lexend] bg-gradient-to-r from-orange-600 to-[#ea8b24]">
    <header class="flex justify-between items-center p-4 bg-orange-950">
        <a href="./adminpage.php" class="flex gap-2 items-center">
            <img class="max-h-9 w-auto ml-1 md:ml-10" src="../asset/logo_Charm.png" alt="logo_Charm">
            <span class="text-lg font-semibold mt-2 text-white">CHARM Admin - Add Costume</span>
        </a>
        <nav class="text-white flex justify-end items-center w-[92%] mx-auto text-lg">
            <div>
                <ul class="flex items-center gap-[4vw]">
                    <li><a class="hover:text-orange-500" href="./adminpage.php">Dashboard</a></li>
                    <li><a class="hover:text-orange-500" href="./costumes.php">Costumes</a></li>
                    <li><a class="hover:text-orange-500 font-bold" href="./addcos.php">Add Costume</a></li>
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
        <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Add New Costume</h1>
            
            <?php echo $message; ?>
            
            <form method="POST" enctype="multipart/form-data" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Costume Name *</label>
                        <input type="text" name="name" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:border-orange-500" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Category *</label>
                        <select name="category_id" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:border-orange-500">
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['id']; ?>" <?php echo (isset($_POST['category_id']) && $_POST['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Description *</label>
                    <textarea name="description" required rows="4" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:border-orange-500"><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Price per Day (IDR) *</label>
                        <input type="number" name="price_per_day" required min="0" step="0.01" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:border-orange-500" value="<?php echo isset($_POST['price_per_day']) ? $_POST['price_per_day'] : ''; ?>">
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Size</label>
                        <select name="size" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:border-orange-500">
                            <option value="XS" <?php echo (isset($_POST['size']) && $_POST['size'] == 'XS') ? 'selected' : ''; ?>>XS</option>
                            <option value="S" <?php echo (isset($_POST['size']) && $_POST['size'] == 'S') ? 'selected' : ''; ?>>S</option>
                            <option value="M" <?php echo (isset($_POST['size']) && $_POST['size'] == 'M') ? 'selected' : ''; ?>>M</option>
                            <option value="L" <?php echo (isset($_POST['size']) && $_POST['size'] == 'L') ? 'selected' : ''; ?>>L</option>
                            <option value="XL" <?php echo (isset($_POST['size']) && $_POST['size'] == 'XL') ? 'selected' : ''; ?>>XL</option>
                            <option value="XXL" <?php echo (isset($_POST['size']) && $_POST['size'] == 'XXL') ? 'selected' : ''; ?>>XXL</option>
                            <option value="One Size" <?php echo (isset($_POST['size']) && $_POST['size'] == 'One Size') ? 'selected' : ''; ?>>One Size</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Condition Rating (1-10)</label>
                        <input type="number" name="condition_rating" min="1" max="10" value="10" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:border-orange-500" value="<?php echo isset($_POST['condition_rating']) ? $_POST['condition_rating'] : '10'; ?>">
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Quantity Available</label>
                        <input type="number" name="quantity" min="1" value="1" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:border-orange-500" value="<?php echo isset($_POST['quantity']) ? $_POST['quantity'] : '1'; ?>">
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Costume Image</label>
                        <input type="file" name="image1" accept="image/*" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:border-orange-500">
                    </div>
                </div>
                
                <div class="flex justify-center space-x-4">
                    <button type="submit" style="background-color: #ea580c !important; color: white !important; padding: 12px 32px !important; border-radius: 8px !important; border: none !important; font-weight: 600 !important; cursor: pointer !important; display: block !important;">
                        Add Costume
                    </button>
                    <a href="./costumes.php" class="bg-gray-500 text-white px-8 py-3 rounded-lg hover:bg-gray-600 transition font-semibold">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <footer class="container mx-auto text-center py-8">
        <p class="text-white text-lg">© 2025 ChARM Costume Rental - Admin Panel</p>
    </footer>
</body>
</html>