<?php
session_start();
include '../database/dbconnection.php';

$username = $email = $phoneNumber = $role = $password = $confirmPassword = "";
$username_error = $email_error = $phoneNumber_error =
    $role_error = $password_error = $confirmPassword_error = "";
$general_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["username"])) || empty(trim($_POST["email"])) || 
        empty(trim($_POST["phoneNumber"])) || empty(trim($_POST["role"])) || 
        empty(trim($_POST["password"])) || empty(trim($_POST["confirm-password"]))) {
        $general_error = "Please fill up all requirements.";
    } else {
        if (!preg_match("/^[a-zA-Z\s]+$/", trim($_POST["username"]))) {
            $username_error = " Fullname must contain only letters and spaces.";
        } else {
            $username = trim($_POST["username"]);
        }
        if (!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
            $email_error = "Please enter a valid email address.";
        } else {
            $email = trim($_POST["email"]);
        }
        if (!preg_match("/^[0-9]{11}$/", trim($_POST["phoneNumber"]))) {
            $phoneNumber_error = "Phone number must be exactly 11 digits.";
        } else {
            $phoneNumber = trim($_POST["phoneNumber"]);
        }
        $role = trim($_POST["role"]);
        if (strlen(trim($_POST["password"])) < 6) {
            $password_error = "Password must have at least 6 characters.";
        } else {
            $password = trim($_POST["password"]);
        }
        $confirmPassword = trim($_POST["confirm-password"]);
        if ($password != $confirmPassword) {
            $confirmPassword_error = "Passwords do not match.";
        }
    }
    $_SESSION['form_data'] = [
        'username' => $username,
        'email' => $email,
        'phoneNumber' => $phoneNumber,
        'role' => $role,
        'password' => $password,
        'confirmPassword' => $confirmPassword
    ];

    $_SESSION['form_errors'] = [
        'username_error' => $username_error,
        'email_error' => $email_error,
        'phoneNumber_error' => $phoneNumber_error,
        'role_error' => $role_error,
        'password_error' => $password_error,
        'confirmPassword_error' => $confirmPassword_error,
        'general_error' => $general_error
    ];

   
    if (empty($username_error) && empty($email_error) && empty($phoneNumber_error) && empty($role_error) && empty($password_error) && empty($confirmPassword_error) && empty($general_error)) {
      
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
     
        $date = date('Y-m-d H:i:s');
        
     
        if (strtolower($role) === 'admin') {
            // Check if email already exists in admin table
            $check_email = $conn->prepare("SELECT email FROM admin WHERE email = ?");
            $check_email->bind_param("s", $email);
            $check_email->execute();
            $result = $check_email->get_result();
            
            if ($result->num_rows > 0) {
                $_SESSION['signup_error_message'] = "Email already exists. Please use a different email.";
            } else {
                // Insert into admin table with Pending status
                $status = 'Pending';
                $insert = $conn->prepare("INSERT INTO admin (username, email, phoneNumber, role, password, date, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $insert->bind_param("sssssss", $username, $email, $phoneNumber, $role, $hashed_password, $date, $status);
                
                if ($insert->execute()) {
                    $_SESSION['signup_success_message'] = "Admin request submitted successfully! Your request will be reviewed by the system administrator.";
                    // Clear form data
                    unset($_SESSION['form_data']);
                    unset($_SESSION['form_errors']);
                    // Redirect back to signup page to show success message
                    header("Location: ../views/Signup.php");
                    exit();
                } else {
                    $_SESSION['signup_error_message'] = "Error submitting admin request. Please try again.";
                }
                $insert->close();
            }
            $check_email->close();
        } else {
            // For customer role, check if email exists in customer table
            $check_email = $conn->prepare("SELECT email FROM customer WHERE email = ?");
            $check_email->bind_param("s", $email);
            $check_email->execute();
            $result = $check_email->get_result();
            
            if ($result->num_rows > 0) {
                $_SESSION['signup_error_message'] = "Email already registered. Please use a different email.";
            } else {
                // For customer role, insert directly into customer table
                $insert = $conn->prepare("INSERT INTO customer (username, email, phoneNumber, role, password, date, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $insert->bind_param("sssssss", $username, $email, $phoneNumber, $role, $hashed_password, $date, $status);
                $status = 'Active';
                
                if ($insert->execute()) {
                    $_SESSION['signup_success_message'] = "Account created successfully!";
                    // Clear form data
                    unset($_SESSION['form_data']);
                    unset($_SESSION['form_errors']);
                    // Redirect back to signup page to show success message
                    header("Location: ../views/Signup.php");
                    exit();
                } else {
                    $_SESSION['signup_error_message'] = "Error creating account. Please try again.";
                }
                $insert->close();
            }
            $check_email->close();
        }
    }

    // Redirect back to signup form with errors/data in session
    header("Location: ../views/Signup.php");
    exit();
}
?>
