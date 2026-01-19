<?php
include 'session_check.php';
include '../database/dbconnection.php';

$email = $_SESSION['email'];

$stmt = $conn->prepare("SELECT username, email, phoneNumber, role FROM signup WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("User not found.");
}
$user = $result->fetch_assoc();

$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $phone    = trim($_POST['phoneNumber']);

    if (!preg_match("/^[a-zA-Z\s]+$/", $username)) {
        $error = "Name can contain only letters and spaces.";
    } elseif (!preg_match("/^[0-9]{11}$/", $phone)) {
        $error = "Phone number must be exactly 11 digits.";
    } else {
        $update = $conn->prepare("UPDATE signup SET username=?, phoneNumber=? WHERE email=?");
        $update->bind_param("sss", $username, $phone, $email);

        if ($update->execute()) {
            $_SESSION['username'] = $username;
            $success = "Profile updated successfully.";
        } else {
            $error = "Update failed.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile | Avestra</title>
    <link rel="stylesheet" href="../styleSheets/user.css">
    <link rel="icon" href="../images/logo.png" type="image/png">
</head>
<body>

<?php include 'nav.php'; ?>

<div class="container">
    <h2>👤 My Profile</h2>

    <?php if ($success): ?><p style="color:green;"><?= $success ?></p><?php endif; ?>
    <?php if ($error): ?><p style="color:red;"><?= $error ?></p><?php endif; ?>

    <form method="post">
        <label>Full Name</label>
        <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>

        <label>Email (cannot be changed)</label>
        <input type="email" value="<?= htmlspecialchars($user['email']) ?>" readonly>

        <label>Phone Number</label>
        <input type="text" name="phoneNumber" value="<?= htmlspecialchars($user['phoneNumber']) ?>" required>

        <label>Account Type</label>
        <input type="text" value="<?= ucfirst($user['role']) ?>" readonly>

        <button type="submit">Update Profile</button>
    </form>
</div>

</body>
</html>