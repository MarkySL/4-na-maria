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

// Function to generate the invoice number
function generateInvoiceNumber($con) {
    $prefix = 'SL-25'; // Defined prefix for the invoice number
    $numDigits = 3; // Defined how many digits for sequential

    // Querying the database
    $result = $con->query("SELECT MAX(invoice_no) AS gen_Invoice FROM services WHERE invoice_no LIKE '{$prefix}%'");
    $row = $result->fetch_assoc();
    $gen_invoiceNo = $row['gen_Invoice'];

    if ($gen_invoiceNo) {
        $startPos = strlen($prefix);
        $currentNumber = (int)substr($gen_invoiceNo, $startPos); // e.g., for 'SL-25005', this gets 5.
        $nextNumber = $currentNumber + 1;
        $paddedNumber = str_pad($nextNumber, $numDigits, '0', STR_PAD_LEFT);
        return $prefix . $paddedNumber;
    } else {
        return $prefix . str_pad(0, $numDigits, '0', STR_PAD_LEFT);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $service = validate($_POST['service']) ?? ''; 
    $timeInput = validate($_POST['schedule_Time']) ??'';

    // Set price based on selected service
    // The case should match the values in the form
    switch ($service) {
        case 'Wash':
            $price = 120;
            break;
        case 'Wash and Dry':
            $price = 170;
            break;
        case 'Full Services':
            $price = 220;
            break;
        default:
            $price = 0; // or handle as an error if the service isn't recognized
            break;
    }

    // Generate invoice number
    $invoiceNumber = generateInvoiceNumber($con);

    // Generate of date of creation
    $dateOfCreation = date('Y-m-d');

    // Add seconds to the time input to make it a valid time format for the database
    $timeFormatted = $timeInput . ':00';

    # Prevent SQL Injection
    $stmt = $con->prepare("INSERT INTO services (invoice_no, service, time, booking_date, price) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssd", $invoiceNumber, $service, $timeFormatted, $dateOfCreation, $price);

    #Execute the statement
    if ($stmt->execute()) {
        $response = [
            'success' => true,
            'message' => "Booking Successfully! Proceed to payment.",
            'invoice_id' => $invoiceNumber // <--- THIS IS CRUCIAL: Send the invoice ID back
        ];
    } else {
        $response = ['success' => false, 'message' => "Error in booking: " . $stmt->error];
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