<?php
require 'config.php';  // Database connection

header("Content-Type: application/json"); // Ensure response is JSON

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = isset($_POST['full_name']) ? trim($_POST['full_name']) : null;
    $email = isset($_POST['email']) ? trim($_POST['email']) : null;
    $password = isset($_POST['password']) ? $_POST['password'] : null;

    if (!$full_name || !$email || !$password) {
        echo json_encode(["status" => "error", "message" => "All fields are required!"]);
        exit();
    }

    // Check if email already exists
    $check_email = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check_email->bind_param("s", $email);
    $check_email->execute();
    $check_email->store_result();

    if ($check_email->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "Email already registered!"]);
        exit();
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $full_name, $email, $hashed_password);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Registration successful!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error during registration!"]);
    }
}
?>
