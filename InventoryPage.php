<?php
$db_server = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "laFrontera";

$conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch products from the database
$sql = "SELECT * FROM products";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>La Frontera - Inventory</title>
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
            <a href="MenuPage.php" class="sidebar-item"><i class="fas fa-home"></i> Home</a>
            <a href="inventory.php" class="sidebar-item active"><i class="fas fa-boxes"></i> Inventory</a>
            <a href="OrderPage.php" class="sidebar-item"><i class="fas fa-shopping-cart"></i> Orders</a>
            <a href="#" class="sidebar-item"><i class="fas fa-exchange-alt"></i> Transactions</a>
            <a href="#" class="sidebar-item"><i class="fas fa-truck"></i> Delivery</a>
        </div>

        <div class="content">
            <div class="content-header">
                <h2>Inventory</h2>
                <button class="btn btn-success" id="openModal">
                    <i class="fas fa-plus"></i> Add Item
                </button>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Edit</th>
                        <th>Product</th>
                        <th>Stock</th>
                        <th>Price</th>
                        <th>Supplier</th>
                    </tr>
                </thead>
                <tbody id="productTable">
             <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                 <tr>
                    <td>
                     <button class="edit-btn" 
                        product_id="<?= $row['product_id'] ?>"
                        product_name="<?= $row['product_name'] ?>" 
                        price="<?= $row['price'] ?>" 
                        quantity="<?= $row['quantity'] ?>" 
                        supplier="<?= $row['supplier'] ?>"> Edit</button>
                
                      <button class="delete-btn" product_id="<?= $row['product_id'] ?>">Delete</button>
                    </td>
                     <td><?= htmlspecialchars($row['product_name']) ?></td>
                     <td><?= htmlspecialchars($row['quantity']) ?></td>
                     <td>₱<?= number_format($row['price'], 2) ?></td>
                     <td><?= htmlspecialchars($row['supplier']) ?></td>
                 </tr>
             <?php endwhile; ?>
            </tbody>
            </table>
        </div>
    </div>

    <div id="itemModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add New Item</h3>
                <span class="close" id="closeModal">&times;</span>
            </div>
            <form id="addItemForm">
                <div class="form-group">
                    <label>Product Name</label>
                    <input type="text" id="productName" required>
                </div>
                <div class="form-group">
                    <label>Price</label>
                    <input type="number" id="productPrice" step="0.01" required>
                </div>
                <div class="form-group">
                    <label>Quantity</label>
                    <input type="number" id="productQuantity" required>
                </div>
                <div class="form-group">
                    <label>Supplier</label>
                    <input type="text" id="productSupplier" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Add Item</button>
                </div>
            </form>
        </div>
    </div>

    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Edit Product</h3>
                <span class="close" id="closeEditModal">&times;</span>
            </div>
            <form id="editItemForm">
                <input type="hidden" id="editId" name="product_id">
                <div class="form-group">
                    <label>Product Name</label>
                    <input type="text" id="editName" name="product_name" required>
                </div>
                <div class="form-group">
                    <label>Price</label>
                    <input type="number" id="editPrice" name="price" step="0.01" required>
                </div>
                <div class="form-group">
                    <label>Quantity</label>
                    <input type="number" id="editQuantity" name="quantity" required>
                </div>
                <div class="form-group">
                    <label>Supplier</label>
                    <input type="text" id="editSupplier" name="supplier" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update Item</button>
                </div>
            </form>
        </div>
    </div>

<script>
// Open modal
document.getElementById("openModal").addEventListener("click", function() {
    document.getElementById("itemModal").style.display = "flex";
});

// Close modal
document.getElementById("closeModal").addEventListener("click", function() {
    document.getElementById("itemModal").style.display = "none";
});

// AJAX form submission
document.getElementById("addItemForm").addEventListener("submit", function(e) {
    e.preventDefault();

        let formData = new FormData();
        formData.append("product_name", document.getElementById("productName").value);
        formData.append("price", document.getElementById("productPrice").value);
        formData.append("quantity", document.getElementById("productQuantity").value);
        formData.append("supplier", document.getElementById("productSupplier").value);

            fetch("add_product.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                location.reload();
            })
            .catch(error => console.error("Error:", error));
    });

// Open Edit Modal with existing product data
document.querySelectorAll(".edit-btn").forEach(button => {
    button.addEventListener("click", function() {
        document.getElementById("editModal").style.display = "flex";
        document.getElementById("editId").value = this.getAttribute("product_id");
        document.getElementById("editName").value = this.getAttribute("product_name");
        document.getElementById("editPrice").value = this.getAttribute("price");
        document.getElementById("editQuantity").value = this.getAttribute("quantity");
        document.getElementById("editSupplier").value = this.getAttribute("supplier");
    });
});

// Submit Edit Form via AJAX
document.getElementById("editItemForm").addEventListener("submit", function(e) {
    e.preventDefault();

    let formData = new FormData(this);

    fetch("edit_product.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        alert(data);
        location.reload();
    })
    .catch(error => console.error("Error:", error));
});

// Close Edit Modal
document.getElementById("closeEditModal").addEventListener("click", function() {
    document.getElementById("editModal").style.display = "none";
});

    // Delete Product via AJAX
document.querySelectorAll(".delete-btn").forEach(button => {
    button.addEventListener("click", function() {
        let product_id = this.getAttribute("product_id"); // Retrieve product_id

        if (confirm("Are you sure you want to delete this product?")) {
            let formData = new FormData();
            formData.append("product_id", product_id); // Correctly append the product_id

            fetch("delete_product.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                location.reload();
            })
            .catch(error => console.error("Error:", error));
        }
    });
});

// Close Edit Modal
document.getElementById("closeEditModal").addEventListener("click", function() {
    document.getElementById("editModal").style.display = "none";
});
</script>
</body>
</html>