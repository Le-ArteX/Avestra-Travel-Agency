<?php
session_start();
include('../database/dbconnection.php');

// Initialize response
$response = ['success' => false, 'message' => ''];

// Handle different actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'add':
            // Add new tour
            $name = trim($_POST['name'] ?? '');
            $destination = trim($_POST['destination'] ?? '');
            $duration = trim($_POST['duration'] ?? '');
            $price = floatval($_POST['price'] ?? 0);
            $status = $_POST['status'] ?? 'Active';
            
            if (empty($name) || empty($destination) || empty($duration) || $price <= 0) {
                $response['message'] = 'All fields are required and price must be greater than 0';
            } else {
                $sql = "INSERT INTO tours (name, destination, duration, price, status) VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssds", $name, $destination, $duration, $price, $status);
                
                if ($stmt->execute()) {
                    $response['success'] = true;
                    $response['message'] = 'Tour added successfully!';
                } else {
                    $response['message'] = 'Error adding tour: ' . $conn->error;
                }
                $stmt->close();
            }
            break;
            
        case 'edit':
            // Edit existing tour
            $id = intval($_POST['id'] ?? 0);
            $name = trim($_POST['name'] ?? '');
            $destination = trim($_POST['destination'] ?? '');
            $duration = trim($_POST['duration'] ?? '');
            $price = floatval($_POST['price'] ?? 0);
            $status = $_POST['status'] ?? 'Active';
            
            if ($id <= 0 || empty($name) || empty($destination) || empty($duration) || $price <= 0) {
                $response['message'] = 'Invalid data provided';
            } else {
                $sql = "UPDATE tours SET name = ?, destination = ?, duration = ?, price = ?, status = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssdsi", $name, $destination, $duration, $price, $status, $id);
                
                if ($stmt->execute()) {
                    $response['success'] = true;
                    $response['message'] = 'Tour updated successfully!';
                } else {
                    $response['message'] = 'Error updating tour: ' . $conn->error;
                }
                $stmt->close();
            }
            break;
            
        case 'toggle':
            // Toggle tour status
            $id = intval($_POST['id'] ?? 0);
            
            if ($id <= 0) {
                $response['message'] = 'Invalid tour ID';
            } else {
                $sql = "UPDATE tours SET status = IF(status = 'Active', 'Inactive', 'Active') WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $id);
                
                if ($stmt->execute()) {
                    $response['success'] = true;
                    $response['message'] = 'Tour status updated!';
                } else {
                    $response['message'] = 'Error updating status: ' . $conn->error;
                }
                $stmt->close();
            }
            break;
            
        <?php
        session_start();
        include('../database/dbconnection.php');

        $response = ['success' => false, 'message' => ''];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            switch ($action) {
                case 'add':
                    $name = trim($_POST['name'] ?? '');
                    $destination = trim($_POST['destination'] ?? '');
                    $duration = trim($_POST['duration'] ?? '');
                    $price = floatval($_POST['price'] ?? 0);
                    $status = $_POST['status'] ?? 'Active';
                    if (empty($name) || empty($destination) || empty($duration) || $price <= 0) {
                        $response['message'] = 'All fields are required and price must be greater than 0';
                    } else {
                        $sql = "INSERT INTO tours (name, destination, duration, price, status) VALUES (?, ?, ?, ?, ?)";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("sssds", $name, $destination, $duration, $price, $status);
                        if ($stmt->execute()) {
                            $response['success'] = true;
                            $response['message'] = 'Tour added successfully!';
                        } else {
                            $response['message'] = 'Error adding tour: ' . $conn->error;
                        }
                        $stmt->close();
                    }
                    break;
                case 'edit':
                    $id = intval($_POST['id'] ?? 0);
                    $name = trim($_POST['name'] ?? '');
                    $destination = trim($_POST['destination'] ?? '');
                    $duration = trim($_POST['duration'] ?? '');
                    $price = floatval($_POST['price'] ?? 0);
                    $status = $_POST['status'] ?? 'Active';
                    if ($id <= 0 || empty($name) || empty($destination) || empty($duration) || $price <= 0) {
                        $response['message'] = 'Invalid data provided';
                    } else {
                        $sql = "UPDATE tours SET name = ?, destination = ?, duration = ?, price = ?, status = ? WHERE id = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("sssdsi", $name, $destination, $duration, $price, $status, $id);
                        if ($stmt->execute()) {
                            $response['success'] = true;
                            $response['message'] = 'Tour updated successfully!';
                        } else {
                            $response['message'] = 'Error updating tour: ' . $conn->error;
                        }
                        $stmt->close();
                    }
                    break;
                case 'delete':
                    $id = intval($_POST['id'] ?? 0);
                    if ($id > 0) {
                        $sql = "DELETE FROM tours WHERE id = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("i", $id);
                        if ($stmt->execute()) {
                            $response['success'] = true;
                            $response['message'] = 'Tour deleted successfully!';
                        } else {
                            $response['message'] = 'Error deleting tour: ' . $conn->error;
                        }
                        $stmt->close();
                    } else {
                        $response['message'] = 'Invalid tour ID';
                    }
                    break;
            }
            echo json_encode($response);
            exit();
        }
