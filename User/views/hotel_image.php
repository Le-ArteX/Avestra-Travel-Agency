<?php
// hotel_image.php: Outputs hotel image from DB as binary
include '../database/dbconnection.php';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    http_response_code(404);
    exit;
}
$stmt = $conn->prepare('SELECT image FROM hotels WHERE id=? AND status="active"');
$stmt->bind_param('i', $id);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows === 1) {
    $stmt->bind_result($image);
    $stmt->fetch();
    if ($image) {
        // Try to detect image type (jpeg, png, gif)
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->buffer($image);
        if (!$mime || !in_array($mime, ['image/jpeg', 'image/png', 'image/gif'])) {
            // Try PNG as fallback
            $mime = 'image/png';
        }
        header('Content-Type: ' . $mime);
        header('Content-Length: ' . strlen($image));
        echo $image;
        exit;
    } else {
        // Output a default image if hotel image is missing
        $default = '../images/hotel1.jpg';
        if (file_exists($default)) {
            header('Content-Type: image/jpeg');
            readfile($default);
            exit;
        } else {
            header('Content-Type: text/plain');
            echo 'Hotel image not found or empty for id=' . $id;
            exit;
        }
    }
}
http_response_code(404);
