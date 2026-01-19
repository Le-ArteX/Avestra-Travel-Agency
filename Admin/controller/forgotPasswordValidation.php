<?php
session_start();
include '../database/dbconnection.php';

$email = $newPassword = $confirmPassword = "";
$email_error = $newPassword_error = $confirmPassword_error = "";
$general_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["email"])) || empty(trim($_POST["new-password"])) || empty(trim($_POST["confirm-password"]))) {
        $general_error = "Please fill in all required fields.";
    } else {
        if (!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
            $email_error = "Please enter a valid email address.";
        } else {
            $email = trim($_POST["email"]);
        }
        if (strlen(trim($_POST["new-password"])) < 6) {
            $newPassword_error = "Password must be at least 6 characters long.";
        } else {
            $newPassword = trim($_POST["new-password"]);
        }
        $confirmPassword = trim($_POST["confirm-password"]);
        if ($newPassword != $confirmPassword) {
            $confirmPassword_error = "Passwords do not match.";
        }
    }
    $_SESSION['forgot_form_data'] = [
        'email' => $email
    ];
    $_SESSION['forgot_form_errors'] = [
        'email_error' => $email_error,
        'newPassword_error' => $newPassword_error,
        'confirmPassword_error' => $confirmPassword_error,
        'general_error' => $general_error
    ];
    if (empty($email_error) && empty($newPassword_error) && empty($confirmPassword_error) && empty($general_error)) {
        $check_email = $conn->prepare("SELECT email FROM customer WHERE email = ?");
        $check_email->bind_param("s", $email);
        $check_email->execute();
        $result = $check_email->get_result();
        if ($result->num_rows > 0) {
            $hashed_password = password_hash($newPassword, PASSWORD_DEFAULT);
            $update = $conn->prepare("UPDATE customer SET password = ? WHERE email = ?");
            $update->bind_param("ss", $hashed_password, $email);
            
            if ($update->execute()) {
                $_SESSION['forgot_success_message'] = "Password reset successfully! You can now login with your new password.";
                // Clear form data
                unset($_SESSION['forgot_form_data']);
                unset($_SESSION['forgot_form_errors']);
                // Redirect to forgot password page to show success message
                header("Location: ../views/forgotPassword.php");
                exit();
            } else {
                $_SESSION['forgot_error_message'] = "Error resetting password. Please try again.";
            }
            $update->close();
        } else {
            $_SESSION['forgot_error_message'] = "Email address not found. Please check and try again.";
        }
        $check_email->close();
    }

    header("Location: ../views/forgotPassword.php");
    exit();
}
?>
