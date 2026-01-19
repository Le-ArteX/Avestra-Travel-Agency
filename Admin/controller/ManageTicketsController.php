<?php
include('../database/dbconnection.php');
include('../database/TicketsData.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../views/ManageTickets.php');
    exit;
}

if (isset($_POST['action']) && $_POST['action'] === 'toggle_status') {
    $id = (int)$_POST['id'];
    $current_status = $_POST['current_status'];
    $new_status = (strtolower($current_status) === 'active') ? 'inactive' : 'active';
    $stmt = $conn->prepare("UPDATE tickets SET status=? WHERE id=?");
    $stmt->bind_param("si", $new_status, $id);
    $result = $stmt->execute();
    $stmt->close();
    if ($result) {
        header("Location: ../views/ManageTickets.php?msg=Ticket status updated successfully");
    } else {
        header("Location: ../views/ManageTickets.php?err=Failed to update ticket status");
    }
    exit;
}

if (isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = (int)$_POST['id'];
    $stmt = $conn->prepare("DELETE FROM tickets WHERE id=?");
    $stmt->bind_param("i", $id);
    $result = $stmt->execute();
    $stmt->close();
    if ($result) {
        header("Location: ../views/ManageTickets.php?msg=Ticket deleted successfully");
    } else {
        header("Location: ../views/ManageTickets.php?err=Failed to delete ticket");
    }
    exit;
}

if (isset($_POST['action']) && $_POST['action'] === 'add') {
    $ticket_code = trim($_POST['ticket_code']);
    $route = trim($_POST['route']);
    $bus_class = $_POST['bus_class'];
    $seat_count = (int)$_POST['seat_count'];
    $status = $_POST['status'];
    if (insert_ticket($conn, $ticket_code, $route, $bus_class, $seat_count, $status)) {
        header("Location: ../views/ManageTickets.php?msg=Ticket added successfully");
    } else {
        header("Location: ../views/ManageTickets.php?err=Failed to add ticket");
    }
    exit;
}

if (isset($_POST['action']) && $_POST['action'] === 'update') {
    $id = (int)$_POST['id'];
    $ticket_code = trim($_POST['ticket_code']);
    $route = trim($_POST['route']);
    $bus_class = $_POST['bus_class'];
    $seat_count = (int)$_POST['seat_count'];
    $status = $_POST['status'];

    if (update_ticket($conn, $id, $ticket_code, $route, $bus_class, $seat_count, $status)) {
        header("Location: ../views/ManageTickets.php?msg=Ticket updated successfully");
    } else {
        header("Location: ../views/ManageTickets.php?err=Failed to update ticket");
    }
    exit;
}