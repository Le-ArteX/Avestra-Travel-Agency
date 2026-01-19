<?php
// Central tour definitions - now fetched from database
include_once('dbconnection.php');

// Fetch all tours from database
function getAllTours($conn) {
    $tours = [];
    $sql = "SELECT id, name, destination, duration, price, status FROM tours ORDER BY id DESC";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $tours[] = $row;
        }
    }
    
    return $tours;
}

// Get tours array
$tours = getAllTours($conn);

// Helper: count active tours.
if (!function_exists('getActiveToursCount')) {
    function getActiveToursCount(array $tours): int
    {
        return count(array_filter($tours, function ($tour) {
            return isset($tour['status']) && strcasecmp($tour['status'], 'Active') === 0;
        }));
    }
}

// Helper: count all tours (active + inactive)
if (!function_exists('getTotalToursCount')) {
    function getTotalToursCount(array $tours): int
    {
        return count($tours);
    }
}

// Helper: get single tour by ID
if (!function_exists('getTourById')) {
    function getTourById($conn, $id) {
        $sql = "SELECT id, name, destination, duration, price, status FROM tours WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
