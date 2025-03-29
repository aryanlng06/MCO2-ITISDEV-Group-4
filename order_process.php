<?php
session_start();
include 'config.php';


$customer_name = $_POST['customer_name'];
$product_id = $_POST['product_id'];
$quantity = $_POST['quantity'];
$total_amount = $_POST['total_amount'];
$payment_method = $_POST['payment_method']; 
$order_status = 'Pending'; // Default status

$sql = "INSERT INTO orders (username, product_id, quantity, total_amount, payment_method, status) 
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("siidss", $customer_name, $product_id, $quantity, $total_amount, $payment_method, $order_status);

if ($stmt->execute()) {
    
    header("Location: OrderPage.php?success=1");
    exit();
} else {
    echo "Error placing order: " . $conn->error;
}
?>
