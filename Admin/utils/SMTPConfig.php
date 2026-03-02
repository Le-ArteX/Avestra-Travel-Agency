<?php
/**
 * SMTP Configuration - Avestra Travel Agency
 * 
 * To send real emails from localhost:
 * 1. If using GMAIL: 
 *    - Enable 2-Step Verification in your Google Account.
 *    - Generate an "App Password" (Search for 'App Passwords' in Google Account settings).
 *    - Use that 16-character code as SMTP_PASS.
 */

// SMTP server settings
define('SMTP_ENABLED', false); // Set to true once credentials are filled
define('LOCAL_DEBUG_MODE', true); // SET TO TRUE TO SEE OTP ON SCREEN FOR LOCALHOST TESTING
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 465); // Standard SSL port for Gmail
define('SMTP_SSL', true); // Enable SSL for Gmail/secure servers
define('SMTP_USER', 'your-email@gmail.com');
define('SMTP_PASS', 'your-app-password'); 
define('SMTP_FROM', 'noreply@avestra-travel.com');
define('SMTP_FROM_NAME', 'Avestra Travel Agency');
?>
