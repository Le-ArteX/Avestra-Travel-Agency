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
    <link rel="stylesheet" href="../node_modules/@fortawesome/fontawesome-free/css/all.min.css"/>
    <style>
        body {
            background-color: #f7fafc;
        }
        .dash-hero {
            background: linear-gradient(135deg, #1A365D 0%, #3182CE 100%);
            color: white;
            padding: 60px 20px;
            text-align: center;
            margin-bottom: 40px;
        }
        .dash-hero-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .dash-hero-title span {
            color: #ecc94b;
        }
        .dash-hero-sub {
            font-size: 1.1rem;
            opacity: 0.9;
        }
        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto 80px;
            padding: 0 20px;
        }
        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 24px;
        }

        /* Animation Keyframes */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dash-card {
            background: white;
            border-radius: 16px;
            padding: 30px 20px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            display: flex;
            flex-direction: column;
            align-items: center;
            border: 1px solid #edf2f7;
            position: relative;
            overflow: hidden;
            
            /* Apply the animation */
            opacity: 0; /* Starts hidden */
            animation: fadeInUp 0.6s ease-out forwards;
        }
        
        /* Staggered delay for each card */
        .dash-card:nth-child(1) { animation-delay: 0.1s; }
        .dash-card:nth-child(2) { animation-delay: 0.2s; }
        .dash-card:nth-child(3) { animation-delay: 0.3s; }
        .dash-card:nth-child(4) { animation-delay: 0.4s; }

        .dash-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.1);
            border-color: var(--card-hover-border, #bee3f8);
        }
        /* A subtle top border logic added on hover to emphasize color */
        .dash-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 4px;
            background: var(--card-hover-color, #3182ce);
            transform: scaleX(0);
            transition: transform 0.3s ease;
            transform-origin: left;
        }
        .dash-card:hover::before {
            transform: scaleX(1);
        }

        .dash-icon-wrap {
            width: 70px;
            height: 70px;
            background: var(--card-light-bg, #ebf8ff);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            color: var(--card-color, #3182ce);
            font-size: 2rem;
            transition: all 0.3s ease;
        }
        .dash-card:hover .dash-icon-wrap {
            transform: scale(1.1);
            background: var(--card-hover-color, #3182ce);
            color: white;
            box-shadow: 0 8px 15px var(--card-shadow, rgba(49, 130, 206, 0.3));
        }
        
        .dash-card h3 {
            font-size: 1.3rem;
            color: #2d3748;
            margin-bottom: 10px;
            transition: color 0.3s;
        }
        .dash-card:hover h3 {
            color: var(--card-hover-color, #3182ce);
        }

        .dash-card p {
            color: #718096;
            font-size: 0.95rem;
            line-height: 1.5;
            flex-grow: 1;
            margin-bottom: 24px;
        }
        .dash-btn {
            display: inline-block;
            padding: 10px 24px;
            background-color: transparent;
            color: var(--card-color, #3182ce);
            border: 2px solid var(--card-color, #3182ce);
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s;
            width: 100%;
            box-sizing: border-box;
        }
        .dash-card:hover .dash-btn {
            background-color: var(--card-hover-color, #3182ce);
            border-color: var(--card-hover-color, #3182ce);
            color: white;
            box-shadow: 0 4px 10px var(--card-shadow, rgba(49, 130, 206, 0.3));
        }

        /* Unique Card Theme Variables */
        .card-tickets {
            --card-color: #3182ce; /* Blue */
            --card-light-bg: #ebf8ff;
            --card-hover-color: #2b6cb0;
            --card-hover-border: #bee3f8;
            --card-shadow: rgba(49, 130, 206, 0.25);
        }
        .card-hotels {
            --card-color: #38a169; /* Green */
            --card-light-bg: #f0fff4;
            --card-hover-color: #2f855a;
            --card-hover-border: #c6f6d5;
            --card-shadow: rgba(56, 161, 105, 0.25);
        }
        .card-tours {
            --card-color: #d69e2e; /* Yellow/Gold */
            --card-light-bg: #fffff0;
            --card-hover-color: #b7791f;
            --card-hover-border: #fef08a;
            --card-shadow: rgba(214, 158, 46, 0.25);
        }
        .card-history {
            --card-color: #805ad5; /* Purple */
            --card-light-bg: #faf5ff;
            --card-hover-color: #6b46c1;
            --card-hover-border: #e9d8fd;
            --card-shadow: rgba(128, 90, 213, 0.25);
        }

    </style>
</head>
<body>

<?php include 'nav.php'; ?>

<div class="dash-hero">
    <h1 class="dash-hero-title">Welcome back, <span><?= htmlspecialchars($_SESSION['username']); ?></span> <i class="fas fa-hand-sparkles"></i></h1>
    <p class="dash-hero-sub">What would you like to explore today?</p>
</div>

<div class="dashboard-container">
    <div class="card-grid">
        <div class="dash-card card-tickets">
            <div class="dash-icon-wrap"><i class="fas fa-ticket-alt"></i></div>
            <h3>Book Tickets</h3>
            <p>Find the best routes on flights, buses, and trains.</p>
            <a href="start_Booking.php" class="dash-btn">Book Now</a>
        </div>
        <div class="dash-card card-hotels">
            <div class="dash-icon-wrap"><i class="fas fa-hotel"></i></div>
            <h3>Find Hotels</h3>
            <p>Discover premium stays and top-rated resorts.</p>
            <a href="find_Hotels.php" class="dash-btn">Browse Hotels</a>
        </div>
        <div class="dash-card card-tours">
            <div class="dash-icon-wrap"><i class="fas fa-map-marked-alt"></i></div>
            <h3>Tour Packages</h3>
            <p>Explore curated adventures and unforgettable trips.</p>
            <a href="explore_Tour_Packages.php" class="dash-btn">Explore Tours</a>
        </div>
        <div class="dash-card card-history">
            <div class="dash-icon-wrap"><i class="fas fa-history"></i></div>
            <h3>Booking History</h3>
            <p>Review and manage all your past travels.</p>
            <a href="bookingHistory.php" class="dash-btn">View History</a>
        </div>
    </div>
</div>



</body>
<?php include 'footer.php'; ?>
</html>