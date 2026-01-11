<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Hotels - Avestra Travel Agency</title>
    <link rel="stylesheet" href="../styleSheets/ManageHotels.css">
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
                    <li><a href="ManageHotels.php" class="active">Hotels</a></li>
                    <li><a href="ManageTours.php">Tours</a></li>
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
                <h1>Manage Hotels</h1>
            </header>
            <section class="admin-section">
                <div class="admin-card">
                    <div class="section-actions">
                        <input type="text" class="section-search" placeholder="Search hotels...">
                        <button class="add-section-btn">+ Add Hotel</button>
                    </div>
                    <div class="section-table-container">
                        <table class="section-table">
                            <thead>
                                <tr>
                                    <th>Hotel Name</th>
                                    <th>Location</th>
                                    <th>Rating</th>
                                    <th>Rooms</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Grand Hotel</td>
                                    <td>New York, USA</td>
                                    <td>★★★★★</td>
                                    <td>150</td>
                                    <td><span class="status active">Active</span></td>
                                    <td>
                                        <button class="edit-btn">Edit</button>
                                        <button class="delete-btn">Delete</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Sunset Resort</td>
                                    <td>Malibu, USA</td>
                                    <td>★★★★</td>
                                    <td>80</td>
                                    <td><span class="status active">Active</span></td>
                                    <td>
                                        <button class="edit-btn">Edit</button>
                                        <button class="delete-btn">Delete</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Mountain Lodge</td>
                                    <td>Denver, USA</td>
                                    <td>★★★</td>
                                    <td>45</td>
                                    <td><span class="status inactive">Inactive</span></td>
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
