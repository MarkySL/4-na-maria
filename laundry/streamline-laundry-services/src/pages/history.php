<?php
session_start(); // Crucial: must be at the very top!
//Set Default Timezone for Logging of Errors
date_default_timezone_set('Asia/Manila');

// Set error logging
ini_set('log_errors', 1);
ini_set('error_log', '../error/error.log');

// Turn off error reporting to the screen
ini_set('display_errors', 0);

require_once '../php/config.php';

// Define your inactivity timeout (e.g., 30 minutes = 1800 seconds)
$inactivity_timeout = 60; // 1 Minute Adjust if needed

// Check if the user is NOT logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Redirect to the login page
    header('Location: login.php'); // Or your actual login page path
    exit(); // Always call exit() after a header redirect
}

// Check for inactivity (only if already logged in)
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $inactivity_timeout)) {
    // Session has expired due to inactivity
    error_log("User " . $_SESSION['email'] . " automatically logged out due to inactivity.");
    header('Location: logout.php'); // Redirect to your logout script
    exit();
}

// If the session is still active, update the last activity timestamp
$_SESSION['last_activity'] = time();

// If the code reaches here, the user is logged in, and the page can be displayed.
// You can now access other session variables, e.g.:
$currentUserId = $_SESSION['user_id'];
$currentUserEmail = $_SESSION['email'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body, h1, th, td {
            color: #ffb700;
        }
    </style>
    <title>Transaction History</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #ffb700;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: #00184c;
            box-shadow: 0 2px 4px #ffb700(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h1 {
            text-align: center;
            color: #ffb700;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ffb700;
        }
        th {
            background-color: #00184c;
        }
        tr:hover {
            background-color: #00184c;
            background-color: #ffb700;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Transaction History</h1>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Service</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>2023-10-01</td>
                    <td>Wash & Fold</td>
                    <td>$15.00</td>
                    <td>Completed</td>
                </tr>
                <tr>
                    <td>2023-10-05</td>
                    <td>Dry Cleaning</td>
                    <td>$25.00</td>
                    <td>Pending</td>
                </tr>
                <tr>
                    <td>2023-10-10</td>
                    <td>Ironing</td>
                    <td>$10.00</td>
                    <td>Completed</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>