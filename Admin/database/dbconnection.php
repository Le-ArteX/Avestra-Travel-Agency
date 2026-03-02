<?php
$host     = getenv('DB_HOST')     ?: 'localhost';
$user     = getenv('DB_USER')     ?: 'root';
$password = getenv('DB_PASSWORD') ?: '';
$database = getenv('DB_NAME')     ?: 'avestra-Travel-Agency';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    error_log("DB connection failed: " . $conn->connect_error);
    die("A database error occurred. Please try again later.");
}

$conn->set_charset("utf8mb4");
