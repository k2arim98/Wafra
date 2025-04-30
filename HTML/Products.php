<?php
session_start();
include '../PHP/config.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if ($search !== '') {
    $stmt = $conn->prepare("SELECT id, name, price, image, category, description FROM products WHERE name LIKE CONCAT('%', ?, '%') OR category LIKE CONCAT('%', ?, '%')");
    $stmt->bind_param("ss", $search, $search);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "SELECT id, name, price, image, category, description FROM products";
    $result = $conn->query($sql);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wafra - Products</title>
    <link rel="stylesheet" href="/Wafra/CSS/products.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
    <div class="logo">
        <img src="/Wafra/images/logo.png" alt="Logo" class="logo-img">
        <span>Wafra</span>
        <form method="GET" action="" class="search-form">
            <input type="text" name="search" placeholder="Search products..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
            <button type="submit">Search</button>
        </form>

    </div>
    
    <ul class="nav-links">
        <li><a href="/Wafra/HTML/index.php">Accueil</a></li>
        <li><a href="/Wafra/HTML/Products.php">Produits</a></li>
        <li><a href="#social">Contact</a></li>
        <li><a href="/Wafra/PHP/logout.php">Logout</a></li>
    </ul>

</nav>


    <!-- Products Grid -->
    <section id="products" class="product-grid">
    <div class="container">
        <h2>Our Products</h2>
        <div class="product-items">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="product-item">
                        <img src="/Wafra/images/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
                        <h3><?= htmlspecialchars($row['name']) ?></h3>
                        <h4><?= htmlspecialchars($row['category']) ?></h4>
                        <p>$<?= number_format($row['price'], 2) ?></p>
                        <a href="details_page.php?id=<?= $row['id'] ?>" class="cta-button">View Details</a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No products found for '<?= htmlspecialchars($search) ?>'</p>
            <?php endif; ?>
        </div>
    </div>
</section>

   
    <section id="social" class="social-media">
        <div class="container">
            <h2>Contactez Nous</h2>
            <div class="social-icons">
                <a href="https://facebook.com" target="_blank" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                <a href="https://instagram.com" target="_blank" class="social-icon"><i class="fab fa-instagram"></i></a>
                <a href="https://twitter.com" target="_blank" class="social-icon"><i class="fab fa-twitter"></i></a>
            </div>
        </div>
    </section>

</body>
</html>

<?php $conn->close(); ?>
