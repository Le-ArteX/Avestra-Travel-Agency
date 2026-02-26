<?php
include(__DIR__ . '/Admin/database/dbconnection.php');

$sql = "ALTER TABLE tours ADD COLUMN package_id VARCHAR(50) NULL AFTER id";

if ($conn->query($sql) === TRUE) {
    echo "Column 'package_id' added successfully.<br>";
} else {
    echo "Error adding column: " . $conn->error . "<br>";
}

$conn->close();
?>
