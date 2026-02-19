<?php
include 'session_check.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Avestra Travel Agency</title>
    <link rel="stylesheet" href="../styleSheets/user.css">
    <link rel="stylesheet" href="../styleSheets/user_dashboard.css">
    <link rel="stylesheet" href="../styleSheets/footer.css">
    <link rel="icon" href="../images/logo.png" type="image/png">
</head>
<body>

<?php include 'nav.php'; ?>

<div class="dashboard-container">
    <h2>Welcome, <?= htmlspecialchars($_SESSION['username']); ?> 👋</h2>

    <div class="card-grid">
        <div class="card">
            <h3>🎫 Book Tickets</h3>
            <p>Flights, buses, and trains</p>
            <a href="start_Booking.php" class="btn">Book Now</a>
        </div>
        <div class="card">
            <h3>🏨 Hotels</h3>
            <p>Best hotels & resorts</p>
            <a href="find_Hotels.php" class="btn">Find Hotels</a>
        </div>
        <div class="card">
            <h3>🧳 Tour Packages</h3>
            <p>Curated travel experiences</p>
            <a href="explore_Tour_Packages.php" class="btn">Explore</a>
        </div>
        <div class="card">
            <h3>📋 Booking History</h3>
            <p>Check your previous bookings</p>
            <a href="bookingHistory.php" class="btn">View History</a>
        </div>
    </div>
</div>



</body>
<?php include 'footer.php'; ?>
</html>