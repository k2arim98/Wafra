<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['action'])) {
    $orderId = (int) $_POST['order_id'];
    $action = $_POST['action'];

    if (in_array($action, ['confirm', 'cancel'])) {
        $status = $action === 'confirm' ? 'confirmed' : 'cancelled';

        $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->bind_param('si', $status, $orderId);
        $stmt->execute();
    }
}

header("Location: /Wafra/admin/dashboard.php");
exit();
