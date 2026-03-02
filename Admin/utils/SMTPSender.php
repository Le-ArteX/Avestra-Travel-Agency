<?php
namespace Admin\Utils;

require_once __DIR__ . '/SMTPConfig.php';

class SMTPSender {
    private static function get_response($socket) {
        $response = "";
        while ($str = fgets($socket, 515)) {
            $response .= $str;
            if (substr($str, 3, 1) == " ") break;
        }
        return $response;
    }

    /**
     * Send email via SMTP sockets
     */
    public static function send($to, $subject, $body) {
        if (!defined('SMTP_ENABLED') || !SMTP_ENABLED) {
            return false;
        }

        $host = SMTP_HOST;
        $port = SMTP_PORT;
        $user = SMTP_USER;
        $pass = SMTP_PASS;
        $from = SMTP_FROM;
        $fromName = SMTP_FROM_NAME;

        // Create socket
        $ssl = (defined('SMTP_SSL') && SMTP_SSL) ? "ssl://" : "";
        $socket = fsockopen($ssl . $host, $port, $errno, $errstr, 30);

        if (!$socket) {
            error_log("SMTP Error: $errstr ($errno)");
            return false;
        }

        self::get_response($socket); // Server greeting

        fputs($socket, "EHLO " . $host . "\r\n");
        self::get_response($socket);

        // Authentication
        fputs($socket, "AUTH LOGIN\r\n");
        self::get_response($socket);

        fputs($socket, base64_encode($user) . "\r\n");
        self::get_response($socket);

        fputs($socket, base64_encode($pass) . "\r\n");
        $authResponse = self::get_response($socket);

        if (strpos($authResponse, "235") === false) {
            error_log("SMTP Auth Failed: " . $authResponse);
            return false;
        }

        // Email headers & body
        fputs($socket, "MAIL FROM: <$from>\r\n");
        self::get_response($socket);

        fputs($socket, "RCPT TO: <$to>\r\n");
        self::get_response($socket);

        fputs($socket, "DATA\r\n");
        self::get_response($socket);

        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= "To: <$to>\r\n";
        $headers .= "From: $fromName <$from>\r\n";
        $headers .= "Subject: $subject\r\n";
        $headers .= "Date: " . date("r") . "\r\n";

        fputs($socket, $headers . "\r\n" . $body . "\r\n.\r\n");
        self::get_response($socket);

        fputs($socket, "QUIT\r\n");
        fclose($socket);

        return true;
    }
}
?>
