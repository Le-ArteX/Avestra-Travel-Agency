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
  $includes_text = trim($_POST['includes_text'] ?? '');

  if ($name === '' || $destination === '' || $duration === '' || $price <= 0) {
    redirect_back('', 'All fields are required and price must be greater than 0');
  }

  // Handle image upload
  $image_name = '';
  if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
      $tmp_name = $_FILES['image']['tmp_name'];
      $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
      $image_name = 'tour_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
      $target_path = __DIR__ . '/../../User/images/' . $image_name;
      
      if (!move_uploaded_file($tmp_name, $target_path)) {
          $image_name = ''; // if upload fails, keep it empty
      }
  }

  $stmt = $conn->prepare("INSERT INTO tours (name, destination, duration, price, status, includes_text, image) VALUES (?, ?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("sssdsss", $name, $destination, $duration, $price, $status, $includes_text, $image_name);

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
  $includes_text = trim($_POST['includes_text'] ?? '');

  if ($id <= 0 || $name === '' || $destination === '' || $duration === '' || $price <= 0) {
    redirect_back('', 'Invalid data provided');
  }

  // Handle image upload if a new image is provided
  $image_q = "";
  $types = "sssdssi";
  $params = [&$name, &$destination, &$duration, &$price, &$status, &$includes_text, &$id];
  
  $image_name = '';
  if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
      $tmp_name = $_FILES['image']['tmp_name'];
      $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
      $image_name = 'tour_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
      $target_path = __DIR__ . '/../../User/images/' . $image_name;
      
      if (move_uploaded_file($tmp_name, $target_path)) {
          $image_q = ", image=?";
          $types = "sssdsssi"; // string for image parameter
          $params = [&$name, &$destination, &$duration, &$price, &$status, &$includes_text, &$image_name, &$id];
      }
  }

  $stmt = $conn->prepare("UPDATE tours SET name=?, destination=?, duration=?, price=?, status=?, includes_text=? $image_q WHERE id=?");
  
  // Use call_user_func_array to bind dynamic parameters
  $bind_names[] = $types;
  for ($i=0; $i<count($params); $i++) {
      $bind_names[] = &$params[$i];
  }
  call_user_func_array(array($stmt, 'bind_param'), $bind_names);

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
