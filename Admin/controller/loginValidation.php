<?php
session_start();
include '../database/dbconnection.php';

$email = $password = "";
$email_error = $password_error = "";
$general_error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if any field is empty
    if (empty(trim($_POST["email"])) || empty(trim($_POST["password"]))) {
        $general_error = 'Please fill in all required fields.';
        $_SESSION['login_form_errors'] = [
            'general_error' => $general_error
        ];
        header("Location: ../views/loginPage.php");
        exit();
    } else {
        // Validate email format
        if (!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
            $email_error = "Please enter a valid email address.";
        } else {
            $email = trim($_POST["email"]);
        }

        // Validate password
        if (strlen(trim($_POST["password"])) < 6) {
            $password_error = "Password must be at least 6 characters.";
        } else {
            $password = trim($_POST["password"]);
        }

        // Store form data and errors in session
        $_SESSION['login_form_data'] = [
            'email' => $email
        ];

        $_SESSION['login_form_errors'] = [
            'email_error' => $email_error,
            'password_error' => $password_error,
            'general_error' => $general_error
        ];

        // If no errors, process login
        if (empty($email_error) && empty($password_error) && empty($general_error)) {
            // Check if user exists
            $check_user = $conn->prepare("SELECT username, email, password, role, status, phoneNumber, Date FROM signup WHERE email = ?");
            $check_user->bind_param("s", $email);
            $check_user->execute();
            $result = $check_user->get_result();
            
            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                
                // Verify password
                if (password_verify($password, $user['password'])) {
                    // Set session variables (admin_ prefix for MyProfile compatibility)
                    $_SESSION['admin_name'] = $user['username'];
                    $_SESSION['admin_email'] = $user['email'];
                    $_SESSION['admin_role'] = $user['role'];
                    $_SESSION['admin_status'] = $user['status'];
                    $_SESSION['admin_phone'] = $user['phoneNumber'];
                    $_SESSION['admin_date'] = $user['Date'];
                    
                    // Also set non-prefixed versions for backward compatibility
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['role'] = $user['role'];
                    
                        // Remember me: set/clear cookies for 30 days
                        if (isset($_POST['remember-me'])) {
                            $cookie_time = time() + (30 * 24 * 60 * 60); // 30 days
                            setcookie('remember_email', $email, $cookie_time, '/');
                            setcookie('remember_password', $password, $cookie_time, '/');
                        } else {
                            // Clear cookies if not selected
                            setcookie('remember_email', '', time() - 3600, '/');
                            setcookie('remember_password', '', time() - 3600, '/');
                        }
                    
                    // Clear form data
                    unset($_SESSION['login_form_data']);
                    unset($_SESSION['login_form_errors']);
                    
                    // Redirect to appropriate dashboard
                    if ($user['role'] === 'admin') {
                        header("Location: ../views/Admin.php");
                    } else {
                    	header("Location: ../../User/views/homepageUser.php");
                    }
                    exit();
                } else {
                    $_SESSION['login_error_message'] = "Invalid email or password.";
                    header("Location: ../views/loginPage.php");
                    exit();
                }
            } else {
                $_SESSION['login_error_message'] = "Invalid email or password.";
                header("Location: ../views/loginPage.php");
                exit();
            }
            $check_user->close();
        } else {
            // Redirect back to login page with validation errors
            header("Location: ../views/loginPage.php");
            exit();
        }
    }
}
?>