<?php
session_start();

// Error handling and security improvements
error_reporting(E_ALL);
ini_set('display_errors', 0);

try {
    require_once 'db_connection.php';
    
    // Validate session
    if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in']) {
        header("Location: index.php");
        exit();
    }
    
    $user_id = filter_var($_SESSION['user_id'], FILTER_VALIDATE_INT);
    $username = htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8');
    
    if (!$user_id) {
        session_destroy();
        header("Location: index.php");
        exit();
    }
    
    // Get costume ID from URL
    $costume_id = filter_var($_GET['id'] ?? 0, FILTER_VALIDATE_INT);
    
    if (!$costume_id) {
        header("Location: main_page.php");
        exit();
    }
    
    // Get costume details with prepared statements
    $costume_sql = "SELECT c.*, cat.name as category_name, s.quantity_available 
                    FROM costumes c 
                    LEFT JOIN categories cat ON c.category_id = cat.id 
                    LEFT JOIN stocks s ON c.id = s.costume_id 
                    WHERE c.id = ? AND c.status = 'available'";
    
    $costume_stmt = $conn->prepare($costume_sql);
    $costume_stmt->bind_param("i", $costume_id);
    $costume_stmt->execute();
    $costume_result = $costume_stmt->get_result();
    $costume = $costume_result->fetch_assoc();
    
    if (!$costume) {
        header("Location: main_page.php");
        exit();
    }
    
} catch (Exception $e) {
    error_log("Costume detail error: " . $e->getMessage());
    header("Location: main_page.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($costume['name'], ENT_QUOTES, 'UTF-8'); ?> - ChARM</title>
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.0.5/css/boxicons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-orange: #f97316;
            --secondary-orange: #ea580c;
            --accent-orange: #fb923c;
            --dark-gray: #1f2937;
            --light-gray: #f3f4f6;
            --text-dark: #111827;
            --border-radius: 12px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        body {
            margin: 0;
            padding: 0;
            font-family: 'Lexend', sans-serif;
            line-height: 1.6;
            background: linear-gradient(135deg, #f97316 0%, #fb923c 50%, #fbbf24 100%);
            min-height: 100vh;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .product-container {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1);
            overflow: hidden;
            margin: 20px 0;
        }
        
        .product-image {
            width: 100%;
            height: 400px;
            object-fit: cover;
        }
        
        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: var(--transition);
            cursor: pointer;
            border: none;
        }
        
        .btn-primary {
            background: var(--primary-orange);
            color: white;
        }
        
        .btn-primary:hover {
            background: var(--secondary-orange);
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background: #6b7280;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #4b5563;
        }
        
        .badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .badge-blue {
            background: #dbeafe;
            color: #1e40af;
        }
        
        .badge-green {
            background: #dcfce7;
            color: #166534;
        }
        
        .badge-orange {
            background: #fed7aa;
            color: #c2410c;
        }
        
        @media (max-width: 768px) {
            .product-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header id="header" class="header flex justify-between overflow-hidden items-center p-4 w-full bg-white shadow-md sticky top-0 z-50">
        <div id="logo">
            <a href="main_page.php" class="flex gap-2 items-center">
                <img class="max-h-9 w-auto ml-1 md:ml-10" src="asset/logo_Charm.png" alt="ChARM Logo" loading="eager">
                <span class="text-lg font-semibold mt-2 text-custom_black">CHARM</span>
            </a>
        </div>

        <div class="md:hidden flex items-center gap-4">
            <button onclick="handleMenu()" class="flex items-center" aria-label="Toggle menu">
                <i class='bx bx-menu text-3xl text-custom_black'></i>
            </button>
        </div>
        
        <!-- navbar -->
        <nav id="navbar" class="flex max-md:fixed max-md:right-[-100%] max-md:top-16 max-md:p-5 transition-[right] max-sm:w-[70%] max-md:w-[60%] max-md:bg-custom_white duration-300 ease-in-out z-10">
            <ul class="flex items-center md:space-x-6 max-md:flex-col max-md:items-start max-md:mt-4 max-md:p-5">
                <!-- Search Bar -->
                <form action="search_costumes.php" method="GET" class="inline-block mt-2 w-[100%]" role="search">
                    <label for="search-input" class="sr-only">Search costumes</label>
                    <input type="text" id="search-input" name="q" placeholder="Search Costume..." class="border rounded-lg px-2 py-1 text-custom_black" required>
                    <button type="submit" class="px-2 py-1 text-white bg-custom_black rounded-lg max-sm:mt-3 hover:bg-gray-800 transition-colors">Go</button>
                </form>
                
                <li class="max-md:flex max-md:flex-row max-md:gap-3 max-md:items-center">
                    <a href="main_page.php" aria-label="Home">
                        <i class='bx bx-home-alt text-3xl text-custom_black mt-2 hover:scale-110 transition-transform'></i>
                    </a>
                    <p class="text-custom_black md:hidden">Home</p>
                </li>
                <li class="max-md:flex max-md:flex-row max-md:gap-3 max-md:items-center">
                    <a href="chart-page.php" aria-label="Shopping cart">
                        <i class='bx bx-shopping-bag text-3xl text-custom_black mt-2 hover:scale-110 transition-transform'></i>
                    </a>
                    <p class="text-custom_black md:hidden">Cart</p>
                </li>
                <li class="max-md:flex max-md:flex-row max-md:gap-3 max-md:items-center">
                    <a href="whistlist-page.php" aria-label="Wishlist">
                        <i class='bx bx-heart text-3xl text-custom_black mt-2 hover:scale-110 transition-transform'></i>
                    </a>
                    <p class="text-custom_black md:hidden">Wish List</p>
                </li>
                <li class="max-md:flex max-md:flex-row max-md:gap-3 max-md:items-center">
                    <a href="account_setting_page.php" aria-label="Account settings">
                        <i class='bx bx-user text-3xl text-custom_black mt-2 hover:scale-110 transition-transform'></i>
                    </a>
                    <p class="text-custom_black md:hidden">Account</p>
                </li>
                <li class="max-md:flex max-md:flex-row max-md:gap-3 max-md:items-center">
                    <a href="logout.php" aria-label="Logout">
                        <i class='bx bx-log-out text-3xl text-custom_black mt-2 hover:scale-110 transition-transform'></i>
                    </a>
                    <p class="text-custom_black md:hidden">Logout</p>
                </li>
            </ul>
        </nav>
    </header>
    
    <!-- Main Content -->
    <main class="container py-8">
        <div class="product-container">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 product-grid">
                <!-- Product Image -->
                <div class="p-6">
                    <?php if ($costume['image1']): ?>
                        <img src="./asset/<?php echo htmlspecialchars($costume['image1'], ENT_QUOTES, 'UTF-8'); ?>" 
                             alt="<?php echo htmlspecialchars($costume['name'], ENT_QUOTES, 'UTF-8'); ?>" 
                             class="product-image rounded-lg">
                    <?php else: ?>
                        <div class="product-image bg-gray-200 flex items-center justify-center rounded-lg">
                            <i class='bx bx-image text-6xl text-gray-400'></i>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Additional Images -->
                    <div class="grid grid-cols-3 gap-2 mt-4">
                        <?php if ($costume['image2']): ?>
                            <img src="./asset/<?php echo htmlspecialchars($costume['image2'], ENT_QUOTES, 'UTF-8'); ?>" 
                                 alt="<?php echo htmlspecialchars($costume['name'], ENT_QUOTES, 'UTF-8'); ?> - Image 2" 
                                 class="w-full h-20 object-cover rounded cursor-pointer hover:opacity-75 transition-opacity">
                        <?php endif; ?>
                        <?php if ($costume['image3']): ?>
                            <img src="./asset/<?php echo htmlspecialchars($costume['image3'], ENT_QUOTES, 'UTF-8'); ?>" 
                                 alt="<?php echo htmlspecialchars($costume['name'], ENT_QUOTES, 'UTF-8'); ?> - Image 3" 
                                 class="w-full h-20 object-cover rounded cursor-pointer hover:opacity-75 transition-opacity">
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Product Details -->
                <div class="p-6">
                    <div class="mb-4">
                        <span class="badge badge-orange"><?php echo htmlspecialchars($costume['category_name'], ENT_QUOTES, 'UTF-8'); ?></span>
                    </div>
                    
                    <h1 class="text-3xl font-bold text-gray-800 mb-4"><?php echo htmlspecialchars($costume['name'], ENT_QUOTES, 'UTF-8'); ?></h1>
                    
                    <div class="text-3xl font-bold text-orange-600 mb-6">
                        Rp <?php echo number_format($costume['price_per_day'], 0, ',', '.'); ?>
                        <span class="text-lg font-normal text-gray-600">/day</span>
                    </div>
                    
                    <!-- Product Info -->
                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between items-center py-2 border-b border-gray-200">
                            <span class="font-semibold text-gray-700">Size:</span>
                            <span class="badge badge-blue"><?php echo htmlspecialchars($costume['size'], ENT_QUOTES, 'UTF-8'); ?></span>
                        </div>
                        
                        <div class="flex justify-between items-center py-2 border-b border-gray-200">
                            <span class="font-semibold text-gray-700">Condition:</span>
                            <span class="badge badge-green"><?php echo htmlspecialchars($costume['costume_condition'], ENT_QUOTES, 'UTF-8'); ?></span>
                        </div>
                        
                        <div class="flex justify-between items-center py-2 border-b border-gray-200">
                            <span class="font-semibold text-gray-700">Available:</span>
                            <span class="badge badge-green"><?php echo $costume['quantity_available']; ?> pieces</span>
                        </div>
                    </div>
                    
                    <!-- Description -->
                    <?php if ($costume['description']): ?>
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Description</h3>
                        <p class="text-gray-600 leading-relaxed"><?php echo nl2br(htmlspecialchars($costume['description'], ENT_QUOTES, 'UTF-8')); ?></p>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Action Buttons -->
                    <div class="space-y-4">
                        <?php if ($costume['quantity_available'] > 0): ?>
                            <form action="chart-page.php" method="POST" class="w-full">
                                <input type="hidden" name="costume_id" value="<?php echo $costume['id']; ?>">
                                <input type="hidden" name="action" value="add_to_cart">
                                <button type="submit" class="btn btn-primary w-full text-center">
                                    <i class='bx bx-cart mr-2'></i>Add to Cart
                                </button>
                            </form>
                            
                            <form action="checkout-page.php" method="POST" class="w-full">
                                <input type="hidden" name="costume_id" value="<?php echo $costume['id']; ?>">
                                <button type="submit" class="btn btn-secondary w-full text-center">
                                    <i class='bx bx-credit-card mr-2'></i>Rent Now
                                </button>
                            </form>
                        <?php else: ?>
                            <button class="btn w-full text-center bg-gray-400 text-white cursor-not-allowed" disabled>
                                <i class='bx bx-x mr-2'></i>Out of Stock
                            </button>
                        <?php endif; ?>
                        
                        <a href="javascript:history.back()" class="btn btn-secondary w-full text-center">
                            <i class='bx bx-arrow-back mr-2'></i>Back to Costumes
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Related Costumes Section -->
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-white mb-6">Related Costumes</h2>
            
            <?php
            // Get related costumes from the same category
            $related_sql = "SELECT c.*, s.quantity_available 
                           FROM costumes c 
                           LEFT JOIN stocks s ON c.id = s.costume_id 
                           WHERE c.category_id = ? AND c.id != ? AND c.status = 'available' AND s.quantity_available > 0
                           ORDER BY RAND() 
                           LIMIT 4";
            
            $related_stmt = $conn->prepare($related_sql);
            $related_stmt->bind_param("ii", $costume['category_id'], $costume_id);
            $related_stmt->execute();
            $related_result = $related_stmt->get_result();
            $related_costumes = $related_result->fetch_all(MYSQLI_ASSOC);
            ?>
            
            <?php if (!empty($related_costumes)): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <?php foreach ($related_costumes as $related): ?>
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:transform hover:scale-105 transition-all duration-300">
                            <a href="costume_detail.php?id=<?php echo $related['id']; ?>" class="block">
                                <?php if ($related['image1']): ?>
                                    <img src="./asset/<?php echo htmlspecialchars($related['image1'], ENT_QUOTES, 'UTF-8'); ?>" 
                                         alt="<?php echo htmlspecialchars($related['name'], ENT_QUOTES, 'UTF-8'); ?>" 
                                         class="w-full h-48 object-cover">
                                <?php else: ?>
                                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                        <i class='bx bx-image text-4xl text-gray-400'></i>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="p-4">
                                    <h3 class="font-bold text-lg mb-2 line-clamp-2"><?php echo htmlspecialchars($related['name'], ENT_QUOTES, 'UTF-8'); ?></h3>
                                    <p class="text-lg font-semibold text-orange-600">Rp <?php echo number_format($related['price_per_day'], 0, ',', '.'); ?>/day</p>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-8">
                    <p class="text-white text-lg">No related costumes found.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>
    
    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-8 mt-16">
        <div class="container text-center">
            <p>&copy; 2025 ChARM Costume Rental. All rights reserved.</p>
        </div>
    </footer>
    
    <script>
        // Image gallery functionality
        document.addEventListener('DOMContentLoaded', function() {
            const mainImage = document.querySelector('.product-image');
            const thumbnails = document.querySelectorAll('.grid img[src*="image"]');
            
            thumbnails.forEach(thumb => {
                thumb.addEventListener('click', function() {
                    mainImage.src = this.src;
                    mainImage.alt = this.alt;
                });
            });
        });
    </script>
</body>
</html>
<style>
.header {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    transition: var(--transition);
}

.header.scrolled {
    box-shadow: var(--shadow-md);
}

.text-custom_black {
    color: #111827;
}

.bg-custom_white {
    background-color: #ffffff;
}

.sr-only {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border: 0;
}

</style>

<script>

// Mobile menu handler
function handleMenu() {
    const navbar = document.getElementById('navbar');
    if (navbar.style.right === '0px' || navbar.style.right === '') {
        navbar.style.right = '-100%';
    } else {
        navbar.style.right = '0px';
    }
}

// Header scroll effect
window.addEventListener('scroll', function() {
    const header = document.getElementById('header');
    if (window.scrollY > 50) {
        header.classList.add('scrolled');
    } else {
        header.classList.remove('scrolled');
    }
});
</script>