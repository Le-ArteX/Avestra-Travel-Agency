<?php
/**
 * SMTP Configuration - Avestra Travel Agency
 *
 * Set the environment variables below (see .env.example) or edit the
 * fallback defaults to configure outgoing email.
 *
 * For Gmail:
 *   1. Enable 2-Step Verification in your Google Account.
 *   2. Generate an "App Password" (search 'App Passwords' in Google Account settings).
 *   3. Set SMTP_PASS to that 16-character code.
 */

define('SMTP_ENABLED',    filter_var(getenv('SMTP_ENABLED')      ?: false, FILTER_VALIDATE_BOOLEAN));
define('LOCAL_DEBUG_MODE', filter_var(getenv('LOCAL_DEBUG_MODE') ?: false, FILTER_VALIDATE_BOOLEAN));
define('SMTP_HOST',      getenv('SMTP_HOST')      ?: 'smtp.gmail.com');
define('SMTP_PORT',      (int)(getenv('SMTP_PORT') ?: 465));
define('SMTP_SSL',       true);
define('SMTP_USER',      getenv('SMTP_USER')      ?: 'your-email@gmail.com');
define('SMTP_PASS',      getenv('SMTP_PASS')      ?: 'your-app-password');
define('SMTP_FROM',      getenv('SMTP_FROM')      ?: 'noreply@avestra-travel.com');
define('SMTP_FROM_NAME', getenv('SMTP_FROM_NAME') ?: 'Avestra Travel Agency');
?>
