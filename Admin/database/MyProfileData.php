<?php
include('dbconnection.php');

$admin_email = $_SESSION['admin_email'] ?? '';
$admin_profile_image = '../images/logo.png';

if (!empty($admin_email)) {
    $sql = "SELECT username, email, phoneNumber, role, status, date as created_at, profile_image FROM admin WHERE email = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("s", $admin_email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $admin = $result->fetch_assoc();
            $_SESSION['admin_name'] = $admin['username'] ?? 'Admin User';
            $_SESSION['admin_email'] = $admin['email'] ?? '';
            $_SESSION['admin_phone'] = $admin['phoneNumber'] ?? '';
            $_SESSION['admin_role'] = $admin['role'] ?? 'Administrator';
            $_SESSION['admin_status'] = $admin['status'] ?? 'Active';
            $_SESSION['admin_date'] = $admin['created_at'] ?? date('Y-m-d');
            if (!empty($admin['profile_image']) && file_exists('../images/profiles/' . $admin['profile_image'])) {
                $admin_profile_image = '../images/profiles/' . $admin['profile_image'];
            }
        }
        $stmt->close();
    }
}
