<?php
// HotelsData.php - Fetch all hotels from the database
include('dbconnection.php');


$hotels = [];

$sql = "SELECT id, name, location, rating, rooms, status
        FROM hotels
        ORDER BY id DESC";

$result = $conn->query($sql);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $hotels[] = $row;
    }
}
?>
