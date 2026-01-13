<?php
$messages = [];

// Collect messages and their types
if (!empty($general_error)) {
    $messages[] = ['type' => 'general-error-message', 'text' => $general_error];
}
if (!empty($_SESSION['success_message'])) {
    $messages[] = ['type' => 'success-message', 'text' => $_SESSION['success_message']];
    unset($_SESSION['success_message']);
}
if (!empty($_SESSION['success_error'])) {
    $messages[] = ['type' => 'general-error-message', 'text' => $_SESSION['success_error']];
    unset($_SESSION['success_error']);
}
// Add more message types as needed
if (!empty($_SESSION['info_message'])) {
    $messages[] = ['type' => 'info-message', 'text' => $_SESSION['info_message']];
    unset($_SESSION['info_message']);
}
if (!empty($_SESSION['warning_message'])) {
    $messages[] = ['type' => 'warning-message', 'text' => $_SESSION['warning_message']];
    unset($_SESSION['warning_message']);
}

// Display all messages
foreach ($messages as $msg) {
    echo '<div class="' . htmlspecialchars($msg['type']) . '">' . htmlspecialchars($msg['text']) . '</div>';
}
?>
