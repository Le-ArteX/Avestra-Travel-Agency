<?php
include 'session_check.php';
include '../database/dbconnection.php';

 $stmt = $conn->prepare("SELECT id, name, location, includes_text, price_per_night, image
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
    <link rel="stylesheet" href="../styleSheets/find_Hotels.css">
    <link rel="stylesheet" href="../styleSheets/footer.css">
    <link rel="icon" href="../images/logo.png" type="image/png">
</head>
<body>

<?php include 'nav.php'; ?>

<div class="find-hotels-container">
    <h2 class="find-hotels-title">Hotels</h2>
</div>
<div class="hotel-card-grid">
    <?php if ($result->num_rows == 0): ?>
        <p>No hotels available right now.</p>
    <?php else: ?>
        <?php while($row = $result->fetch_assoc()): ?>
            <div class="hotel-card">
                <?php if (!empty($row['image'])): ?>
                    <img src="hotel_image.php?id=<?= (int)$row['id'] ?>" alt="Hotel Image">
                <?php else: ?>
                    <img src="../images/hotel1.jpg" alt="Default Hotel Image">
                <?php endif; ?>

                <h3><?= htmlspecialchars($row['name']) ?></h3>
                <p>📍 <?= htmlspecialchars($row['location']) ?></p>
                <p>Includes: <?= htmlspecialchars($row['includes_text']) ?></p>
                <p><b>Price/Night:</b> <?= ($row['price_per_night'] > 0 ? (float)$row['price_per_night'] . ' ৳' : 'Contact for price') ?></p>

                <form action="confirmOrder.php" method="post" class="hotel-booking-form">
                    <input type="hidden" name="service_type" value="hotel">
                    <input type="hidden" name="service_id" value="<?= (int)$row['id'] ?>">
                    <button type="submit">Book Hotel</button>
                </form>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>
</div>

</body>
<?php include 'footer.php'; ?>
</html>