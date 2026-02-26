<?php
include 'session_check.php';
include '../database/dbconnection.php';


// --- Pagination & Search Setup ---
$perPage     = 6;
$currentPage = max(1, (int)($_GET['page'] ?? 1));
$search      = trim($_GET['search'] ?? '');
$offset      = ($currentPage - 1) * $perPage;

// --- Count total active tickets ---
if ($search !== '') {
    $like = '%' . $search . '%';
    if (is_numeric($search)) {
        $stmtCount = $conn->prepare("SELECT COUNT(*) FROM tickets WHERE status='active' AND (route LIKE ? OR ticket_code LIKE ? OR id = ?)");
        $searchId = (int)$search;
        $stmtCount->bind_param('ssi', $like, $like, $searchId);
    } else {
        $stmtCount = $conn->prepare("SELECT COUNT(*) FROM tickets WHERE status='active' AND (route LIKE ? OR ticket_code LIKE ?)");
        $stmtCount->bind_param('ss', $like, $like);
    }
} else {
    $stmtCount = $conn->prepare("SELECT COUNT(*) FROM tickets WHERE status='active'");
}
$stmtCount->execute();
$stmtCount->bind_result($totalRows);
$stmtCount->fetch();
$stmtCount->close();
$totalPages = max(1, (int)ceil($totalRows / $perPage));
if ($currentPage > $totalPages) $currentPage = $totalPages;

