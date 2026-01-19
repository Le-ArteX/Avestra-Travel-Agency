<?php
// BookingsData.php - Handles all database operations for bookings
include('dbconnection.php');

function getAllBookings($conn, $q = '') {
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
    return $bookings;
}

function getBookingById($conn, $id) {
    $stmt = $conn->prepare("SELECT * FROM bookings WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return $row;
}

function deleteBooking($conn, $id) {
    $stmt = $conn->prepare("DELETE FROM bookings WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}
