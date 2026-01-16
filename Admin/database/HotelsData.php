<?php
// HotelsData.php - Fetch all hotels from the database
include('dbconnection.php');

function getHotels() {
    global $conn;
    $hotels = [];
    $sql = "SELECT * FROM hotels ORDER BY id DESC";
    $result = $conn->query($sql);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $hotels[] = $row;
        }
    }
    return $hotels;
}

$hotels = getHotels();
?>
