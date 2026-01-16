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

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

  <link rel="icon" href="../images/logo.png" type="image/png" />
  
  <style>
    :root {
      --primary-color: #2563eb;
      --success-color: #10b981;
      --danger-color: #ef4444;
      --warning-color: #f59e0b;
      --light-bg: #f9fafb;
      --border-color: #e5e7eb;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
    }

    .admin-header {
      background: linear-gradient(135deg, var(--primary-color) 0%, #1e40af 100%);
      color: white;
      padding: 2rem;
      border-radius: 12px;
      margin-bottom: 2rem;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .admin-header h1 {
      font-size: 2.5rem;
      font-weight: 700;
      margin: 0;
    }

    .tour-actions {
      display: flex;
      gap: 1rem;
      margin-bottom: 1.5rem;
      align-items: center;
      flex-wrap: wrap;
    }

    .tour-search {
      flex: 1;
      min-width: 200px;
      padding: 0.75rem 1rem;
      border: 2px solid var(--border-color);
      border-radius: 8px;
      font-size: 1rem;
      transition: all 0.3s ease;
    }

    .tour-search:focus {
      outline: none;
      border-color: var(--primary-color);
      box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .add-tour-btn {
      background: linear-gradient(135deg, var(--primary-color) 0%, #1e40af 100%);
      color: white;
      padding: 0.75rem 1.5rem;
      border-radius: 8px;
      text-decoration: none;
      font-weight: 600;
      transition: all 0.3s ease;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
    }

    .add-tour-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4);
    }

    .tour-table {
      width: 100%;
      border-collapse: collapse;
    }

    .tour-table thead th {
      background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
      color: #374151;
      padding: 1rem;
      text-align: left;
      font-weight: 600;
      border-bottom: 3px solid var(--primary-color);
    }

    .tour-table tbody tr {
      border-bottom: 1px solid var(--border-color);
      transition: all 0.3s ease;
    }

    .tour-table tbody tr:hover {
      background-color: #f0f9ff;
      box-shadow: inset 0 0 10px rgba(37, 99, 235, 0.05);
    }

    .tour-table tbody td {
      padding: 1.2rem;
      color: #374151;
    }

    .status {
      padding: 0.4rem 0.8rem;
      border-radius: 20px;
      font-size: 0.85rem;
      font-weight: 600;
      display: inline-flex;
      align-items: center;
      gap: 0.4rem;
    }

    .status.active {
      background-color: #dcfce7;
      color: #166534;
    }

    .status.active::before {
      content: '●';
      font-size: 1rem;
    }

    .status.inactive {
      background-color: #fee2e2;
      color: #991b1b;
    }

    .status.inactive::before {
      content: '●';
      font-size: 1rem;
    }

    .action-group {
      display: flex;
      gap: 0.5rem;
      flex-wrap: wrap;
    }

    .action-group a {
      padding: 0.5rem 0.8rem;
      border-radius: 6px;
      text-decoration: none;
      font-size: 0.9rem;
      font-weight: 500;
      transition: all 0.3s ease;
      display: inline-flex;
      align-items: center;
      gap: 0.3rem;
    }

    .edit-btn {
      background-color: #dbeafe;
      color: var(--primary-color);
    }

    .edit-btn:hover {
      background-color: var(--primary-color);
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    .toggle-btn {
      background-color: #fef3c7;
      color: var(--warning-color);
    }

    .toggle-btn:hover {
      background-color: var(--warning-color);
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    }

    .delete-btn {
      background-color: #fee2e2;
      color: var(--danger-color);
    }

    .delete-btn:hover {
      background-color: var(--danger-color);
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    .modal-overlay {
      background: rgba(0, 0, 0, 0.5);
      backdrop-filter: blur(4px);
    }

    .modal-box {
      background: white;
      border-radius: 12px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
      animation: slideUp 0.3s ease;
    }

    @keyframes slideUp {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .modal-header {
      background: linear-gradient(135deg, var(--primary-color) 0%, #1e40af 100%);
      color: white;
      border-radius: 12px 12px 0 0;
    }

    .tour-form input,
    .tour-form select {
      border: 2px solid var(--border-color);
      border-radius: 8px;
      transition: all 0.3s ease;
    }

    .tour-form input:focus,
    .tour-form select:focus {
      outline: none;
      border-color: var(--primary-color);
      box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .save-btn {
      background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%);
      color: white;
      border: none;
      box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
    }

    .save-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
    }

    .cancel-btn {
      background-color: #f3f4f6;
      color: #374151;
      border: 2px solid var(--border-color);
    }

    .cancel-btn:hover {
      background-color: #e5e7eb;
    }
  </style>
</head>

<body>
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
          <li><a href="ManageBookings.php">Bookings</a></li>
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
              <!-- Tour Card 1 -->
              <div class="tour-card">
                <div class="tour-card-header">
                  <h3><i class="fas fa-tag"></i> Sundarbans Adventure</h3>
                  <span class="status active">Active</span>
                </div>
                <div class="tour-card-body">
                  <div class="tour-info">
                    <p><i class="fas fa-location-dot"></i> <strong>Destination:</strong> Khulna</p>
                    <p><i class="fas fa-calendar-days"></i> <strong>Duration:</strong> 3 Days</p>
                    <p><i class="fas fa-bangladeshi-taka-sign"></i> <strong>Price:</strong> ৳19,900</p>
                  </div>
                </div>
                <div class="tour-card-footer">
                  <a class="edit-btn" href="#tourModal"><i class="fas fa-pen-to-square"></i> Edit</a>
                  <a class="toggle-btn" href="#"><i class="fas fa-pause"></i> Make Inactive</a>
                  <a class="delete-btn" href="#"><i class="fas fa-trash"></i> Delete</a>
                </div>
              </div>

              <!-- Tour Card 2 -->
              <div class="tour-card">
                <div class="tour-card-header">
                  <h3><i class="fas fa-tag"></i> Cox's Bazar Getaway</h3>
                  <span class="status inactive">Inactive</span>
                </div>
                <div class="tour-card-body">
                  <div class="tour-info">
                    <p><i class="fas fa-location-dot"></i> <strong>Destination:</strong> Cox's Bazar</p>
                    <p><i class="fas fa-calendar-days"></i> <strong>Duration:</strong> 2 Days</p>
                    <p><i class="fas fa-bangladeshi-taka-sign"></i> <strong>Price:</strong> ৳14,900</p>
                  </div>
                </div>
                <div class="tour-card-footer">
                  <a class="edit-btn" href="#tourModal"><i class="fas fa-pen-to-square"></i> Edit</a>
                  <a class="toggle-btn" href="#"><i class="fas fa-play"></i> Make Active</a>
                  <a class="delete-btn" href="#"><i class="fas fa-trash"></i> Delete</a>
                </div>
              </div>

              <!-- Tour Card 3 -->
              <div class="tour-card">
                <div class="tour-card-header">
                  <h3><i class="fas fa-tag"></i> Bandarban Hills Tour</h3>
                  <span class="status active">Active</span>
                </div>
                <div class="tour-card-body">
                  <div class="tour-info">
                    <p><i class="fas fa-location-dot"></i> <strong>Destination:</strong> Bandarban</p>
                    <p><i class="fas fa-calendar-days"></i> <strong>Duration:</strong> 4 Days</p>
                    <p><i class="fas fa-bangladeshi-taka-sign"></i> <strong>Price:</strong> ৳24,900</p>
                  </div>
                </div>
                <div class="tour-card-footer">
                  <a class="edit-btn" href="#tourModal"><i class="fas fa-pen-to-square"></i> Edit</a>
                  <a class="toggle-btn" href="#"><i class="fas fa-pause"></i> Make Inactive</a>
                  <a class="delete-btn" href="#"><i class="fas fa-trash"></i> Delete</a>
                </div>
              </div>
            </div>
          </div>

        </div>
      </section>
    </main>

  </div>

  <!-- ===== Pure HTML/CSS Modal using :target ===== -->
  <div id="tourModal" class="modal-overlay">
    <div class="modal-box" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
      <div class="modal-header">
        <h3 id="modalTitle"><i class="fas fa-plus-circle"></i> Add / Edit Tour</h3>
        <a class="modal-close" href="#">✕</a>
      </div>

      <div class="modal-body">
        <form class="tour-form">
          <div class="form-grid">
            <div class="form-group">
              <label><i class="fas fa-tag"></i> Tour Name</label>
              <input type="text" placeholder="e.g., Cox's Bazar Getaway" required />
            </div>

            <div class="form-group">
              <label><i class="fas fa-location-dot"></i> Destination</label>
              <input type="text" placeholder="e.g., Cox's Bazar" required />
            </div>

            <div class="form-group">
              <label><i class="fas fa-calendar-days"></i> Duration</label>
              <input type="text" placeholder="e.g., 3 Days" required />
            </div>

            <div class="form-group">
              <label><i class="fas fa-bangladeshi-taka-sign"></i> Price (BDT)</label>
              <input type="number" placeholder="e.g., 19900" required />
            </div>

            <div class="form-group full">
              <label><i class="fas fa-circle-info"></i> Status</label>
              <select required>
                <option value="Active">✓ Active</option>
                <option value="Inactive">✗ Inactive</option>
              </select>
            </div>
          </div>

          <div class="form-actions">
            <button class="save-btn" type="submit"><i class="fas fa-check"></i> Save Tour</button>
            <a class="cancel-btn" href="#"><i class="fas fa-times"></i> Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>

</body>
</html>
