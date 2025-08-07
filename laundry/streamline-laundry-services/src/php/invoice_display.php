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

// Check if an invoice_id is present in the URL
if (isset($_GET['invoice_id'])) {
    $invoice_id = $_GET['invoice_id'];

    // Prepare and execute the statement to fetch only the specific invoice details
    // Ensure the order of columns in SELECT matches the order in bind_result
    $stmt = $con->prepare("SELECT invoice_no, service, booking_date, price FROM services WHERE invoice_no = ?");
    $stmt->bind_param("s", $invoice_id); // 's' for string (invoice_no is a string)
    $stmt->execute();

    // Bind result variables
    $stmt->bind_result($db_invoice_no, $db_service, $db_booking_date, $db_price);

    // Fetch the single row
    $invoice_data = [];
    if ($stmt->fetch()) {
        $invoice_data = [
            'invoice_no' => $db_invoice_no,
            'booking_date' => $db_booking_date,
            'service' => $db_service,
            'price' => $db_price
        ];
    }

    // Close statement and connection
    $stmt->close();
    $con->close();

    // Return JSON Response
    header('Content-Type: application/json');
    // If no data was found for the invoice_id, return an empty array or an error
    echo json_encode($invoice_data ? [$invoice_data] : []); // Wrap in an array for consistency with the Fetch JS expecting an array
    exit();

} else {
    // If no invoice_id is provided, return an error or an empty array
    header('Content-Type: application/json');
    echo json_encode(['error' => 'No invoice ID provided.']);
    exit();
}

?>