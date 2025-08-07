<?php
// Start session for potential token/email storage (though not strictly necessary if passed via GET)
session_start();

// Ensure error logging is set for debugging potential PHP errors during development
ini_set('log_errors', 1);
ini_set('error_log', '../error/error.log'); // Separate log for reset errors
ini_set('display_errors', 0); // Hide errors from screen in production

// Get token and email from URL parameters
$token = $_GET['token'] ?? '';
$email = $_GET['email'] ?? '';

// Basic validation for presence (more detailed validation on the server-side)
if (empty($token) || empty($email)) {
    // Redirect or show an error if token or email is missing
    header('Location: login.php?error=invalid_reset_link');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Streamline Laundry Services</title>
    <link rel="stylesheet" href="../css/styles.css">

    <!--================= Sweet Alert Message 2 Script ================  -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container">
        <h1>Reset Your Password</h1>
        <form id="reset-password-form">
            <!-- Hidden inputs to pass token and email securely -->
            <input type="hidden" id="auth_token" name="token" value="<?php echo htmlspecialchars($token); ?>">
            <input type="hidden" id="confirmed_email" name="email" value="<?php echo htmlspecialchars($email); ?>">

            <div class="form-group">
                <label for="new-password">New Password</label>
                <input type="password" id="new_password" name="new_pass" required>
            </div>

            <div class="form-group">
                <label for="confirm-password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_pass" required>
            </div>
            <button type="submit" class="rst_btn">Reset Password</button>
        </form>
        <p>Remembered your password? <a href="login.php">Login here</a></p>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Get a reference to the form
            const resetPasswordForm = document.getElementById('reset-password-form');

            // Add an event listener for the form submission
            resetPasswordForm.addEventListener('submit', function(event) {
                event.preventDefault(); // Stop the default form submission (which would cause a page reload)

                // Get values from form inputs
                const newPassword = document.getElementById('new_password').value;
                const confirmPassword = document.getElementById('confirm_password').value;
                const token = document.getElementById('auth_token').value; // Value from hidden input
                const email = document.getElementById('confirmed_email').value;   // Value from hidden input

                // Client-side validation for password match and length
                if (newPassword !== confirmPassword) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Password Mismatch',
                        text: 'New password and confirm password do not match.'
                    });
                    return; // Stop execution if validation fails
                }

                // Prepare data for sending using FormData (suitable for form data) should match the reset form
                const formData = new FormData();
                formData.append('token', token);
                formData.append('email', email);
                formData.append('new_pass', newPassword);
                formData.append('confirm_pass', confirmPassword);

                // Use Fetch API to send the data to your PHP logic
                fetch('../php/reset_logic.php', {
                    method: 'POST', // Use POST method for sensitive data
                    body: formData  // The FormData object is automatically correctly encoded
                })
                .then(response => {
                    // Check if the response is OK (status 200-299)
                    if (!response.ok) {
                        throw new Error('Network response was not ok: ' + response.statusText);
                    }
                    return response.json(); // Parse the JSON response from the PHP script
                })
                .then(data => {
                    // Handle the response from your PHP script
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Password Reset!',
                            text: data.message,
                            showConfirmButton: false, // Hide the OK button
                            timer: 2000 // Automatically close after 2 seconds
                        }).then(() => {
                            // After the SweetAlert closes, redirect to the login page
                            window.location.href = 'login.php';
                        });
                    } else {
                        // Show an error SweetAlert if the PHP script returned success: false
                        Swal.fire({
                            icon: 'error',
                            title: 'Reset Failed',
                            text: data.message // Display the error message from the PHP script
                        });
                    }
                })
                .catch(error => {
                    // Catch any network errors or errors during parsing the response
                    console.error('Fetch Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An unexpected error occurred. Please try again later.'
                    });
                });
            });
        });
    </script>
</body>
</html>