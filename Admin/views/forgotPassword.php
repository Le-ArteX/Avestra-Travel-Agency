<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avestra Travel Agency</title>
    <link rel="stylesheet" href="../styleSheets/forgotPassword.css">
    <link rel="icon" href="../images/logo.png" type="image/png">
</head>

<body>
    <div class="forgot-container">
        <h2>Forgot Password</h2>
        <form class="forgot-form" action="forgotPassword.php" method="post">
            <div class="form-group">
                <input type="email" id="email" name="email" placeholder=" " required autocomplete="email">
                <label for="email">Enter your email address</label>
            </div>

            <div class="form-group">
                <input type="password" id="new-password" name="new-password" placeholder=" " required autocomplete="new-password">
                <label for="new-password">New Password</label>
            </div>

            <div class="form-group">
                <input type="password" id="confirm-password" name="confirm-password" placeholder=" " required autocomplete="new-password">
                <label for="confirm-password">Confirm New Password</label>
            </div>

            <button type="submit">Reset Password</button>
        </form>
        <div class="forgot-footer">
            Remembered your password? <a href="loginPage.php">Login</a>
        </div>
    </div>
     <script src="../js/theme.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            applyStoredTheme();
        });
    </script>
</html>