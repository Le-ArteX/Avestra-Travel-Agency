<?php
include 'session_check.php';
include '../database/dbconnection.php';

$stmt = $conn->prepare("SELECT id, route_from, route_to, transport_type, provider, includes_text, price, image 
                        FROM tickets 
                        WHERE status='active'
                        ORDER BY created_at DESC");
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Book Tickets</title>
    <link rel="stylesheet" href="../styleSheets/user.css">
    <link rel="icon" href="../images/logo.png" type="image/png">
</head>
<body>

<?php include 'nav.php'; ?>

<div class="container">
    <h2>Available Tickets</h2>

    <div class="card-grid">
        <?php if ($result->num_rows == 0): ?>
            <p>No tickets available right now.</p>
        <?php else: ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="service-card">
                    <img src="<?= htmlspecialchars($row['image']) ?>" alt="Ticket Image">
                    <h3><?= htmlspecialchars($row['route_from']) ?> → <?= htmlspecialchars($row['route_to']) ?></h3>
                    <p><?= htmlspecialchars($row['transport_type']) ?> | <?= htmlspecialchars($row['provider']) ?></p>
                    <p>Includes: <?= htmlspecialchars($row['includes_text']) ?></p>
                    <p><b>Price:</b> <?= (float)$row['price'] ?> ৳</p>

                    <form action="confirmOrder.php" method="post">
                        <input type="hidden" name="service_type" value="ticket">
                        <input type="hidden" name="service_id" value="<?= (int)$row['id'] ?>">
                        <button class="btn">Book Now</button>
                        <br><br>
                        <br><br>
                        <br><br>
                
                    </form>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>
</div>

</body>
</html>