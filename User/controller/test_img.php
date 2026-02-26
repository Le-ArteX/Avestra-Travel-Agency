<?php
// test_img.php - minimal image test, visit: http://localhost/Avestra-Travel-Agency/User/controller/test_img.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../database/dbconnection.php';

// Get first hotel that has image data
$r = $conn->query("SELECT id, name, LENGTH(image) as imglen, LEFT(image,4) as sig FROM hotels WHERE image IS NOT NULL AND LENGTH(image) > 0 LIMIT 1");

if (!$r || $r->num_rows === 0) {
    echo "NO HOTELS WITH IMAGE DATA FOUND IN DB";
    exit;
}

$row = $r->fetch_assoc();
echo "Found hotel id=" . $row['id'] . " name=" . htmlspecialchars($row['name']);
echo " | image bytes=" . $row['imglen'];
echo " | hex sig=" . bin2hex($row['sig']);
echo "<br><br>Trying to serve image for id=" . $row['id'] . ":<br>";
echo "<img src='hotel_image.php?id=" . (int)$row['id'] . "' style='max-height:150px;border:2px solid red'>";
echo "<br><br>Direct raw output test (click): <a href='hotel_image.php?id=" . (int)$row['id'] . "' target='_blank'>hotel_image.php?id=" . (int)$row['id'] . "</a>";
