<?php
session_start();
//Set Default Timezone for Logging of Errors
date_default_timezone_set('Asia/Manila');

// Set error logging
ini_set('log_errors', 1);
ini_set('error_log', '../error/error.log');

// Turn off error reporting to the screen
ini_set('display_errors', 0);

require_once 'config.php';
require __DIR__ . '/../../vendor/autoload.php';
require 'mailer_initialize.php'; // Include the PHPMailer initialization

use PHPMailer\PHPMailer\Exception;

function generateToken($length = 64) { // Changed default to 64 for consistency
    return bin2hex(random_bytes($length));
}

$response = ['success' => false, 'message' => 'An unexpected error occurred.'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = validate($_POST['email'] ?? '');

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response = ['success' => false, 'message' => 'Please enter a valid email address.'];
        echo json_encode($response);
        exit();
    }

    try {
        $stmt = $con->prepare("SELECT id, email FROM user_reg WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            // Generic success message for security, preventing email enumeration
            $response = ['success' => true, 'message' => 'If your email address is in our database, you will receive a password reset link. Please check your inbox (and spam folder).'];
            echo json_encode($response);
            exit();
        }

        $user = $result->fetch_assoc();
        $id = $user['id'];
        $token = generateToken();
        $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour')); // Token valid for 1 hour

        $update_stmt = $con->prepare("UPDATE user_reg SET reset_token = ?, reset_token_expires_at = ? WHERE id = ?");
        $update_stmt->bind_param("ssi", $token, $expiresAt, $id);
        $update_stmt->execute();

        if ($update_stmt->affected_rows === 0) {
            error_log("Failed to update reset token for user ID: " . $id);
            $response = ['success' => false, 'message' => 'Could not process your request at this time. Please try again.'];
            echo json_encode($response);
            exit(); // Exit early on update failure
        }

        // Prepare the reset link and its correct path
        $resetLink = $base_url . '/pages/reset-password.php?token=' . $token . '&email=' . urlencode($email);

        $mail = getMailer();
        $mail->SMTPDebug = 0; // IMPORTANT: Set to 0 for production!

        $mail->setFrom($mail->Username, 'Streamline Laundry Services');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Request';
        $mail->Body    = 'Hello,<br><br>You have requested a password reset for your account. Please click the following link to reset your password:<br><br>'
                       . '<a href="' . $resetLink . '">' . $resetLink . '</a><br><br>'
                       . 'This link will expire in 1 hour. If you did not request a password reset, please ignore this email.<br><br>Thank you,<br>Streamline Launder Services';
        $mail->AltBody = 'Hello, You have requested a password reset for your account. Please visit the following link to reset your password: ' . $resetLink . ' This link will expire in 1 hour. If you did not request a password reset, please ignore this email. Thank you, Streamline Launder Services';

        $mail->send();
        // Generic success message even if mail fails (for security)
        $response = ['success' => true, 'message' => 'If your email address is in our database, you will receive a password reset link. Please check your inbox (and spam folder).'];

    } catch (Exception $e) {
        // Log the actual error for debugging, but return a generic message to the user
        error_log("Forgot password error: " . $e->getMessage() . " | Mailer Info: " . ($mail->ErrorInfo ?? 'N/A'));
        $response = ['success' => true, 'message' => 'If your email address is in our database, you will receive a password reset link. Please check your inbox (and spam folder).'];
    } finally {
        // Ensure all statements and connections are closed
        if (isset($stmt) && $stmt instanceof mysqli_stmt) $stmt->close();
        if (isset($update_stmt) && $update_stmt instanceof mysqli_stmt) $update_stmt->close();
        if ($con && $con instanceof mysqli) $con->close();
    }
}

header('Content-Type: application/json');
echo json_encode($response);
exit();
?>