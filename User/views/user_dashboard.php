<?php
include 'session_check.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard | Avestra Travel</title>
    <link rel="stylesheet" href="../styleSheets/user.css">
    <link rel="icon" href="../images/logo.png" type="image/png">
</head>
<body>

<?php include 'nav.php'; ?>

<div class="dashboard-container">
    <h2>Welcome, <?= htmlspecialchars($_SESSION['username']); ?> 👋</h2>
    <p>Email: <?= htmlspecialchars($_SESSION['email']); ?></p>
    <p>Role: <?= ucfirst($_SESSION['role']); ?></p>

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

<style>
.dashboard-container {
    max-width: 1100px;
    margin: 20px auto;
    padding: 0 20px;
    font-family: Arial, sans-serif;
}

.card-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.card {
    background-color: #f0f8ff;
    border: 1px solid #ddd;
    border-radius: 10px;
    padding: 20px;
    text-align: center;
    transition: transform 0.2s, box-shadow 0.2s;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0px 5px 15px rgba(0,0,0,0.2);
}

.card h3 { margin-bottom: 10px; }
.card p { margin-bottom: 15px; color: #333; }

.btn {
    display: inline-block;
    padding: 8px 15px;
    background-color: #00ADB5;
    color: #fff;
    text-decoration: none;
    border-radius: 5px;
    transition: background 0.3s;
}

.btn:hover { background-color: #007a80; }
</style>

</body>
</html>