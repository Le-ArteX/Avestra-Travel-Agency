<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<div class="navbar">
    <div class="nav-left">
        <img src="../images/logo.png" alt="Logo" height="35">
        <span>Avestra Travel</span>
    </div>

    <div class="nav-right">
        <a href="user_homePage.php">Home</a>
        <a href="user_dashboard.php">Dashboard</a>
        <a href="start_Booking.php">Tickets</a>
        <a href="find_Hotels.php">Hotels</a>
        <a href="explore_Tour_Packages.php">Tours</a>
        <a href="bookingHistory.php">Bookings</a>
        <a href="profile.php">Profile</a>
        <a href="logout.php" class="logout">Logout</a>
    </div>
</div>