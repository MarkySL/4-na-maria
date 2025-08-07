<?php
//Declaring varable names to each data
$server = "localhost";
$username = "root"; // Change with your web hosting username
$password = ""; // Change with your web hosting password
$db = "stream_laundry"; // Change with your web hosting database name

// Create Connection
$con = new mysqli($server,$username,$password,$db);

//Check Connection
if ($con->connect_error) {
    die("Connetion Error: ".$con->connect_error);
}

# Form Validations
function validate($data){
    $data = trim($data); 
    $data = stripslashes($data); 
    $data = htmlspecialchars($data);

    return $data;
}

# Email Validation
function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Define the base URL directly if not in config.php
$base_url = 'http://localhost:8080/4-na-maria/laundry/streamline-laundry-services/src';
?>