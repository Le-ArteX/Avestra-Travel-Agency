<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avestra Travel Agency</title>
    <link rel="stylesheet" href="../styleSheets/Signup.css">
    <link rel="icon" href="../images/logo.png" type="image/png">
</head>

<body>
    <div class="signup-container">
        <h2>Create Your Account</h2>
        <form class="signup-form" action="Signup.php" method="post">
            <div class="form-group">
                <input type="text" id="username" name="username" placeholder=" " required autocomplete="username">
                <label for="username">Full Name</label>
            </div>

            <div class="form-group">
                <input type="email" id="email" name="email" placeholder=" " required autocomplete="email">
                <label for="email">Email</label>
            </div>

            <div class="form-group">
                <input type="text" id="phoneNumber" name="phoneNumber" placeholder=" " required
                    autocomplete="phoneNumber">
                <label for="phoneNumber">Phone Number</label>
            </div>

            <div class="form-group select-group">
                <select id="role" name="role" required>
                    <option value="" disabled selected>Select account type</option>
                    <option value="customer">Customer</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <div class="form-group">
                <input type="password" id="password" name="password" placeholder=" " required
                    autocomplete="new-password">
                <label for="password">Password</label>
            </div>

            <div class="form-group">
                <input type="password" id="confirm-password" name="confirm-password" placeholder=" " required
                    autocomplete="new-password">
                <label for="confirm-password">Confirm Password</label>
            </div>

            <button type="submit">Sign Up</button>
        </form>
        <div class="signup-footer">
            Already have an account? <a href="loginPage.php">Sign In</a>
        </div>
    </div>

    <script src="../js/theme.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            applyStoredTheme();
        });
    </script>
</body>

</html>