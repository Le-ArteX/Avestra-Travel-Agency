<?php
// ManageHotelsController.php - Handles add, edit, toggle, and delete for hotels
include('../database/dbconnection.php');

$action = $_POST['action'] ?? '';

if ($action === 'add' || $action === 'edit') {
    $name = $_POST['name'] ?? '';
    $location = $_POST['location'] ?? '';
    $rating = $_POST['rating'] ?? '';
    $rooms = $_POST['rooms'] ?? '';
    $status = $_POST['status'] ?? 'Inactive';
    $id = $_POST['id'] ?? '';
    if ($action === 'add') {
        $stmt = $conn->prepare("INSERT INTO hotels (name, location, rating, rooms, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('sssis', $name, $location, $rating, $rooms, $status);
        $stmt->execute();
    } else if ($action === 'edit' && $id) {
        $stmt = $conn->prepare("UPDATE hotels SET name=?, location=?, rating=?, rooms=?, status=? WHERE id=?");
        $stmt->bind_param('sssisi', $name, $location, $rating, $rooms, $status, $id);
        $stmt->execute();
    }
    header('Location: ../views/ManageHotels.php');
    exit;
}

if ($action === 'toggle' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $status = $_POST['status'] === 'Active' ? 'Inactive' : 'Active';
    $stmt = $conn->prepare("UPDATE hotels SET status=? WHERE id=?");
    $stmt->bind_param('si', $status, $id);
    $stmt->execute();
    header('Location: ../views/ManageHotels.php');
    exit;
}

if ($action === 'delete' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $stmt = $conn->prepare("DELETE FROM hotels WHERE id=?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    header('Location: ../views/ManageHotels.php');
    exit;
}
?>
