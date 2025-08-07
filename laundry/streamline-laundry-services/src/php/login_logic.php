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
    $email = validate($_POST['email']) ?? '';
    $password = validate($_POST['password']) ?? '';

    $stmt = $con->prepare("SELECT * FROM user_reg WHERE email = ?");
    $stmt->bind_param("s", $email);

    if ($stmt->execute()) { 
        $result = $stmt->get_result();
        $login_row = $result->fetch_assoc();

        if ($login_row) {
            $hashedPassword = $login_row['password'];
            // Verify the entered password against the stored hashed password
            if (password_verify($password, $hashedPassword)) {
                $userId = $login_row['id']; // Default user ID column name is 'id' from the database

                // Login successful
                $_SESSION['loggedin'] = true; // Set a session variable to indicate logged in status
                $_SESSION['user_id'] = $userId; // Store user ID for later use (e.g., retrieving user data)
                $_SESSION['email'] = $email; // Store email as well, or username

                // Regenerate session ID to prevent session fixation attacks
                session_regenerate_id(true);
                
                $response = ['success' => true, 'message' => "Login Successfully!"];
            } else {
                $response = ['success' => false, 'message' => "Invalid email or password."];
            }
        } else {
            $response = ['success' => false, 'message' => "Invalid email or password."]; // More user-friendly message
        }
    } else {
        /*echo "Error in database query: " . mysqli_error($con);*/
        echo "Error in database query" . $stmt->error;
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