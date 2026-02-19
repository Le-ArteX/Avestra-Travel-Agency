<?php
include 'session_check.php';
include '../database/dbconnection.php';

$booking_id = (int)($_GET['booking_id'] ?? 0);
$email = $_SESSION['email'];

$stmt = $conn->prepare("SELECT * FROM bookings WHERE id=? AND user_email=?");
$stmt->bind_param("is", $booking_id, $email);
$stmt->execute();
$booking = $stmt->get_result()->fetch_assoc();

if (!$booking) {
    die("Invalid booking.");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Payment | Avestra</title>
    <link rel="stylesheet" href="../styleSheets/user.css">
    <link rel="stylesheet" href="../styleSheets/payment.css">
    <link rel="stylesheet" href="../styleSheets/footer.css">
    <link rel="icon" href="../images/logo.png" type="image/png">
</head>
<body>

<?php include 'nav.php'; ?>

<div class="payment-container">
    <h2 class="payment-title">💳 Payment</h2>

    <div class="payment-summary">
        <p><b>Service:</b> <?= htmlspecialchars($booking['service_name']) ?></p>
        <p><b>Total:</b> <?= htmlspecialchars($booking['total_price']) ?> ৳</p>
        <p><b>Status:</b> <?= htmlspecialchars($booking['payment_status']) ?></p>
    </div>

    <form action="processPayment.php" method="post" class="payment-form">
        <input type="hidden" name="booking_id" value="<?= $booking_id ?>">

        <label><input type="radio" name="payment_method" value="bkash" required> bKash</label><br>
        <label><input type="radio" name="payment_method" value="nagad"> Nagad</label><br>
        <label><input type="radio" name="payment_method" value="card"> Card</label><br><br>

        <button type="submit">Pay Now</button>
    </form>
</div>

</body>
<?php include 'footer.php'; ?>
</html>