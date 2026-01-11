<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings - Avestra Travel Agency</title>
    <link rel="stylesheet" href="../styleSheets/ManageBookings.css">
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
                    <li><a href="ManageBookings.php" class="active">Bookings</a></li>
                    <li><a href="ManageHotels.html">Hotels</a></li>
                    <li><a href="ManageTours.html">Tours</a></li>
                    <li><a href="Reports.html">Reports</a></li>
                    <li><a href="Payments.html">Payments</a></li>
                    <li><a href="Settings.html">Settings</a></li>
                    <li><a href="homePage.php">Logout</a></li>
                </ul>
            </nav>
        </aside>
        <main class="main-content">
            <header class="admin-header">
                <h1>Manage Bookings</h1>
            </header>
            <section class="admin-section">
                <div class="admin-card">
                    <div class="section-actions">
                        <input type="text" class="section-search" placeholder="Search bookings...">
                        <button class="add-section-btn">+ Add Booking</button>
                    </div>
                    <div class="section-table-container">
                        <table class="section-table">
                            <thead>
                                <tr>
                                    <th>Booking ID</th>
                                    <th>User</th>
                                    <th>Hotel/Tour</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>#B001</td>
                                    <td>Jane Doe</td>
                                    <td>Grand Hotel</td>
                                    <td>2024-06-01</td>
                                    <td><span class="status active">Confirmed</span></td>
                                    <td>
                                        <button class="edit-btn">Edit</button>
                                        <button class="delete-btn">Delete</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#B002</td>
                                    <td>John Smith</td>
                                    <td>City Tour</td>
                                    <td>2024-06-03</td>
                                    <td><span class="status inactive">Pending</span></td>
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
