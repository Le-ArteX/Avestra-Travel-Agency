<?php
include 'session_check.php';
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

</body>
</html>