<?php
session_start();
include '../database/dbconnection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (empty($_POST['email']) || empty($_POST['password'])) {
        $_SESSION['login_error_message'] = "All fields required.";
        header("Location: ../../Admin/views/loginPage.php");
        exit();
    }

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare(
        "SELECT username, email, password, role FROM signup WHERE email=?"
    );
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {

            $_SESSION['email'] = $user['email'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            /* Admin redirect */
            // if ($user['role'] === 'admin') {
            //     header("Location: ../Admin/views/Admin.php");
            //     exit();
            // }

            /* Redirect back after booking */
            if (isset($_SESSION['redirect_after_login'])) {
                $redirect = $_SESSION['redirect_after_login'];
                unset($_SESSION['redirect_after_login']);
                header("Location: $redirect");
                exit();
            }

            /* Default user dashboard */
            header("Location: ../User/views/user_dashboard.php");
            exit();
        }
    }

    $_SESSION['login_error_message'] = "Invalid email or password.";
    header("Location: ../Admin/views/loginPage.php");
    exit();
}
?>