<?php
// Database connection
$servername = "localhost";
$username = "root";  
$password = "root"; 
$dbname = "wafra";  

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch products from database
$sql = "SELECT id, name, price, image FROM products";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wafra - Products</title>
    <link rel="stylesheet" href="../CSS/products.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="logo">
            <img src="../images/logo.png" alt="Logo" class="logo-img">
            <span>Wafra </span>
        </div>
        <ul class="nav-links">
            <li><a href="index.html">Accueil</a></li>
            <li><a href="#products">Produits</a></li>
            <li><a href="">À propos</a></li>
            <li><a href="#social">Contact</a></li>
        </ul>
    </nav>

    <!-- Products Grid -->
    <section id="products" class="product-grid">
        <div class="container">
            <h2>Our Products</h2>
            <div class="product-items">
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <div class="product-item" >
                        <img src="../images/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
                        <h3><?php echo $row['name']; ?></h3>
                        <p>$<?php echo number_format($row['price'], 2); ?></p>
                        <a href="details_page.php?id=<?php echo $row['id']; ?>" class="cta-button">View Details</a>
                    </div>
                <?php } ?>
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
