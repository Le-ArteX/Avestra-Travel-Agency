<?php
session_start();
include('../database/dbconnection.php');

 $search = $search ?? '';
    $role_filter = $role_filter ?? '';
    $status_filter = $status_filter ?? '';

    // Display success/error messages
    $success_message = $_SESSION['success_message'] ?? '';
    $error_message = $_SESSION['error_message'] ?? '';
    unset($_SESSION['success_message'], $_SESSION['error_message']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch($action) {
        case 'delete':
            $email = $_POST['email'] ?? '';
            if (!empty($email)) {
                $stmt = $conn->prepare("DELETE FROM signup WHERE email = ?");
                $stmt->bind_param("s", $email);
                if ($stmt->execute()) {
                    $_SESSION['success_message'] = "User deleted successfully!";
                } else {
                    $_SESSION['error_message'] = "Error deleting user.";
                }
                $stmt->close();
            }
            break;
            
        case 'block':
            $email = $_POST['email'] ?? '';
            if (!empty($email)) {
                $stmt = $conn->prepare("UPDATE signup SET status = 'Blocked' WHERE email = ?");
                $stmt->bind_param("s", $email);
                if ($stmt->execute()) {
                    $_SESSION['success_message'] = "User blocked successfully!";
                } else {
                    $_SESSION['error_message'] = "Error blocking user.";
                }
                $stmt->close();
            }
            break;
            
        case 'unblock':
            $email = $_POST['email'] ?? '';
            if (!empty($email)) {
                $stmt = $conn->prepare("UPDATE signup SET status = 'Active' WHERE email = ?");
                $stmt->bind_param("s", $email);
                if ($stmt->execute()) {
                    $_SESSION['success_message'] = "User unblocked successfully!";
                } else {
                    $_SESSION['error_message'] = "Error unblocking user.";
                }
                $stmt->close();
            }
            break;
            
        case 'add':
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $role = trim($_POST['role'] ?? 'Customer');
            $status = trim($_POST['status'] ?? 'Active');
            $password = trim($_POST['password'] ?? '');
            
            if (!empty($username) && !empty($email) && !empty($password)) {
                // Check if email exists
                $check = $conn->prepare("SELECT email FROM signup WHERE email = ?");
                $check->bind_param("s", $email);
                $check->execute();
                if ($check->get_result()->num_rows > 0) {
                    $_SESSION['error_message'] = "Email already exists!";
                } else {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $date = date('Y-m-d H:i:s');
                    $stmt = $conn->prepare("INSERT INTO signup (username, email, phoneNumber, role, password, status, Date) VALUES (?, ?, '', ?, ?, ?, ?)");
                    $stmt->bind_param("ssssss", $username, $email, $role, $hashed_password, $status, $date);
                    if ($stmt->execute()) {
                        $_SESSION['success_message'] = "User added successfully!";
                    } else {
                        $_SESSION['error_message'] = "Error adding user.";
                    }
                    $stmt->close();
                }
                $check->close();
            } else {
                $_SESSION['error_message'] = "All fields are required!";
            }
            break;
            
        case 'edit':
            $old_email = $_POST['old_email'] ?? '';
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $role = trim($_POST['role'] ?? 'Customer');
            $status = trim($_POST['status'] ?? 'Active');
            
            if (!empty($old_email) && !empty($username) && !empty($email)) {
                $stmt = $conn->prepare("UPDATE signup SET username = ?, email = ?, role = ?, status = ? WHERE email = ?");
                $stmt->bind_param("sssss", $username, $email, $role, $status, $old_email);
                if ($stmt->execute()) {
                    $_SESSION['success_message'] = "User updated successfully!";
                } else {
                    $_SESSION['error_message'] = "Error updating user.";
                }
                $stmt->close();
            }
            break;
    }
    
    $conn->close();
    header("Location: ../views/ManageUsers.php");
    exit();
}
?>
