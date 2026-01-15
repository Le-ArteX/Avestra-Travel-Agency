<?php
// Database schema update - Add settings columns to admin table
include('dbconnection.php');

$columns_to_add = [
    'site_theme' => "ALTER TABLE admin ADD COLUMN site_theme VARCHAR(50) DEFAULT 'light' AFTER profile_image",
    'email_notifications' => "ALTER TABLE admin ADD COLUMN email_notifications VARCHAR(50) DEFAULT 'enabled' AFTER site_theme",
    'language' => "ALTER TABLE admin ADD COLUMN language VARCHAR(10) DEFAULT 'en' AFTER email_notifications",
    'timezone' => "ALTER TABLE admin ADD COLUMN timezone VARCHAR(50) DEFAULT 'UTC' AFTER language",
    'privacy_mode' => "ALTER TABLE admin ADD COLUMN privacy_mode VARCHAR(50) DEFAULT 'public' AFTER timezone",
    'maintenance_mode' => "ALTER TABLE admin ADD COLUMN maintenance_mode VARCHAR(50) DEFAULT 'off' AFTER privacy_mode"
];

foreach ($columns_to_add as $column_name => $alter_query) {
    // Check if column exists
    $check_column = $conn->query("SHOW COLUMNS FROM admin LIKE '$column_name'");
    
    if ($check_column && $check_column->num_rows == 0) {
        if ($conn->query($alter_query)) {
            echo "✓ Column '$column_name' added successfully.<br>";
        } else {
            echo "✗ Error adding column '$column_name': " . $conn->error . "<br>";
        }
    } else {
        echo "Column '$column_name' already exists.<br>";
    }
}

$conn->close();
?>
