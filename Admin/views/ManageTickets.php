
<?php
session_start();
include('../database/dbconnection.php');

function esc($s) { return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'); }

$msg = $_GET['msg'] ?? '';
$err = $_GET['err'] ?? '';

// Only show/manage Bus tickets

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $q = trim($_POST['q'] ?? '');
  $status = trim($_POST['status'] ?? '');
} else {
  $q = trim($_GET['q'] ?? '');
  $status = trim($_GET['status'] ?? '');
}

// Fetch tickets from database
$tickets = [];
$sql = "SELECT * FROM tickets WHERE ticket_type = 'Bus'";
if ($q !== '') {
  $sql .= " AND (ticket_code LIKE '%" . $conn->real_escape_string($q) . "%' OR route LIKE '%" . $conn->real_escape_string($q) . "%')";
}
if ($status !== '') {
  $sql .= " AND status = '" . $conn->real_escape_string($status) . "'";
}
$sql .= " ORDER BY id DESC";
$result = $conn->query($sql);
if ($result) {
  while ($row = $result->fetch_assoc()) {
    $tickets[] = $row;
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Manage Tickets - Avestra Travel Agency</title>
  <link rel="stylesheet" href="../styleSheets/ManageTickets.css" />
  <link rel="icon" href="../images/logo.png" type="image/png" />
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
          <li><a href="ManageBooking.php">Bookings</a></li>
          <li><a href="ManageHotels.php">Hotels</a></li>
          <li><a href="ManageTours.php">Tours</a></li>
          <li><a href="ManageTickets.php" class="active">Tickets</a></li>
          <li><a href="Reports.php">Reports</a></li>
          <li><a href="Payments.php">Payments</a></li>
          <li><a href="Settings.php">Settings</a></li>
          <li><a href="MyProfile.php">My Profile</a></li>
          <li><a href="homePage.php">Logout</a></li>
        </ul>
      </nav>
    </aside>

    <main class="main-content">
      <header class="admin-header">
        <h1>Manage Tickets</h1>
      </header>

      <?php if ($msg): ?>
        <div class="toast success"><?= esc($msg) ?></div>
      <?php endif; ?>

      <?php if ($err): ?>
        <div class="toast error"><?= esc($err) ?></div>
      <?php endif; ?>

      <!-- ACTION BAR -->
      <div class="admin-card">
        <div class="section-actions">
          <form class="search-wrap" method="POST" action="ManageTickets.php">
                 <input type="text" class="section-search" name="q"
                   placeholder="Search (Ticket ID)..."
                   value="<?= esc($q) ?>" />
                 <button class="mini-btn" type="submit">Search</button>
               </form>
          <button class="add-section-btn" type="button" id="openFormBtn">+ Add Ticket</button>
        </div>
      </div>

      <!-- FORM CARD -->
      <div class="admin-card form-card" id="ticketFormCard">
        <div class="form-title">
          <h2 id="formTitle">Add Ticket</h2>
          <button type="button" class="x-btn" id="closeFormBtn">✕</button>
        </div>

        <form class="form-container" id="ticketForm" method="POST" action="../controller/ManageTicketsController.php">
          <input type="hidden" name="action" id="actionField" value="add" />
          <input type="hidden" name="id" id="idField" value="" />


          <div class="form-group">
            <label>Ticket Code</label>
            <input type="text" name="ticket_code" id="ticket_code" placeholder="Example: T202601" />
            <div class="field-error" id="err_ticket_code"></div>
          </div>

          <div class="form-group">
            <label>Bus Ticket Type</label>
            <input type="text" name="ticket_type" id="ticket_type" value="Bus" readonly />
            <div class="field-error" id="err_ticket_type"></div>
          </div>

          <div class="form-group">
            <label>Route</label>
            <input type="text" name="route" id="route" placeholder="Example: Dhaka → Cox’s Bazar" />
            <div class="field-error" id="err_route"></div>
          </div>


          <div class="form-group">
            <label>Bus Class</label>
            <select name="bus_class" id="bus_class">
              <option value="AC">AC</option>
              <option value="Non-AC">Non-AC</option>
            </select>
            <div class="field-error" id="err_bus_class"></div>
          </div>

          <div class="form-group">
            <label>Seat Count</label>
            <input type="number" name="seat_count" id="seat_count" min="1" max="45" placeholder="Enter seat count" />
            <div class="field-error" id="err_seat_count"></div>
          </div>

          <div class="form-group">
            <label>Status</label>
            <select name="status" id="status">
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
            </select>
            <div class="field-error" id="err_status"></div>
          </div>

          <div class="form-actions">
            <button class="save-btn" type="submit" id="submitBtn">Save Ticket</button>
            <button class="cancel-btn" type="button" id="cancelFormBtn">Cancel</button>
          </div>
        </form>
      </div>




      <!-- TICKET GRID CARDS -->
      <div class="ticket-grid" style="margin-bottom: 32px;">
        <?php if (empty($tickets)): ?>
          <div style="padding: 40px; text-align: center; width: 100%;">No tickets found.</div>
        <?php else: ?>
          <?php foreach ($tickets as $t): ?>
            <?php
              $statusClass = strtolower($t['status']) === 'active' ? 'active' : 'inactive';
              $busClass = esc($t['bus_class']);
              $statusText = ucfirst($t['status']);
            ?>
            <div class="ticket-card card-responsive"
              data-id="<?= (int)$t['id'] ?>"
              data-ticket_code="<?= esc($t['ticket_code']) ?>"
              data-ticket_type="<?= esc($t['ticket_type'] ?? 'Bus') ?>"
              data-route="<?= esc($t['route']) ?>"
              data-bus_class="<?= esc($t['bus_class']) ?>"
              data-seat_count="<?= (int)$t['seat_count'] ?>"
              data-status="<?= esc($t['status']) ?>"
            >
              <div style="background: #2563eb; color: #fff; padding: 18px 24px 14px 24px; display: flex; align-items: center; justify-content: space-between;">
                <div style="font-size: 18px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                  <span style="font-size: 20px;">🚌</span> <?= esc($t['ticket_code']) ?>
                </div>
                <span style="background: <?= $statusClass === 'active' ? '#0ecb81' : '#f87171' ?>20; color: <?= $statusClass === 'active' ? '#0ecb81' : '#f87171' ?>; padding: 4px 16px; border-radius: 20px; font-size: 13px; font-weight: 500; display: flex; align-items: center; gap: 6px;">
                  <span style="display:inline-block; width:8px; height:8px; border-radius:50%; background:<?= $statusClass === 'active' ? '#0ecb81' : '#f87171' ?>;"></span> <?= $statusText ?>
                </span>
              </div>
              <div style="padding: 18px 24px 10px 24px;">
                <div style="margin-bottom: 8px;"><b>Route:</b> <?= esc($t['route']) ?></div>
                <div style="margin-bottom: 8px;"><b>Bus Class:</b> <?= $busClass ?></div>
                <div style="margin-bottom: 8px;"><b>Seats:</b> <?= (int)$t['seat_count'] ?></div>
              </div>
              <div style="padding: 12px 24px 18px 24px; border-top: 1px solid #e3e8ee; display: flex; gap: 10px;">
                <button class="edit-btn" type="button" data-action="edit" style="flex:1; background:#eff6ff; color:#2563eb; border:none; border-radius:6px; padding:8px 0; font-weight:500; cursor:pointer;">Edit</button>
                <form method="POST" action="../controller/ManageTicketsController.php" style="flex:1; display:inline;">
                  <input type="hidden" name="action" value="toggle_status">
                  <input type="hidden" name="id" value="<?= (int)$t['id'] ?>">
                  <input type="hidden" name="current_status" value="<?= esc($t['status']) ?>">

                  <?php if (strtolower($t['status']) === 'active'): ?>
                    <button type="submit" style="width:100%; background:#fef9e7; color:#f59e42; border:none; border-radius:6px; padding:8px 0; font-weight:500; cursor:pointer;">Make Inactive</button>
                  <?php else: ?>
                    <button type="submit" style="width:100%; background:#e6f9f0; color:#0ecb81; border:none; border-radius:6px; padding:8px 0; font-weight:500; cursor:pointer;">Make Active</button>
                  <?php endif; ?>
                </form>
                <form method="POST" action="../controller/ManageTicketsController.php" style="flex:1; display:inline;">
                  <input type="hidden" name="action" value="delete">
                  <input type="hidden" name="id" value="<?= (int)$t['id'] ?>">
                  <button class="delete-btn" type="submit"
                    style="width:100%; background:#fef2f2; color:#f87171; border:none; border-radius:6px; padding:8px 0; font-weight:500; cursor:pointer;<?php if (strtolower($t['status']) === 'active') echo ' opacity:0.5; pointer-events:none;'; ?>">
                    Delete
                  </button>
                </form>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>

    </main>
  </div>

  <script src="../js/ManageTicket.js"></script>
  <script>
    // Auto-hide toast after 3 seconds and remove ?msg or ?err from URL
    document.addEventListener('DOMContentLoaded', function() {
      var toast = document.querySelector('.toast');
      if (toast) {
        setTimeout(function() {
          toast.style.opacity = '0';
          setTimeout(function() { toast.remove(); }, 500);
        }, 3000);
        // Remove ?msg or ?err from URL after showing
        setTimeout(function() {
          if (window.history.replaceState) {
            const url = new URL(window.location.href);
            url.searchParams.delete('msg');
            url.searchParams.delete('err');
            window.history.replaceState({}, document.title, url.pathname + url.search);
          }
        }, 1000);
      }
    });
  </script>
</body>
</html>
