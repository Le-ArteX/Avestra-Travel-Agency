<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payments - Avestra Travel Agency</title>
    <link rel="stylesheet" href="../styleSheets/Payment.css">
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
                    <li><a href="Payments.php" class="active">Payments</a></li>
                    <li><a href="Settings.php">Settings</a></li>
                    <li><a href="MyProfile.php">My Profile</a></li>
                    <li><a href="homePage.php">Logout</a></li>
                </ul>
            </nav>
        </aside>
        <main class="main-content">
            <header class="admin-header">
                <h1>Payments</h1>
            </header>
            <section class="admin-section">
                <!-- Record Payment -->
                <div class="admin-card">
                    <h3>Record Payment</h3>
                    <form class="payment-form">
                        <div class="payment-row">
                            <label for="payment-amount">Payment Amount:</label>
                            <input type="number" id="payment-amount" name="payment-amount" placeholder="Enter amount" required>
                        </div>
                        <div class="payment-row">
                            <label for="payment-method">Payment Method:</label>
                            <select id="payment-method" name="payment-method" required>
                                <option value="cash">Cash</option>
                                <option value="card">Card</option>
                                <option value="mobile-banking">Mobile Banking</option>
                            </select>
                        </div>
                        <div class="payment-row">
                            <label for="payment-date">Payment Date:</label>
                            <input type="date" id="payment-date" name="payment-date" required>
                        </div>
                        <div class="payment-row">
                            <label for="transaction-status">Transaction Status:</label>
                            <select id="transaction-status" name="transaction-status" required>
                                <option value="completed">Completed</option>
                                <option value="pending">Pending</option>
                                <option value="refunded">Refunded</option>
                            </select>
                        </div>
                        <div class="payment-row">
                            <button type="submit" class="save-payment-btn">Record Payment</button>
                        </div>
                    </form>
                </div>

                <!-- Payment History -->
                <div class="admin-card">
                    <h3>Payment History</h3>
                    <table class="payment-table">
                        <thead>
                            <tr>
                                <th>Payment ID</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Example Row -->
                            <tr>
                                <td>#1001</td>
                                <td>$500</td>
                                <td>Card</td>
                                <td>2023-10-01</td>
                                <td>Completed</td>
                                <td>
                                    <button class="action-btn refund-btn">Refund</button>
                                </td>
                            </tr>
                            <!-- Add dynamic rows here -->
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>
    <script src="../js/payment.js"></script>
</body>
</html>
