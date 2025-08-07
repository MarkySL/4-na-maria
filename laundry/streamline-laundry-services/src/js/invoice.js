document.addEventListener('DOMContentLoaded', function() {
    // Get the invoice_id from the URL query parameters
    const urlParams = new URLSearchParams(window.location.search);
    const invoiceId = urlParams.get('invoice_id');

    if (!invoiceId) {
        document.getElementById('invoice-details').innerHTML = '<p>No Result: Please book a service first!</p>'; // Return an error if no invoice ID
        return; // Stop execution if no invoice ID
    }

    // Fetch invoice data for the specific invoice_id
    // Ensure this path is correct relative to where your logic is located
    fetch(`../php/invoice_display.php?invoice_id=${encodeURIComponent(invoiceId)}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok ' + response.statusText);
            }
            return response.json();
        })
    .then(data => {
        // If data is an empty array or has an 'error' property
        if (data.length === 0 || data.error) {
            document.getElementById('invoice-details').innerHTML = '<p>Invoice details not found or an error occurred.</p>';
            return;
        }

        // Since you only expect one service per booking/invoice
        const invoice = data[0]; // Get the first (and only) invoice object

        document.getElementById('invoice-number').textContent = invoice.invoice_no;
        document.getElementById('invoice-date').textContent = invoice.booking_date; 

        // Display the single service and its price
        const serviceParagraph = document.getElementById('service');
        serviceParagraph.innerHTML = `${invoice.service} - ₱${parseFloat(invoice.price).toFixed(2)}`;

        // The total amount is simply the price of this single service
        document.getElementById('total-amount').textContent = `₱${parseFloat(invoice.price).toFixed(2)}`;
    })
    .catch(error => {
        document.getElementById('invoice-details').innerHTML = `<p>Error loading invoice: ${error.message}</p>`;
    });
});