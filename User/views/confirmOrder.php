<?php
include 'session_check.php';
include '../database/dbconnection.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: user_dashboard.php");
    exit();
}

$email = $_SESSION['email'];
$service_type = trim($_POST['service_type'] ?? '');
$service_id   = (int)($_POST['service_id'] ?? 0);

if ($service_type === '' || $service_id <= 0) {
    die("Invalid booking request.");
}

/* Fetch service from DB based on service_type */
if ($service_type === 'ticket') {
    $q = $conn->prepare("SELECT route_from, route_to, price FROM tickets WHERE id=? AND status='active'");
    $q->bind_param("i", $service_id);
    $q->execute();
    $s = $q->get_result()->fetch_assoc();
    if (!$s) die("Ticket not found.");

    $service_name = $s['route_from'] . " → " . $s['route_to'];
    $total_price  = (float)$s['price'];

} elseif ($service_type === 'hotel') {
    $q = $conn->prepare("SELECT hotel_name, price_per_night FROM hotels WHERE id=? AND status='active'");
    $q->bind_param("i", $service_id);
    $q->execute();
    $s = $q->get_result()->fetch_assoc();
    if (!$s) die("Hotel not found.");

    $service_name = $s['hotel_name'];
    $total_price  = (float)$s['price_per_night'];

} elseif ($service_type === 'tour') {
    $q = $conn->prepare("SELECT package_name, price FROM tour_packages WHERE id=? AND status='active'");
    $q->bind_param("i", $service_id);
    $q->execute();
    $s = $q->get_result()->fetch_assoc();
    if (!$s) die("Tour package not found.");

    $service_name = $s['package_name'];
    $total_price  = (float)$s['price'];

} else {
    die("Invalid service type.");
}

/* Default booking values */
$travel_date = date("Y-m-d"); // user can edit later if you want
$quantity = 1;

/* Insert booking */
$ins = $conn->prepare("
    INSERT INTO bookings
    (user_email, service_type, service_name, travel_date, quantity, total_price,
     booking_status, payment_status, payment_method, created_at)
    VALUES (?, ?, ?, ?, ?, ?, 'pending', 'unpaid', NULL, NOW())
");

$ins->bind_param("ssssid", $email, $service_type, $service_name, $travel_date, $quantity, $total_price);

if ($ins->execute()) {
    $booking_id = $conn->insert_id;
    header("Location: payment.php?booking_id=" . $booking_id);
    exit();
}

die("Booking failed: " . $ins->error);