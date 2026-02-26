<?php
include 'session_check.php';
include '../database/dbconnection.php';

$old_email = $_SESSION['email'];

$stmt = $conn->prepare("SELECT username, email, phoneNumber, role, image FROM customer WHERE email=?");
$stmt->bind_param("s", $old_email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("User not found.");
}
$user = $result->fetch_assoc();
$current_image = !empty($user['image']) ? $user['image'] : 'logo.png';

$success = "";
$error = "";

if (isset($_SESSION['profile_success'])) {
    $success = $_SESSION['profile_success'];
    unset($_SESSION['profile_success']);
}
if (isset($_SESSION['profile_error'])) {
    $error = $_SESSION['profile_error'];
    unset($_SESSION['profile_error']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $phone    = trim($_POST['phoneNumber']);
    $new_email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    $image_path = $user['image']; // Keep existing by default

    if (!preg_match("/^[a-zA-Z\s]+$/", $username)) {
        $error = "Name can contain only letters and spaces.";
    } elseif (!preg_match("/^[0-9]{11}$/", $phone)) {
        $error = "Phone number must be exactly 11 digits.";
    } elseif (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        $email_available = true;
        if ($new_email !== $old_email) {
            $check = $conn->prepare("SELECT email FROM customer WHERE email=?");
            $check->bind_param("s", $new_email);
            $check->execute();
            if ($check->get_result()->num_rows > 0) {
                $email_available = false;
                $error = "Email is already in use.";
            }
        }

        if ($email_available) {
            // Handle image upload
            if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = '../images/profiles/';
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
                
                $file_tmp = $_FILES['profile_picture']['tmp_name'];
                $file_name = time() . '_' . basename($_FILES['profile_picture']['name']);
                $target_file = $upload_dir . $file_name;
                
                $file_type = mime_content_type($file_tmp);
                if (in_array($file_type, ['image/jpeg', 'image/png', 'image/gif', 'image/webp'])) {
                    if (move_uploaded_file($file_tmp, $target_file)) {
                        $image_path = "profiles/" . $file_name;
                    } else {
                        $error = "Failed to upload image.";
                    }
                } else {
                    $error = "Invalid image format. Only JPG, PNG, GIF, WEBP allowed.";
                }
            }

            if (empty($error)) {
                if (!empty($password)) {
                    if ($password !== $confirm_password) {
                        $error = "Passwords do not match.";
                    } elseif (strlen($password) < 6) {
                        $error = "Password must be at least 6 characters.";
                    } else {
                        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                        $update = $conn->prepare("UPDATE customer SET username=?, email=?, phoneNumber=?, image=?, password=? WHERE email=?");
                        $update->bind_param("ssssss", $username, $new_email, $phone, $image_path, $hashed_password, $old_email);
                    }
                } else {
                    $update = $conn->prepare("UPDATE customer SET username=?, email=?, phoneNumber=?, image=? WHERE email=?");
                    $update->bind_param("sssss", $username, $new_email, $phone, $image_path, $old_email);
                }

                if (empty($error) && isset($update)) {
                    if ($update->execute()) {
                        $_SESSION['username'] = $username;
                        $_SESSION['email'] = $new_email;
                        $_SESSION['profile_success'] = "Profile updated successfully.";
                        
                        header("Location: profile.php");
                        exit();
                    } else {
                        $error = "Update failed.";
                    }
                }
            }
        }
    }
    
    if (!empty($error)) {
        $_SESSION['profile_error'] = $error;
        header("Location: profile.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile | Avestra</title>
    <link rel="stylesheet" href="../styleSheets/user.css">
    <link rel="stylesheet" href="../styleSheets/profile.css">
    <link rel="stylesheet" href="../styleSheets/footer.css">
    <link rel="icon" href="../images/logo.png" type="image/png">
</head>
<body>

<?php include 'nav.php'; ?>

<div class="profile-container">
    <h2 class="profile-title">👤 My Profile</h2>

    <?php if ($success): ?><p class="message success"><?= $success ?></p><?php endif; ?>
    <?php if ($error): ?><p class="message error"><?= $error ?></p><?php endif; ?>

    <form method="post" enctype="multipart/form-data" class="profile-info" id="profileForm">
        
        <div class="profile-image-container">
            <img src="../images/<?= htmlspecialchars($current_image) ?>" alt="Profile Picture" class="profile-avatar" id="avatarPreview" onerror="this.src='../images/logo.png'">
            <div class="profile-image-upload">
                <label for="profile_picture" class="upload-btn">📷 Change Picture</label>
                <input type="file" name="profile_picture" id="profile_picture" accept="image/*" style="display: none;" onchange="previewImage(event)">
            </div>
        </div>

        <div class="profile-info-row">
            <label>Full Name</label>
            <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
        </div>
        <div class="profile-info-row">
            <label>Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
        </div>
        <div class="profile-info-row">
            <label>Phone Number</label>
            <input type="text" name="phoneNumber" value="<?= htmlspecialchars($user['phoneNumber']) ?>" required>
        </div>
        <div class="profile-info-row">
            <label>Account Type</label>
            <input type="text" value="<?= ucfirst($user['role']) ?>" readonly>
        </div>
        <div class="profile-info-row">
            <label>New Password (Optional)</label>
            <input type="password" name="password" placeholder="New password">
        </div>
        <div class="profile-info-row">
            <label>Confirm New Password</label>
            <input type="password" name="confirm_password" placeholder="Confirm your new password">
        </div>
        <div class="profile-actions">
            <button type="submit">Update Profile</button>
        </div>
    </form>
</div>

<!-- Custom Confirmation Modal -->
<div id="confirmModal" class="custom-modal">
    <div class="modal-content">
        <h3>Confirm Update</h3>
        <p>Are you sure you want to save your profile changes?</p>
        <div class="modal-actions">
            <button type="button" class="btn-cancel" onclick="closeConfirmModal()">Cancel</button>
            <button type="button" class="btn-confirm" onclick="submitProfileForm()">Yes</button>
        </div>
    </div>
</div>

<script>
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function() {
        const output = document.getElementById('avatarPreview');
        output.src = reader.result;
    };
    if(event.target.files[0]) {
        reader.readAsDataURL(event.target.files[0]);
    }
}

// Hide messages after 3 seconds
document.addEventListener("DOMContentLoaded", function() {
    const message = document.querySelector('.message');
    if (message) {
        setTimeout(() => {
            message.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            message.style.opacity = '0';
            message.style.transform = 'scale(0.95)';
            setTimeout(() => message.remove(), 500);
        }, 3000);
    }
});

// Custom Modal Logic
const profileForm = document.getElementById('profileForm');
const confirmModal = document.getElementById('confirmModal');

profileForm.addEventListener('submit', function(e) {
    e.preventDefault(); // Stop default submission
    confirmModal.classList.add('active');
});

function closeConfirmModal() {
    confirmModal.classList.remove('active');
}

function submitProfileForm() {
    // Hide modal and submit programmatically
    confirmModal.classList.remove('active');
    profileForm.submit();
}
</script>

</body>
<?php include 'footer.php'; ?>
</html>