// --- Fetch paged tickets ---
if ($search !== '') {
    if (is_numeric($search)) {
        $stmt = $conn->prepare("SELECT id, ticket_code, ticket_type, route, bus_class, seat_count, status, image, includes_text, price, provider 
                                FROM tickets 
                                WHERE status='active' AND (route LIKE ? OR ticket_code LIKE ? OR id = ?)
                                ORDER BY id DESC
                                LIMIT ? OFFSET ?");
        $stmt->bind_param('ssiii', $like, $like, $searchId, $perPage, $offset);
    } else {
        $stmt = $conn->prepare("SELECT id, ticket_code, ticket_type, route, bus_class, seat_count, status, image, includes_text, price, provider 
                                FROM tickets 
                                WHERE status='active' AND (route LIKE ? OR ticket_code LIKE ?)
                                ORDER BY id DESC
                                LIMIT ? OFFSET ?");
        $stmt->bind_param('ssii', $like, $like, $perPage, $offset);
    }
} else {
    $stmt = $conn->prepare("SELECT id, ticket_code, ticket_type, route, bus_class, seat_count, status, image, includes_text, price, provider 
                            FROM tickets 
                            WHERE status='active'
                            ORDER BY id DESC
                            LIMIT ? OFFSET ?");
    $stmt->bind_param('ii', $perPage, $offset);
}
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Tickets - Avestra Travel</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../styleSheets/user.css">
    <link rel="stylesheet" href="../styleSheets/start_Booking.css">
    <link rel="stylesheet" href="../styleSheets/footer.css">
    <link rel="icon" href="../images/logo.png" type="image/png">
</head>
<body>

<?php include 'nav.php'; ?>

<!-- Page Header -->
<div class="ticket-hero">
    <div class="ticket-hero-inner">
        <h1 class="ticket-hero-title">🎫 Book Your Journey</h1>
        <p class="ticket-hero-sub">Discover the best routes with premium comfort and safety</p>

        <!-- Search Bar -->
        <form class="tk-search-form" method="GET" action="">
            <div class="tk-search-wrap">
                <span class="tk-search-icon">🔍</span>
                <input
                    class="tk-search-input"
                    type="text"
                    name="search"
                    placeholder="Search by route, ticket code, or ID…"
                    value="<?= htmlspecialchars($search) ?>"
                    autocomplete="off"
                >
                <button class="tk-search-btn" type="submit">Search</button>
            </div>
        </form>
    </div>
</div>

<!-- Results Info -->
<div class="tk-meta">
    <?php if ($search !== ''): ?>
        <span>Results for "<strong><?= htmlspecialchars($search) ?></strong>" — <?= $totalRows ?> ticket<?= $totalRows !== 1 ? 's' : '' ?> found</span>
        <a class="tk-clear-search" href="start_Booking.php">✕ Clear</a>
    <?php else: ?>
        <span><?= $totalRows ?> ticket<?= $totalRows !== 1 ? 's' : '' ?> available</span>
    <?php endif; ?>
    <span class="tk-page-info">Page <?= $currentPage ?> of <?= $totalPages ?></span>
</div>

<div class="ticket-container">
    <div class="card-grid">
        <?php if ($result->num_rows == 0): ?>
            <div class="no-tickets">
                <div class="empty-icon">🚌</div>
                <h3>No Tickets Found</h3>
                <p><?= $search !== '' ? 'Try a different search term.' : 'Check back later for new routes.' ?></p>
                <?php if ($search !== ''): ?>
                    <a class="tk-back-btn" href="start_Booking.php">View All Tickets</a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="service-card">
                    <div class="image-container">
                        <?php if (!empty($row['image'])): ?>
                            <img src="../../Admin/images/<?= htmlspecialchars($row['image']) ?>" alt="Ticket Image" loading="lazy">
                        <?php else: ?>
                            <img src="../images/ticket1.jpg" alt="Default Ticket Image" loading="lazy">
                        <?php endif; ?>
                        <div class="ticket-type-badge"><?= htmlspecialchars($row['ticket_type']) ?></div>
                    </div>
                    
                    <div class="card-body">
                        <h3><?= htmlspecialchars($row['route']) ?></h3>
                        <p class="provider-info">
                            <span class="icon">🏢</span> <?= htmlspecialchars($row['provider'] ?? 'Avestra Airlines/Bus') ?>
                        </p>
                        
                        <div class="ticket-details-grid">
                            <div class="detail-item">
                                <span class="detail-label">Code</span>
                                <span class="detail-value"><?= htmlspecialchars($row['ticket_code']) ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Class</span>
                                <span class="detail-value"><?= htmlspecialchars($row['bus_class']) ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Seats</span>
                                <span class="detail-value highlight"><?= (int)$row['seat_count'] ?></span>
                            </div>
                        </div>

                        <div class="price-section">
                            <span class="price-amount"><?= number_format((float)$row['price'], 0) ?> <span>৳</span></span>
                        </div>

                        <form action="confirmOrder.php" method="post" class="start-booking-form">
                            <input type="hidden" name="service_type" value="ticket">
                            <input type="hidden" name="service_id" value="<?= (int)$row['id'] ?>">
                            <button type="submit" class="book-btn">
                                Book Now →
                            </button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
    <nav class="tk-pagination" aria-label="Ticket pagination">
        <?php $baseUrl = 'start_Booking.php?' . ($search !== '' ? 'search=' . urlencode($search) . '&' : ''); ?>
        
        <!-- Prev -->
        <?php if ($currentPage > 1): ?>
            <a class="tk-page-btn tk-page-nav" href="<?= $baseUrl ?>page=<?= $currentPage - 1 ?>">‹ Prev</a>
        <?php else: ?>
            <span class="tk-page-btn tk-page-nav disabled">‹ Prev</span>
        <?php endif; ?>

        <!-- Numbered pages -->
        <?php
        $start = max(1, $currentPage - 2);
        $end   = min($totalPages, $currentPage + 2);
        if ($start > 1): ?>
            <a class="tk-page-btn" href="<?= $baseUrl ?>page=1">1</a>
            <?php if ($start > 2): ?><span class="tk-page-dots">…</span><?php endif; ?>
        <?php endif;

        for ($p = $start; $p <= $end; $p++): ?>
            <?php if ($p === $currentPage): ?>
                <span class="tk-page-btn active"><?= $p ?></span>
            <?php else: ?>
                <a class="tk-page-btn" href="<?= $baseUrl ?>page=<?= $p ?>"><?= $p ?></a>
            <?php endif; ?>
        <?php endfor;

        if ($end < $totalPages): ?>
            <?php if ($end < $totalPages - 1): ?><span class="tk-page-dots">…</span><?php endif; ?>
            <a class="tk-page-btn" href="<?= $baseUrl ?>page=<?= $totalPages ?>"><?= $totalPages ?></a>
        <?php endif; ?>

        <!-- Next -->
        <?php if ($currentPage < $totalPages): ?>
            <a class="tk-page-btn tk-page-nav" href="<?= $baseUrl ?>page=<?= $currentPage + 1 ?>">Next ›</a>
        <?php else: ?>
            <span class="tk-page-btn tk-page-nav disabled">Next ›</span>
        <?php endif; ?>
    </nav>
    <?php endif; ?>
</div>

</body>
<?php include 'footer.php'; ?>
</html>