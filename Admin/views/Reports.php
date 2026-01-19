<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - Avestra Travel Agency</title>
    <link rel="stylesheet" href="../styleSheets/Reports.css">
    <link rel="icon" href="../images/logo.png" type="image/png">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                     <li><a href="ManageTickets.php">Tickets</a></li>
                    <li><a href="ManageHotels.php">Hotels</a></li>
                    <li><a href="ManageTours.php">Tours</a></li>
                    <li><a href="Reports.php" class="active">Reports</a></li>
                    <li><a href="Payments.php">Payments</a></li>
                    <li><a href="Settings.php">Settings</a></li>
                    <li><a href="MyProfile.php">My Profile</a></li>
                    <li><a href="homePage.php">Logout</a></li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
            <header class="admin-header">
                <h1>Reports</h1>
                <div class="role-warning">
                    <span>Only authorized users can view sensitive business information.</span>
                </div>
            </header>
            <section class="admin-section">
                <div class="admin-card">
                    <div class="report-actions">
                        <form class="date-filter-form" method="get">
                            <label for="date-filter">Filter by:</label>
                            <select id="date-filter" name="date-filter">
                                <option value="daily">Daily</option>
                                <option value="monthly">Monthly</option>
                                <option value="custom">Custom</option>
                            </select>
                            <input type="date" id="start-date" name="start-date">
                            <input type="date" id="end-date" name="end-date">
                            <button type="submit" class="filter-btn">Apply</button>
                        </form>
                        <div class="export-btns">
                            <button class="export-btn pdf" type="button">Export PDF</button>
                        </div>
                    </div>
                    <div class="report-summary">
                        <div class="summary-card">
                            <div class="summary-title">Total Bookings</div>
                            <div class="summary-value">320</div>
                        </div>
                        <div class="summary-card">
                            <div class="summary-title">Confirmed</div>
                            <div class="summary-value confirmed">250</div>
                        </div>
                        <div class="summary-card">
                            <div class="summary-title">Cancelled</div>
                            <div class="summary-value cancelled">40</div>
                        </div>
                        <div class="summary-card">
                            <div class="summary-title">Pending</div>
                            <div class="summary-value pending">30</div>
                        </div>
                        <div class="summary-card">
                            <div class="summary-title">Total Revenue</div>
                            <div class="summary-value revenue">$45,000</div>
                        </div>
                    </div>
                    <div class="charts-row">
                        <div class="chart-card">
                            <div class="chart-title">Bookings Trend</div>
                            <canvas id="bookingsChart"></canvas>
                        </div>
                        <div class="chart-card">
                            <div class="chart-title">Revenue Trend</div>
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>
                    <div class="performance-row">
                        <div class="performance-card">
                            <div class="performance-title">Top Customers</div>
                            <table class="performance-table">
                                <thead>
                                    <tr>
                                        <th>Customer</th>
                                        <th>Bookings</th>
                                        <th>Revenue</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Jane Doe</td>
                                        <td>12</td>
                                        <td>$2,400</td>
                                    </tr>
                                    <tr>
                                        <td>John Smith</td>
                                        <td>10</td>
                                        <td>$2,000</td>
                                    </tr>
                                    <tr>
                                        <td>Emily Clark</td>
                                        <td>8</td>
                                        <td>$1,600</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
    <script src="../js/theme.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            applyStoredTheme();

            // Bookings Trend Chart
            const bookingsCtx = document.getElementById('bookingsChart').getContext('2d');
            new Chart(bookingsCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Bookings',
                        data: [25, 30, 28, 35, 40, 38, 45, 50, 48, 52, 55, 60],
                        backgroundColor: 'rgba(79, 195, 247, 0.2)',
                        borderColor: '#4fc3f7',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: '#22304a'
                            },
                            grid: {
                                color: '#e9f3fa'
                            }
                        },
                        x: {
                            ticks: {
                                color: '#22304a'
                            },
                            grid: {
                                color: '#e9f3fa'
                            }
                        }
                    }
                }
            });

            // Revenue Trend Chart
            const revenueCtx = document.getElementById('revenueChart').getContext('2d');
            new Chart(revenueCtx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Revenue ($)',
                        data: [2500, 3000, 2800, 3500, 4000, 3800, 4500, 5000, 4800, 5200, 5500, 6000],
                        backgroundColor: '#4fc3f7',
                        borderColor: '#22304a',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: '#22304a',
                                callback: function(value) {
                                    return '$' + value;
                                }
                            },
                            grid: {
                                color: '#e9f3fa'
                            }
                        },
                        x: {
                            ticks: {
                                color: '#22304a'
                            },
                            grid: {
                                color: '#e9f3fa'
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>
