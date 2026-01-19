<?php
include 'session_check.php';
include '../database/dbconnection.php';

$stmt = $conn->prepare("SELECT id, ticket_code, ticket_type, route, bus_class, seat_count, status, image,includes_text, price, provider 
                        FROM tickets 
                        WHERE status='active'
                        ORDER BY id DESC");
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
                    <?php if (!empty($row['image'])): ?>
                        <img src="../images/<?= htmlspecialchars($row['image']) ?>" alt="Ticket Image" style="max-width:150px;">
                    <?php else: ?>
                        <img src="../images/ticket1.jpg" alt="Default Ticket Image" style="max-width:150px;">
                    <?php endif; ?>
                    <h3><?= htmlspecialchars($row['route']) ?></h3>
                    <p><?= htmlspecialchars($row['ticket_type']) ?> | <?= htmlspecialchars($row['bus_class']) ?> | <?= htmlspecialchars($row['provider']) ?></p>
                    <p>Seats: <?= (int)$row['seat_count'] ?></p>
                    <p>Status: <?= htmlspecialchars($row['status']) ?></p>
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