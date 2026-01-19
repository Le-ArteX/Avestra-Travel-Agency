<?php
include 'session_check.php';
include '../database/dbconnection.php';

$stmt = $conn->prepare("SELECT id, hotel_name, location, includes_text, price_per_night, image
                        FROM hotels
                        WHERE status='active'
                        ORDER BY created_at DESC");
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Find Hotels</title>
    <link rel="stylesheet" href="../styleSheets/user.css">
    <link rel="icon" href="../images/logo.png" type="image/png">
</head>
<body>

<?php include 'nav.php'; ?>

<div class="container">
    <h2>Hotels</h2>

    <div class="card-grid">
        <?php if ($result->num_rows == 0): ?>
            <p>No hotels available right now.</p>
        <?php else: ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="service-card">
                    <img src="../images/<?= htmlspecialchars($row['image']) ?>" alt="Hotel Image">

                    <h3><?= htmlspecialchars($row['hotel_name']) ?></h3>
                    <p>📍 <?= htmlspecialchars($row['location']) ?></p>
                    <p>Includes: <?= htmlspecialchars($row['includes_text']) ?></p>
                    <p><b>Price/Night:</b> <?= (float)$row['price_per_night'] ?> ৳</p>

                    <form action="confirmOrder.php" method="post">
                        <input type="hidden" name="service_type" value="hotel">
                        <input type="hidden" name="service_id" value="<?= (int)$row['id'] ?>">
                        <button class="btn">Book Hotel</button>
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