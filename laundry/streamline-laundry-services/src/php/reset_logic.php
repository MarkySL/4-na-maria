<?php
session_start();
date_default_timezone_set('Asia/Manila');

ini_set('log_errors', 1);
ini_set('error_log', '../error/error.log'); // Specific error log for this script
ini_set('display_errors', 0); // Hide errors in production

require_once 'config.php'; // For database connection

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $token = validate($_POST['token'] ?? '');
    $email = validate($_POST['email'] ?? '');
    $newPassword = validate($_POST['new_pass'] ?? ''); 
    $confirmPassword = validate($_POST['confirm_pass'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'Invalid email format.';
        echo json_encode($response);
        exit();
    }

    if ($newPassword !== $confirmPassword) {
        $response['message'] = 'New password and confirm password do not match.';
        echo json_encode($response);
        exit();
    }

    try {
        // 2. Validate token and email against the database
        // Also check if the token has expired
        $stmt = $con->prepare("SELECT id FROM user_reg WHERE email = ? AND reset_token = ? AND reset_token_expires_at > NOW() LIMIT 1");
        $stmt->bind_param("ss", $email, $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            $response['message'] = 'Invalid or expired reset link. Please request a new one.';
            echo json_encode($response);
            exit();
        }

        $user = $result->fetch_assoc();
        $userId = $user['id'];

        // 3. Hash the new password securely
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // 4. Update the user's password and clear the reset token
        $update_stmt = $con->prepare("UPDATE user_reg SET password = ?, reset_token = NULL, reset_token_expires_at = NULL WHERE id = ?");
        $update_stmt->bind_param("si", $hashedPassword, $userId);
        $update_stmt->execute();

        if ($update_stmt->affected_rows > 0) {
            $response = ['success' => true, 'message' => 'Your password has been successfully reset.'];
        } else {
            // This case is unlikely if the previous query found a user, but good for robustness
            error_log("Failed to update password for user ID: " . $userId . " (No rows affected)");
            $response['message'] = 'Could not reset password at this time. Please try again.';
        }

    } catch (Exception $e) {
        error_log("Password reset error: " . $e->getMessage());
        $response['message'] = 'An internal server error occurred. Please try again.';
    } finally {
        if (isset($stmt) && $stmt instanceof mysqli_stmt) $stmt->close();
        if (isset($update_stmt) && $update_stmt instanceof mysqli_stmt) $update_stmt->close();
        if ($con && $con instanceof mysqli) $con->close();
    }
}

header('Content-Type: application/json'); // Set header for JSON response
echo json_encode($response);
exit();
?>