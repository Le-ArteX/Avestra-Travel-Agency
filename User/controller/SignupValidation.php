<?php
session_start();
include '../database/dbconnection.php';

$username = $email = $phoneNumber = $role = $password = "";
$username_error = $email_error = $phoneNumber_error =
$role_error = $password_error = $confirmPassword_error = "";
$general_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (
        empty(trim($_POST["username"])) ||
        empty(trim($_POST["email"])) ||
        empty(trim($_POST["phoneNumber"])) ||
        empty(trim($_POST["role"])) ||
        empty(trim($_POST["password"])) ||
        empty(trim($_POST["confirm-password"]))
    ) {
        $general_error = "Please fill up all requirements.";
    } else {

        if (!preg_match("/^[a-zA-Z\s]+$/", $_POST["username"])) {
            $username_error = "Full name must contain only letters and spaces.";
        } else {
            $username = trim($_POST["username"]);
        }

        if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
            $email_error = "Invalid email address.";
        } else {
            $email = trim($_POST["email"]);
        }

        if (!preg_match("/^[0-9]{11}$/", $_POST["phoneNumber"])) {
            $phoneNumber_error = "Phone number must be exactly 11 digits.";
        } else {
            $phoneNumber = trim($_POST["phoneNumber"]);
        }

        $role = trim($_POST["role"]);

        if (strlen($_POST["password"]) < 6) {
            $password_error = "Password must be at least 6 characters.";
        } else {
            $password = $_POST["password"];
        }

        if ($_POST["password"] !== $_POST["confirm-password"]) {
            $confirmPassword_error = "Passwords do not match.";
        }
    }

    if (
        empty($username_error) && empty($email_error) &&
        empty($phoneNumber_error) && empty($password_error) &&
        empty($confirmPassword_error) && empty($general_error)
    ) {

        $check = $conn->prepare("SELECT email FROM signup WHERE email=?");
        $check->bind_param("s", $email);
        $check->execute();
        $res = $check->get_result();

        if ($res->num_rows > 0) {
            $_SESSION['signup_error_message'] = "Email already exists.";
            header("Location: ../views/Signup.php");
            exit();
        }

        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $insert = $conn->prepare(
            "INSERT INTO signup(username,email,phoneNumber,role,password)
             VALUES (?,?,?,?,?)"
        );
        $insert->bind_param("sssss", $username, $email, $phoneNumber, $role, $hashed);

        if ($insert->execute()) {

            /* Auto login */
            $_SESSION['email'] = $email;
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;

            /* Redirect back after booking */
            if (isset($_SESSION['redirect_after_login'])) {
                $redirect = $_SESSION['redirect_after_login'];
                unset($_SESSION['redirect_after_login']);
                header("Location: $redirect");
                exit();
            }

            /* Default */
            header("Location: ../views/user_dashboard.php");
            exit();
        }
    }

    header("Location: ../views/Signup.php");
    exit();
}
?>