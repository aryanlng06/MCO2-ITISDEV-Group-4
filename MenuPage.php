<?php
session_start();
include 'config.php';
$current_page = basename($_SERVER['PHP_SELF']);

if (!isset($_SESSION['email'])) {
    die("User not logged in.");
}
$email = $_SESSION['email']; 
$query = "SELECT id, usertype FROM users WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {    
    $user_id = $row['id'];
    $usertype = $row['usertype'];
} else {
    die("User not found in database.");
}

$conn->close();
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
        <a href="#" class="nav-item"><i ></i> Sales & Transactions</a>
        <a href="#" class="nav-item">Reports</a>
        <a href="#" class="nav-item">Refunds</a>
            
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
        <a href="#" class="sidebar-item <?php echo ($current_page == 'InventoryPage.php') ? 'active' : ''; ?>">
            <i class="fas fa-boxes"></i> Inventory
        </a>
        <a href="ProductPage.php" class="sidebar-item">
            <i class="fas fa-box"></i> Products
        </a>
        <a href="OrderPage.php" class="sidebar-item">
            <i class="fas fa-shopping-cart"></i> Orders
        </a>
        <a href="delivery.php" class="sidebar-item <?php echo ($current_page == 'delivery.php') ? 'active' : ''; ?>">
            <i class="fas fa-truck"></i> Delivery
        </a>
    </div>
    <p>email: <strong><?php echo htmlspecialchars($email); ?></strong></p>
    <p>User Type: <strong><?php echo htmlspecialchars($usertype); ?></strong></p>
    <div class="content">
        <div class="wallpaper"></div>
    </div>
</div>
        
</body>
</html>