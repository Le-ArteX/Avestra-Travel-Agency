<?php
// Include database connection
include('dbconnection.php');

// Get admin email from session (logged-in user)
$admin_email = $_SESSION['admin_email'] ?? '';
$admin_profile_image = '../images/logo.png'; // Default image

if (!empty($admin_email)) {
    // Fetch admin data from admin table including profile_image
    $sql = "SELECT username, email, phoneNumber, role, status, date as created_at, profile_image FROM admin WHERE email = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("s", $admin_email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $admin = $result->fetch_assoc();
            
            // Store in session variables for use in view
            $_SESSION['admin_name'] = $admin['username'] ?? 'Admin User';
            $_SESSION['admin_email'] = $admin['email'] ?? '';
            $_SESSION['admin_phone'] = $admin['phoneNumber'] ?? '';
            $_SESSION['admin_role'] = $admin['role'] ?? 'Administrator';
            $_SESSION['admin_status'] = $admin['status'] ?? 'Active';
            $_SESSION['admin_date'] = $admin['created_at'] ?? date('Y-m-d');
            
            // Set profile image path
            if (!empty($admin['profile_image']) && file_exists('../images/profiles/' . $admin['profile_image'])) {
                $admin_profile_image = '../images/profiles/' . $admin['profile_image'];
            }
        }
        $stmt->close();
    }
}
?>
