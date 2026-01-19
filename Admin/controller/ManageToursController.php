<?php
session_start();
include('../database/dbconnection.php');

function redirect_back($msg = '', $err = '') {
  if ($msg) $_SESSION['tour_success'] = $msg;
  if ($err) $_SESSION['tour_error'] = $err;
  header("Location: ../views/ManageTours.php");
  exit;
}

function json_out($success, $message) {
  header('Content-Type: application/json');
  echo json_encode(['success' => $success, 'message' => $message]);
  exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  redirect_back('', 'Invalid request');
}

$action = $_POST['action'] ?? '';

if ($action === 'add') {
  $name = trim($_POST['name'] ?? '');
  $destination = trim($_POST['destination'] ?? '');
  $duration = trim($_POST['duration'] ?? '');
  $price = (float)($_POST['price'] ?? 0);
  $status = $_POST['status'] ?? 'Active';

  if ($name === '' || $destination === '' || $duration === '' || $price <= 0) {
    redirect_back('', 'All fields are required and price must be greater than 0');
  }

  $stmt = $conn->prepare("INSERT INTO tours (name, destination, duration, price, status) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("sssds", $name, $destination, $duration, $price, $status);

  if ($stmt->execute()) redirect_back('Tour added successfully!');
  redirect_back('', 'Error adding tour');
}

if ($action === 'edit') {
  $id = (int)($_POST['id'] ?? 0);
  $name = trim($_POST['name'] ?? '');
  $destination = trim($_POST['destination'] ?? '');
  $duration = trim($_POST['duration'] ?? '');
  $price = (float)($_POST['price'] ?? 0);
  $status = $_POST['status'] ?? 'Active';

  if ($id <= 0 || $name === '' || $destination === '' || $duration === '' || $price <= 0) {
    redirect_back('', 'Invalid data provided');
  }

  $stmt = $conn->prepare("UPDATE tours SET name=?, destination=?, duration=?, price=?, status=? WHERE id=?");
  $stmt->bind_param("sssdsi", $name, $destination, $duration, $price, $status, $id);

  if ($stmt->execute()) redirect_back('Tour updated successfully!');
  redirect_back('', 'Error updating tour');
}

if ($action === 'toggle') {
  $id = (int)($_POST['id'] ?? 0);
  if ($id <= 0) json_out(false, 'Invalid tour ID');

  $stmt = $conn->prepare("UPDATE tours SET status = IF(status='Active','Inactive','Active') WHERE id=?");
  $stmt->bind_param("i", $id);

  if ($stmt->execute()) json_out(true, 'Tour status updated!');
  json_out(false, 'Error updating status');
}

if ($action === 'delete') {
  $id = (int)($_POST['id'] ?? 0);
  if ($id <= 0) json_out(false, 'Invalid tour ID');

  // Only inactive tours can be deleted
  $stmt = $conn->prepare("DELETE FROM tours WHERE id=? AND status='Inactive'");
  $stmt->bind_param("i", $id);

  if ($stmt->execute() && $stmt->affected_rows > 0) {
    json_out(true, 'Tour deleted successfully!');
  }
  json_out(false, 'Only inactive tours can be deleted (or tour not found)');
}

redirect_back('', 'Invalid action');
