<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Avestra Travel Agency</title>
    <link rel="stylesheet" href="../styleSheets/Admin.css">
    <link rel="icon" href="../images/logo.png" type="image/png">
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <div style="padding: 24px 32px;">
                <div style="text-align: center; margin-bottom: 16px;">
                    <img src="../images/logo.png" alt="Avestra Logo" style="width: 60px; height: auto;">
                </div>
                <h2 class="sidebar-title">Admin Panel</h2>
            </div>
            <nav>
                <ul class="sidebar-menu">
                    <li><a href="Admin.php" class="active">Dashboard</a></li>
                    <li><a href="ManageUsers.php">Manage Users</a></li>
                    <li><a href="ManageBookings.html">Bookings</a></li>
                    <li><a href="ManageHotels.html">Hotels</a></li>
                    <li><a href="ManageTours.html">Tours</a></li>
                    <li><a href="Reports.html">Reports</a></li>
                    <li><a href="Payments.html">Payments</a></li>
                    <li><a href="Settings.html">Settings</a></li>
                    <li><a href="homePage.php">Logout</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content Area -->
        <main class="main-content">
            <header class="admin-header">
                <h1>Welcome to the Admin Dashboard</h1>
                <p>Overview of your travel agency management system</p>
            </header>
            
            <section class="admin-section">
                <div class="admin-card">
                    <h3>Statistics Overview</h3>
                    <div class="admin-stats">
                        <div class="stat-box">
                            <span class="stat-number">120</span>
                            <span class="stat-label">Bookings</span>
                        </div>
                        <div class="stat-box">
                            <span class="stat-number">45</span>
                            <span class="stat-label">Payments</span>
                        </div>
                        <div class="stat-box">
                            <span class="stat-number">8</span>
                            <span class="stat-label">Tours</span>
                        </div>
                        <div class="stat-box">
                            <span class="stat-number">3</span>
                            <span class="stat-label">Hotels</span>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script src="../js/theme.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            applyStoredTheme();
        });
    </script>
</body>
</html>
