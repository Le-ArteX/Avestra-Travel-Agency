<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Avestra Travel Agency</title>
    <link rel="stylesheet" href="../styleSheets/MyProfile.css">
    <link rel="icon" href="../images/logo.png" type="image/png">
</head>

<body>
    <div class="admin-container">

        <aside class="sidebar">
            <div style="padding: 24px 32px;">
                <div style="text-align: center; margin-bottom: 16px;">
                    <img src="../images/logo.png" alt="Avestra Logo" style="width: 60px; height: auto;">
                </div>
                <h2 class="sidebar-title">Admin Panel</h2>
            </div>
            <nav>
                <ul class="sidebar-menu">
                    <li><a href="Admin.php">Dashboard</a></li>
                    <li><a href="ManageUsers.php">Manage Users</a></li>
                    <li><a href="ManageBookings.php">Bookings</a></li>
                    <li><a href="ManageHotels.php">Hotels</a></li>
                    <li><a href="ManageTours.php">Tours</a></li>
                    <li><a href="Reports.php">Reports</a></li>
                    <li><a href="Payments.php">Payments</a></li>
                    <li><a href="Settings.php">Settings</a></li>
                    <li><a href="MyProfile.php" class="active">My Profile</a></li>
                    <li><a href="homePage.php">Logout</a></li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
            <header class="admin-header">
                <h1>My Profile</h1>
            </header>

            <section class="admin-section">
                <div class="admin-card">
                    <div class="profile-header">
                        <div class="profile-avatar">
                            <img src="../images/logo.png" alt="Profile Avatar" style="width: 100px; height: 100px; border-radius: 50%;">
                        </div>
                        <div class="profile-info">
                            <h2>Admin User</h2>
                            <p class="profile-role">Administrator</p>
                            <p class="profile-status">Active</p>
                        </div>
                    </div>
                </div>

                <div class="admin-card">
                    <h3>Profile Information</h3>
                    <form class="profile-form">
                        <div class="form-row">
                            <label for="full-name">Full Name:</label>
                            <input type="text" id="full-name" name="full-name" value="Admin User" placeholder="Enter full name">
                        </div>
                        <div class="form-row">
                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" value="admin@avestra.com" placeholder="Enter email">
                        </div>
                        <div class="form-row">
                            <label for="phone">Phone:</label>
                            <input type="tel" id="phone" name="phone" value="+1-800-123-4567" placeholder="Enter phone number">
                        </div>
                        <div class="form-row">
                            <label for="department">Department:</label>
                            <input type="text" id="department" name="department" value="Administration" placeholder="Enter department">
                        </div>
                        <div class="form-row">
                            <label for="joined-date">Joined Date:</label>
                            <input type="text" id="joined-date" name="joined-date" value="2024-01-15" disabled placeholder="Joined date">
                        </div>
                        <div class="form-row">
                            <button type="submit" class="update-profile-btn">Update Profile</button>
                        </div>
                    </form>
                </div>

                <div class="admin-card">
                    <h3>Change Password</h3>
                    <form class="profile-form">
                        <div class="form-row">
                            <label for="current-pwd">Current Password:</label>
                            <input type="password" id="current-pwd" name="current-pwd" placeholder="Enter current password">
                        </div>
                        <div class="form-row">
                            <label for="new-pwd">New Password:</label>
                            <input type="password" id="new-pwd" name="new-pwd" placeholder="Enter new password">
                        </div>
                        <div class="form-row">
                            <label for="confirm-pwd">Confirm Password:</label>
                            <input type="password" id="confirm-pwd" name="confirm-pwd" placeholder="Confirm new password">
                        </div>
                        <div class="form-row">
                            <button type="submit" class="update-profile-btn">Change Password</button>
                        </div>
                    </form>
                </div>
            </section>
        </main>
    </div>

    <script src="../js/theme.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            applyStoredTheme();
        });
    </script>
</body>

</html>
