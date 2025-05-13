<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cart_id'], $_POST['action'])) {
    $cartId = (int) $_POST['cart_id'];
    $action = $_POST['action'];

    if (in_array($action, ['confirm', 'cancel'])) {
        $status = $action === 'confirm' ? 'confirmed' : 'cancelled';

        $stmt = $conn->prepare("UPDATE cart SET status = ? WHERE id = ?");
        $stmt->bind_param('si', $status, $cartId);
        $stmt->execute();
    }
}

header("Location: /Wafra/admin/dashboard.php");
exit();
