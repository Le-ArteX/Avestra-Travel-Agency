<?php
include 'session_check.php';
include '../database/dbconnection.php'; // Connect to avestra-Travel-Agency database

$sql = "SELECT * FROM tickets WHERE status = 'active'";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmed</title>
</head>
<body>

<h2>Ticket Order Confirmed</h2>
<p>You have successfully ordered: <strong><?php echo $item; ?></strong></p>

<a href="homePage.php">Go to Home</a>

<h2>Active Tickets</h2>
<?php
if ($result && $result->num_rows > 0) {
    echo "<table border='1'>";
    echo "<tr>
            <th>ID</th>
            <th>Ticket Code</th>
            <th>Ticket Type</th>
            <th>Route</th>
            <th>Bus Class</th>
            <th>Seat Count</th>
            <th>Status</th>
          </tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['ticket_code']}</td>
                <td>{$row['ticket_type']}</td>
                <td>{$row['route']}</td>
                <td>{$row['bus_class']}</td>
                <td>{$row['seat_count']}</td>
                <td>{$row['status']}</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "No active tickets found.";
}
?>

</body>
</html>