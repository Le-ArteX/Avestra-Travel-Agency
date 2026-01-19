<?php
include 'session_check.php';
include '../database/dbconnection.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: user_dashboard.php");
    exit();
}

$booking_id = (int)($_POST['booking_id'] ?? 0);
$method = trim($_POST['payment_method'] ?? '');
$email = $_SESSION['email'];

if ($booking_id <= 0 || $method === '') {
    die("Invalid payment request.");
}

/* Get booking amount */
$stmt = $conn->prepare("SELECT total_price FROM bookings WHERE id=? AND user_email=?");
$stmt->bind_param("is", $booking_id, $email);
$stmt->execute();
$b = $stmt->get_result()->fetch_assoc();

if (!$b) die("Booking not found.");

$amount = (float)$b['total_price'];
$txn = strtoupper(uniqid("TXN"));

/* Insert payment record */
$pay = $conn->prepare("
    INSERT INTO payments
    (booking_id, user_email, amount, payment_method, transaction_id, payment_status, payment_date)
    VALUES (?, ?, ?, ?, ?, 'paid', NOW())
");

$pay->bind_param("isdss", $booking_id, $email, $amount, $method, $txn);

if (!$pay->execute()) {
    die("Payment failed: " . $pay->error);
}

/* Update booking */
$upd = $conn->prepare("
    UPDATE bookings
    SET payment_status='paid',
        booking_status='confirmed',
        payment_method=?
    WHERE id=? AND user_email=?
");

$upd->bind_param("sis", $method, $booking_id, $email);
$upd->execute();

header("Location: bookingHistory.php");
exit();