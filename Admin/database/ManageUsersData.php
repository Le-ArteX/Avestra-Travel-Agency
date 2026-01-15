<?php
// Include database connection
include('dbconnection.php');

// Debug: Check what's in POST
// echo "<pre>POST: "; print_r($_POST); echo "</pre>";

// Get search and filter parameters
$search = $_POST['search'] ?? '';
$role_filter = $_POST['role_filter'] ?? '';
$status_filter = $_POST['status_filter'] ?? '';

// Pagination setup
$items_per_page = 10;
$current_page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
$offset = ($current_page - 1) * $items_per_page;

// Build WHERE clause
$where_conditions = [];
$params = [];
$types = '';

if (!empty($search)) {
    $where_conditions[] = "(username LIKE ? OR email LIKE ?)";
    $search_param = "%$search%";
    $params[] = $search_param;
    $params[] = $search_param;
    $types .= 'ss';
}

if (!empty($role_filter)) {
    $where_conditions[] = "role = ?";
    $params[] = $role_filter;
    $types .= 's';
}

if (!empty($status_filter)) {
    // Only filter by status if column exists
    $check_column = $conn->query("SHOW COLUMNS FROM signup LIKE 'status'");
    if ($check_column->num_rows > 0) {
        $where_conditions[] = "status = ?";
        $params[] = $status_filter;
        $types .= 's';
    }
}

$where_clause = !empty($where_conditions) ? "WHERE " . implode(" AND ", $where_conditions) : "";

// Get total count of users
$count_sql = "SELECT COUNT(*) as total FROM signup $where_clause";
if (!empty($params)) {
    $count_stmt = $conn->prepare($count_sql);
    $count_stmt->bind_param($types, ...$params);
    $count_stmt->execute();
    $count_result = $count_stmt->get_result();
} else {
    $count_result = $conn->query($count_sql);
}
$count_row = $count_result->fetch_assoc();
$total_users = $count_row['total'];
$total_pages = max(1, ceil($total_users / $items_per_page));

// Fetch users from signup table with pagination
// Check if status column exists
$check_status = $conn->query("SHOW COLUMNS FROM signup LIKE 'status'");
$has_status = ($check_status->num_rows > 0);

if ($has_status) {
    $sql = "SELECT username, email, role, status, Date as created_at FROM signup $where_clause ORDER BY Date DESC LIMIT $items_per_page OFFSET $offset";
} else {
    $sql = "SELECT username, email, role, 'Active' as status, Date as created_at FROM signup $where_clause ORDER BY Date DESC LIMIT $items_per_page OFFSET $offset";
}

if (!empty($params)) {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query($sql);
}

$users = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

// Calculate showing range
$showing_from = ($total_users == 0) ? 0 : ($offset + 1);
$showing_to = min($offset + $items_per_page, $total_users);

$conn->close();
?>
