<?php
session_start();
include '../database/dbconnection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (empty($_POST['email']) || empty($_POST['password'])) {
        $_SESSION['login_error_message'] = "All fields required.";
        header("Location: ../../Admin/views/loginPage.php");
        exit();
    }

    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Query the customer table (corrected from the legacy 'signup' table)
    $stmt = $conn->prepare(
        "SELECT username, email, password, role, status FROM customer WHERE email = ?"
    );
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {

            // Block inactive / suspended accounts
            if ($user['status'] !== 'Active') {
                $_SESSION['login_error_message'] = "Your account is " . strtolower($user['status']) . ". Please contact support.";
                header("Location: ../../Admin/views/loginPage.php");
                exit();
            }

            $_SESSION['email']    = $user['email'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role']     = $user['role'];

            // Redirect back to original page after login (local paths only — prevents open redirect)
            if (isset($_SESSION['redirect_after_login'])) {
                $redirect = $_SESSION['redirect_after_login'];
                unset($_SESSION['redirect_after_login']);
                if (strpos($redirect, '/') === 0) {
                    header("Location: $redirect");
                    exit();
                }
            }

            // Default user dashboard
            header("Location: ../User/views/user_dashboard.php");
            exit();
        }
    }

    $_SESSION['login_error_message'] = "Invalid email or password.";
    header("Location: ../../Admin/views/loginPage.php");
    exit();
}
?>