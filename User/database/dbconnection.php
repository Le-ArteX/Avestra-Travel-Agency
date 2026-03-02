<?php
// Database connection configuration
$host     = getenv('DB_HOST')     ?: 'localhost';
$user     = getenv('DB_USER')     ?: 'root';
$password = getenv('DB_PASSWORD') ?: '';
$database = getenv('DB_NAME')     ?: 'avestra-Travel-Agency';

// Create connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    error_log("DB connection failed: " . $conn->connect_error);
    die("A database error occurred. Please try again later.");
}

// Set charset to UTF-8
$conn->set_charset("utf8mb4");