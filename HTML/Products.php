<?php
session_start();
include '../PHP/config.php';
$cart_items = [];
$total_items = 0;
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
if (isset($_SESSION['user_email'])) {
    $email = $_SESSION['user_email'];
    $stmt = $conn->prepare("
        SELECT p.name, p.price, p.image, c.quantity
        FROM cart c
        JOIN products p ON c.product_id = p.id
        WHERE c.user_email = ?
    ");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result2 = $stmt->get_result();
    while ($item = $result2->fetch_assoc()) {
        $cart_items[] = $item;
        $total_items += $item['quantity'];
    }
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
        <li><a href="account.php">Compte</a></li>
        <li>
        <a href="#" id="cart-toggle">🛒 <span class="cart-count"><?= $total_items ?></span></a>
        </li>
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

<div id="message-modal" class="cart-modal">
    <div class="cart-modal-content">
        <span class="close-btn" id="close-message">&times;</span>
        <h3>Envoyer un message à l'administrateur</h3>
        <form action="/Wafra/PHP/send_message.php" method="POST">
        <textarea name="message" rows="5" placeholder="Écrivez votre message ici..." required></textarea>
        <button type="submit" class="cta-button">Envoyer</button>
        </form>
    </div>
    </div>
    
    <div class="modal-button-container">
    <a href="#" id="open-message-modal" class="modal-button">Contacter Admin</a>
    </div>

   
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
    <div id="cart-modal" class="cart-modal">
    <div class="cart-modal-content">
        <span class="close-btn" id="close-cart">&times;</span>
        <h3>Votre Panier</h3>
        <?php if (!empty($cart_items)): ?>
        <ul>
            <?php foreach ($cart_items as $item): ?>
            <li>
                <img src="/Wafra/images/<?= htmlspecialchars($item['image']) ?>" alt="" width="40">
                <?= htmlspecialchars($item['name']) ?> x<?= $item['quantity'] ?>
                <span>$<?= number_format($item['price'], 2) ?></span>
            </li>
            <?php endforeach; ?>
        </ul>
        <a href="/Wafra/HTML/cart.php" class="cta-button">Voir le panier</a>
        <?php else: ?>
        <p>Votre panier est vide.</p>
        <?php endif; ?>
    </div>
    </div>
    
</body>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const modal = document.getElementById("cart-modal");
        const openBtn = document.getElementById("cart-toggle");
        const closeBtn = document.getElementById("close-cart");

        openBtn.addEventListener("click", (e) => {
            e.preventDefault();
            modal.style.display = "block";
        });

        closeBtn.addEventListener("click", () => {
            modal.style.display = "none";
        });

        window.addEventListener("click", (e) => {
            if (e.target === modal) {
                modal.style.display = "none";
            }
        });
    });
</script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    const msgModal = document.getElementById("message-modal");
    const openMsg = document.getElementById("open-message-modal");
    const closeMsg = document.getElementById("close-message");

    openMsg.addEventListener("click", function(e) {
        e.preventDefault();
        msgModal.style.display = "block";
    });

    closeMsg.addEventListener("click", () => {
        msgModal.style.display = "none";
    });

    window.addEventListener("click", (e) => {
        if (e.target === msgModal) {
            msgModal.style.display = "none";
        }
    });
});
</script>

</html>

<?php $conn->close(); ?>
