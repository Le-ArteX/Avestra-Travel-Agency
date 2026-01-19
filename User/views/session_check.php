<?php
session_start();

if (!isset($_SESSION['email'])) {
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    header("Location: loginPage.php");
    exit();
}
?>