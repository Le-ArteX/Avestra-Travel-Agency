<?php
session_start();
include('../database/MaintenanceCheck.php');

include('../database/ToursData.php');
include('../database/HotelsData.php');

// Helper: count active hotels
function getActiveHotelsCount(array $hotels): int {
    return count(array_filter($hotels, function ($hotel) {
        return isset($hotel['status']) && strcasecmp($hotel['status'], 'Active') === 0;
    }));
}
$activeHotelsCount = getActiveHotelsCount($hotels);

// Check if maintenance mode is enabled - allow admin bypass
checkMaintenanceMode(true);
$activeToursCount = getActiveToursCount($tours);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avestra Travel Agency : Book Tickets, Hotel, Transport</title>
    <link rel="stylesheet" href="../styleSheets/homePage.css">
    <link rel="icon" href="../images/logo.png" type="image/png">
    <style>
        .alert {
            padding: 15px;
            margin: 20px auto;
            max-width: 1100px;
            border-radius: 5px;
            text-align: center;
            font-weight: 500;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>

<body>
    <?php
    if (isset($_SESSION['contact_success'])) {
        echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['contact_success']) . '</div>';
        unset($_SESSION['contact_success']);
    }
    if (isset($_SESSION['contact_error'])) {
        echo '<div class="alert alert-error">' . htmlspecialchars($_SESSION['contact_error']) . '</div>';
        unset($_SESSION['contact_error']);
    }
    ?>
    <header>
        <button id="mode-toggle">🌙</button>
        <div class="container">
            <div class="logo-container">
                <a href="homePage.php">
                    <img src="../images/logo.png" alt="Avestra Travel Agency Logo">
                </a>
            </div>
            <div id="branding">
                <h1><span class="highlight">Avestra</span> Travel Agency</h1>
            </div>

            <nav>
                <ul>
                    <li class="current"><a href="loader.php"> Home</a></li>
                    <li><a href="#services-section-tickets"><img src="../images/ticket-detailed-fill.svg"
                                alt="Tickets Icon" class="ticket-icon"> Tickets</a></li>
                    <li><a href="#services-section-hotel"><img src="../images/house-add.svg" alt="Hotel Icon"
                                class="hotel-icon"> Hotel</a></li>
                    <li><a href="#services-section-tour"><img src="../images/suitcase-lg.svg" alt="Tour Icon"
                                class="tour-icon"> Tour</a></li>
                    <li><a href="#contact-section"> Contact Us</a></li>
                    <li><a href="#about-section"> About Us</a></li>
                    <li><a href="loginPage.php"> Login</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <section id="showcase">
        <div class="container">
            <h1>Welcome to Avestra Travel Agency</h1>
            <p>Travel today, treasure forever.</p>
        </div>
    </section>
    <section id="boxes">
        <div class="container">
            <div class="box">
                <img src="../images/ticket.png" alt="Book Tickets Icon">
                <h3>Book Tickets</h3>
                <p>Easily book your flight, train, or bus tickets with our user-friendly platform.</p>
            </div>
            <div class="box">
                <img src="../images/hotel.png" alt="Book Hotel Icon">
                <h3>Book Hotel</h3>
                <p>Find and reserve the best hotels at competitive prices for your stay.</p>
                <p class="hotels-count-box"><?php echo $activeHotelsCount; ?> hotels available</p>
            </div>
            <div class="box">
                <img src="../images/tour.png" alt="Book Tour Icon">
                <h3>Book Tours</h3>
                <p>Explore guided tours and experiences tailored to your interests.</p>
                <p class="tours-count-box"><?php echo $activeToursCount; ?> tours available</p>
            </div>
        </div>
    </section>
    <section id="travel-cards">
        <div class="container">
            <h2>Discover Amazing Destinations</h2>
            <div class="travel-card-grid">
                <div class="travel-card">
                    <img src="../images/maldives.jpg" alt="Maldives Beach">
                    <div class="travel-card-overlay">
                        <h3>Maldives</h3>
                        <p>Crystal clear waters and white sandy beaches.</p>
                    </div>
                </div>
                <div class="travel-card">
                    <img src="../images/paris.jpg" alt="Eiffel Tower, Paris">
                    <div class="travel-card-overlay">
                        <h3>Paris</h3>
                        <p>The city of lights and romance.</p>
                    </div>
                </div>
                <div class="travel-card">
                    <img src="../images/bali.jpg" alt="Bali Rice Terraces">
                    <div class="travel-card-overlay">
                        <h3>Bali</h3>
                        <p>Experience lush landscapes and vibrant culture.</p>
                    </div>
                </div>
                <div class="travel-card">
                    <img src="../images/newyork.jpg" alt="New York Skyline">
                    <div class="travel-card-overlay">
                        <h3>New York</h3>
                        <p>The city that never sleeps.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="services-section">
        <div class="container services-grid">
            <div id="services-section-tickets" class="service-card">
                <img src="../images/ticket-detailed-fill.svg" alt="Tickets" class="service-icon">
                <h3>Tickets</h3>
                <p>Book flights, trains, and buses in minutes. Compare routes and prices across trusted carriers.</p>
                <a href="BookTickets.php" class="button_1">Start Booking</a>
            </div>
            <div id="services-section-hotel" class="service-card">
                <img src="../images/house-add.svg" alt="Hotel" class="service-icon">
                <h3>Hotel</h3>
                <p>Find the right stay — from boutique stays to luxury resorts — with honest reviews and great deals.</p>
                <a href="BookHotel.php" class="button_1">Find Hotels</a>
            </div>
            <div id="services-section-tour" class="service-card">
                <img src="../images/suitcase-lg.svg" alt="Tour" class="service-icon">
                <h3>Tour</h3>
                <p>Browse guided tours and experiences tailored to your interests, from city highlights to offbeat adventures.</p>
                <a href="BookTour.php" class="button_1">Explore Tours</a>
            </div>
        </div>
    </section>

    <section id="contact-section">
        <div class="container contact-grid">
            <div class="contact-copy">
                <h2>Contact Us</h2>
                <p>Questions about bookings, custom trips, or support? We are here to help every day.</p>
                <ul>
                    <li><strong>Call:</strong> +88012345678910</li>
                    <li><strong>Email:</strong> support@avestra.travel</li>
                    <li><strong>Visit:</strong> Kuril,khilkhet, Dhaka, Bangladesh</li>
                </ul>
            </div>
            <div>
                <div id="contact-message" style="display:none; margin-bottom:15px;"></div>
                <form class="contact-form" id="contactForm">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" placeholder="Your name" required>

                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="you@example.com" required>

                    <label for="message">Message</label>
                    <textarea id="message" name="message" rows="4" placeholder="Tell us how we can help" required></textarea>

                    <button type="submit" class="button_1">Send Message</button>
                </form>
            </div>
        </div>
    </section>

    <section id="about-section">
        <div class="container about-grid">
            <div class="about-copy">
                <h2>About Us</h2>
                <p>Avestra Travel Agency helps travelers discover, plan, and book memorable journeys. From transportation and hotels to curated tours, we bring seamless travel experiences to life.</p>
                <p>Founded by explorers, powered by technology, and guided by customer love — we’re committed to making every trip easier and more inspiring.</p>
            </div>
            <div class="about-highlights">
                <div class="highlight-card">
                    <h3>Trusted Partners</h3>
                    <p>We work with globally recognized airlines, hotels, and tour operators.</p>
                </div>
                <div class="highlight-card">
                    <h3>Support 24/7</h3>
                    <p>Our team is here for you before, during, and after your trip.</p>
                </div>
                <div class="highlight-card">
                    <h3>Best Prices</h3>
                    <p>Competitive rates and exclusive deals, without hidden fees.</p>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <p>&copy; 2025 Avestra Travel Agency. All rights reserved.</p>
    </footer>


    <script src="../js/theme.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            applyStoredTheme();
            wireThemeToggle('mode-toggle');

            const logoLink = document.querySelector('.logo-container a');
            if (logoLink) {
                logoLink.addEventListener('click', function (e) {
                    e.preventDefault();
                    window.location.href = 'loader.php';
                });
            }

            // AJAX Contact Form
            const contactForm = document.getElementById('contactForm');
            const contactMessage = document.getElementById('contact-message');
            
            contactForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(contactForm);
                const submitBtn = contactForm.querySelector('button[type="submit"]');
                
                // Disable button during submission
                submitBtn.disabled = true;
                submitBtn.textContent = 'Sending...';
                
                fetch('../controller/ContactFormHandler.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        contactMessage.className = 'alert alert-success';
                        contactMessage.textContent = data.message;
                        contactForm.reset();
                    } else {
                        contactMessage.className = 'alert alert-error';
                        contactMessage.textContent = data.message;
                    }
                    contactMessage.style.display = 'block';
                    
                    // Hide message after 5 seconds
                    setTimeout(() => {
                        contactMessage.style.display = 'none';
                    }, 5000);
                })
                .catch(error => {
                    contactMessage.className = 'alert alert-error';
                    contactMessage.textContent = 'Error sending message. Please try again.';
                    contactMessage.style.display = 'block';
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Send Message';
                });
            });
        });
    </script>
</body>
</html>