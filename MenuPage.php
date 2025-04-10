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

// Fetch Total Revenue, Orders, Pending Orders
$stats_query = "SELECT 
    SUM(CASE WHEN status = 'Complete' THEN total_amount ELSE 0 END) AS total_revenue,
    COUNT(id) AS total_orders,
    SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) AS pending_orders
FROM orders";
$stats_result = $conn->query($stats_query);
$stats = $stats_result->fetch_assoc();

// Fetch Top-Selling Products
$top_products_query = "SELECT p.product_name, SUM(o.quantity) AS total_sold
    FROM orders o
    JOIN products p ON o.product_id = p.product_id
    GROUP BY p.product_name
    ORDER BY total_sold DESC
    LIMIT 5";
$top_products_result = $conn->query($top_products_query);

// Fetch Low Stock Products
$low_stock_query = "SELECT product_name, quantity FROM products ORDER BY quantity ASC LIMIT 5";
$low_stock_result = $conn->query($low_stock_query);

// Fetch Latest Orders
$latest_orders_query = "SELECT o.id, p.product_name, o.quantity, o.total_amount, o.status FROM orders o 
    JOIN products p ON o.product_id = p.product_id ORDER BY o.id DESC LIMIT 5";
$latest_orders_result = $conn->query($latest_orders_query);
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
    <style>
        .user-dropdown {
            position: relative;
            display: inline-block;
        }

        .user-icon {
            cursor: pointer;
            padding: 10px;
            font-size: 20px;
            position: relative;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            background-color: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            border-radius: 8px;
            z-index: 1000;
            min-width: 150px;
            padding: 10px 0;
        }

        .dropdown-menu a {
            display: block;
            padding: 10px 20px;
            color: #333;
            text-decoration: none;
            transition: background 0.2s ease;
        }

        .dropdown-menu a:hover {
            background-color: #f0f0f0;
        }

        .content {
            flex: 1;
            padding: 20px;
        }

        .dashboard {
           display: flex;
           justify-content: space-between;
           gap: 20px;
        }

       .card {
          flex: 1;
          background: white;
          padding: 20px;
          border-radius: 10px;
          box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        text-align: center;
        }

        /* Tables */
        .data-section {
          margin-top: 30px;
          background: white;
          padding: 20px;
          border-radius: 10px;
          box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        .data-section h2 {
          margin-bottom: 15px;
        }

        table {
          width: 100%;
          border-collapse: collapse;
        }

        thead {
          background: #2c3e50;
        }

        thead th {
          padding: 10px;
        }

        tbody tr:nth-child(even) {
          background: #f9f9f9;
        }

        td {
          padding: 10px;
          border-bottom: 1px solid #ddd;
          text-align: center;
        }        
    </style>
</head>
<body>
<div class="header">
    <div class="logo">La Frontera</div>
    <div class="search-nav">
        <a href="MenuPage.php" class="nav-item">Dashboard</a>
        <a href="#" class="nav-item">Sales & Transactions</a>
        <a href="reports.php" class="nav-item">Reports</a>
    </div>
    <div class="user-dropdown">
        <div class="user-icon" id="userDropdownBtn">
            <i class="fas fa-user"></i>
        </div>
        <div class="dropdown-menu" id="userDropdownMenu">
            <a href="profile.php">My Profile</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
</div>
    
<div class="container">
    <div class="sidebar">
        <div>
        <p>Email: <strong><?php echo htmlspecialchars($email); ?></strong></p><br>
        <p>User Type: <strong><?php echo htmlspecialchars($usertype); ?></strong></p><br>
        </div>
        <a href="MenuPage.php" class="sidebar-item active"><i class="fas fa-home"></i> Home</a>
        <a href="InventoryPage.php" class="sidebar-item"><i class="fas fa-boxes"></i> Inventory</a>
        <a href="ProductPage.php" class="sidebar-item"><i class="fas fa-box"></i> Products</a>
        <a href="OrderPage.php" class="sidebar-item"><i class="fas fa-shopping-cart"></i> Orders</a>
    </div>
    
    <div class="content">
        <div class="dashboard">
            <div class="card"><h2>Total Revenue: ₱<?php echo number_format($stats['total_revenue'], 2); ?></h2></div>
            <div class="card"><h2>Total Orders: <?php echo $stats['total_orders']; ?></h2></div>
            <div class="card"><h2>Pending Orders: <?php echo $stats['pending_orders']; ?></h2></div>
        </div>
        
        <div class="data-section">
            <h2>Top-Selling Products</h2>
            <table>
                <thead><tr><th>Product</th><th>Sold Quantity</th></tr></thead>
                <tbody>
                    <?php while ($row = $top_products_result->fetch_assoc()): ?>
                        <tr><td><?php echo htmlspecialchars($row['product_name']); ?></td><td><?php echo $row['total_sold']; ?></td></tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        
        <div class="data-section">
            <h2>Low Stock Products</h2>
            <table>
                <thead><tr><th>Product</th><th>Stock</th></tr></thead>
                <tbody>
                    <?php while ($row = $low_stock_result->fetch_assoc()): ?>
                        <tr><td><?php echo htmlspecialchars($row['product_name']); ?></td><td><?php echo $row['quantity']; ?></td></tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        
        <div class="data-section">
            <h2>Latest Orders</h2>
            <table>
                <thead><tr><th>Order ID</th><th>Product</th><th>Quantity</th><th>Amount</th><th>Status</th></tr></thead>
                <tbody>
                    <?php while ($row = $latest_orders_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                            <td><?php echo $row['quantity']; ?></td>
                            <td>₱<?php echo number_format($row['total_amount'], 2); ?></td>
                            <td><?php echo htmlspecialchars($row['status']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    document.getElementById("userDropdownBtn").addEventListener("click", function() {
        const dropdown = document.getElementById("userDropdownMenu");
        dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
    });

    window.addEventListener("click", function(event) {
        if (!event.target.closest(".user-dropdown")) {
            document.getElementById("userDropdownMenu").style.display = "none";
        }
    });
</script>
</body>
</html>
