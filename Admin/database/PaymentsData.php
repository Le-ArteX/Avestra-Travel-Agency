
<?php

function getAllPayments($conn) {
    $payments = [];
    $result = $conn->query("SELECT * FROM payments ORDER BY id DESC");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $payments[] = $row;
        }
    }
    return $payments;
}

function searchPayments($conn, $search) {
    $payments = [];
    $search = trim($search);
 
    if (strpos($search, '#') === 0) {
        $search = substr($search, 1);
    }
    $like = "%" . $conn->real_escape_string($search) . "%";
    $sql = "SELECT * FROM payments WHERE CAST(booking_id AS CHAR) LIKE '$like' OR LOWER(user_email) LIKE LOWER('$like') ORDER BY id DESC";
    $result = $conn->query($sql);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $payments[] = $row;
        }
    }
    return $payments;
}

