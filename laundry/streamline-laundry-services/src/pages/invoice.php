<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - Streamline Laundry Services</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <header>
        <h1>4 Na Maria Laundromat</h1>
        <nav>
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="booking.php">Book a Service</a></li>
                <li><a href="history.php">View Transactions</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <center>
            <section id="invoice">
                <h2>Your Invoice</h2>
                <div id="invoice-details">
                    <p>Invoice Number: <span id="invoice-number"></span></p>
                    <p>Date: <span id="invoice-date"></span></p>
                    <h3>Service Rendered:</h3>
                    <p id="service"></p>
                    <h3>Total Amount: <span id="total-amount"></span></h3>
                </div>
                <button id="make-a-payment">Make a Payment</button>
                <form id="payment-form" style="display: none; margin-top: 20px;">
                    <h3>Payment Method</h3>
                    <label for="payment-method">Choose a payment method:</label>
                    <select id="payment-method" name="payment-method" required>
                        <option value="gcash">GCash</option>
                        <option value="cash-on-pickup">Cash on Pickup</option>
                        <option value="maya">Maya</option>
                </form>
            </section>
        </center>
    </main>

    <footer>
        <p>&copy; 2023 Streamline Laundry Services. All rights reserved.</p>
    </footer>

    <script>
        document.getElementById('make-a-payment').addEventListener('click', function() {
            document.getElementById('payment-form').style.display = 'block';
        });
    </script>
    
    <!-- Fetch API for Invoice Page -->
    <script src="../js/invoice.js"></script>
</body>
</html>