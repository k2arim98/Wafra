<!-- admin_panel.php -->
<?php
session_start();
include '../PHP/config.php';

if (!isset($_SESSION['user_email']) || $_SESSION['is_admin'] != 1) {
    header("Location: /Wafra/HTML/loginRegisterPage.html"); 
    exit();
}
// Fetch all users
$users = $conn->query("SELECT * FROM users")->fetch_all(MYSQLI_ASSOC);

// Fetch All messages
$sql = "SELECT * FROM messages ORDER BY sent_at DESC";
$messages = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);

// Fetch all products
$products = $conn->query("SELECT * FROM products")->fetch_all(MYSQLI_ASSOC);

// Fetch order history
$orders = $conn->query("SELECT c.*, u.full_name, p.name AS product_name, p.price 
                        FROM cart c
                        JOIN users u ON u.email = c.user_email
                        JOIN products p ON p.id = c.product_id
                        ORDER BY c.created_at DESC")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Panel</title>
  <link rel="stylesheet" href="/Wafra/CSS/dashboard.css">
  </head>
<body>
<nav class="navbar">
        <div class="logo">
            <img src="/Wafra/images/logo.png" alt="Logo" class="logo-img">
            <span>Wafra </span>
        </div>
        <ul class="nav-links">
            <li><a href="/Wafra/HTML/index.php">Accueil</a></li>
            <li><a href="/Wafra/HTML//Products.php">Produits</a></li>
            <li><a href="#social">Contact</a></li>
            <li><a href="/Wafra/HTML/loginRegisterPage.html">Compte</a></li>

        </ul>
    </nav>
  <h1>Admin Panel</h1>

  <section>
    <h2>Users</h2>
    <table>
      <tr><th>ID</th><th>Name</th><th>Email</th></tr>
      <?php foreach ($users as $user): ?>
        <tr>
          <td><?= $user['id'] ?></td>
          <td><?= htmlspecialchars($user['full_name']) ?></td>
          <td><?= htmlspecialchars($user['email']) ?></td>
        </tr>
      <?php endforeach; ?>
    </table>
  </section>

  <section>
  <h2>Shopping History</h2>
  <table>
    <tr>
      <th>User</th><th>Email</th><th>Product</th><th>Quantity</th><th>Price</th><th>Date</th><th>Status</th><th>Actions</th>
    </tr>
    <?php foreach ($orders as $order): ?>
      <tr>
        <td><?= htmlspecialchars($order['full_name']) ?></td>
        <td><?= htmlspecialchars($order['user_email']) ?></td>
        <td><?= htmlspecialchars($order['product_name']) ?></td>
        <td><?= $order['quantity'] ?></td>
        <td>$<?= number_format($order['price'], 2) ?></td>
        <td><?= $order['created_at'] ?></td>
        <td><?= ucfirst($order['status']) ?></td>
        <td>
          <?php if ($order['status'] === 'pending'): ?>
            <form method="post" action="/Wafra/PHP/update_order_status.php" style="display:inline;">
              <input type="hidden" name="cart_id" value="<?= $order['id'] ?>">
              <button type="submit" name="action" value="confirm">✅ Confirm</button>
              <button type="submit" name="action" value="cancel" onclick="return confirm('Cancel this order?')">❌ Cancel</button>
            </form>
          <?php else: ?>
            <em><?= ucfirst($order['status']) ?></em>
          <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
</section>


  <section>
    <h2>Products</h2>
    <h3>Add New Product</h3>
    <form action="/Wafra/PHP/add_product.php" method="post" enctype="multipart/form-data">
      <input type="text" name="name" placeholder="Product Name" required>
      <input type="text" name="category" placeholder="Product Category" required>
      <input type="text" name="description" placeholder="Product Description" required>

      <input type="number" step="0.01" name="price" placeholder="Price" required>
      <input type="file" name="image" required>
      <button type="submit">Add Product</button>
    </form>
    <table>
  <tr><th>ID</th><th>Name</th><th>Price</th><th>Image</th><th>Actions</th></tr>
  <?php foreach ($products as $product): ?>
    <tr>
      <td><?= $product['id'] ?></td>
      <td><?= htmlspecialchars($product['name']) ?></td>
      <td>$<?= number_format($product['price'], 2) ?></td>
      <td><img src="/Wafra/images/<?= htmlspecialchars($product['image']) ?>" width="50"></td>
      <td>
        <a href="/Wafra/PHP/add_product.php?edit=<?= $product['id'] ?>">Edit</a>

        <a href="/Wafra/PHP/add_product.php?delete=<?= $product['id'] ?>" onclick="return confirm('Delete this product?')">Delete</a>
      </td>
    </tr>
    <?php endforeach; ?>
  </table>
  </section>

  <section>
    <h2>User Messages</h2>
    <?php if (count($messages) > 0): ?>
      <?php foreach ($messages as $msg): ?>
        <div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">
          <strong><?= htmlspecialchars($msg['user_email']) ?></strong>
          <p><?= nl2br(htmlspecialchars($msg['message'])) ?></p>
          <small><?= htmlspecialchars($msg['sent_at']) ?></small>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p>No messages yet.</p>
    <?php endif; ?>
  </section>

</body>
</html>
