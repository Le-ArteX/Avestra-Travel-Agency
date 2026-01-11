<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Tours - Avestra Travel Agency</title>
    <link rel="stylesheet" href="../styleSheets/ManageTours.css">
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
                    <li><a href="ManageTours.php" class="active">Tours</a></li>
                    <li><a href="Reports.php">Reports</a></li>
                    <li><a href="Payments.php">Payments</a></li>
                    <li><a href="Settings.php">Settings</a></li>
                    <li><a href="MyProfile.php">My Profile</a></li>
                    <li><a href="homePage.php">Logout</a></li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
            <header class="admin-header">
                <h1>Manage Tours</h1>
            </header>

            <section class="admin-section">
                <div class="admin-card">
                    <div class="tour-actions">
                        <input type="text" class="tour-search" placeholder="Search tours...">
                        <button class="add-tour-btn">+ Add Tour</button>
                    </div>
                    <div class="tour-table-container">
                        <table class="tour-table">
                            <thead>
                                <tr>
                                    <th>Tour Name</th>
                                    <th>Destination</th>
                                    <th>Duration</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>City Explorer Tour</td>
                                    <td>New York</td>
                                    <td>3 Days</td>
                                    <td>$499</td>
                                    <td><span class="status active">Active</span></td>
                                    <td>
                                        <button class="edit-btn">Edit</button>
                                        <button class="delete-btn">Delete</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Beach Paradise Tour</td>
                                    <td>Maldives</td>
                                    <td>5 Days</td>
                                    <td>$899</td>
                                    <td><span class="status active">Active</span></td>
                                    <td>
                                        <button class="edit-btn">Edit</button>
                                        <button class="delete-btn">Delete</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
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
