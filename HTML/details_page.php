<?php
// Connect to database
$conn = new mysqli("localhost", "root", "root", "wafra");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if product ID is passed in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Erreur: Produit non trouvé !");
}

$product_id = intval($_GET['id']);

// Fetch product details
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
    <link rel="stylesheet" href="../CSS/details.css">
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <img src="../images/logo.png" alt="Logo" class="logo-img">
            <span>Wafra</span>
        </div>
        <ul class="nav-links">
            <li><a href="#">Accueil</a></li>
            <li><a href="#products">Produits</a></li>
            <li><a href="">À propos</a></li>
            <li><a href="#social">Contact</a></li>
        </ul>
    </nav>

    <main>
        <section class="product-details">
            <div class="container">
                <div class="product-gallery">
                    <img src="../images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="main-image">
                </div>
                <div class="product-info">
                    <h1 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h1>
                    <p class="product-description"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                    <p class="product-price">Prix: $<?php echo number_format($product['price'], 2); ?></p>
                    <a href="cart.php?id=<?php echo $product['id']; ?>" class="cta-button">Ajouter au panier</a>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
