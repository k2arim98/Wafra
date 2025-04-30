<?php
session_start();
include '../PHP/config.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Erreur: Produit non trouvé !");
}

$product_id = intval($_GET['id']);
$user_email = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : '';
$sql = "SELECT * FROM products WHERE id = $product_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();
} else {
    die("Erreur: Produit introuvable !");
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - Wafra</title>
    <link rel="stylesheet" href="/Wafra/CSS/details.css">
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <img src="/Wafra/images/logo.png" alt="Logo" class="logo-img">
            <span>Wafra</span>
        </div>
        <ul class="nav-links">
            <li><a href="index.php">Accueil</a></li>
            <li><a href="#products">Produits</a></li>
            <li><a href="#social">Contact</a></li>
            <li><a href="account.php">Compte</a></li>

        </ul>
    </nav>

    <main>
        <section class="product-details">
            <div class="container">
                <div class="product-gallery">
                    <img src="/Wafra/images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="main-image">
                </div>
                <div class="product-info">
                    <h1 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h1>
                    <p class="product-description"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                    <p class="product-price">Prix: $<?php echo number_format($product['price'], 2); ?></p>
                    <h4><?php echo $product['category']; ?></h4>
                    <a href="/Wafra/PHP/add_to_cart.php?id=<?php echo $product['id']; ?>" class="cta-button">
    Ajouter au panier
</a>

                </div>
            </div>
        </section>
    </main>
</body>
</html>
