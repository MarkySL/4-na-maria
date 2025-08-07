document.addEventListener("DOMContentLoaded", function () {
    const regForm = document.getElementById("booking-form"); // Reference the form with its ID

    regForm.addEventListener("submit", function (e) {
        e.preventDefault(); // Prevent the normal form submission
        
        const formData = new FormData(regForm);

        fetch('../php/booking_logic.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: 'Success!',
                    text: data.message,
                    icon: 'success',
                    timer: 2000,
                    timerProgressBar: true
                }).then(() => {
                    // After SweetAlert closes, redirect to invoice page with the invoice_id
                    // Ensure data.invoice_id is available from the PHP response
                    if (data.invoice_id) {
                        // Redirect to the invoice page, passing the invoice_id as a URL parameter
                        // Using 'invoice.php' as the target page or whatever your page is named
                        window.location.href = `invoice.php?invoice_id=${encodeURIComponent(data.invoice_id)}`;
                    } else {
                        // Fallback if invoice_id is not present in the response
                        console.error("Invoice ID not received from server after successful booking.");
                        Swal.fire({
                            title: 'Booking Complete!',
                            text: 'Your booking was successful, but there was an issue retrieving the invoice ID. Please check your booking history.',
                            icon: 'warning'
                        });
                    }
                });
            } else {
                Swal.fire({
                    title: 'Error!',
                    text: data.message,
                    icon: 'error',
                    timer: 2000,
                    timerProgressBar: true
                });
            }
        })
        .catch(error => {
            Swal.fire({
                title: 'Error!',
                text: 'There was an error processing your request.',
                icon: 'error',
                timer: 2000,
                timerProgressBar: true
            });
        });
    });
});