<?php
// Database connection configuration
$host = "localhost";
$user = "root";
$password = "";
$database = "Avestra-Travel-Agency";

// Create connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to UTF-8
$conn->set_charset("utf8mb4");

?>
