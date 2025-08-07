<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Streamline Laundry Services</title>
    <link rel="stylesheet" href="../css/styles.css">

    <!--================= Sweet Alert Message 2 Script ================  -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container">
        <h1>Forgot Password</h1>
        <form id="forgot-password-form">
            <label for="email">Enter your email address:</label>
            <input type="email" id="email" name="email" required>
            <button type="submit" class="forgot_btn">Reset</button>
        </form>
        <p>Remembered your password? <a href="login.php">Login here</a></p>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const forgotPasswordForm = document.getElementById("forgot-password-form");

            forgotPasswordForm.addEventListener("submit", function (e) {
                e.preventDefault(); // Prevent default form submission

                const formData = new FormData(forgotPasswordForm);
                const emailInput = document.getElementById('email').value;

                // Basic client-side validation
                if (!emailInput || !emailInput.includes('@')) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid Email',
                        text: 'Please enter a valid email address.',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                // Show loading spinner while fetching
                Swal.fire({
                    title: 'Processing...',
                    text: 'Sending password reset link...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch('../php/forgot_logic.php', { // Make sure this path is correct
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        // Attempt to parse JSON error if available, otherwise generic error
                        return response.json().then(errorData => {
                            throw new Error(errorData.message || 'Server responded with an error.');
                        }).catch(() => {
                            throw new Error('Network response was not ok.');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    Swal.close(); // Close the loading spinner

                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: data.message, // This will be the generic "If your email is..." message
                            confirmButtonText: 'OK'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: data.message || 'An error occurred. Please try again.',
                            confirmButtonText: 'OK'
                        });
                    }
                })
                .catch(error => {
                    Swal.close(); // Close the loading spinner
                    console.error('Fetch error:', error); // Keep this for debugging fetch issues
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Could not send reset request. Please check your internet connection and try again.',
                        confirmButtonText: 'OK'
                    });
                });
            });
        });
    </script>
</body>
</html>