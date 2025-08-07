<?php
session_start();
date_default_timezone_set('Asia/Manila');

ini_set('log_errors', 1);
ini_set('error_log', '../error/error.log'); // Specific error log for this script
ini_set('display_errors', 0); // Hide errors in production

require_once 'config.php'; // For database connection

$response = ['status' => 'active', 'message' => 'Session is active.'];

// Define your inactivity timeout (e.g., 30 minutes = 1800 seconds)
$inactivity_timeout = 120; // 30 minutes

// This checks if the user is logged in by verifying the session variable
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    $response = ['status' => 'logged_out', 'message' => 'You are not logged in.'];
    echo json_encode($response);
    exit(); // Exit early if not logged in
}

// 2. Check for inactivity
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $inactivity_timeout)) {
    // Session has expired due to inactivity debugging purpose
    error_log("User " . $_SESSION['email'] . " automatically logged out due to inactivity.");

    // Perform server-side session cleanup
    $_SESSION = array(); // Clear all session variables
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy(); // Destroy the session data on the server

    $response = ['status' => 'inactive', 'message' => 'You have been logged out due to inactivity.'];
    echo json_encode($response);
    exit(); // Exit after sending response and destroying session
}

// This acts as a "heartbeat" to keep the session alive.
$_SESSION['last_activity'] = time();

// If we reach here, the session is active and activity timestamp has been updated.
header('Content-Type: application/json'); // Always send JSON response
echo json_encode($response);
exit();
?>