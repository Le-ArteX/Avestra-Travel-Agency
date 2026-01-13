<?php
session_start();

$form_data = $_SESSION['login_form_data'] ?? [];
$form_errors = $_SESSION['login_form_errors'] ?? [];

$email = $form_data['email'] ?? '';

$email_error = $form_errors['email_error'] ?? '';
$password_error = $form_errors['password_error'] ?? '';
$general_error = $form_errors['general_error'] ?? '';

// Don't unset until after all variables are used
?>
