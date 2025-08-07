<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration - Streamline Laundry Services</title>
    <link rel="stylesheet" href="../css/styles.css">

    <!--================= Sweet Alert Message 2 Script ================  -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container">
        <h1>Create an Account</h1>
        <form id="registrationForm">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirmPassword">Confirm Password:</label>
                <input type="password" id="confirmPassword" name="confirmPassword" required>
            </div>
            <!-- ====== Register Button ========= -->
            <button type="submit" name="registerbtn" class="reg_btn">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const regForm = document.getElementById("registrationForm"); // Reference the form with its ID

        // Get references to the password and confirm password input fields
        // **IMPORTANT:** Ensure your HTML input fields have these exact IDs.
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('confirmPassword');

        // Check if all necessary elements are found before proceeding for debugging purposes
        if (!regForm || !passwordInput || !confirmPasswordInput) {
            console.error("Error: One or more required form elements (registration_Form, password, confirm_password) not found.");
            // You might want to display a user-friendly message here as well
            return; // Stop execution if elements are missing
        }

        regForm.addEventListener("submit", function (e) {
            e.preventDefault(); // Prevent the normal form submission

            // --- Client-side Password Confirmation Check ---
            // Access the current values directly from the input elements
            const password = passwordInput.value;
            const confirmPassword = confirmPasswordInput.value;

            if (password !== confirmPassword) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Passwords do not match. Please try again.',
                    icon: 'error',
                    timer: 3000, // Give user a bit more time to read this specific error
                    timerProgressBar: true
                });
                return; // STOP the form submission if passwords don't match
            }

            // --- If passwords match, proceed with the fetch request ---
            const formData = new FormData(regForm);

            fetch('../php/reg_logic.php', { // Path of registration logic php script
                method: 'POST',
                body: formData
            })
            .then(response => { // Changed 'data' to 'response' for clarity
                // Check if the response is OK (status 200-299)
                if (!response.ok) {
                    // If the response is not OK, something went wrong on the server side.
                    // We still try to parse it as JSON to get the error message from PHP.
                    return response.json().then(errorData => {
                        throw new Error(errorData.message || 'Server error occurred.');
                    });
                }
                // If response is OK, parse the JSON
                return response.json();
            })
            .then(data => {
                // Handle the JSON response from your PHP script
                if (data.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: data.message,
                        icon: 'success',
                        timer: 2000,
                        timerProgressBar: true
                    }).then(() => {
                        // Refresh the page after the SweetAlert closes
                        setTimeout(() => {
                            window.location.href = 'login.php'; // This will clear the form as well
                        }, 500); // Slight delay for smoother transition
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: data.message, // Display the error message provided by your PHP script
                        icon: 'error',
                        timer: 2000,
                        timerProgressBar: true
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    title: 'Error!',
                    text: error.message || 'An unexpected error occurred during registration. Please try again.',
                    icon: 'error',
                    timer: 3000,
                    timerProgressBar: true
                });
            });
        });
    });
</script>
</body>
</html>