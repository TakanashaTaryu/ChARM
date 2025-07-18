<?php
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true || 
    !isset($_SESSION['admin_value']) || $_SESSION['admin_value'] != 1) {
    header("Location: ../login.php");
    exit();
}

require_once '../db_connection.php';

// Get dashboard statistics
$stats = [];

// Total costumes
$result = $conn->query("SELECT COUNT(*) as total FROM costumes WHERE status = 'available'");
$stats['total_costumes'] = $result->fetch_assoc()['total'];

// Total users
$result = $conn->query("SELECT COUNT(*) as total FROM users WHERE admin_value = 0");
$stats['total_users'] = $result->fetch_assoc()['total'];

// Total categories
$result = $conn->query("SELECT COUNT(*) as total FROM categories WHERE is_active = 1");
$stats['total_categories'] = $result->fetch_assoc()['total'];

// Recent rentals
$result = $conn->query("SELECT COUNT(*) as total FROM rent_details WHERE status = 'active'");
$stats['active_rentals'] = $result->fetch_assoc()['total'];

$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | ChARM</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.0.5/css/boxicons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-orange: #ea580c;
            --primary-orange-dark: #c2410c;
            --primary-orange-light: #fb923c;
            --secondary-orange: #ea8b24;
            --dark-brown: #7c2d12;
            --light-gray: #f8fafc;
            --medium-gray: #64748b;
            --dark-gray: #1e293b;
            --white: #ffffff;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            font-family: 'Lexend', sans-serif;
            background: linear-gradient(135deg, var(--primary-orange) 0%, var(--secondary-orange) 100%);
            color: var(--dark-gray);
        }
        
        .content-wrapper {
            flex: 1;
            padding-bottom: 2rem;
        }
        
        /* Header Styles */
        .header {
            background: var(--dark-brown);
            padding: 1rem 0;
            box-shadow: var(--shadow-lg);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 1rem;
        }
        
        .logo-section {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
        }
        
        .logo-section img {
            height: 2.5rem;
            width: auto;
        }
        
        .logo-text {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--white);
            margin-top: 0.25rem;
        }
        
        .nav-menu {
            display: flex;
            align-items: center;
            gap: 2rem;
        }
        
        .nav-links {
            display: flex;
            align-items: center;
            gap: 2rem;
            list-style: none;
        }
        
        .nav-links a {
            color: var(--white);
            text-decoration: none;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }
        
        .nav-links a:hover {
            background-color: var(--primary-orange);
            transform: translateY(-1px);
        }
        
        .logout-btn {
            background: var(--white) !important;
            color: var(--dark-gray) !important;
            padding: 0.75rem 1.5rem !important;
            border-radius: 2rem !important;
            font-weight: 600 !important;
            transition: all 0.3s ease !important;
        }
        
        .logout-btn:hover {
            background: var(--dark-gray) !important;
            color: var(--white) !important;
            transform: translateY(-2px) !important;
            box-shadow: var(--shadow-md) !important;
        }
        
        /* Dashboard Content */
        .dashboard-header {
            text-align: center;
            margin: 3rem 0;
            color: var(--white);
        }
        
        .dashboard-title {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }
        
        .welcome-text {
            font-size: 1.25rem;
            font-weight: 400;
            opacity: 0.9;
        }
        
        /* Statistics Cards */
        .stats-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }
        
        .stat-card {
            background: var(--white);
            border-radius: 1rem;
            padding: 2rem;
            text-align: center;
            box-shadow: var(--shadow-xl);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-orange), var(--secondary-orange));
        }
        
        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-xl);
        }
        
        .stat-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            display: block;
        }
        
        .stat-icon.costumes { color: var(--primary-orange); }
        .stat-icon.users { color: #3b82f6; }
        .stat-icon.categories { color: #10b981; }
        .stat-icon.rentals { color: #8b5cf6; }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--dark-gray);
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            color: var(--medium-gray);
            font-weight: 500;
            font-size: 1rem;
        }
        
        /* Quick Actions */
        .quick-actions {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        
        .actions-card {
            background: var(--white);
            border-radius: 1rem;
            padding: 2.5rem;
            box-shadow: var(--shadow-xl);
        }
        
        .actions-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark-gray);
            margin-bottom: 2rem;
            text-align: center;
        }
        
        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
        }
        
        .action-btn {
            display: block;
            padding: 2rem;
            border-radius: 1rem;
            text-align: center;
            text-decoration: none;
            color: var(--white) !important;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-md);
            position: relative;
            overflow: hidden;
        }
        
        .action-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }
        
        .action-btn:hover::before {
            left: 100%;
        }
        
        .action-btn:hover {
            transform: translateY(-4px) scale(1.02);
            box-shadow: var(--shadow-xl);
        }
        
        .action-btn i {
            display: block;
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: inherit;
        }
        
        .action-btn p {
            margin: 0;
            font-weight: 600;
            font-size: 1.1rem;
            color: inherit;
        }
        
        .btn-orange {
            background: linear-gradient(135deg, var(--primary-orange), var(--primary-orange-light));
        }
        
        .btn-blue {
            background: linear-gradient(135deg, #3b82f6, #60a5fa);
        }
        
        .btn-green {
            background: linear-gradient(135deg, #10b981, #34d399);
        }
        
        /* Footer */
        footer {
            background: var(--dark-brown);
            text-align: center;
            padding: 2rem 0;
            margin-top: auto;
            box-shadow: 0 -4px 6px -1px rgb(0 0 0 / 0.1);
        }
        
        .footer-text {
            color: var(--white);
            font-size: 1.1rem;
            font-weight: 500;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .header-container {
                flex-direction: column;
                gap: 1rem;
            }
            
            .nav-links {
                flex-wrap: wrap;
                justify-content: center;
                gap: 1rem;
            }
            
            .dashboard-title {
                font-size: 2rem;
            }
            
            .welcome-text {
                font-size: 1rem;
            }
            
            .stats-container {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .stat-card {
                padding: 1.5rem;
            }
            
            .actions-card {
                padding: 1.5rem;
            }
            
            .actions-grid {
                grid-template-columns: 1fr;
            }
        }
        
        /* Animation Classes */
        .fade-in {
            animation: fadeIn 0.6s ease-out;
        }
        
        .slide-up {
            animation: slideUp 0.8s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-container">
            <a href="#" class="logo-section">
                <img src="../asset/logo_Charm.png" alt="ChARM Logo">
                <span class="logo-text">ChARM Admin Dashboard</span>
            </a>
            <nav class="nav-menu">
                <ul class="nav-links">
                    <li><a href="./adminpage.php">Dashboard</a></li>
                    <li><a href="./costumes.php">Costumes</a></li>
                    <li><a href="./addcos.php">Add Costume</a></li>
                    <li><a href="./categories.php">Categories</a></li>
                    <li><a href="./users.php">Users</a></li>
                    <li><a href="./rentals.php">Rentals</a></li>
                    <li><a href="../logout.php" class="logout-btn">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="content-wrapper">
        <div class="dashboard-header fade-in">
            <h1 class="dashboard-title">Admin Dashboard</h1>
            <p class="welcome-text">Welcome back, <?php echo htmlspecialchars($username); ?>!</p>
        </div>
        
        <div class="stats-container slide-up">
            <div class="stat-card">
                <i class="bx bx-closet stat-icon costumes"></i>
                <h3 class="stat-number"><?php echo $stats['total_costumes']; ?></h3>
                <p class="stat-label">Total Costumes</p>
            </div>
            <div class="stat-card">
                <i class="bx bx-user stat-icon users"></i>
                <h3 class="stat-number"><?php echo $stats['total_users']; ?></h3>
                <p class="stat-label">Total Users</p>
            </div>
            <div class="stat-card">
                <i class="bx bx-category stat-icon categories"></i>
                <h3 class="stat-number"><?php echo $stats['total_categories']; ?></h3>
                <p class="stat-label">Categories</p>
            </div>
            <div class="stat-card">
                <i class="bx bx-calendar stat-icon rentals"></i>
                <h3 class="stat-number"><?php echo $stats['active_rentals']; ?></h3>
                <p class="stat-label">Active Rentals</p>
            </div>
        </div>

        <div class="quick-actions slide-up">
            <div class="actions-card">
                <h2 class="actions-title">Quick Actions</h2>
                <div class="actions-grid">
                    <a href="./addcos.php" class="action-btn btn-orange">
                        <i class="bx bx-plus"></i>
                        <p>Add New Costume</p>
                    </a>
                    <a href="./costumes.php" class="action-btn btn-blue">
                        <i class="bx bx-list-ul"></i>
                        <p>Manage Costumes</p>
                    </a>
                    <a href="./rentals.php" class="action-btn btn-green">
                        <i class="bx bx-calendar-check"></i>
                        <p>View Rentals</p>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <p class="footer-text">© 2025 ChARM Costume Rental - Admin Panel</p>
    </footer>

    <script>
        // Add smooth scrolling and enhanced interactions
        document.addEventListener('DOMContentLoaded', function() {
            // Add stagger animation to stat cards
            const statCards = document.querySelectorAll('.stat-card');
            statCards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
                card.classList.add('slide-up');
            });
            
            // Add hover sound effect (optional)
            const actionBtns = document.querySelectorAll('.action-btn');
            actionBtns.forEach(btn => {
                btn.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-4px) scale(1.02)';
                });
                btn.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                });
            });
        });
    </script>
</body>
</html>
