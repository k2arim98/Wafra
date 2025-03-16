<?php
session_start();

// Check if the cart exists, otherwise create an empty one
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Database connection
$conn = new mysqli("localhost", "root", "root", "wafra");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Add product to cart
if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']);

    // Fetch product details
    $sql = "SELECT * FROM products WHERE id = $product_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();

        // Check if product already in cart
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity'] += 1; // Increment quantity
        } else {
            // Add new product
            $_SESSION['cart'][$product_id] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'image' => $product['image'],
                'quantity' => 1
            ];
        }
    }
}

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

                <?php if (!empty($_SESSION['cart'])): ?>
                    <table>
                        <tr>
                            <th>Image</th>
                            <th>Nom</th>
                            <th>Prix</th>
                            <th>Quantité</th>
                            <th>Action</th>
                        </tr>
                        <?php foreach ($_SESSION['cart'] as $item): ?>
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
