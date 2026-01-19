<?php
include 'session_check.php';
include '../database/dbconnection.php';

$stmt = $conn->prepare("SELECT id, name, duration, includes_text, price, image
                        FROM tours
                        WHERE status='active'
                        ORDER BY id DESC");
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Tour Packages</title>
    <link rel="stylesheet" href="../styleSheets/user.css">
    <link rel="icon" href="../images/logo.png" type="image/png">
</head>
<body>

<?php include 'nav.php'; ?>

<div class="container">
    <h2>Tour Packages</h2>

    <div class="card-grid">
        <?php if ($result->num_rows == 0): ?>
            <p>No tour packages available right now.</p>
        <?php else: ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="service-card">
                    <?php if (!empty($row['image'])): ?>
                        <img src="../images/<?= htmlspecialchars($row['image']) ?>" alt="Tour Image" style="max-width:150px;">
                    <?php else: ?>
                        <img src="../images/tour1.jpg" alt="Default Tour Image" style="max-width:150px;">
                    <?php endif; ?>

                    <h3><?= htmlspecialchars($row['name']) ?></h3>
                    <p><?= htmlspecialchars($row['duration']) ?></p>
                    <p>Includes: <?= htmlspecialchars($row['includes_text']) ?></p>
                    <p><b>Price:</b> <?= (float)$row['price'] ?> ৳</p>

                    <form action="confirmOrder.php" method="post">
                        <input type="hidden" name="service_type" value="tour">
                        <input type="hidden" name="service_id" value="<?= (int)$row['id'] ?>">
                        <button class="btn">Book Package</button>
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