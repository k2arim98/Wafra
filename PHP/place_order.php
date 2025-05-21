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
    $check_product = $conn->prepare("SELECT id FROM products WHERE id = ?");
    $check_product->bind_param("i", $item['product_id']);
    $check_product->execute();
    $check_product->store_result();

    if ($check_product->num_rows > 0) {
        $insert_item = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)");
        $insert_item->bind_param("iii", $order_id, $item['product_id'], $item['quantity']);
        $insert_item->execute();
        $insert_item->close();
    } else {
        error_log("Product ID " . $item['product_id'] . " does not exist and was skipped.");
    }

    $check_product->close();
}


$clear_cart = $conn->prepare("DELETE FROM cart WHERE user_email = ?");
$clear_cart->bind_param("s", $email);
$clear_cart->execute();

$conn->close();

header("Location: /Wafra/HTML/order_success.php");
exit;
?>
