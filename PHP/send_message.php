<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    if (!isset($_SESSION['user_email'])) {
        die("Vous devez être connecté pour envoyer un message.");
    }

    $email = $_SESSION['user_email'];
    $message = trim($_POST['message']);

    if ($message === '') {
        die("Message vide.");
    }

    $stmt = $conn->prepare("INSERT INTO messages (user_email, message) VALUES (?, ?)");
    $stmt->bind_param("ss", $email, $message);

    if ($stmt->execute()) {
        echo "<script>alert('Message envoyé avec succès !'); window.history.back();</script>";
    } else {
        echo "<script>alert('Erreur lors de l\'envoi du message.'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
