<?php
session_start();
include 'config.php';
require 'vendor/autoload.php';  // Load PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    // Update order status
    $sql = "UPDATE orders SET status = '$status' WHERE id = '$order_id'";
    
    if (mysqli_query($conn, $sql)) {
        // Get customer's email and name
        $query = "SELECT customers.email, customers.name, orders.service_type 
                  FROM orders 
                  INNER JOIN customers ON orders.customer_id = customers.id 
                  WHERE orders.id = '$order_id'";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);

        if ($row) {
            $email = $row['email'];
            $name = $row['name'];
            $service = $row['service_type'];

            // Send email
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'von4delacruz@gmail.com'; // Your Gmail
                $mail->Password = 'xiqu veag hdzb ocne';     // App password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('von4delacruz@gmail.com', 'Laundry System Services');
                $mail->addAddress($email, $name);

                $mail->isHTML(true);
                $mail->Subject = 'Your Laundry Order Status Update';
                $mail->Body = "Hello <strong>$name</strong>,<br>Your laundry order for <strong>$service</strong> is now <strong>$status</strong>.<br><br>Thank you for choosing our service!";
                $mail->AltBody = "Hello $name,\nYour laundry order for $service is now $status.\n\nThank you!";

                $mail->send();
            } catch (Exception $e) {
                error_log("Email could not be sent. Mailer Error: {$mail->ErrorInfo}");
            }
        }

        header("Location: admin_orders.php");
        exit();
    } else {
        echo "Error updating status: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>
