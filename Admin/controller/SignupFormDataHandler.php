<?php
// Retrieve form data and errors from session if available
// Note: session_start() is called in SignupValidation.php, so not needed here

$form_data = $_SESSION['form_data'] ?? [];
$form_errors = $_SESSION['form_errors'] ?? [];

$username = $form_data['username'] ?? '';
$email = $form_data['email'] ?? '';
$phoneNumber = $form_data['phoneNumber'] ?? '';
$role = $form_data['role'] ?? '';
$password = $form_data['password'] ?? '';
$confirmPassword = $form_data['confirmPassword'] ?? '';

$username_error = $form_errors['username_error'] ?? '';
$email_error = $form_errors['email_error'] ?? '';
$phoneNumber_error = $form_errors['phoneNumber_error'] ?? '';
$role_error = $form_errors['role_error'] ?? '';
$password_error = $form_errors['password_error'] ?? '';
$confirmPassword_error = $form_errors['confirmPassword_error'] ?? '';
$general_error = $form_errors['general_error'] ?? '';

// Clear session after retrieving data
unset($_SESSION['form_data']);
unset($_SESSION['form_errors']);
?>
