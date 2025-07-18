<?php
session_start();

// Error handling and security improvements
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't display errors to users in production

// Database connection with better error handling
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
    
    // Get featured costumes with prepared statements
    $featured_sql = "SELECT c.*, cat.name as category_name, s.quantity_available 
                     FROM costumes c 
                     LEFT JOIN categories cat ON c.category_id = cat.id 
                     LEFT JOIN stocks s ON c.id = s.costume_id 
                     WHERE c.status = 'available' AND s.quantity_available > 0
                     ORDER BY c.created_at DESC";
    
    $featured_stmt = $conn->prepare($featured_sql);
    $featured_stmt->execute();
    $featured_result = $featured_stmt->get_result();
    $featured_costumes = $featured_result->fetch_all(MYSQLI_ASSOC);
    
    // Get categories for navigation
    $categories_sql = "SELECT * FROM categories WHERE is_active = 1 ORDER BY sort_order, name";
    $categories_stmt = $conn->prepare($categories_sql);
    $categories_stmt->execute();
    $categories_result = $categories_stmt->get_result();
    $categories = $categories_result->fetch_all(MYSQLI_ASSOC);
    
} catch (Exception $e) {
    error_log("Main page error: " . $e->getMessage());
    $featured_costumes = [];
    $categories = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="ChARM - Premium costume rental service for anime, cosplay, and themed events">
    <meta name="keywords" content="costume rental, anime costumes, cosplay, themed events">
    <title>Home - ChARM Costume Rental</title>
    
    <!-- Preload critical resources -->
    <link rel="preload" href="./css/style.css" as="style">
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&display=swap" as="style">
    
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
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        * {
            box-sizing: border-box;
        }
        
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            min-height: 100vh;
            font-family: 'Lexend', sans-serif;
            line-height: 1.6;
            scroll-behavior: smooth;
        }
        
        /* Loading animation */
        .loading {
            opacity: 0;
            transform: translateY(20px);
            transition: var(--transition);
        }
        
        .loading.loaded {
            opacity: 1;
            transform: translateY(0);
        }
        
        /* Header improvements */
        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
            transition: var(--transition);
        }
        
        .header.scrolled {
            box-shadow: var(--shadow-md);
        }
        
        /* Enhanced carousel */
        .carousel-container {
            position: relative;
            width: 100%;
            height: 500px;
            overflow: hidden;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-xl);
            margin: 0 auto;
            max-width: 1400px;
        }
        
        .carousel-slide {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .carousel-slide.active {
            opacity: 1;
        }
        
        .carousel-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .carousel-slide:hover img {
            transform: scale(1.02);
        }
        
        /* Enhanced navigation */
        .carousel-nav {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 12px;
            background: rgba(0, 0, 0, 0.3);
            padding: 8px 16px;
            border-radius: 20px;
            backdrop-filter: blur(10px);
        }
        
        .carousel-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            transition: var(--transition);
            position: relative;
        }
        
        .carousel-dot.active {
            background: white;
            transform: scale(1.2);
        }
        
        .carousel-dot:hover {
            background: rgba(255, 255, 255, 0.8);
        }
        
        .carousel-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0, 0, 0, 0.6);
            color: white;
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
            backdrop-filter: blur(10px);
        }
        
        .carousel-arrow:hover {
            background: rgba(0, 0, 0, 0.8);
            transform: translateY(-50%) scale(1.1);
        }
        
        .carousel-arrow.prev {
            left: 20px;
        }
        
        .carousel-arrow.next {
            right: 20px;
        }
        
        /* Enhanced costume cards */
        .costume-card {
            background: white;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--shadow-md);
            transition: var(--transition);
            position: relative;
            height: 100%;
        }
        
        .costume-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-xl);
        }
        
        .costume-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, transparent, rgba(249, 115, 22, 0.1));
            opacity: 0;
            transition: var(--transition);
            pointer-events: none;
        }
        
        .costume-card:hover::before {
            opacity: 1;
        }
        
        .costume-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            transition: var(--transition);
        }
        
        .costume-card:hover .costume-image {
            transform: scale(1.05);
        }
        
        /* Enhanced filter buttons */
        .filter-btn {
            background: white;
            border: 2px solid #e5e7eb;
            color: var(--text-dark);
            padding: 12px 24px;
            border-radius: 50px;
            font-weight: 500;
            transition: var(--transition);
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }
        
        .filter-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(249, 115, 22, 0.2), transparent);
            transition: left 0.5s;
        }
        
        .filter-btn:hover::before {
            left: 100%;
        }
        
        .filter-btn:hover,
        .filter-btn.active {
            background: var(--primary-orange);
            color: white;
            border-color: var(--primary-orange);
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }
        
        /* Enhanced animations */
        .fade-in {
            animation: fadeInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .stagger-animation > * {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }
        
        .stagger-animation > *:nth-child(1) { animation-delay: 0.1s; }
        .stagger-animation > *:nth-child(2) { animation-delay: 0.2s; }
        .stagger-animation > *:nth-child(3) { animation-delay: 0.3s; }
        .stagger-animation > *:nth-child(4) { animation-delay: 0.4s; }
        .stagger-animation > *:nth-child(5) { animation-delay: 0.5s; }
        .stagger-animation > *:nth-child(6) { animation-delay: 0.6s; }
        
        /* Responsive improvements */
        .container {
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        
        @media (min-width: 640px) {
            .container { padding: 0 1.5rem; }
        }
        
        @media (min-width: 1024px) {
            .container { padding: 0 2rem; }
        }
        
        @media (max-width: 768px) {
            .carousel-container {
                height: 300px;
            }
            
            .carousel-arrow {
                width: 40px;
                height: 40px;
                font-size: 16px;
            }
            
            .filter-btn {
                padding: 8px 16px;
                font-size: 14px;
            }
        }
        
        /* Performance optimizations */
        .costume-image {
            will-change: transform;
        }
        
        .carousel-slide {
            will-change: opacity;
        }
        
        /* Accessibility improvements */
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
        
        /* Focus styles */
        .carousel-arrow:focus,
        .carousel-dot:focus,
        .filter-btn:focus {
            outline: 2px solid var(--primary-orange);
            outline-offset: 2px;
        }
    </style>
</head>

<body class="bg-gradient-to-r from-custom_orange via-bright_orange to-dark_cream">
    <!-- Loading overlay -->
    <div id="loading-overlay" class="fixed inset-0 bg-white z-50 flex items-center justify-center transition-opacity duration-500">
        <div class="text-center">
            <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-orange-500 mx-auto mb-4"></div>
            <p class="text-gray-600">Loading ChARM...</p>
        </div>
    </div>

    <!-- Header -->
    <header id="header" class="header flex justify-between overflow-hidden items-center p-4 w-full">
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

    <!-- Hero Carousel Section -->
    <section class="container py-8">
        <div class="carousel-container mb-8 loading">
            <div class="carousel-slide active">
                <img src="asset/costume-scrol1.png" alt="Featured Costume Collection 1" loading="eager">
            </div>
            <div class="carousel-slide">
                <img src="asset/costume-scrol2.png" alt="Featured Costume Collection 2" loading="lazy">
            </div>
            <div class="carousel-slide">
                <img src="asset/costume-scrol3.png" alt="Featured Costume Collection 3" loading="lazy">
            </div>
            
            <!-- Navigation arrows -->
            <button class="carousel-arrow prev" onclick="changeSlide(-1)" aria-label="Previous slide">
                <i class='bx bx-chevron-left'></i>
            </button>
            <button class="carousel-arrow next" onclick="changeSlide(1)" aria-label="Next slide">
                <i class='bx bx-chevron-right'></i>
            </button>
            
            <!-- Navigation dots -->
            <div class="carousel-nav" role="tablist">
                <button class="carousel-dot active" onclick="currentSlide(1)" aria-label="Go to slide 1" role="tab"></button>
                <button class="carousel-dot" onclick="currentSlide(2)" aria-label="Go to slide 2" role="tab"></button>
                <button class="carousel-dot" onclick="currentSlide(3)" aria-label="Go to slide 3" role="tab"></button>
            </div>
        </div>
    </section>

    <!-- Welcome Section -->
    <section class="container py-8">
        <div class="text-center mb-8 loading">
            <h1 class="text-4xl font-bold text-custom_black mb-4">Welcome back, <?php echo $username; ?>!</h1>
            <p class="text-lg text-dark_brown">Discover amazing anime costumes for your next adventure</p>
        </div>

        <!-- Featured Costumes Section -->
        <div class="mb-12 loading">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-custom_black">Featured Costumes</h2>
                <a href="all_costumes.php" class="text-dark_orange hover:text-bright_orange font-semibold transition-colors">View All →</a>
            </div>
            
            <!-- Categories Filter Section -->
            <?php if (!empty($categories)): ?>
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-custom_black mb-4">Filter by Category</h3>
                <div class="flex flex-wrap gap-4 justify-start stagger-animation">
                    <button onclick="showAllCostumes()" 
                            class="filter-btn active" 
                            data-category="all">
                        <i class='bx bx-grid-alt mr-2'></i>All Costumes
                    </button>
                    <?php foreach ($categories as $category): ?>
                        <button onclick="filterCostumes('<?php echo $category['id']; ?>')" 
                                class="filter-btn"
                                data-category="<?php echo $category['id']; ?>">
                            <i class='bx bx-category mr-2'></i><?php echo htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8'); ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($featured_costumes)): ?>
                <div id="costumes-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-6 gap-6 stagger-animation">
                    <?php foreach ($featured_costumes as $costume): ?>
                        <div class="costume-item costume-card" data-category="<?php echo $costume['category_id']; ?>">
                            <a href="costume_detail.php?id=<?php echo $costume['id']; ?>" class="block h-full">
                                <?php if ($costume['image1']): ?>
                                    <img src="./asset/<?php echo htmlspecialchars($costume['image1'], ENT_QUOTES, 'UTF-8'); ?>" 
                                         alt="<?php echo htmlspecialchars($costume['name'], ENT_QUOTES, 'UTF-8'); ?>" 
                                         class="costume-image"
                                         loading="lazy">
                                <?php else: ?>
                                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                        <i class='bx bx-image text-4xl text-gray-400'></i>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="p-4">
                                    <h3 class="font-bold text-lg mb-2 line-clamp-2"><?php echo htmlspecialchars($costume['name'], ENT_QUOTES, 'UTF-8'); ?></h3>
                                    <p class="text-sm text-gray-600 mb-2"><?php echo htmlspecialchars($costume['category_name'], ENT_QUOTES, 'UTF-8'); ?></p>
                                    <p class="text-lg font-semibold text-dark_orange mb-2">Rp <?php echo number_format($costume['price_per_day'], 0, ',', '.'); ?>/day</p>
                                    
                                    <div class="flex justify-between items-center">
                                        <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">Size: <?php echo htmlspecialchars($costume['size'], ENT_QUOTES, 'UTF-8'); ?></span>
                                        <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">Available: <?php echo $costume['quantity_available']; ?></span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- No results message -->
                <div id="no-results" class="text-center py-20 hidden" style="min-height: 60vh;">
                    <div class="flex flex-col justify-center items-center h-full">
                        <i class='bx bx-search text-6xl text-gray-400 mb-6'></i>
                        <p class="text-xl text-gray-600 mb-4">No costumes found in this category.</p>
                        <p class="text-sm text-gray-500">Try selecting a different category to see available costumes.</p>
                        <button onclick="showAllCostumes()" class="mt-4 px-6 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors">
                            Show All Costumes
                        </button>
                    </div>
                </div>
            <?php else: ?>
                <div class="text-center py-20" style="min-height: 60vh;">
                    <div class="flex flex-col justify-center items-center h-full">
                        <i class='bx bx-package text-6xl text-gray-400 mb-6'></i>
                        <p class="text-xl text-gray-600 mb-4">No costumes available at the moment.</p>
                        <p class="text-sm text-gray-500">Please check back later for new arrivals.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Enhanced Footer -->
    <footer class="custom-footer">
        <div class="footer-container">
            <div class="footer-grid">
                <!-- Company Info -->
                <div class="footer-company">
                    <div class="footer-logo">
                        <img src="asset/logo_Charm.png" alt="ChARM Logo" class="footer-logo-img">
                        <h3 class="footer-brand">ChARM</h3>
                    </div>
                    <p class="footer-description">
                        Your premier destination for high-quality costume rentals. From anime characters to historical figures, we bring your imagination to life with our extensive collection.
                    </p>
                    <div class="footer-social">
                        <a href="#" class="social-link">
                            <i class='bx bxl-facebook'></i>
                        </a>
                        <a href="#" class="social-link">
                            <i class='bx bxl-instagram'></i>
                        </a>
                        <a href="#" class="social-link">
                            <i class='bx bxl-twitter'></i>
                        </a>
                        <a href="#" class="social-link">
                            <i class='bx bxl-youtube'></i>
                        </a>
                    </div>
                </div>
                <style>
                            /* Custom Footer Styles */
        .custom-footer {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 50%, #000000 100%) !important;
            color: #ffffff !important;
            padding: 64px 0 32px 0 !important;
            margin-top: 64px !important;
            position: relative !important;
            z-index: 10 !important;
        }
        
        .footer-container {
            max-width: 1200px !important;
            margin: 0 auto !important;
            padding: 0 20px !important;
        }
        
        .footer-grid {
            display: grid !important;
            grid-template-columns: 2fr 1fr 1fr !important;
            gap: 40px !important;
            margin-bottom: 48px !important;
        }
        
        @media (max-width: 768px) {
            .footer-grid {
                grid-template-columns: 1fr !important;
                gap: 32px !important;
            }
        }
        
        .footer-company {
            max-width: 400px !important;
        }
        
        .footer-logo {
            display: flex !important;
            align-items: center !important;
            margin-bottom: 24px !important;
        }
        
        .footer-logo-img {
            height: 48px !important;
            width: auto !important;
            margin-right: 16px !important;
        }
        
        .footer-brand {
            font-size: 32px !important;
            font-weight: bold !important;
            background: linear-gradient(45deg, #f97316, #ea580c) !important;
            -webkit-background-clip: text !important;
            -webkit-text-fill-color: transparent !important;
            background-clip: text !important;
            margin: 0 !important;
        }
        
        .footer-description {
            color: #d1d5db !important;
            line-height: 1.6 !important;
            margin-bottom: 24px !important;
            font-size: 16px !important;
        }
        
        .footer-social {
            display: flex !important;
            gap: 16px !important;
        }
        
        .social-link {
            width: 40px !important;
            height: 40px !important;
            background-color: #374151 !important;
            border-radius: 50% !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            color: #ffffff !important;
            text-decoration: none !important;
            transition: all 0.3s ease !important;
        }
        
        .social-link:hover {
            background-color: #f97316 !important;
            transform: translateY(-2px) !important;
        }
        
        .social-link i {
            font-size: 20px !important;
        }
        
        .footer-section {
            /* Styles for Quick Links and Contact sections */
        }
        
        .footer-title {
            font-size: 18px !important;
            font-weight: 600 !important;
            color: #f97316 !important;
            margin-bottom: 24px !important;
            margin-top: 0 !important;
        }
        
        .footer-links {
            list-style: none !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        
        .footer-links li {
            margin-bottom: 12px !important;
        }
        
        .footer-link {
            color: #d1d5db !important;
            text-decoration: none !important;
            transition: color 0.3s ease !important;
            font-size: 16px !important;
        }
        
        .footer-link:hover {
            color: #f97316 !important;
        }
        
        .footer-contact {
            /* Contact section styles */
        }
        
        .contact-item {
            display: flex !important;
            align-items: flex-start !important;
            margin-bottom: 16px !important;
        }
        
        .contact-icon {
            color: #f97316 !important;
            font-size: 20px !important;
            margin-right: 12px !important;
            margin-top: 2px !important;
            flex-shrink: 0 !important;
        }
        
        .contact-text {
            color: #d1d5db !important;
            font-size: 16px !important;
            line-height: 1.5 !important;
        }
        
        .contact-text p {
            margin: 0 !important;
            line-height: 1.4 !important;
        }
        
        .footer-bottom {
            border-top: 1px solid #374151 !important;
            padding-top: 32px !important;
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            flex-wrap: wrap !important;
            gap: 16px !important;
        }
        
        @media (max-width: 768px) {
            .footer-bottom {
                flex-direction: column !important;
                text-align: center !important;
            }
        }
        
        .footer-copyright {
            color: #9ca3af !important;
            font-size: 14px !important;
            margin: 0 !important;
        }
        
        .brand-highlight {
            color: #f97316 !important;
            font-weight: 600 !important;
        }
        
        .footer-legal {
            display: flex !important;
            gap: 24px !important;
            flex-wrap: wrap !important;
        }
        
        .legal-link {
            color: #9ca3af !important;
            text-decoration: none !important;
            font-size: 14px !important;
            transition: color 0.3s ease !important;
        }
        
        .legal-link:hover {
            color: #f97316 !important;
        }
        
        /* Ensure footer is always visible */
        .custom-footer {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
        }
                </style>
                
                <!-- Quick Links -->
                <div class="footer-section">
                    <h4 class="footer-title">Quick Links</h4>
                    <ul class="footer-links">
                        <li><a href="main_page.php" class="footer-link">Home</a></li>
                        <li><a href="all_costumes.php" class="footer-link">All Costumes</a></li>
                        <li><a href="#" class="footer-link">Categories</a></li>
                        <li><a href="#" class="footer-link">About Us</a></li>
                        <li><a href="#" class="footer-link">FAQ</a></li>
                    </ul>
                </div>
                
                <!-- Contact Info -->
                <div class="footer-section">
                    <h4 class="footer-title">Contact Us</h4>
                    <div class="footer-contact">
                        <div class="contact-item">
                            <i class='bx bx-map contact-icon'></i>
                            <div class="contact-text">
                                <p>123 Costume Street</p>
                                <p>Jakarta, Indonesia</p>
                            </div>
                        </div>
                        <div class="contact-item">
                            <i class='bx bx-phone contact-icon'></i>
                            <span class="contact-text">+62 123 456 7890</span>
                        </div>
                        <div class="contact-item">
                            <i class='bx bx-envelope contact-icon'></i>
                            <span class="contact-text">info@charm.com</span>
                        </div>
                        <div class="contact-item">
                            <i class='bx bx-time contact-icon'></i>
                            <span class="contact-text">Mon-Sun: 9AM-9PM</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Bottom Bar -->
            <div class="footer-bottom">
                <p class="footer-copyright">
                    © 2025 <span class="brand-highlight">ChARM Costume Rental</span>. All rights reserved.
                </p>
                <div class="footer-legal">
                    <a href="#" class="legal-link">Privacy Policy</a>
                    <a href="#" class="legal-link">Terms of Service</a>
                    <a href="#" class="legal-link">Return Policy</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Enhanced JavaScript -->
    <script>
        // Performance and stability improvements
        document.addEventListener('DOMContentLoaded', function() {
            // Hide loading overlay
            setTimeout(() => {
                const loadingOverlay = document.getElementById('loading-overlay');
                if (loadingOverlay) {
                    loadingOverlay.style.opacity = '0';
                    setTimeout(() => {
                        loadingOverlay.style.display = 'none';
                    }, 500);
                }
                
                // Trigger loading animations
                const loadingElements = document.querySelectorAll('.loading');
                loadingElements.forEach((el, index) => {
                    setTimeout(() => {
                        el.classList.add('loaded');
                    }, index * 200);
                });
            }, 1000);
            
            // Header scroll effect
            const header = document.getElementById('header');
            let lastScrollY = window.scrollY;
            
            window.addEventListener('scroll', () => {
                const currentScrollY = window.scrollY;
                
                if (currentScrollY > 100) {
                    header.classList.add('scrolled');
                } else {
                    header.classList.remove('scrolled');
                }
                
                lastScrollY = currentScrollY;
            }, { passive: true });
        });
        
        // Enhanced menu handling
        function handleMenu() {
            const navbar = document.getElementById('navbar');
            const isOpen = !navbar.classList.contains('max-md:right-[-100%]');
            
            if (isOpen) {
                navbar.classList.add('max-md:right-[-100%]');
                navbar.classList.remove('max-md:right-0');
            } else {
                navbar.classList.remove('max-md:right-[-100%]');
                navbar.classList.add('max-md:right-0');
            }
        }
        
        // Enhanced filter functionality
        function filterCostumes(categoryId) {
            const costumes = document.querySelectorAll('.costume-item');
            const filterButtons = document.querySelectorAll('.filter-btn');
            const noResults = document.getElementById('no-results');
            let visibleCount = 0;
            
            // Update button states
            filterButtons.forEach(btn => {
                btn.classList.remove('active');
            });
            
            const activeButton = document.querySelector(`[data-category="${categoryId}"]`);
            if (activeButton) {
                activeButton.classList.add('active');
            }
            
            // Filter costumes with animation
            costumes.forEach((costume, index) => {
                const costumeCategory = costume.getAttribute('data-category');
                
                if (costumeCategory === categoryId) {
                    setTimeout(() => {
                        costume.style.display = 'block';
                        costume.classList.add('fade-in');
                    }, index * 50);
                    visibleCount++;
                } else {
                    costume.style.display = 'none';
                    costume.classList.remove('fade-in');
                }
            });
            
            // Show/hide no results message
            setTimeout(() => {
                if (visibleCount === 0) {
                    noResults.classList.remove('hidden');
                } else {
                    noResults.classList.add('hidden');
                }
            }, 300);
        }
        
        function showAllCostumes() {
            const costumes = document.querySelectorAll('.costume-item');
            const filterButtons = document.querySelectorAll('.filter-btn');
            const noResults = document.getElementById('no-results');
            
            // Update button states
            filterButtons.forEach(btn => {
                btn.classList.remove('active');
            });
            
            const allButton = document.querySelector('[data-category="all"]');
            if (allButton) {
                allButton.classList.add('active');
            }
            
            // Show all costumes
            costumes.forEach((costume, index) => {
                setTimeout(() => {
                    costume.style.display = 'block';
                    costume.classList.add('fade-in');
                }, index * 30);
            });
            
            noResults.classList.add('hidden');
        }
        
        // Enhanced carousel functionality
        let currentSlideIndex = 0;
        const slides = document.querySelectorAll('.carousel-slide');
        const dots = document.querySelectorAll('.carousel-dot');
        let autoSlideInterval;
        
        function showSlide(index) {
            // Hide all slides
            slides.forEach(slide => slide.classList.remove('active'));
            dots.forEach(dot => dot.classList.remove('active'));
            
            // Show current slide
            if (slides[index]) {
                slides[index].classList.add('active');
            }
            if (dots[index]) {
                dots[index].classList.add('active');
            }
        }
        
        function changeSlide(direction) {
            currentSlideIndex += direction;
            
            if (currentSlideIndex >= slides.length) {
                currentSlideIndex = 0;
            } else if (currentSlideIndex < 0) {
                currentSlideIndex = slides.length - 1;
            }
            
            showSlide(currentSlideIndex);
            resetAutoSlide();
        }
        
        function currentSlide(index) {
            currentSlideIndex = index - 1;
            showSlide(currentSlideIndex);
            resetAutoSlide();
        }
        
        function startAutoSlide() {
            autoSlideInterval = setInterval(() => {
                changeSlide(1);
            }, 5000);
        }
        
        function resetAutoSlide() {
            clearInterval(autoSlideInterval);
            startAutoSlide();
        }
        
        // Start auto-slide when page loads
        if (slides.length > 0) {
            startAutoSlide();
        }
        
        // Pause auto-slide on hover
        const carouselContainer = document.querySelector('.carousel-container');
        if (carouselContainer) {
            carouselContainer.addEventListener('mouseenter', () => {
                clearInterval(autoSlideInterval);
            });
            
            carouselContainer.addEventListener('mouseleave', () => {
                startAutoSlide();
            });
        }
        
        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') {
                changeSlide(-1);
            } else if (e.key === 'ArrowRight') {
                changeSlide(1);
            }
        });
        
        // Error handling for images
        document.querySelectorAll('img').forEach(img => {
            img.addEventListener('error', function() {
                this.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZGRkIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0iIzk5OSIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPkltYWdlIG5vdCBmb3VuZDwvdGV4dD48L3N2Zz4=';
                this.alt = 'Image not found';
            });
        });
        
        // Performance optimization: Lazy load images
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        if (img.dataset.src) {
                            img.src = img.dataset.src;
                            img.removeAttribute('data-src');
                        }
                        observer.unobserve(img);
                    }
                });
            });
            
            document.querySelectorAll('img[data-src]').forEach(img => {
                imageObserver.observe(img);
            });
        }
    </script>
</body>
</html>

<?php 
if (isset($conn)) {
    $conn->close();
}
?>