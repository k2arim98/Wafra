<?php
session_start();
if (!isset($_SESSION['user_email'])) {
    header("Location: loginRegisterPage.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wafra</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="/Wafra/CSS/styles.css">
</head>
<body>
    <!-- navbar -->
    <nav class="navbar">
    <div class="logo">
        <img src="/Wafra/images/logo.png" alt="Logo" class="logo-img">
        <span>Wafra</span>
    </div>
    <ul class="nav-links">
        <li><a href="/Wafra/HTML/index.html">Accueil</a></li>
        <li><a href="/Wafra/HTML/Products.php">Produits</a></li>
        <li><a href="#social">Contact</a></li>
        <li><a href="account.php">Compte</a></li>
    </ul>
</nav>

        
    <!-- Animation Section -->
    <div class="hero">
        <img src="/Wafra/images/background.webp" alt="Hero Background" class="hero-image">
        <div class="hero-content">
            <h1>👉 Rayonnez avec <span id="dynamic-text">Éclat & Saveur</span></h1>
            <p>💄✨ Prenez soin de vous, régalez-vous – parce que vous méritez l'excellence !</p>
            <a href="Products.php" class="cta-button">Je Me Fais Plaisir !</a>
        </div>
    </div>

    <!-- Section des Produits en vedettes -->
    <section id="products" class="products-preview">
        <div class="container">
            <h2>Produits Vedettes</h2>
            <div class="product-grid">
                <!-- Alimentaires -->
                <div class="product-item">
                    <img src="/Wafra/images/Alimentaires.jpg" alt="Produit Alimentaire">
                    <h3>Produits Alimentaires</h3>
                    <p>Découvrez une sélection de produits alimentaires de qualité, parfaits pour une alimentation saine et équilibrée.</p>
                    <a href="Products.php" class="cta-button">Voir Plus</a>
                </div>
                <!-- Cosmétiques -->
                <div class="product-item">
                    <img src="/Wafra/images/cosmetique.jpg" alt="Produit Cosmétiques">
                    <h3>Produits Cosmétiques</h3>
                    <p>Offrez à votre peau le soin qu’elle mérite avec notre gamme de produits cosmétiques de haute qualité.</p>
                    <a href="Products.php" class="cta-button">Voir Plus</a>
                </div>
            </div>
        </div>
    </section>
    

<!-- Ce que disent Nos Client -->
    <section id="testimonials" class="testimonials">
        <div class="container">
            <h2>Ce Que Disent Nos Clients</h2>
            <div class="testimonial-grid">
                <div class="testimonial-item">
                    <p>"Des produits cosmétiques incroyables et un service client exceptionnel ! Ma peau n'a jamais été aussi éclatante."</p>
                    <p class="customer-name">Sophie Martin</p>
                </div>
                <div class="testimonial-item">
                    <p>"Les produits alimentaires sont de grande qualité et 100% naturels. Un vrai plaisir pour toute la famille !"</p>
                    <p class="customer-name">Lucas Dupont</p>
                </div>
            </div>
        </div>
    </section>

<section id="social" class="social-media">
    <div class="container">
        <h2>Contactez Nous</h2>
        <div class="social-icons">
            <a href="https://facebook.com" target="_blank" class="social-icon">
                <i class="fab fa-facebook-f"></i>
            </a>
            <a href="https://instagram.com" target="_blank" class="social-icon">
                <i class="fab fa-instagram"></i>
            </a>
            <a href="https://twitter.com" target="_blank" class="social-icon">
                <i class="fab fa-twitter"></i>
            </a>
        </div>
    </div>
</section>


<!-- Footer Section -->

    <script src="/Wafra/JS/scripts.js"></script>
</body>
</html>
