<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    die("Utilisateur non connecté.");
}

$user_id = $_SESSION['user_id'];
$full_name = $_POST['full_name'];
$email = $_POST['email'];
$wilaya = $_POST['wilaya'];
$address = $_POST['address'];

// Insert into orders
$order_stmt = $conn->prepare("INSERT INTO orders (user_id, full_name, email, wilaya, address) VALUES (?, ?, ?, ?, ?)");
$order_stmt->bind_param("issss", $user_id, $full_name, $email, $wilaya, $address);
$order_stmt->execute();

$order_id = $order_stmt->insert_id;

// Fetch cart items
$cart_stmt = $conn->prepare("SELECT product_id, quantity FROM cart WHERE user_email = ?");
$cart_stmt->bind_param("s", $email);
$cart_stmt->execute();
$cart_result = $cart_stmt->get_result();

while ($item = $cart_result->fetch_assoc()) {
    $insert_item = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)");
    $insert_item->bind_param("iii", $order_id, $item['product_id'], $item['quantity']);
    $insert_item->execute();
}

// Clear cart
$clear_cart = $conn->prepare("DELETE FROM cart WHERE user_email = ?");
$clear_cart->bind_param("s", $email);
$clear_cart->execute();

$conn->close();

// Redirect or confirm
header("Location: /Wafra/HTML/order_success.php");
exit;
?>
