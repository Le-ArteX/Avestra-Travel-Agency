<?php
session_start();

include ('../database/dbconnection.php');
include ('../validation/HotelValidation.php');

$action = $_POST['action'] ?? '';

function redirectBack() {
    header('Location: ../views/ManageHotels.php');
    exit;
}

if ($action === 'add' || $action === 'edit') {
    $name = $_POST['name'] ?? '';
    $location = $_POST['location'] ?? '';
    $rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;
    $rooms = isset($_POST['rooms']) ? intval($_POST['rooms']) : 0;
    $status = $_POST['status'] ?? 'Inactive';
    $id = $_POST['id'] ?? '';
    $errors = validateHotelForm($name, $location, $rating, $rooms, $status);
    if (!empty($errors)) {
        $_SESSION['hotel_error'] = implode(' ', $errors);
        header('Location: ../views/ManageHotels.php');
        exit;
    }

    $name     = trim($_POST['name']);
    $location = trim($_POST['location']);
    $rating   = (int)$_POST['rating'];
    $rooms    = (int)$_POST['rooms'];
    $status   = $_POST['status'];

    if ($action === 'add') {
        $stmt = $conn->prepare("INSERT INTO hotels (name, location, rating, rooms, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssiis", $name, $location, $rating, $rooms, $status);

        if ($stmt->execute()) {
            $_SESSION['hotel_success'] = "Hotel added successfully.";
        } else {
            $_SESSION['hotel_error'] = "Failed to add hotel.";
        }
        redirectBack();
    }

    // edit
    $id = (int)($_POST['id'] ?? 0);
    if ($id <= 0) {
        $_SESSION['hotel_error'] = "Invalid hotel ID for update.";
        redirectBack();
    }

    $stmt = $conn->prepare("UPDATE hotels SET name=?, location=?, rating=?, rooms=?, status=? WHERE id=?");
    $stmt->bind_param("ssiisi", $name, $location, $rating, $rooms, $status, $id);

    if ($stmt->execute()) {
        $_SESSION['hotel_success'] = "Hotel updated successfully.";
    } else {
        $_SESSION['hotel_error'] = "Failed to update hotel.";
    }
    redirectBack();
}

if ($action === 'toggle') {
    $id = (int)($_POST['id'] ?? 0);
    if ($id <= 0) {
        $_SESSION['hotel_error'] = "Invalid hotel ID.";
        redirectBack();
    }

    $currentStatus = $_POST['current_status'] ?? 'Inactive';
    $newStatus = (strcasecmp($currentStatus, 'Active') === 0) ? 'Inactive' : 'Active';

    $stmt = $conn->prepare("UPDATE hotels SET status=? WHERE id=?");
    $stmt->bind_param("si", $newStatus, $id);

    if ($stmt->execute()) {
        $_SESSION['hotel_success'] = "Hotel status updated to {$newStatus}.";
    } else {
        $_SESSION['hotel_error'] = "Failed to update status.";
    }
    redirectBack();
}

if ($action === 'delete') {
    $id = (int)($_POST['id'] ?? 0);
    $status = $_POST['status'] ?? '';

    if ($id <= 0) {
        $_SESSION['hotel_error'] = "Invalid hotel ID.";
        redirectBack();
    }

    // Only inactive can be deleted
    if (strcasecmp($status, 'Inactive') !== 0) {
        $_SESSION['hotel_error'] = "Only inactive hotels can be deleted.";
        redirectBack();
    }

    $stmt = $conn->prepare("DELETE FROM hotels WHERE id=?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['hotel_success'] = "Hotel deleted successfully.";
    } else {
        $_SESSION['hotel_error'] = "Failed to delete hotel.";
    }
    redirectBack();
}

$_SESSION['hotel_error'] = "Invalid action.";
redirectBack();
