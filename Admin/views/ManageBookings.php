<?php
// Controller logic
include('../controller/ManageBookingsController.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Manage Bookings - Avestra Travel Agency</title>
  <link rel="stylesheet" href="../styleSheets/ManageBooking.css" />
  <link rel="icon" href="../images/logo.png" type="image/png" />
</head>

<body>
  <div class="admin-container">
    <!-- SIDEBAR -->
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
          <li><a href="ManageBooking.php" class="active">Bookings</a></li>
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

    <!-- MAIN -->
    <main class="main-content">
      <header class="admin-header">
        <h1>Manage Bookings</h1>
      </header>

      <?php if ($msg): ?>
        <div class="toast success"><?= esc($msg) ?></div>
      <?php endif; ?>

      <?php if (!empty($errors)): ?>
        <div class="toast error">
          <ul class="err-list">
            <?php foreach ($errors as $e): ?>
              <li><?= esc($e) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <!-- ACTION BAR -->
      <div class="admin-card">
        <div class="section-actions">
          <form class="search-wrap" method="GET" action="ManageBooking.php">
            <input type="text" name="q" class="section-search" placeholder="Search bookings..."
                   value="<?= esc($q) ?>" />
            <button class="mini-btn" type="submit">Search</button>
            <?php if ($q !== ''): ?>
              <a class="mini-btn ghost" href="ManageBooking.php">Reset</a>
            <?php endif; ?>
          </form>

          <button class="add-section-btn" type="button" id="openFormBtn">+ Add Booking</button>
        </div>
      </div>

      <!-- FORM (hidden by default) -->
      <div class="admin-card form-card" id="bookingFormCard">
        <div class="form-title">
          <h2><?= $editRow ? "Edit Booking" : "Add Booking" ?></h2>
          <button type="button" class="x-btn" id="closeFormBtn">✕</button>
        </div>

        <form method="POST" action="ManageBooking.php" class="form-container">
          <input type="hidden" name="action" value="<?= $editRow ? 'update' : 'add' ?>">
          <input type="hidden" name="booking_id" value="<?= (int)($editRow['id'] ?? 0) ?>">

          <div class="form-group full">
            <label>Customer Name</label>
            <input type="text" name="customer_name" placeholder="Enter customer name"
                   value="<?= esc($editRow['customer_name'] ?? '') ?>" />
          </div>

          <div class="form-group">
            <label>Service Type</label>
            <select name="service_type">
              <?php
                $st = $editRow['service_type'] ?? 'Hotel';
                foreach (['Hotel','Tour'] as $opt) {
                  $sel = ($st === $opt) ? 'selected' : '';
                  echo "<option value='".esc($opt)."' $sel>".esc($opt)."</option>";
                }
              ?>
            </select>
          </div>

          <div class="form-group">
            <label>Hotel/Tour Name</label>
            <input type="text" name="item_name" placeholder="Example: Grand Hotel / Cox’s Tour"
                   value="<?= esc($editRow['item_name'] ?? '') ?>" />
          </div>

          <div class="form-group">
            <label>Booking Date</label>
            <input type="date" name="booking_date"
                   value="<?= esc($editRow['booking_date'] ?? '') ?>" />
          </div>

          <div class="form-group">
            <label>Status</label>
            <select name="status">
              <?php
                $ss = $editRow['status'] ?? 'Pending';
                foreach (['Pending','Confirmed','Canceled'] as $opt) {
                  $sel = ($ss === $opt) ? 'selected' : '';
                  echo "<option value='".esc($opt)."' $sel>".esc($opt)."</option>";
                }
              ?>
            </select>
          </div>

          <div class="form-actions">
            <button class="save-btn" type="submit"><?= $editRow ? "Update Booking" : "Save Booking" ?></button>
            <a class="cancel-btn" href="ManageBooking.php" type="button">Cancel</a>
          </div>
        </form>
      </div>

      <!-- TABLE -->
      <div class="admin-card">
        <div class="section-table-container">
          <table class="section-table">
            <thead>
              <tr>
                <th>Booking ID</th>
                <th>Customer</th>
                <th>Type</th>
                <th>Hotel/Tour</th>
                <th>Date</th>
                <th>Status</th>
                <th style="width: 160px;">Actions</th>
              </tr>
            </thead>

            <tbody>
              <?php if (empty($bookings)): ?>
                <tr><td colspan="7" class="empty">No bookings found.</td></tr>
              <?php else: ?>
                <?php foreach ($bookings as $b): ?>
                  <?php
                    $statusClass = 'pending';
                    if (($b['status'] ?? '') === 'Confirmed') $statusClass = 'confirmed';
                    if (($b['status'] ?? '') === 'Canceled') $statusClass = 'canceled';
                  ?>
                  <tr>
                    <td><?= esc($b['booking_code'] ?? ('#' . $b['id'])) ?></td>
                    <td><?= esc($b['customer_name']) ?></td>
                    <td><?= esc($b['service_type']) ?></td>
                    <td><?= esc($b['item_name']) ?></td>
                    <td><?= esc($b['booking_date']) ?></td>
                    <td><span class="status <?= esc($statusClass) ?>"><?= esc($b['status']) ?></span></td>
                    <td>
                      <a class="edit-btn" href="ManageBooking.php?edit=<?= (int)$b['id'] ?>">Edit</a>
                      <a class="delete-btn"
                         href="ManageBooking.php?delete=<?= (int)$b['id'] ?>"
                         onclick="return confirm('Delete this booking?');">Delete</a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

    </main>
  </div>

  <script>
    const formCard = document.getElementById('bookingFormCard');
    const openBtn = document.getElementById('openFormBtn');
    const closeBtn = document.getElementById('closeFormBtn');

    function openForm() {
      formCard.classList.add('show');
      formCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
    function closeForm() {
      formCard.classList.remove('show');
    }

    openBtn?.addEventListener('click', openForm);
    closeBtn?.addEventListener('click', closeForm);

    // Auto-open when editing or when there are validation errors
    <?php if ($editRow || !empty($errors)): ?>
      openForm();
    <?php endif; ?>
  </script>
</body>
</html>
