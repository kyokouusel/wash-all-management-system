<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';  // Load Composer's autoloader

$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();                                            
    $mail->Host       = 'smtp.gmail.com';                     
    $mail->SMTPAuth   = true;                                   
    $mail->Username   = 'von4delacruz@gmail.com';  // your Gmail
    $mail->Password   = 'xiqu veag hdzb ocne';    // app password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         
    $mail->Port       = 587;

    // Recipients
    $mail->setFrom('von4delacruz@gmail.com', 'Laundry System Services');
    $mail->addAddress('von4delacruz@gmail.com', 'Test Customer'); // same as your account for now

    // Content
    $mail->isHTML(true);                                  
    $mail->Subject = 'Test Email from Laundry System';
    $mail->Body    = '<b>Hello!</b><br>This is a test email from your laundry system.';
    $mail->AltBody = 'Hello! This is a test email from your laundry system.';

    $mail->send();
    echo 'Message has been sent successfully.';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
