document.addEventListener('DOMContentLoaded', function () {
    if (typeof applyStoredTheme === "function") applyStoredTheme();
});

function openAddModal() {
    document.getElementById('modalTitle').textContent = 'Add User';
    document.getElementById('modalAction').value = 'add';
    document.getElementById('userForm').reset();
    document.getElementById('passwordField').style.display = 'block';
    document.getElementById('modalPassword').required = true;
    document.getElementById('userModal').style.display = 'flex';
}

function openEditModal(user) {
    document.getElementById('modalTitle').textContent = 'Edit User';
    document.getElementById('modalAction').value = 'edit';
    document.getElementById('oldEmail').value = user.email;
    document.getElementById('modalUsername').value = user.username;
    document.getElementById('modalEmail').value = user.email;
    document.getElementById('modalRole').value = user.role || 'Customer';
    document.getElementById('modalStatus').value = user.status || 'Active';
    document.getElementById('passwordField').style.display = 'none';
    document.getElementById('modalPassword').required = false;
    document.getElementById('userModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('userModal').style.display = 'none';
}

let pendingAction = null;
let pendingEmail = null;
let pendingUsername = '';

function openConfirmModal(message, action, email, username) {
    pendingAction = action;
    pendingEmail = email;
    pendingUsername = username || '';
    document.getElementById('confirmMessage').textContent = message;
    document.getElementById('confirmModal').style.display = 'flex';
}

function closeConfirmModal() {
    document.getElementById('confirmModal').style.display = 'none';
    pendingAction = null;
    pendingEmail = null;
    pendingUsername = '';
}

function submitPendingAction() {
    if (!pendingAction || !pendingEmail) return closeConfirmModal();
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '../controller/ManageUsersActions.php';
    form.innerHTML = `
        <input type="hidden" name="action" value="${pendingAction}">
        <input type="hidden" name="email" value="${pendingEmail}">
      `;
    document.body.appendChild(form);
    form.submit();
}

function deleteUser(email, username) {
    openConfirmModal(`Delete user: ${username}?`, 'delete', email, username);
}

function blockUser(email) {
    openConfirmModal('Block this user?', 'block', email);
}

function unblockUser(email) {
    openConfirmModal('Unblock this user?', 'unblock', email);
}
