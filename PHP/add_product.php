<?php
include 'config.php';

// Default variables for the form
$name = '';
$price = '';
$imageName = '';
$productId = null;

// Check if 'edit' is set in the URL to fetch product data
if (isset($_GET['edit'])) {
    $productId = $_GET['edit'];

    // Fetch product details from the database
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if product was found
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        $name = $product['name'];
        $price = $product['price'];
        $imageName = $product['image'];
    } else {
        echo "No product found for this ID.";
    }
}

// Handle form submission for adding or editing product
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get POST values
    $name = $_POST['name'];
    $price = $_POST['price'];
    $productId = isset($_POST['product_id']) ? $_POST['product_id'] : null;
    
    // Check if image is uploaded
    if (!empty($_FILES['image']['name'])) {
        $imageName = $_FILES['image']['name'];
        // Move image to the folder
        move_uploaded_file($_FILES['image']['tmp_name'], "../images/$imageName");
    } elseif ($productId && empty($imageName)) {
        // If editing and no new image, keep the existing one
        $stmt = $conn->prepare("SELECT image FROM products WHERE id = ?");
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        $existingProduct = $result->fetch_assoc();
        $imageName = $existingProduct['image'];
    }

    // If product ID exists, update the existing product
    if ($productId) {
        $stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, image = ? WHERE id = ?");
        $stmt->bind_param("sdsi", $name, $price, $imageName, $productId);
        $stmt->execute();

        // Check if the update was successful
        if ($stmt->affected_rows > 0) {
            echo "Product updated successfully!";
        } else {
            echo "Error updating product: " . $stmt->error;
        }
    } else {
        // If product ID is not set, create a new product
        $stmt = $conn->prepare("INSERT INTO products (name, price, image) VALUES (?, ?, ?)");
        $stmt->bind_param("sds", $name, $price, $imageName);
        $stmt->execute();

        // Check if the insertion was successful
        if ($stmt->affected_rows > 0) {
            echo "New product added successfully!";
        } else {
            echo "Error adding new product: " . $stmt->error;
        }
    }

    // Redirect to the admin panel
    header("Location: /Wafra/admin/dashboard.php");
    exit;
}

// Handle delete request
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: /Wafra/admin/dashboard.php");
    exit;
}
?>

<!-- HTML Form for Add/Edit Product -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= $productId ? 'Edit Product' : 'Add Product' ?></title>
  <link rel="stylesheet" href="../CSS/dashboard.css">
</head>
<body>

  <h1><?= $productId ? 'Edit Product' : 'Add New Product' ?></h1>
  <form action="add_product.php" method="post" enctype="multipart/form-data">
    <?php if ($productId): ?>
      <input type="hidden" name="product_id" value="<?= $productId ?>">
    <?php endif; ?>
    <input type="text" name="name" value="<?= htmlspecialchars($name) ?>" placeholder="Product Name" required>
    <input type="number" step="0.01" name="price" value="<?= $price ?>" placeholder="Price" required>
    <input type="file" name="image" <?= $productId ? '' : 'required' ?>>
    <?php if ($productId && $imageName): ?>
      <p>Current image: <?= htmlspecialchars($imageName) ?></p>
    <?php endif; ?>
    <button type="submit"><?= $productId ? 'Update Product' : 'Add Product' ?></button>
  </form>

</body>
</html>