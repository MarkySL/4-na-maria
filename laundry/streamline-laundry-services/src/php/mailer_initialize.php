<?php
use PHPMailer\PHPMailer\PHPMailer;
use Dotenv\Dotenv;

// Require the composer
require __DIR__ . '/../../vendor/autoload.php';

function getMailer(){
    // Load environment variables from .env file in the relative path 
    $dotenv = Dotenv::createImmutable(__DIR__ . '/../env');
    $dotenv->load();

    // Get environment variables
    $username = $_ENV['GMAIL_USERNAME'];
    $password = $_ENV['GMAIL_APP_PASSWORD'];

    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);

    // Set mailer to use SMTP
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // Set SMTP host
    $mail->Port = 587; // Set SMTP port
    $mail->SMTPAuth = true; // Enable SMTP authentication
    $mail->Username = $username; // Your Gmail username
    $mail->Password = $password; // Your app-specific password
    $mail->SMTPSecure = 'tls'; // Enable TLS encryption
    
    return $mail;
}
?>