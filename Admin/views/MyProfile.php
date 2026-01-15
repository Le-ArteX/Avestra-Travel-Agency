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
    <?php include('../controller/MyProfileController.php'); ?>
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
                <?php if (!empty($error_message)): ?>
                    <div style="padding:12px; background:#ffe0e0; color:#c62828; border-radius:8px; margin-bottom:16px;">
                        <?php echo htmlspecialchars($error_message); ?>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($success_message)): ?>
                    <div style="padding:12px; background:#d0f8e8; color:#2e7d32; border-radius:8px; margin-bottom:16px;">
                        <?php echo htmlspecialchars($success_message); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($is_admin_logged_in): ?>
                <div class="admin-card">
                    <div class="profile-header">
                        <div class="profile-avatar">
                            <img id="profileImagePreview" src="../images/logo.png" alt="Profile Avatar"
                                class="profile-image-circle">
                        </div>
                        <div class="profile-info">
                            <h2 id="profileName"><?php echo htmlspecialchars($admin_name); ?></h2>
                            <p class="profile-role"><?php echo htmlspecialchars($admin_role); ?></p>
                            <p class="profile-status"><?php echo htmlspecialchars($admin_status); ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="admin-card">
                    <h3>Profile Information</h3>
                    <form class="profile-form" enctype="multipart/form-data" id="profileForm">
                        <div class="form-row profile-image-upload-row">
                            <div class="profile-image-upload-container">
                                <img id="profileImagePreview" src="../images/logo.png" alt="Profile Avatar"
                                    class="profile-image-circle">
                                <input type="file" id="profile-image" name="profile-image" accept="image/*"
                                    class="profile-image-input">
                                <span class="profile-image-upload-icon">&#128247;</span>
                            </div>
                        </div>
                        <div class="form-row">
                            <label for="full-name">Full Name:</label>
                            <input type="text" id="full-name" name="full-name" value="<?php echo htmlspecialchars($admin_name); ?>"
                                placeholder="Enter full name" readonly>
                        </div>
                        <div class="form-row">
                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($admin_email); ?>"
                                placeholder="Enter email" readonly>
                        </div>
                        <div class="form-row">
                            <label for="phone">Mobile Number:</label>
                            <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($admin_phone); ?>"
                                placeholder="Enter mobile number" readonly>
                        </div>
                        <div class="form-row">
                            <label for="department">Department:</label>
                            <input type="text" id="department" name="department" value="<?php echo htmlspecialchars($admin_role); ?>"
                                placeholder="Enter department">
                        </div>
                        <div class="form-row">
                            <label for="joined-date">Joined Date:</label>
                            <input type="text" id="joined-date" name="joined-date" value="<?php echo $admin_date; ?>" disabled
                                placeholder="Joined date">
                        </div>
                        <div class="form-row">
                            <label for="profile-password">Password:</label>
                            <input type="password" id="profile-password" name="profile-password" value="********"
                                placeholder="Enter password" disabled>
                            <small style="color: #888;">To change your password, use the section below.</small>
                        </div>
                        <div class="form-row">
                            <button type="submit" class="update-profile-btn">Update Profile</button>
                        </div>
                    </form>
                </div>
                <?php else: ?>
                <div class="admin-card" style="text-align: center; padding: 40px;">
                    <p style="color: #c62828; font-size: 1.1em;">Please log in with an active account to view your profile.</p>
                </div>
                <?php endif; ?>
            </section>
        </main>
    </div>
    <script>

        document.getElementById('profile-image').addEventListener('change', function (event) {
            const [file] = event.target.files;
            if (file) {
                document.querySelector('.profile-image-upload-container #profileImagePreview').src = URL.createObjectURL(file);
            }
        });

        document.querySelector('.profile-image-upload-icon').addEventListener('click', function () {
            document.getElementById('profile-image').click();
        });

    </script>
    <script src="../js/theme.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            applyStoredTheme();
        });
    </script>
</body>

</html>
