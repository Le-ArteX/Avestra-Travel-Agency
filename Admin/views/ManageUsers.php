<?php
// Server-side Manage Users (no JavaScript actions)
$dataFile = __DIR__ . '/resource/users.json';

function loadUsers(string $file): array {
    if (!file_exists($file)) {
        $seed = [
            [ 'id' => 1, 'username' => 'admin', 'email' => 'admin@avestra.com', 'role' => 'admin', 'status' => 'active', 'created' => '2025-01-15', 'password_hash' => '' ],
            [ 'id' => 2, 'username' => 'lena', 'email' => 'lena@example.com', 'role' => 'customer', 'status' => 'active', 'created' => '2025-02-03', 'password_hash' => '' ],
            [ 'id' => 3, 'username' => 'mike', 'email' => 'mike@example.com', 'role' => 'customer', 'status' => 'inactive', 'created' => '2025-03-22', 'password_hash' => '' ],
        ];
        file_put_contents($file, json_encode($seed, JSON_PRETTY_PRINT));
        return $seed;
    }
    $raw = file_get_contents($file);
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

function saveUsers(string $file, array $users): void {
    file_put_contents($file, json_encode($users, JSON_PRETTY_PRINT));
}

function sanitize(string $s): string { return trim($s); }

$users = loadUsers($dataFile);
$message = $_GET['msg'] ?? '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'create') {
        $username = sanitize($_POST['username'] ?? '');
        $email = sanitize($_POST['email'] ?? '');
        $role = $_POST['role'] ?? '';
        $status = $_POST['status'] ?? '';
        $password = $_POST['password'] ?? '';

        if ($username === '' || $email === '' || $role === '' || $status === '' || $password === '') {
            $errors[] = 'All fields are required for creating a user.';
        }
        if (!in_array($role, ['admin','customer'], true)) $errors[] = 'Invalid role.';
        if (!in_array($status, ['active','inactive'], true)) $errors[] = 'Invalid status.';

        if (!$errors) {
            $nextId = count($users) ? (max(array_map(fn($u) => $u['id'], $users)) + 1) : 1;
            $users[] = [
                'id' => $nextId,
                'username' => $username,
                'email' => $email,
                'role' => $role,
                'status' => $status,
                'created' => date('Y-m-d'),
                'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            ];
            saveUsers($dataFile, $users);
            header('Location: ManageUsers.php?msg=User+created');
            exit;
        }
    } elseif ($action === 'update') {
        $id = (int)($_POST['id'] ?? 0);
        $username = sanitize($_POST['username'] ?? '');
        $email = sanitize($_POST['email'] ?? '');
        $role = $_POST['role'] ?? '';
        $status = $_POST['status'] ?? '';
        $password = $_POST['password'] ?? '';
        if (!in_array($role, ['admin','customer'], true)) $errors[] = 'Invalid role.';
        if (!in_array($status, ['active','inactive'], true)) $errors[] = 'Invalid status.';
        foreach ($users as &$u) {
            if ($u['id'] === $id) {
                $u['username'] = $username ?: $u['username'];
                $u['email'] = $email ?: $u['email'];
                $u['role'] = $role ?: $u['role'];
                $u['status'] = $status ?: $u['status'];
                if ($password !== '') {
                    $u['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
                }
                break;
            }
        }
        unset($u);
        if (!$errors) {
            saveUsers($dataFile, $users);
            header('Location: ManageUsers.php?msg=User+updated');
            exit;
        }
    } elseif ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        $users = array_values(array_filter($users, fn($u) => $u['id'] !== $id));
        saveUsers($dataFile, $users);
        header('Location: ManageUsers.php?msg=User+deleted');
        exit;
    }
}

$q = strtolower(sanitize($_GET['q'] ?? ''));
$roleFilter = $_GET['role'] ?? '';
$statusFilter = $_GET['status'] ?? '';

$filtered = array_values(array_filter($users, function($u) use ($q, $roleFilter, $statusFilter) {
    $text = strtolower($u['username'] . ' ' . $u['email']);
    $matchesText = $q === '' || str_contains($text, $q);
    $matchesRole = $roleFilter === '' || $u['role'] === $roleFilter;
    $matchesStatus = $statusFilter === '' || $u['status'] === $statusFilter;
    return $matchesText && $matchesRole && $matchesStatus;
}));

$totalUsers = count($users);
$adminCount = count(array_filter($users, fn($u) => $u['role'] === 'admin'));
$customerCount = count(array_filter($users, fn($u) => $u['role'] === 'customer'));

