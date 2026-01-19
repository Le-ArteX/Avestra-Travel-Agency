<?php
// Show customer payment history from database
include('../database/dbconnection.php');
include('../database/PaymentsData.php');
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';
$err = isset($_GET['err']) ? $_GET['err'] : '';
if (!isset($msg)) $msg = '';
if (!isset($err)) $err = '';
$search = isset($_POST['search']) ? trim($_POST['search']) : '';
if ($search !== '') {
    $payments = searchPayments($conn, $search);
} else {
    $payments = getAllPayments($conn);
}
?>
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
                <img src="../images/logo.png" alt="Avestra Logo" style="width: 60px;">
            </div>
            <h2 class="sidebar-title">Admin Panel</h2>
        </div>
        <nav>
            <ul class="sidebar-menu">
                <li><a href="Admin.php">Dashboard</a></li>
                <li><a href="ManageUsers.php">Manage Users</a></li>
                <li><a href="ManageTickets.php">Tickets</a></li>
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

            <div class="admin-card">
                <h3>Customer Payment History</h3>
                <form method="post" class="payment-search-bar" style="margin-bottom: 18px; display: flex; gap: 12px; align-items: center;">
                    <input type="text" name="search" value="<?= htmlspecialchars($search ?? '') ?>" placeholder="Search by Booking ID or Email" class="search-input" style="padding: 10px 16px; border-radius: 7px; border: 1px solid #dbe6f3; font-size: 1em; min-width: 220px;">
                    <button type="submit" class="search-btn" style="background: #0ecb81; color: #fff; border: none; border-radius: 7px; padding: 10px 28px; font-weight: 600; font-size: 1em; cursor: pointer;">Search</button>
                    <?php if ($search): ?>
                        <a href="Payments.php" class="reset-btn" style="background: #f87171; color: #fff; border: none; border-radius: 7px; padding: 10px 18px; font-weight: 600; font-size: 1em; text-decoration: none; margin-left: 4px;">Reset</a>
                    <?php endif; ?>
                </form>

                <table class="payment-table">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>User Email</th>
                            <th>Amount</th>
                            <th>Payment Method</th>
                            <th>Transaction ID</th>
                            <th>Status</th>
                            <th>Payment Date</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($payments)): ?>
                        <tr>
                            <td colspan="7" style="text-align:center;">No payments found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($payments as $p): ?>
                            <tr>
                                <td>#<?= htmlspecialchars($p['booking_id']) ?></td>
                                <td><?= htmlspecialchars($p['user_email']) ?></td>
                                <td><?= number_format($p['amount'], 2) ?></td>
                                <td><?= htmlspecialchars($p['payment_method']) ?></td>
                                <td><?= htmlspecialchars($p['transaction_id']) ?></td>
                                <td><?= htmlspecialchars(ucfirst($p['payment_status'])) ?></td>
                                <td><?= htmlspecialchars($p['payment_date']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>

            </div>
        </section>
    </main>
</div>
</div>
</body>
</html>
