<?php
// Settings stored in session (not in database)

// Read maintenance mode from file (accessible to all users)
$maintenance_file = __DIR__ . '/maintenance_mode.txt';
if (file_exists($maintenance_file)) {
    $maintenance_mode = trim(file_get_contents($maintenance_file));
} else {
    $maintenance_mode = 'off';
}

// Get settings from session with defaults
$site_theme = $_SESSION['settings']['site_theme'] ?? 'light';
$message_option = $_SESSION['settings']['message_option'] ?? 'enabled';
$language = $_SESSION['settings']['language'] ?? 'en';
$timezone = $_SESSION['settings']['timezone'] ?? 'UTC';
$privacy_mode = $_SESSION['settings']['privacy_mode'] ?? 'public';

// Get current admin profile data from database
$current_username = '';
$current_email = '';

if (isset($_SESSION['admin_email'])) {
    $admin_email = $_SESSION['admin_email'];
    $sql = "SELECT username, email FROM admin WHERE email = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("s", $admin_email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $admin_data = $result->fetch_assoc();
            $current_username = $admin_data['username'];
            $current_email = $admin_data['email'];
        }
        $stmt->close();
    }
}
?>
