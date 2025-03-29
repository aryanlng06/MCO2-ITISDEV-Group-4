<?php
// Get current page from URL
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>La Frontera - Inventory System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header">
        <div class="logo">La Frontera</div>
        <div class="search-nav">
            <a href="#" class="nav-item">Dashboard</a>
            <a href="#" class="nav-item">Products</a>
            <a href="#" class="nav-item">Orders</a>
            <a href="#" class="nav-item">Reports</a>
            <input type="text" class="search-bar" placeholder="Search...">
        </div>
        <div class="user-icon">
            <i class="fas fa-user"></i>
        </div>
    </div>
    
    <div class="container">
        <div class="sidebar">
            <a href="MenuPage.php" class="sidebar-item <?php echo ($current_page == 'MenuPage.php') ? 'active' : ''; ?>">
                <i class="fas fa-home"></i> Home
            </a>
            <a href="InventoryPage.php" class="sidebar-item <?php echo ($current_page == 'InventoryPage.php') ? 'active' : ''; ?>">
                <i class="fas fa-boxes"></i> Inventory
            </a>
            <a href="OrderPage.php" class="sidebar-item <?php echo ($current_page == 'OrderPage.php') ? 'active' : ''; ?>">
                <i class="fas fa-shopping-cart"></i> Order
            </a>
            <a href="transactions.php" class="sidebar-item <?php echo ($current_page == 'transactions.php') ? 'active' : ''; ?>">
                <i class="fas fa-exchange-alt"></i> Transactions
                <i class="fas fa-chevron-down" style="margin-left: auto;"></i>
            </a>
            <a href="delivery.php" class="sidebar-item <?php echo ($current_page == 'delivery.php') ? 'active' : ''; ?>">
                <i class="fas fa-truck"></i> Delivery
            </a>
        </div>

        <div class="content">
    <div class="wallpaper"></div>
</div>
        
</body>
</html>