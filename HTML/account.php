<?php
session_start();
require '../PHP/config.php';

if (!isset($_SESSION['user_email'])) {
    header("Location: ../HTML/loginRegisterPage.html"); 
    exit();
}

$user_email = $_SESSION['user_email'];

// Fetch user info
$stmt = $conn->prepare("SELECT full_name, email FROM users WHERE email = ?");
$stmt->bind_param("s", $user_email);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Fetch cart items
$cart = $conn->prepare("SELECT p.name, p.price, c.quantity 
                        FROM cart c 
                        JOIN products p ON c.product_id = p.id 
                        WHERE c.user_email = ?");
$cart->bind_param("s", $user_email);
$cart->execute();
$cart_items = $cart->get_result()->fetch_all(MYSQLI_ASSOC);

$purchases = $conn->prepare("SELECT p.name, p.price, c.quantity, c.created_at 
                             FROM cart c 
                             JOIN products p ON c.product_id = p.id 
                             WHERE c.user_email = ?
                             ORDER BY c.created_at DESC");
$purchases->bind_param("s", $user_email);
$purchases->execute();
$purchase_history = $purchases->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Account</title>
    <link rel="stylesheet" href="../CSS/account.css">
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <img src="../images/logo.png" alt="Logo" class="logo-img">
            <span>Wafra</span>
        </div>
        <ul class="nav-links">
            <li><a href="../HTML/index.html">Accueil</a></li>
            <li><a href="../HTML/Products.php">Produits</a></li>
            <li><a href="#social">Contact</a></li>
            <li><a href="../PHP/logout.php">Logout</a></li>

        </ul>
    </nav>
    <h1>Welcome, <?= htmlspecialchars($user['full_name']) ?></h1>
    <p>Email: <?= htmlspecialchars($user['email']) ?></p>

    <h2>🛒 Current Cart</h2>
    <table>
        <tr><th>Product</th><th>Price</th><th>Quantity</th></tr>
        <?php foreach ($cart_items as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['name']) ?></td>
                <td>$<?= number_format($item['price'], 2) ?></td>
                <td><?= $item['quantity'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <h2>📦 Purchase History</h2>
    <table>
        <tr><th>Product</th><th>Price</th><th>Quantity</th><th>Date</th></tr>
        <?php foreach ($purchase_history as $purchase): ?>
            <tr>
                <td><?= htmlspecialchars($purchase['name']) ?></td>
                <td>$<?= number_format($purchase['price'], 2) ?></td>
                <td><?= $purchase['quantity'] ?></td>
                <td><?= $purchase['created_at'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

</body>
</html>
