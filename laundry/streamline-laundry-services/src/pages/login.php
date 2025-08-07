<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Streamline Laundry Services</title>
    <link rel="stylesheet" href="../css/styles.css">

    <!--================= Sweet Alert Message 2 Script ================-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container">
        <h1>Login to Your Account</h1>
        <form id="loginForm">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <!-- ====== Login Button ========= -->
            <button type="submit" name="loginbtn" class="login_btn">Login</button>

            <p><a href="forgot-password.php">Forgot Password?</a></p>
            <p>Don't have an account? <a href="registration.php">Register here</a></p>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const loginForm = document.getElementById("loginForm");

            loginForm.addEventListener("submit", function (e) {
                e.preventDefault(); // Prevent the normal form submission

                const formData = new FormData(loginForm);

                fetch('../php/login_logic.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    // Check if the response is OK (status 200) and handle errors gracefully
                    if (!response.ok) {
                        return response.json().then(errorData => {
                            throw new Error(errorData.message || 'Server error occurred.');
                        }).catch(() => {
                            // If JSON parsing fails or no message, throw a generic error
                            throw new Error('An unknown server error occurred.');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: 'Success!',
                            text: 'Login successful! Redirecting...',
                            icon: 'success',
                            timer: 1500,
                            timerProgressBar: true,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = 'booking.php'; // Redirect to booking page
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: data.message || 'Invalid credentials or an unknown error occurred.',
                            icon: 'error',
                            confirmButtonText: 'Try Again'
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        title: 'Error!',
                        text: 'An unexpected error occurred. Please try again later.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                });
            });
        });
    </script>
</body>
</html>