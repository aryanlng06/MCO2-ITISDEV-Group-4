<?php
session_start();
?>

<?php
include 'config.php';
$sql = "SELECT o.id, p.product_name, o.total_amount, o.status, o.username, o.payment_method
        FROM orders o
        JOIN products p ON o.product_id = p.product_id
        ORDER BY o.id DESC";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>La Frontera - Orders</title>
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
    </style>
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
        <a href="MenuPage.php" class="sidebar-item"><i class="fas fa-home"></i> Home</a>
        <a href="InventoryPage.php" class="sidebar-item"><i class="fas fa-boxes"></i> Inventory</a>
        <a href="ProductPage.php" class="sidebar-item"><i class="fas fa-box"></i> Products</a>
        <a href="OrderPage.php" class="sidebar-item active"><i class="fas fa-shopping-cart"></i> Orders</a>
        <a href="#" class="sidebar-item"><i class="fas fa-truck"></i> Delivery</a>
    </div>

    <div class="content">
        <div class="content-header">
            <h2>Orders</h2>
            <a href="order_products.php" class="btn btn-success" style="text-decoration: none;">
                <i class="fas fa-plus" style="margin-right: 5px;"></i> New Order
            </a>
        </div>

        <?php if (isset($_SESSION['role'])): ?>
            <form method="POST" action="order_process.php">
                <label for="product_id">Product:</label>
                <select id="product_id" name="product_id" onchange="updateTotal()" required>
                    <option value="">Select product</option>
                    <option value="1">Battery</option>
                    <option value="2">Charger</option>
                </select>

                <label for="quantity">Quantity:</label>
                <input type="number" id="quantity" name="quantity" min="1" value="1" onchange="updateTotal()" required>

                <label for="total_amount">Total Amount:</label>
                <input type="number" step="0.01" id="total_amount" name="total_amount" required readonly>

                <button type="submit">Add Order</button>
            </form>
        <?php endif; ?>

        <table id="orders-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Product</th>
                    <th>Payment Method</th>
                    <th>Status</th>
                    <th>Cancel</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td>#<?= htmlspecialchars($row['id']) ?></td>
                            <td><?= htmlspecialchars($row['product_name']) ?></td>
                            <td><?= htmlspecialchars($row['payment_method']) ?></td>
                            <td><?= htmlspecialchars($row['status']) ?></td>
                            <td><button class="btn btn-danger cancel-btn">Cancel</button></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="5">No orders yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    const productPrices = {
        1: 100.00, // Battery
        2: 150.00  // Charger
    };

    function updateTotal() {
        const productId = document.getElementById("product_id").value;
        const quantity = parseInt(document.getElementById("quantity").value) || 0;
        const price = productPrices[productId] || 0;
        document.getElementById("total_amount").value = (price * quantity).toFixed(2);
    }

    document.addEventListener("DOMContentLoaded", function () {
        const userRole = "<?php echo $_SESSION['role'] ?? 'staff'; ?>";
        const cancelButtons = document.querySelectorAll(".cancel-btn");
        let canceledOrders = JSON.parse(localStorage.getItem("canceledOrders")) || [];

        document.querySelectorAll("#orders-table tbody tr").forEach(row => {
            const orderId = row.querySelector("td:first-child").innerText.replace("#", "").trim();
            if (canceledOrders.includes(orderId)) {
                row.style.display = "none";
            }
        });

        cancelButtons.forEach(button => {
            button.addEventListener("click", function () {
                const row = this.closest("tr");
                const orderId = row.querySelector("td:first-child").innerText.replace("#", "").trim();

                if (userRole !== "staff") {
                    alert("You do not have permission to cancel orders.");
                    return;
                }

                if (confirm("Are you sure you want to cancel this order?")) {
                    canceledOrders.push(orderId);
                    localStorage.setItem("canceledOrders", JSON.stringify(canceledOrders));
                    row.style.display = "none";
                    alert("Order has been canceled.");
                }
            });
        });

        // Toggle profile dropdown
        document.getElementById("userDropdownBtn").addEventListener("click", function() {
            const dropdown = document.getElementById("userDropdownMenu");
            dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
        });

        // Close dropdown if clicked outside
        window.addEventListener("click", function(event) {
            if (!event.target.closest(".user-dropdown")) {
                document.getElementById("userDropdownMenu").style.display = "none";
            }
        });
    });
</script>

</body>
</html>