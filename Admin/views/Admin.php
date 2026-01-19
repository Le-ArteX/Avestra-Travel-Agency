<?php
session_start();
include('../database/dbconnection.php');


$activeToursCount = isset($tours) ? getActiveToursCount($tours) : 0;


include('../database/HotelsData.php');


function getActiveHotelsCount(array $hotels): int {
    return count(array_filter($hotels, function ($hotel) {
        return isset($hotel['status']) && strcasecmp($hotel['status'], 'Active') === 0;
    }));
}


function getAvailableHotelsCount(array $hotels): int {
    return count($hotels);
}


$activeHotelsCount = getActiveHotelsCount($hotels);
$availableHotelsCount = getAvailableHotelsCount($hotels);


$acSeats = 0;
$nonAcSeats = 0;
$totalSeats = 0;
$sql = "SELECT bus_class, seat_count FROM tickets WHERE ticket_type='Bus' AND status='active'";
$result = $conn->query($sql);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        if (strcasecmp($row['bus_class'], 'AC') === 0) {
            $acSeats += (int)$row['seat_count'];
        } elseif (strcasecmp($row['bus_class'], 'Non-AC') === 0) {
            $nonAcSeats += (int)$row['seat_count'];
        }
    }
    $totalSeats = $acSeats + $nonAcSeats;
}


$message_option = $_SESSION['settings']['message_option'] ?? 'enabled';
?>
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
                    <li><a href="ManageTickets.php">Tickets</a></li>
                    <li><a href="ManageHotels.php">Hotels</a></li>
                    <li><a href="ManageTours.php">Tours</a></li>
                    <li><a href="Reports.php">Reports</a></li>
                    <li><a href="Payments.php">Payments</a></li>
                    <li><a href="Settings.php">Settings</a></li>
                    <li><a href="MyProfile.php">My Profile</a></li>
                    <li><a href="homePage.php">Logout</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content Area -->
        <main class="main-content">
            <header class="admin-header">
                <h1>Welcome to the Admin Dashboard</h1>
            </header>
                        
            <section class="admin-section">
                <div class="admin-card">
                    <h3>Statistics Overview</h3>
                    <div class="admin-stats">
                        <div class="stat-box">
                            <span class="stat-number"><?php echo $acSeats; ?></span>
                            <span class="stat-label">Available AC Seats</span>
                        </div>
                        <div class="stat-box">
                            <span class="stat-number"><?php echo $nonAcSeats; ?></span>
                            <span class="stat-label">Available Non-AC Seats</span>
                        </div>
                        <div class="stat-box">
                            <span class="stat-number"><?php echo $totalSeats; ?></span>
                            <span class="stat-label">Total Available Seats</span>
                        </div>
                        <div class="stat-box">
                            <span class="stat-number"><?php echo $activeToursCount; ?></span>
                            <span class="stat-label">Active Tours</span>
                        </div>
                        <div class="stat-box">
                            <span class="stat-number"><?php echo $activeHotelsCount; ?></span>
                            <span class="stat-label">Active Hotels</span>
                        </div>
                    </div>
                </div>
            </section>

            
            <?php if ($message_option === 'enabled'): ?>
            <section class="admin-section">
                <div class="admin-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:25px; padding: 20px 20px 0;">
                        <div>
                            <h3 style="margin:0; font-size: 24px; font-weight: 600;">📩 Recent Contact Messages</h3>
                            <p style="margin: 5px 0 0; opacity: 0.9; font-size: 14px;">Latest messages from customers</p>
                        </div>
                        <a href="ContactMessages.php" style="background: rgba(255,255,255,0.2); padding:10px 20px; text-decoration:none; color: white; border-radius: 25px; font-weight: 500; transition: all 0.3s; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);" onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">View All →</a>
                    </div>
                    
                    <?php
               
                    $sql = "SELECT id, name, email, message, date FROM contact_messages ORDER BY date DESC LIMIT 4";
                    $result = $conn->query($sql);
                    $recent_messages = [];
                    while ($row = $result->fetch_assoc()) {
                        $recent_messages[] = $row;
                    }
                    ?>
                    
                    <div style="padding: 0 20px 20px;">
                        <?php if (!empty($recent_messages)): ?>
                            <?php foreach ($recent_messages as $index => $msg): ?>
                                <div style="background: rgba(255,255,255,0.95); border-radius: 12px; padding: 18px; margin-bottom: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(0,0,0,0.15)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(0,0,0,0.1)'">
                                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 10px;">
                                        <div style="flex: 1;">
                                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 5px;">
                                                <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 16px;">
                                                    <?php echo strtoupper(substr($msg['name'], 0, 1)); ?>
                                                </div>
                                                <div>
                                                    <h4 style="margin: 0; color: #2d3748; font-size: 16px; font-weight: 600;"><?php echo htmlspecialchars($msg['name']); ?></h4>
                                                    <p style="margin: 2px 0 0; color: #718096; font-size: 13px;">✉️ <?php echo htmlspecialchars($msg['email']); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div style="text-align: right;">
                                            <span style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 500;">
                                                🕐 <?php echo date('M d, h:i A', strtotime($msg['date'])); ?>
                                            </span>
                                        </div>
                                    </div>
                                    <p style="margin: 0; color: #4a5568; font-size: 14px; line-height: 1.6; padding-left: 50px;">
                                        💬 <?php echo htmlspecialchars(substr($msg['message'], 0, 80)) . (strlen($msg['message']) > 80 ? '...' : ''); ?>
                                    </p>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div style="background: rgba(255,255,255,0.95); border-radius: 12px; padding: 40px; text-align: center;">
                                <div style="font-size: 48px; margin-bottom: 15px;">📭</div>
                                <p style="margin: 0; color: #4a5568; font-size: 16px;">No messages yet</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
            <?php endif; ?>
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
</html>