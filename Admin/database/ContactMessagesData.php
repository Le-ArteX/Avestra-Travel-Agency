<?php
// Include database connection
include('dbconnection.php');

// Pagination setup
$items_per_page = 10;
$page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
$page = max(1, $page); // Ensure page is at least 1
$offset = ($page - 1) * $items_per_page;

// Get total count of messages
$count_sql = "SELECT COUNT(*) as total FROM contact_messages";
$count_result = $conn->query($count_sql);
$count_row = $count_result->fetch_assoc();
$total_messages = $count_row['total'] ?? 0;
$total_pages = ($total_messages > 0) ? ceil($total_messages / $items_per_page) : 1;

// Get contact messages
$sql = "SELECT id, name, email, message, status, date FROM contact_messages ORDER BY date DESC LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $items_per_page, $offset);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}
$stmt->close();

// Pagination display values
if ($total_messages > 0) {
    $showing_from = ($offset) + 1;
    $showing_to = $offset + count($messages);
} else {
    $showing_from = 0;
    $showing_to = 0;
}
?>
