<?php
include 'session_check.php';
include '../database/dbconnection.php';

$email = $_SESSION['email'];

$stmt = $conn->prepare("
    SELECT id, service_name, service_type, travel_date, quantity, total_price,
           booking_status, payment_status, created_at
    FROM bookings
    WHERE user_email = ?
    ORDER BY created_at DESC
");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Booking History</title>
    <link rel="stylesheet" href="../styleSheets/user.css">
    <link rel="stylesheet" href="../styleSheets/bookingHistory.css">
    <link rel="stylesheet" href="../styleSheets/footer.css">
    <link rel="icon" href="../images/logo.png" type="image/png">
</head>
<body>

<?php include 'nav.php'; ?>

<div class="booking-history-container">
    <h2 class="booking-history-title">📋 My Booking History</h2>

    <?php if ($result->num_rows === 0): ?>
        <p>No bookings found.</p>
    <?php else: ?>

        <table class="booking-history-table">
            <tr>
                <th>#</th>
                <th>Service</th>
                <th>Type</th>
                <th>Travel Date</th>
                <th>Qty</th>
                <th>Total</th>
                <th>Booking</th>
                <th>Payment</th>
                <th>Booked On</th>
                <th>Invoice</th>
            </tr>

            <?php $i = 1; while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= htmlspecialchars($row['service_name']) ?></td>
                    <td><?= ucfirst($row['service_type']) ?></td>
                    <td><?= htmlspecialchars($row['travel_date']) ?></td>
                    <td><?= (int)$row['quantity'] ?></td>
                    <td><?= (float)$row['total_price'] ?> ৳</td>
                    <td><?= ucfirst($row['booking_status']) ?></td>
                    <td><?= ucfirst($row['payment_status']) ?></td>
                    <td><?= date("d M Y", strtotime($row['created_at'])) ?></td>
                    <td><a href="invoice.php?id=<?= (int)$row['id'] ?>">View Invoice</a></td>
                </tr>
            <?php endwhile; ?>
        </table>

    <?php endif; ?>

</div>

</body>
<?php include 'footer.php'; ?>
</html>