<?php
$host = "localhost"; 
$user = "root";  
$pass = "root";  // MAMP default for macOS (use "" if on Windows)
$dbname = "wafra";

// Connect to MySQL
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
?>
