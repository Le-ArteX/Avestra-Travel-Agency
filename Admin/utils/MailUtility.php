<?php
namespace Admin\Utils;

require_once __DIR__ . '/SMTPConfig.php';
require_once __DIR__ . '/SMTPSender.php';

class MailUtility {
    /**
     * Send a simple HTML email
     */
    public static function sendHTMLMail($to, $subject, $body) {
        // If SMTP is enabled, use our custom authenticated sender
        if (defined('SMTP_ENABLED') && SMTP_ENABLED) {
            return SMTPSender::send($to, $subject, $body);
        }

        // Fallback to default PHP mail (works on live servers)
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $from_email = defined('SMTP_FROM') ? SMTP_FROM : 'noreply@avestra-travel.com';
        $from_name = defined('SMTP_FROM_NAME') ? SMTP_FROM_NAME : 'Avestra Travel Agency';
        $headers .= "From: $from_name <$from_email>" . "\r\n";

        return mail($to, $subject, $body, $headers);
    }

    /**
     * Send OTP email
     */
    public static function sendOTPMail($to, $otp) {
        $subject = "Your Verification Code - Avestra Travel Agency";
        $body = "
        <html>
        <head>
            <style>
                .container { font-family: Arial, sans-serif; padding: 20px; color: #333; }
                .otp-code { font-size: 24px; font-weight: bold; color: #2563eb; letter-spacing: 2px; }
                .footer { font-size: 12px; color: #666; margin-top: 20px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <h2>Verification Code</h2>
                <p>Hello,</p>
                <p>Use the following code to verify your action on Avestra Travel Agency:</p>
                <p class='otp-code'>$otp</p>
                <p>This code will expire in 5 minutes.</p>
                <div class='footer'>
                    <p>&copy; " . date('Y') . " Avestra Travel Agency. All rights reserved.</p>
                </div>
            </div>
        </body>
        </html>
        ";
        return self::sendHTMLMail($to, $subject, $body);
    }
}
?>
