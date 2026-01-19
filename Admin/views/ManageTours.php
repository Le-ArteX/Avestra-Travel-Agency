<?php
session_start();
include('../database/ToursData.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Manage Tours - Admin Panel</title>

  <!-- Base styles -->
  <link rel="stylesheet" href="../styleSheets/ManageTours.css" />
  <!-- Extra styles -->
  <link rel="stylesheet" href="../styleSheets/ManageToursExtra.css" />


  <link rel="icon" href="../images/logo.png" type="image/png" />

  <!-- ManageTours JS -->
  <script src="../js/ManageTours.js"></script>
</head>

<body>
  <!-- Custom Stylish Message -->
  <div id="customMessage" style="display:none;position:fixed;top:32px;right:32px;z-index:99999;min-width:320px;max-width:420px;">
    <div id="customMessageBox">
      <span id="customMessageText"></span>
      <button onclick="document.getElementById('customMessage').style.display='none'">Close</button>
    </div>
  </div>

  <!-- Custom Confirm Modal -->
  <div id="confirmModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.45);z-index:10000;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:12px;box-shadow:0 8px 32px rgba(0,0,0,0.18);padding:32px 28px;min-width:320px;max-width:90vw;text-align:center;">
      <div id="confirmModalMessage"></div>
      <button onclick="handleConfirmModalYes()">Yes</button>
      <button onclick="hideConfirmModal()">No</button>
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
          <li><a href="ManageHotels.php">Hotels</a></li>
          <li><a class="active" href="ManageTours.php">Tours</a></li>
          <li><a href="Reports.php">Reports</a></li>
          <li><a href="Payments.php">Payments</a></li>
          <li><a href="Settings.php">Settings</a></li>
          <li><a href="MyProfile.php">My Profile</a></li>
          <li><a href="homePage.php">Logout</a></li>
        </ul>
      </nav>
    </aside>

    <!-- Main -->
    <main class="main-content">
        <?php
        if (isset($_SESSION['tour_success'])) {
          echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['tour_success']) . '</div>';
          unset($_SESSION['tour_success']);
        }
        if (isset($_SESSION['tour_error'])) {
          echo '<div class="alert alert-error">' . htmlspecialchars($_SESSION['tour_error']) . '</div>';
          unset($_SESSION['tour_error']);
        }
        ?>
      <header class="admin-header">
        <h1><i class="fas fa-map-location-dot" style="margin-right: 0.5rem;"></i>Manage Tours</h1>
        <p style="margin: 0.5rem 0 0 0; opacity: 0.95; font-size: 1rem;">Explore, add, and manage all your tour packages with ease</p>
      </header>

      <section class="admin-section">
        <div class="admin-card">

          <!-- Actions -->
          <div class="tour-actions">
            <input class="tour-search" type="text" placeholder="🔍 Search tours by name or destination..." />
            <button class="search-btn" type="button"><i class="fas fa-search"></i> Search</button>
            <!-- Opens modal using :target -->
            <a class="add-tour-btn" href="#tourModal"><i class="fas fa-plus"></i> Add Tour</a>
          </div>

          <!-- Table -->
          <div class="tour-table-container">
            <div class="tour-cards-grid">
              <?php if (!empty($tours)): ?>
                <?php $hasValidTour = false; ?>
                <?php foreach ($tours as $tour): ?>
                  <?php if (!empty($tour['id']) && is_numeric($tour['id'])): ?>
                    <?php
                      $hasValidTour = true;
                      $isActive = isset($tour['status']) && strcasecmp($tour['status'], 'Active') === 0;
                      $statusClass = $isActive ? 'active' : 'inactive';
                      $toggleIcon = $isActive ? 'fa-pause' : 'fa-play';
                      $toggleText = $isActive ? 'Make Inactive' : 'Make Active';
                    ?>
                    <div class="tour-card" data-tour-id="<?php echo $tour['id']; ?>">
                      <div class="tour-card-header">
                        <h3><i class="fas fa-tag"></i> <?php echo htmlspecialchars($tour['name']); ?></h3>
                        <span class="status <?php echo $statusClass; ?>"><?php echo htmlspecialchars($tour['status']); ?></span>
                      </div>
                      <div class="tour-card-body">
                        <div class="tour-info">
                          <p><i class="fas fa-location-dot"></i> <strong>Destination:</strong> <?php echo htmlspecialchars($tour['destination']); ?></p>
                          <p><i class="fas fa-calendar-days"></i> <strong>Duration:</strong> <?php echo htmlspecialchars($tour['duration']); ?></p>
                          <p><i class="fas fa-bangladeshi-taka-sign"></i> <strong>Price:</strong> ৳<?php echo number_format($tour['price']); ?></p>
                        </div>
                      </div>
                      <div class="tour-card-footer">
                        <a class="edit-btn" href="#tourModal" 
                           data-id="<?php echo $tour['id']; ?>" 
                           data-name="<?php echo htmlspecialchars($tour['name']); ?>" 
                           data-destination="<?php echo htmlspecialchars($tour['destination']); ?>" 
                           data-duration="<?php echo htmlspecialchars($tour['duration']); ?>" 
                           data-price="<?php echo $tour['price']; ?>" 
                           data-status="<?php echo htmlspecialchars($tour['status']); ?>">
                          <i class="fas fa-pen-to-square"></i> Edit
                        </a>
                        <a class="toggle-btn" href="#" onclick="toggleTour(<?php echo $tour['id']; ?>); return false;"><i class="fas <?php echo $toggleIcon; ?>"></i> <?php echo $toggleText; ?></a>
                        <?php if ($statusClass === 'inactive'): ?>
                          <a class="delete-btn" href="#" onclick="deleteTour(<?php echo $tour['id']; ?>, '<?php echo htmlspecialchars($tour['name']); ?>'); return false;"><i class="fas fa-trash"></i> Delete</a>
                        <?php else: ?>
                          <a class="delete-btn" href="#" style="pointer-events:none;opacity:0.5;" title="Only inactive tours can be deleted"><i class="fas fa-trash"></i> Delete</a>
                        <?php endif; ?>
                      </div>
                    </div>
                  <?php endif; ?>
                <?php endforeach; ?>
                <?php if (!$hasValidTour): ?>
                  <div class="no-tours-message" style="text-align:center;padding:48px 0;color:#888;font-size:1.2em;">No tours available.</div>
                <?php endif; ?>
              <?php else: ?>
                <div class="no-tours-message" style="text-align:center;padding:48px 0;color:#888;font-size:1.2em;">No tours available.</div>
              <?php endif; ?>
            </div>
          </div>

        </div>
      </section>
    </main>

  </div>

  
  <div id="tourModal" class="modal-overlay">
    <div class="modal-box" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
      <div class="modal-header">
        <h3 id="modalTitle"><i class="fas fa-plus-circle"></i> Add / Edit Tour</h3>
        <a class="modal-close" href="#">✕</a>
      </div>

      <div class="modal-body">
        <form class="tour-form" id="tourForm" action="../controller/ManageToursController.php" method="POST">
          <input type="hidden" name="action" id="formAction" value="add">
          <input type="hidden" name="id" id="tourId" value="">
          
          <div class="form-grid">
            <div class="form-group">
              <label><i class="fas fa-tag"></i> Tour Name</label>
              <input type="text" name="name" id="tourName" placeholder="e.g., Cox's Bazar Getaway" required />
            </div>

            <div class="form-group">
              <label><i class="fas fa-location-dot"></i> Destination</label>
              <input type="text" name="destination" id="tourDestination" placeholder="e.g., Cox's Bazar" required />
            </div>

            <div class="form-group">
              <label><i class="fas fa-calendar-days"></i> Duration</label>
              <input type="text" name="duration" id="tourDuration" placeholder="e.g., 3 Days" required />
            </div>

            <div class="form-group">
              <label><i class="fas fa-bangladeshi-taka-sign"></i> Price (BDT)</label>
              <input type="number" name="price" id="tourPrice" placeholder="e.g., 19900" step="0.01" required />
            </div>

            <div class="form-group full">
              <label><i class="fas fa-circle-info"></i> Status</label>
              <select name="status" id="tourStatus" required>
                <option value="Active">✓ Active</option>
                <option value="Inactive">✗ Inactive</option>
              </select>
            </div>
          </div>

          <div class="form-actions">
            <button class="save-btn" type="submit"><i class="fas fa-check"></i> Save Tour</button>
            <a class="cancel-btn" href="#" onclick="resetForm(); return false;"><i class="fas fa-times"></i> Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
