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
    <title>Booking - Streamline Laundry Services</title>
    <link rel="stylesheet" href="../css/styles.css">

    <!--================= Sweet Alert Message 2 Script ================-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <header>
        <h1>4 Na Maria Laundromat</h1>
        <nav>
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="booking.php">Book Now</a></li>
                <li><a href="invoice.php">Invoice</a></li>
                <li><a href="history.php">View Transactions</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    
    <main>
        <h2>Book Your Laundry Service</h2>
        <form id="booking-form">
            <label for="service">Select Service:</label>
            <select id="service" name="service" required>
                <option value="Wash">Wash</option>
                <option value="Wash and Dry">Wash and Dry</option>
                <option value="Full Services">Full Service</option>
            </select>

            <label for="schedule-time">Schedule time:</label>
            <input type="time" id="schedule-time" name="schedule_Time" class="time_btn" required>

            <button type="submit" name="bookingbtn" class="book_btn">Book Now</button>
        </form>
    </main>

    <footer>
        <p>&copy; 2023 Streamline Laundry Services. All rights reserved.</p>
    </footer>

    <!-- Sweet Alert Message Script for Booking Function -->
    <script src="../js/booking.js"></script>
</body>
</html>