<?php
include('dark_mode.php');
include('../database/dbconnection.php');

// Security check: Only admins can view this page
if (!isset($_SESSION['admin_email'])) {
    if (isset($_SESSION['email']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
        // Fallback for older sessions
    } else {
        header("Location: loginPage.php");
        exit();
    }
}

// Fetch some basic statistics for the report dashboard
$stats = [
    'total_users' => 0,
    'total_revenue' => 0,
    'total_bookings' => 0,
    'total_tickets' => 0
];

// 1. Total active users (Customers and Admins)
$userQuery = $conn->query("SELECT COUNT(*) as count FROM customer WHERE status='Active'");
if ($userQuery) $stats['total_users'] += $userQuery->fetch_assoc()['count'];

$adminQuery = $conn->query("SELECT COUNT(*) as count FROM admin WHERE status='Active'");
if ($adminQuery) $stats['total_users'] += $adminQuery->fetch_assoc()['count'];

// 2. Total revenue & bookings from payments table
$revenueQuery = $conn->query("SELECT SUM(amount) as total_revenue, COUNT(*) as total_bookings FROM payments WHERE payment_status='completed'");
if ($revenueQuery) {
    $row = $revenueQuery->fetch_assoc();
    $stats['total_revenue'] = $row['total_revenue'] ?? 0;
    $stats['total_bookings'] = $row['total_bookings'] ?? 0;
}

// 3. Total active ticket routes
$ticketsQuery = $conn->query("SELECT COUNT(*) as count FROM tickets WHERE status='active'");
if ($ticketsQuery) $stats['total_tickets'] = $ticketsQuery->fetch_assoc()['count'];

// 4. Monthly Revenue Data for Chart/Table mapping
$monthlyRevenue = [];
$monthlyQuery = $conn->query("
    SELECT DATE_FORMAT(payment_date, '%M %Y') as month, SUM(amount) as revenue 
    FROM payments 
    WHERE payment_status='completed' 
    GROUP BY DATE_FORMAT(payment_date, '%M %Y') 
    ORDER BY MIN(payment_date) DESC
    LIMIT 6
");
if ($monthlyQuery) {
    while($row = $monthlyQuery->fetch_assoc()) {
        $monthlyRevenue[] = $row;
    }
}

// 5. Recent Bookings (Last 5)
$recentBookings = [];
$recentBookingQuery = $conn->query("SELECT booking_id, user_email, amount, payment_date, payment_status FROM payments ORDER BY payment_date DESC LIMIT 5");
if ($recentBookingQuery) {
    while($row = $recentBookingQuery->fetch_assoc()) {
        $recentBookings[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports &amp; Analytics Dashboard</title>
    <link rel="stylesheet" href="../styleSheets/Reports.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../styleSheets/dark-mode.css?v=<?php echo time(); ?>">
    <script>
        localStorage.setItem('theme', '<?= $current_theme ?>');
        document.documentElement.setAttribute('data-theme', '<?= $current_theme ?>');
    </script>
    <link rel="icon" href="../images/logo.png" type="image/png">
</head>
<body class="<?= $is_dark ? 'dark-mode' : '' ?>">
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
                    <li><a href="Admin.php">Dashboard</a></li>
                    <li><a href="ManageUsers.php">Manage Users</a></li>
                    <li><a href="ManageTickets.php">Tickets</a></li>
                    <li><a href="ManageHotels.php">Hotels</a></li>
                    <li><a href="ManageTours.php">Tours</a></li>
                    <li><a href="Payments.php">Payments</a></li>
                    <li><a href="Reports.php" class="active">Reports</a></li>
                    <li><a href="Settings.php">Settings</a></li>
                    <li><a href="MyProfile.php">My Profile</a></li>
                    <li><a href="homePage.php">Logout</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content Area -->
        <main class="main-content">
            <header class="admin-header">
                <h1>Reports & Analytics</h1>
                <p class="subtitle">Overview of your travel agency's performance</p>
            </header>
            
            <section class="admin-section">
                <!-- Summary KPI Cards -->
                <div class="report-stats">
                    <div class="stat-card">
                        <div class="stat-icon rev-icon">💰</div>
                        <div class="stat-info">
                            <span class="stat-number">$<?= number_format($stats['total_revenue'], 2) ?></span>
                            <span class="stat-label">Total Revenue</span>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon bk-icon">🎫</div>
                        <div class="stat-info">
                            <span class="stat-number"><?= number_format($stats['total_bookings']) ?></span>
                            <span class="stat-label">Successful Bookings</span>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon us-icon">👥</div>
                        <div class="stat-info">
                            <span class="stat-number"><?= number_format($stats['total_users']) ?></span>
                            <span class="stat-label">Active Users</span>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon tk-icon">🛣️</div>
                        <div class="stat-info">
                            <span class="stat-number"><?= number_format($stats['total_tickets']) ?></span>
                            <span class="stat-label">Active Ticket Routes</span>
                        </div>
                    </div>
                </div>

                <div class="report-grid">
                    <!-- Monthly Revenue Table -->
                    <div class="report-card">
                        <h3>Monthly Revenue (Past 6 Months)</h3>
                        <table class="report-table">
                            <thead>
                                <tr>
                                    <th>Month</th>
                                    <th style="text-align: right;">Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($monthlyRevenue)): ?>
                                    <tr><td colspan="2" style="text-align: center; color: #64748b;">No revenue data available.</td></tr>
                                <?php else: ?>
                                    <?php foreach($monthlyRevenue as $m): ?>
                                    <tr>
                                        <td><strong><?= htmlspecialchars($m['month']) ?></strong></td>
                                        <td style="text-align: right; color: #0f172a; font-weight: 600;">$<?= number_format($m['revenue'], 2) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Recent Transactions -->
                    <div class="report-card">
                        <h3>Recent Transactions</h3>
                        <table class="report-table">
                            <thead>
                                <tr>
                                    <th>Booking ID</th>
                                    <th>Date</th>
                                    <th style="text-align: right;">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($recentBookings)): ?>
                                    <tr><td colspan="3" style="text-align: center; color: #64748b;">No recent transactions.</td></tr>
                                <?php else: ?>
                                    <?php foreach($recentBookings as $b): ?>
                                    <tr>
                                        <td style="color: #3b82f6; font-weight: 500;">#<?= htmlspecialchars($b['booking_id']) ?></td>
                                        <td style="font-size: 0.9em; color: #64748b;"><?= date('M d, Y', strtotime($b['payment_date'])) ?></td>
                                        <td style="text-align: right; font-weight: 600;">$<?= number_format($b['amount'], 2) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </section>
        </main>
    </div>

    <!-- Theme Handling Scripts -->
    <script src="../js/theme.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            applyStoredTheme();
        });
    </script>
</body>
</html>
