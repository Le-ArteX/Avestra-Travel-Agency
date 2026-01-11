<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avestra Travel Agency</title>
    <link rel="stylesheet" href="../styleSheets/loginPage.css">
    <link rel="icon" href="../images/logo.png" type="image/png">
</head>

<body>
    <div class="login-container">
        <div class="logo-container">
            <a href="homePage.php">
                <img src="../images/logo.png" alt="Avestra Travel Agency Logo" width="100" height="132">
            </a>
        </div>
        <h2>Login to Your Account</h2>
        <form class="login-form" action="admin.php" method="post">
            <div class="form-group">
                <input type="text" id="username" name="username" placeholder=" " required autocomplete="username">
                <label for="username">Username or Email</label>
            </div>

            <div class="form-group">
                <input type="password" id="password" name="password" placeholder=" " required autocomplete="current-password">
                <label for="password">Password</label>
            </div>
             <div class="forgot-link">
                <a href="forgotPassword.php">Forgot password?</a>
            </div>
            <button type="submit">Login</button>
        </form>
        <div class="login-footer">
            Don't have an account? <a href="Signup.php">Sign up</a>
        </div>
    </div>

    <script src="../js/theme.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            applyStoredTheme();
        });
    </script>
</body>

</html>