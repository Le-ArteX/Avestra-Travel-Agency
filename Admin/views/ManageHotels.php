<?php
session_start();
include('../database/HotelsData.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manage Hotels - Admin Panel</title>

    <link rel="stylesheet" href="../styleSheets/ManageHotels.css" />
    <link rel="stylesheet" href="../styleSheets/ManageHotelsExtra.css" />

    <link rel="icon" href="../images/logo.png" type="image/png" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <script src="../js/ManageHotels.js" defer></script>
</head>

<body>

    <!-- Custom confirm modal (UI only) -->
    <div id="confirmModal" class="confirm-overlay" aria-hidden="true">
        <div class="confirm-box">
            <div id="confirmModalMessage" class="confirm-message"></div>
            <div class="confirm-actions">
                <button type="button" class="confirm-yes" id="confirmYesBtn">Yes</button>
                <button type="button" class="confirm-no" id="confirmNoBtn">No</button>
            </div>
        </div>
    </div>

    <div class="admin-container">

        <!-- Sidebar -->
        <aside class="sidebar">
            <div style="padding: 24px 32px;">
                <div style="text-align:center; margin-bottom: 16px;">
                    <img src="../images/logo.png" alt="Avestra Logo" style="width:60px; height:auto;">
                </div>
                <h2 class="sidebar-title">Admin Panel</h2>
            </div>

            <nav>
                <ul class="sidebar-menu">
                    <li><a href="Admin.php">Dashboard</a></li>
                    <li><a href="ManageUsers.php">Manage Users</a></li>
                    <li><a href="ManageTickets.php">Tickets</a></li>
                    <li><a class="active" href="ManageHotels.php">Hotels</a></li>
                    <li><a href="ManageTours.php">Tours</a></li>
                    <li><a href="Payments.php">Payments</a></li>
                    <li><a href="Settings.php">Settings</a></li>
                    <li><a href="MyProfile.php">My Profile</a></li>
                    <li><a href="homePage.php">Logout</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main content -->
        <main class="main-content">

            <!-- Alerts -->
            <?php if (isset($_SESSION['hotel_success'])): ?>
                <div class="alert alert-success">
                    <?= htmlspecialchars($_SESSION['hotel_success']) ?>
                </div>
                <?php unset($_SESSION['hotel_success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['hotel_error'])): ?>
                <div class="alert alert-error">
                    <?= htmlspecialchars($_SESSION['hotel_error']) ?>
                </div>
                <?php unset($_SESSION['hotel_error']); ?>
            <?php endif; ?>

            <header class="admin-header">
                <h1><i class="fa-solid fa-hotel"></i> Manage Hotels</h1>

            </header>

            <section class="admin-section">
                <div class="admin-card">

                    <div class="hotel-actions">
                        <input id="hotelSearch" class="hotel-search" type="text"
                            placeholder="🔍 Search hotels by name or location..." />
                        <button class="search-btn" type="button" id="searchBtn">
                            <i class="fa-solid fa-magnifying-glass"></i> Search
                        </button>
                        <a class="add-hotel-btn" href="#hotelModal" id="openAddHotel">
                            <i class="fa-solid fa-plus"></i> Add Hotel
                        </a>
                    </div>

                    <div class="hotel-table-container">
                        <div class="hotel-cards-container" id="hotelGrid">


                            <?php
                            if (!empty($hotels)) {
                                foreach ($hotels as $hotel) {
                                    if (empty($hotel['id']) || !is_numeric($hotel['id']))
                                        continue;
                                    $isActive = isset($hotel['status']) && strcasecmp($hotel['status'], 'Active') === 0;
                                    $statusClass = $isActive ? 'active' : 'inactive';
                                    $rating = (int) ($hotel['rating'] ?? 0);
                                    $rating = max(0, min(5, $rating));
                                    ?>
                                    <div class="hotel-card" data-name="<?= htmlspecialchars($hotel['name']) ?>"
                                        data-location="<?= htmlspecialchars($hotel['location']) ?>"
                                        data-status="<?= htmlspecialchars($hotel['status']) ?>">
                                        <div class="hotel-card-header">
                                            <h3><i class="fa-solid fa-hotel"></i> <?= htmlspecialchars($hotel['name']) ?></h3>
                                            <span
                                                class="status <?= $statusClass ?>"><?= htmlspecialchars($hotel['status']) ?></span>
                                        </div>
                                        <div class="hotel-card-body">
                                            <div class="hotel-info">
                                                <p>
                                                    <i class="fa-solid fa-location-dot"></i>
                                                    <strong>Location:</strong>
                                                    <?= htmlspecialchars($hotel['location']) ?>
                                                </p>
                                                <p>
                                                    <i class="fa-solid fa-star"></i>
                                                    <strong>Rating:</strong>
                                                    <span class="rating-stars">
                                                        <?php for ($i = 1; $i <= 5; $i++)
                                                            echo ($i <= $rating) ? '★' : '☆'; ?>
                                                    </span>
                                                </p>
                                                <p>
                                                    <i class="fa-solid fa-bed"></i>
                                                    <strong>Rooms:</strong>
                                                    <?= htmlspecialchars($hotel['rooms']) ?>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="hotel-card-footer hotel-actions">
                                            <a class="edit-btn" href="#hotelModal" data-id="<?= (int) $hotel['id'] ?>"
                                                data-name="<?= htmlspecialchars($hotel['name']) ?>"
                                                data-location="<?= htmlspecialchars($hotel['location']) ?>"
                                                data-rating="<?= (int) $hotel['rating'] ?>"
                                                data-rooms="<?= (int) $hotel['rooms'] ?>"
                                                data-status="<?= htmlspecialchars($hotel['status']) ?>">
                                                <i class="fa-regular fa-pen-to-square"></i> Edit
                                            </a>
                                            <form action="../controller/ManageHotelsController.php" method="POST"
                                                class="inline-form toggleForm">
                                                <input type="hidden" name="action" value="toggle">
                                                <input type="hidden" name="id" value="<?= (int) $hotel['id'] ?>">
                                                <input type="hidden" name="current_status"
                                                    value="<?= htmlspecialchars($hotel['status']) ?>">
                                                <button type="submit" class="toggle-btn"
                                                    data-confirm="Change hotel status?">
                                                    <i class="fa-solid fa-arrows-rotate"></i>
                                                    <?= $isActive ? 'Make Inactive' : 'Make Active' ?>
                                                </button>
                                            </form>
                                            <!-- Delete (only inactive) -->
                                            <?php if (!$isActive): ?>
                                                <form action="../controller/ManageHotelsController.php" method="POST"
                                                    class="inline-form deleteForm">
                                                    <input type="hidden" name="action" value="delete">
                                                    <input type="hidden" name="id" value="<?= (int) $hotel['id'] ?>">
                                                    <input type="hidden" name="status"
                                                        value="<?= htmlspecialchars($hotel['status']) ?>">
                                                    <button type="submit" class="delete-btn"
                                                        data-confirm="Delete this hotel? This cannot be undone.">
                                                        <i class="fa-solid fa-trash"></i> Delete
                                                    </button>
                                                </form>
                                            <?php else: ?>
                                                <a class="delete-btn disabled-link" title="Only inactive hotels can be deleted">
                                                    <i class="fa-solid fa-trash"></i> Delete
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <?php
                                }
                            } else {
                                echo '<div class="no-hotels-message">No hotels available.</div>';
                            }
                            ?>

                        </div>
                    </div>

                </div>
            </section>

        </main>
    </div>

    <!-- Modal (pure CSS :target) -->
    <div id="hotelModal" class="modal-overlay">
        <div class="modal-box" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
            <div class="modal-header">
                <h3 id="modalTitle"><i class="fa-solid fa-circle-plus"></i> Add / Edit Hotel</h3>
                <a class="modal-close" href="#">✕</a>
            </div>

            <div class="modal-body">
                <form class="hotel-form" id="hotelForm" action="../controller/ManageHotelsController.php" method="POST">
                    <input type="hidden" name="action" id="formAction" value="add">
                    <input type="hidden" name="id" id="hotelId" value="">

                    <div class="form-grid">
                        <div class="form-group">
                            <label><i class="fa-solid fa-hotel"></i> Hotel Name</label>
                            <input type="text" name="name" id="hotelName" placeholder="e.g., Radision Hotel" required />
                        </div>

                        <div class="form-group">
                            <label><i class="fa-solid fa-location-dot"></i> Location</label>
                            <input type="text" name="location" id="hotelLocation" placeholder="e.g., Dhaha "
                                required />
                        </div>

                        <!-- Rating as Stars -->
                        <div class="form-group full">
                            <label><i class="fa-solid fa-star"></i> Rating</label>
                            <div class="star-rating" id="starRating">
                                <input type="radio" name="rating" id="star5" value="5">
                                <label for="star5">★</label>

                                <input type="radio" name="rating" id="star4" value="4">
                                <label for="star4">★</label>

                                <input type="radio" name="rating" id="star3" value="3">
                                <label for="star3">★</label>

                                <input type="radio" name="rating" id="star2" value="2">
                                <label for="star2">★</label>

                                <input type="radio" name="rating" id="star1" value="1" required>
                                <label for="star1">★</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label><i class="fa-solid fa-bed"></i> Rooms</label>
                            <input type="number" name="rooms" id="hotelRooms" placeholder="e.g., 150" required />
                        </div>

                        <div class="form-group full">
                            <label><i class="fa-solid fa-circle-info"></i> Status</label>
                            <select name="status" id="hotelStatus" required>
                                <option value="Active">✓ Active</option>
                                <option value="Inactive">✗ Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button class="save-btn" type="submit">
                            <i class="fa-solid fa-check"></i> Save Hotel
                        </button>
                        <a class="cancel-btn" href="#" id="cancelBtn">
                            <i class="fa-solid fa-xmark"></i> Cancel
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>

</body>

</html>