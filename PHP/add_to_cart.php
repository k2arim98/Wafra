<?php
session_start();
include 'config.php'; 

if (!isset($_SESSION['user_email'])) {
    die("Utilisateur non connecté.");

}

if (!isset($_GET['id'])) {
    die("ID produit manquant.");
}

$user_email = $_SESSION['user_email'];
$product_id = intval($_GET['id']);

// Check if product exists
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Produit introuvable.");
}

$product = $result->fetch_assoc();

$check = $conn->prepare("SELECT * FROM cart WHERE user_email = ? AND product_id = ?");
$check->bind_param("si", $user_email, $product_id);
$check->execute();
$check_result = $check->get_result();

if ($check_result->num_rows > 0) {
    // Update quantity
    $update = $conn->prepare("UPDATE cart SET quantity = quantity + 1 WHERE user_email = ? AND product_id = ?");
    $update->bind_param("si", $user_email, $product_id);
    $update->execute();
} else {
    // Insert new item
    $insert = $conn->prepare("INSERT INTO cart (user_email, product_id, quantity) VALUES (?, ?, 1)");
    $insert->bind_param("si", $user_email, $product_id);
    $insert->execute();
}

$conn->close();

// Redirect to cart page
header("Location: ../HTML/cart.php");
exit;