$editingId = isset($_GET['edit']) ? (int)$_GET['edit'] : 0;
$editingUser = null;
if ($editingId) {
    foreach ($users as $u) { if ($u['id'] === $editingId) { $editingUser = $u; break; } }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Avestra Travel Agency</title>
    <link rel="stylesheet" href="../styleSheets/homePage.css">
    <link rel="stylesheet" href="../styleSheets/ManageUsers.css">
    <link rel="icon" href="../images/logo.png" type="image/png">
</head>
<body>
    <header>
        <div class="container">
            <div class="logo-container">
                <a href="homePage.php">
                    <img src="../images/logo.png" alt="Avestra Travel Agency Logo">
                </a>
            </div>
            <div id="branding">
                <h1><span class="highlight">Avestra</span> Admin Panel</h1>
            </div>
            <nav>
                <ul>
                    <li><a href="Admin.php">Dashboard</a></li>
                    <li class="current"><a href="ManageUsers.php">Manage Users</a></li>
                    <li><a href="#">Bookings</a></li>
                    <li><a href="#">Hotels</a></li>
                    <li><a href="#">Tours</a></li>
                    <li><a href="#">Reports</a></li>
                    <li><a href="#">Payments</a></li>
                    <li><a href="#">Settings</a></li>
                    <li><a href="homePage.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section id="manage-users">
        <div class="container">
            <div class="page-header">
                <h2>Manage Users</h2>
                <div class="actions">
                    <form method="get" class="filters">
                        <input type="text" name="q" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" placeholder="Search by name or email">
                        <select name="role">
                            <option value="" <?= $roleFilter === '' ? 'selected' : '' ?>>All Roles</option>
                            <option value="admin" <?= $roleFilter === 'admin' ? 'selected' : '' ?>>Admin</option>
                            <option value="customer" <?= $roleFilter === 'customer' ? 'selected' : '' ?>>Customer</option>
                        </select>
                        <select name="status">
                            <option value="" <?= $statusFilter === '' ? 'selected' : '' ?>>All Statuses</option>
                            <option value="active" <?= $statusFilter === 'active' ? 'selected' : '' ?>>Active</option>
                            <option value="inactive" <?= $statusFilter === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                        </select>
                        <button type="submit" class="btn">Apply</button>
                        <a href="ManageUsers.php" class="btn">Reset</a>
                    </form>
                </div>
            </div>

            <?php if ($message): ?>
                <div class="stat-box" style="margin-bottom:12px;"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>
            <?php if ($errors): ?>
                <div class="stat-box" style="margin-bottom:12px; color:#b00020;">
                    <?= htmlspecialchars(implode(' ', $errors)) ?>
                </div>
            <?php endif; ?>

            <div class="stats">
                <div class="stat-box">
                    <span class="stat-number"><?= $totalUsers ?></span>
                    <span class="stat-label">Total Users</span>
                </div>
                <div class="stat-box">
                    <span class="stat-number"><?= $adminCount ?></span>
                    <span class="stat-label">Admins</span>
                </div>
                <div class="stat-box">
                    <span class="stat-number"><?= $customerCount ?></span>
                    <span class="stat-label">Customers</span>
                </div>
            </div>

            <div class="table-wrapper">
                <table class="users-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!count($filtered)): ?>
                            <tr><td colspan="7" style="text-align:center;">No users match your filters.</td></tr>
                        <?php else: ?>
                            <?php foreach ($filtered as $u): ?>
                                <tr>
                                    <td><?= (int)$u['id'] ?></td>
                                    <td><?= htmlspecialchars($u['username']) ?></td>
                                    <td><?= htmlspecialchars($u['email']) ?></td>
                                    <td><span class="tag <?= htmlspecialchars($u['role']) ?>"><?= htmlspecialchars($u['role']) ?></span></td>
                                    <td><span class="tag <?= htmlspecialchars($u['status']) ?>"><?= htmlspecialchars($u['status']) ?></span></td>
                                    <td><?= htmlspecialchars($u['created']) ?></td>
                                    <td style="display:flex; gap:6px;">
                                        <a class="btn small" href="ManageUsers.php?edit=<?= (int)$u['id'] ?>">Edit</a>
                                        <form method="post" style="display:inline;">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?= (int)$u['id'] ?>">
                                            <button type="submit" class="btn small danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div style="margin-top:16px;" class="table-wrapper">
                <?php if ($editingUser): ?>
                    <div class="modal-header"><h3>Edit User #<?= (int)$editingUser['id'] ?></h3></div>
                    <form method="post" style="padding:16px;">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="id" value="<?= (int)$editingUser['id'] ?>">
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" name="username" value="<?= htmlspecialchars($editingUser['username']) ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" value="<?= htmlspecialchars($editingUser['email']) ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Role</label>
                                <select name="role" required>
                                    <option value="admin" <?= $editingUser['role']==='admin'?'selected':'' ?>>Admin</option>
                                    <option value="customer" <?= $editingUser['role']==='customer'?'selected':'' ?>>Customer</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" required>
                                    <option value="active" <?= $editingUser['status']==='active'?'selected':'' ?>>Active</option>
                                    <option value="inactive" <?= $editingUser['status']==='inactive'?'selected':'' ?>>Inactive</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>New Password (optional)</label>
                                <input type="password" name="password" minlength="6">
                                <small class="hint">Leave blank to keep current password.</small>
                            </div>
                        </div>
                        <div class="modal-actions">
                            <a href="ManageUsers.php" class="btn">Cancel</a>
                            <button type="submit" class="btn primary">Save</button>
                        </div>
                    </form>
                <?php else: ?>
                    <div class="modal-header"><h3>Add User</h3></div>
                    <form method="post" style="padding:16px;">
                        <input type="hidden" name="action" value="create">
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" name="username" required>
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label>Role</label>
                                <select name="role" required>
                                    <option value="customer" selected>Customer</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" required>
                                    <option value="active" selected>Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" name="password" minlength="6" required>
                                <small class="hint">Min 6 chars.</small>
                            </div>
                        </div>
                        <div class="modal-actions">
                            <button type="submit" class="btn primary">Create</button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <footer>
        <p>&copy; 2025 Avestra Travel Agency. All rights reserved.</p>
    </footer>

    <script src="../js/theme.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            applyStoredTheme();
        });
    </script>
</body>
</html>
