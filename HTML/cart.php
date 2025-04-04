<?php
session_start();
include '../PHP/config.php';

if (!isset($_SESSION['user_email'])) {
    header('Location: login.php');
    exit;
}

$email = $_SESSION['user_email'];

$stmt = $conn->prepare("
    SELECT c.*, p.name, p.image, p.price 
    FROM cart c 
    JOIN products p ON c.product_id = p.id 
    WHERE c.user_email = ?
");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$items = $result->fetch_all(MYSQLI_ASSOC);

// Remove item from cart
if (isset($_GET['remove'])) {
    $remove_id = intval($_GET['remove']);
    unset($_SESSION['cart'][$remove_id]);
}

// Clear cart
if (isset($_GET['clear'])) {
    $_SESSION['cart'] = [];
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votre Panier - Wafra</title>
    <link rel="stylesheet" href="../CSS/cart.css">
</head>
<body>

    <nav class="navbar">
        <div class="logo">
            <img src="../images/logo.png" alt="Logo" class="logo-img">
            <span>Wafra</span>
        </div>
        <ul class="nav-links">
            <li><a href="index.html">Accueil</a></li>
            <li><a href="Products.php">Produits</a></li>
            <li><a href="">À propos</a></li>
            <li><a href="#social">Contact</a></li>
        </ul>
    </nav>

    <main>
        <section class="cart-section">
            <div class="container">
                <h1>Votre Panier</h1>

                <?php if (!empty($items)): ?>
                    <table>
                        <tr>
                            <th>Image</th>
                            <th>Nom</th>
                            <th>Prix</th>
                            <th>Quantité</th>
                            <th>Action</th>
                        </tr>
                        <?php foreach ($items as $item): ?>
                            <tr>
                                <td><img src="../images/<?php echo $item['image']; ?>" width="50"></td>
                                <td><?php echo htmlspecialchars($item['name']); ?></td>
                                <td>$<?php echo number_format($item['price'], 2); ?></td>
                                <td><?php echo $item['quantity']; ?></td>
                                <td>
                                    <a href="cart.php?remove=<?php echo $item['id']; ?>" class="remove-btn">❌</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                    <br>
                    <div class="button-container">
                        <a href="cart.php?clear=true" class="cta-button">Vider le Panier</a>
                        <a href="checkout.php" class="cta-button">Passer à la Caisse</a>
                    </div>
                <?php else: ?>
                    <p>Votre panier est vide.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>

</body>
</html>
