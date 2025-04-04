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

$total = 0;
foreach ($items as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Passer à la Caisse - Wafra</title>
    <link rel="stylesheet" href="../CSS/cart.css">
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <img src="../images/logo.png" alt="Logo" class="logo-img">
            <span>Wafra</span>
        </div>
    </nav>

    <main>
        <section class="checkout-section">
            <div class="container">
                <h1>Résumé de votre commande</h1>

                <?php if (!empty($items)): ?>
                    <table>
                        <tr>
                            <th>Produit</th>
                            <th>Quantité</th>
                            <th>Prix Unitaire</th>
                            <th>Sous-total</th>
                        </tr>
                        <?php foreach ($items as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['name']); ?></td>
                                <td><?php echo $item['quantity']; ?></td>
                                <td>$<?php echo number_format($item['price'], 2); ?></td>
                                <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>

                    <h2>Total: $<?php echo number_format($total, 2); ?></h2>

                    <form action="confirm_order.php" method="post">
                        <input type="hidden" name="total" value="<?php echo $total; ?>">
                        <button type="submit" class="cta-button">Confirmer la Commande</button>
                    </form>
                <?php else: ?>
                    <p>Votre panier est vide.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>
</body>
</html>
