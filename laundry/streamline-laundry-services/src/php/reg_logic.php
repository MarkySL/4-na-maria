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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = validate($_POST['username']) ?? '';
    $email = validate($_POST['email']) ?? ''; 
    $password = validate($_POST['password']) ?? '';
    $confirmPassword = validate($_POST['confirmPassword']) ?? '';

    // --- Add the password confirmation check ---
    if ($password !== $confirmPassword) {
        $response = ['success' => false, 'message' => "Error: Passwords do not match. Please try again."];
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }

    // Hash the password using the PASSWORD_DEFAULT algorithm
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    # Prevent SQL Injection
    $stmt = $con->prepare("INSERT INTO user_reg (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hashedPassword);

    #Execute the statement
    if ($stmt->execute()) {
        $response = ['success' => true, 'message' => "Registration Successful!"];
    } else {
        $response = ['success' => false, 'message' => "Error: There was a problem with your registration. Please try again later."]; // More user-friendly message
    }

    # Close Statement and Connection
    $stmt->close();  
    $con->close(); 

    # Return JSON Response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

?>