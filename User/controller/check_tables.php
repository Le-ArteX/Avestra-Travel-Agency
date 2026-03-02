<?php
include '../database/dbconnection.php';

$tables = ['signup', 'customer', 'admin'];

foreach ($tables as $table) {
    try {
        $result = $conn->query("SELECT COUNT(*) as count FROM $table");
        if ($result) {
            $row = $result->fetch_assoc();
            echo "$table: " . $row['count'] . "\n";
        } else {
            echo "$table: [Table not found or error]\n";
        }
    } catch (Exception $e) {
        echo "$table: [Error: " . $e->getMessage() . "]\n";
    }
}
?>
