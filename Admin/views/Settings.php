<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Avestra Travel Agency</title>
    <link rel="stylesheet" href="../styleSheets/Settings.css">
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
                    <li><a href="Settings.php" class="active">Settings</a></li>
                    <li><a href="MyProfile.php">My Profile</a></li>
                    <li><a href="homePage.php">Logout</a></li>
                </ul>
            </nav>
        </aside>
        <main class="main-content">
            <header class="admin-header">
                <h1>Settings</h1>
            </header>
            <section class="admin-section">
                <!-- General Settings -->
                <div class="admin-card">
                    <h3>General Settings</h3>
                    <form class="settings-form">
                        <div class="settings-row">
                            <label for="site-theme">Site Theme:</label>
                            <select id="site-theme" name="site-theme">
                                <option value="light">Light</option>
                                <option value="dark">Dark</option>
                            </select>
                        </div>
                        <div class="settings-row">
                            <label for="email-notifications">Email Notifications:</label>
                            <select id="email-notifications" name="email-notifications">
                                <option value="enabled">Enabled</option>
                                <option value="disabled">Disabled</option>
                            </select>
                        </div>
                        <div class="settings-row">
                            <label for="language">Language:</label>
                            <select id="language" name="language">
                                <option value="en">English</option>
                                <option value="es">Spanish</option>
                                <option value="fr">French</option>
                            </select>
                        </div>
                        <div class="settings-row">
                            <label for="timezone">Timezone:</label>
                            <select id="timezone" name="timezone">
                                <option value="UTC">UTC</option>
                                <option value="EST">EST</option>
                                <option value="CST">CST</option>
                                <option value="PST">PST</option>
                            </select>
                        </div>
                        <div class="settings-row">
                            <label for="privacy">Privacy Mode:</label>
                            <select id="privacy" name="privacy">
                                <option value="public">Public</option>
                                <option value="private">Private</option>
                            </select>
                        </div>
                        <div class="settings-row">
                            <label for="maintenance">Maintenance Mode:</label>
                            <select id="maintenance" name="maintenance">
                                <option value="off">Off</option>
                                <option value="on">On</option>
                            </select>
                        </div>
                        <div class="settings-row">
                            <button type="submit" class="save-settings-btn">Save Settings</button>
                        </div>
                    </form>
                </div>

                <!-- Profile Settings -->
                <div class="admin-card">
                    <h3>Profile Settings</h3>
                    <form class="settings-form">
                        <div class="settings-row">
                            <label for="profile-name">Name:</label>
                            <input type="text" id="profile-name" name="profile-name" placeholder="Enter your name">
                        </div>
                        <div class="settings-row">
                            <label for="profile-email">Email:</label>
                            <input type="email" id="profile-email" name="profile-email" placeholder="Enter your email">
                        </div>
                        <div class="settings-row">
                            <button type="submit" class="save-settings-btn">Update Profile</button>
                        </div>
                    </form>
                </div>

                <!-- Password Settings -->
                <div class="admin-card">
                    <h3>Change Password</h3>
                    <form class="settings-form">
                        <div class="settings-row">
                            <label for="current-password">Current Password:</label>
                            <input type="password" id="current-password" name="current-password" placeholder="Enter current password">
                        </div>
                        <div class="settings-row">
                            <label for="new-password">New Password:</label>
                            <input type="password" id="new-password" name="new-password" placeholder="Enter new password">
                        </div>
                        <div class="settings-row">
                            <label for="confirm-password">Confirm Password:</label>
                            <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm new password">
                        </div>
                        <div class="settings-row">
                            <button type="submit" class="save-settings-btn">Change Password</button>
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
