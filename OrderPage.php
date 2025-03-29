<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>La Frontera - Orders</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header">
        <div class="logo">La Frontera</div>
        <div class="search-nav">
            <a href="#" class="nav-item">Dashboard</a>
            <a href="#" class="nav-item">Products</a>
            <a href="#" class="nav-item active">Orders</a>
            <a href="#" class="nav-item">Reports</a>
            <input type="text" class="search-bar" placeholder="Search...">
        </div>
        <div class="user-icon">
            <i class="fas fa-user"></i>
        </div>
    </div>
    
    <div class="container">
        <div class="sidebar">
            <a href="MenuPage.php" class="sidebar-item">
                <i class="fas fa-home"></i> Home
            </a>
            <a href="InventoryPage.php" class="sidebar-item">
                <i class="fas fa-boxes"></i> Inventory
            </a>
            <a href="OrderPage.php" class="sidebar-item active">
                <i class="fas fa-shopping-cart"></i> Orders
            </a>
            <a href="#" class="sidebar-item">
                <i class="fas fa-exchange-alt"></i> Transactions
            </a>
            <a href="#" class="sidebar-item">
                <i class="fas fa-truck"></i> Delivery
            </a>
        </div>
        
        <div class="content">
            <div class="content-header">
                <h2>Orders</h2>
                <button class="btn btn-success">
                    <i class="fas fa-plus" style="margin-right: 5px;"></i> New Order
                </button>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Product</th>
                        <th>Payment Method</th>
                        <th>Status</th>
                        <th>Modify</th>
                        <th>Cancel</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>#7676</td>
                        <td>Battery</td>
                        <td>Cash</td>
                        <td>Pending</td>
                        <td><button class="btn btn-primary">Edit</button></td>
                        <td><button class="btn btn-danger">Cancel</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>