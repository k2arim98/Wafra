<?php
session_start();
require '../PHP/config.php';

if (!isset($_SESSION['user_email'])) {
    header("Location: /Wafra/HTML/loginRegisterPage.html"); 
    exit();
}

$user_email = $_SESSION['user_email'];

// Fetch user info
$stmt = $conn->prepare("SELECT id, full_name, email FROM users WHERE email = ?");
$stmt->bind_param("s", $user_email);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$user_id = $user['id']; // Needed for order history


// Fetch purchase history from orders + order_items
$purchases = $conn->prepare("
    SELECT p.name, p.price, oi.quantity, o.order_date AS created_at
    FROM orders o
    JOIN order_items oi ON o.id = oi.order_id
    JOIN products p ON oi.product_id = p.id
    WHERE o.email = ?
    ORDER BY o.order_date DESC
");
$purchases->bind_param("s", $user_email);
$purchases->execute();
$purchase_history = $purchases->get_result()->fetch_all(MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Account</title>
    <link rel="stylesheet" href="/Wafra/CSS/account.css">
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <img src="/Wafra/images/logo.png" alt="Logo" class="logo-img">
            <span>Wafra</span>
        </div>
        <ul class="nav-links">
            <li><a href="/Wafra/HTML/index.php">Accueil</a></li>
            <li><a href="/Wafra/HTML/Products.php">Produits</a></li>
            <li><a href="#social">Contact</a></li>
            <li><a href="/Wafra/PHP/logout.php">Logout</a></li>

        </ul>
    </nav>
    <h1>Welcome, <?= htmlspecialchars($user['full_name']) ?></h1>
    <p>Email: <?= htmlspecialchars($user['email']) ?></p>


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