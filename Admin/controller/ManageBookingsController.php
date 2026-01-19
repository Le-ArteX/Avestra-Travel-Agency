<?php
session_start();

include('../database/dbconnection.php');

function esc($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

function redirectToList($msg = '') {
    $url = "../views/ManageBookings.php";
    if ($msg !== '') $url .= "?msg=" . urlencode($msg);
    header("Location: $url");
    exit;
}

$errors = [];
$editRow = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    $booking_id     = (int)($_POST['booking_id'] ?? 0);
    $customer_name  = trim($_POST['customer_name'] ?? '');
    $service_type   = trim($_POST['service_type'] ?? 'Hotel'); // Hotel / Tour
    $item_name      = trim($_POST['item_name'] ?? '');
    $booking_date   = trim($_POST['booking_date'] ?? '');
    $status         = trim($_POST['status'] ?? 'Pending'); // Pending/Confirmed/Canceled

    // Basic validation
    if ($customer_name === '') $errors[] = "Customer name is required.";
    if (!in_array($service_type, ['Hotel', 'Tour'], true)) $errors[] = "Invalid service type.";
    if ($item_name === '') $errors[] = "Hotel/Tour name is required.";
    if ($booking_date === '') $errors[] = "Booking date is required.";
    if (!in_array($status, ['Pending', 'Confirmed', 'Canceled'], true)) $errors[] = "Invalid status.";

    if (empty($errors)) {
        if ($action === 'add') {
            $booking_code = 'B' . str_pad((string)rand(1, 9999), 4, '0', STR_PAD_LEFT);
            $stmt = $conn->prepare("
                INSERT INTO bookings (booking_code, customer_name, service_type, item_name, booking_date, status)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->bind_param("ssssss", $booking_code, $customer_name, $service_type, $item_name, $booking_date, $status);
            if ($stmt->execute()) {
                $stmt->close();
                redirectToList("Booking added successfully.");
            }
            $stmt->close();
            $errors[] = "Failed to add booking.";
        }
        if ($action === 'update') {
            if ($booking_id <= 0) {
                $errors[] = "Invalid booking id.";
            } else {
                $stmt = $conn->prepare("
                    UPDATE bookings
                    SET customer_name=?, service_type=?, item_name=?, booking_date=?, status=?
                    WHERE id=?
                ");
                $stmt->bind_param("sssssi", $customer_name, $service_type, $item_name, $booking_date, $status, $booking_id);
                if ($stmt->execute()) {
                    $stmt->close();
                    redirectToList("Booking updated successfully.");
                }
                $stmt->close();
                $errors[] = "Failed to update booking.";
            }
        }
    }
}

if (isset($_GET['delete'])) {
    $deleteId = (int)$_GET['delete'];
    if ($deleteId > 0) {
        $stmt = $conn->prepare("DELETE FROM bookings WHERE id=?");
        $stmt->bind_param("i", $deleteId);
        $stmt->execute();
        $stmt->close();
        redirectToList("Booking deleted.");
    }
}

if (isset($_GET['edit'])) {
    $editId = (int)$_GET['edit'];
    if ($editId > 0) {
        $stmt = $conn->prepare("SELECT * FROM bookings WHERE id=?");
        $stmt->bind_param("i", $editId);
        $stmt->execute();
        $result = $stmt->get_result();
        $editRow = $result->fetch_assoc();
        $stmt->close();
    }
}

$q = trim($_GET['q'] ?? '');
$bookings = [];

if ($q !== '') {
    $like = "%" . $q . "%";
    $stmt = $conn->prepare("
        SELECT * FROM bookings
        WHERE booking_code LIKE ?
           OR customer_name LIKE ?
           OR item_name LIKE ?
           OR service_type LIKE ?
           OR status LIKE ?
        ORDER BY id DESC
    ");
    $stmt->bind_param("sssss", $like, $like, $like, $like, $like);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) $bookings[] = $row;
    $stmt->close();
} else {
    $res = $conn->query("SELECT * FROM bookings ORDER BY id DESC");
    while ($row = $res->fetch_assoc()) $bookings[] = $row;
}

$msg = $_GET['msg'] ?? '';
