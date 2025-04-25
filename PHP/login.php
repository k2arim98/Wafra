<?php
require 'config.php';  // Database connection

session_start();  // Start the session at the top to avoid any header issues

header("Content-Type: application/json"); // Ensure response is JSON

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = isset($_POST['email']) ? trim($_POST['email']) : null;
    $password = isset($_POST['password']) ? $_POST['password'] : null;

    // Validate input
    if (!$email || !$password) {
        echo json_encode(["status" => "error", "message" => "Email and password are required!"]);
        exit();
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["status" => "error", "message" => "Invalid email format"]);
        exit();
    }

    // Fetch user from database
    $stmt = $conn->prepare("SELECT id, full_name, password, is_admin FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        // Successful login, set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['full_name'];
        $_SESSION['user_email'] = $email; 
        $_SESSION['is_admin'] = $user['is_admin'];          

        echo json_encode(["status" => "success", "message" => "Login successful!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid email or password!"]);
    }
}
?>
