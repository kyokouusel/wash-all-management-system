<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userName = strip_tags(trim($_POST['name']));
    $userEmail = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $userMessage = strip_tags(trim($_POST['message']));

    if (!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'invalid_email']);
        exit;
    }

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'von4delacruz@gmail.com'; // Your Gmail
        $mail->Password   = 'gioj xyku azud pxxx'; // Your App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('von4delacruz@gmail.com', 'Laundry System Contact');
        $mail->addReplyTo($userEmail, $userName);
        $mail->addAddress('von4delacruz@gmail.com', 'Admin');

        $mail->isHTML(true);
        $mail->Subject = 'New Contact Form Submission';
        $mail->Body    = "
            <h3>New Message</h3>
            <p><strong>Name:</strong> {$userName}</p>
            <p><strong>Email:</strong> {$userEmail}</p>
            <p><strong>Message:</strong><br>" . nl2br($userMessage) . "</p>
        ";

        $mail->send();
        echo json_encode(['status' => 'success']);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => $mail->ErrorInfo
        ]);
    }
} else {
    echo json_encode(['status' => 'invalid_request']);
}
