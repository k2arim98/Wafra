<?php
session_start();
include 'config.php'; 

if (!isset($_SESSION['user_email'])) {
    die("Utilisateur non connecté.");
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Méthode non autorisée.");
}

if (!isset($_POST['product_id']) || !isset($_POST['quantity'])) {
    die("Données manquantes.");
}

$user_email = $_SESSION['user_email'];
$product_id = intval($_POST['product_id']);
$quantity = intval($_POST['quantity']);

if ($product_id <= 0 || $quantity <= 0) {
    die("Entrées invalides.");
}

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

// Check if product is already in the cart
$check = $conn->prepare("SELECT * FROM cart WHERE user_email = ? AND product_id = ?");
$check->bind_param("si", $user_email, $product_id);
$check->execute();
$check_result = $check->get_result();

if ($check_result->num_rows > 0) {
    // Update quantity
    $update = $conn->prepare("UPDATE cart SET quantity = quantity + ? WHERE user_email = ? AND product_id = ?");
    $update->bind_param("isi", $quantity, $user_email, $product_id);
    $update->execute();
} else {
    // Insert new item
    $insert = $conn->prepare("INSERT INTO cart (user_email, product_id, quantity) VALUES (?, ?, ?)");
    $insert->bind_param("sii", $user_email, $product_id, $quantity);
    $insert->execute();
}

$conn->close();

// Redirect to cart page
header("Location: /Wafra/HTML/cart.php");
exit;
