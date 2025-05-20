<?php
session_start();
include 'config.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Ensure the PHPMailer class is loaded

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];
$order_id = $_POST['order_id'];

// Get customer email from the database
$sql = "SELECT * FROM orders WHERE id = '$order_id' AND customer_id = '$customer_id' AND (status = 'Pending' OR status = 'In Progress')";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $order = mysqli_fetch_assoc($result);
    $customer_email = $order['customer_email']; // Assuming there's a column customer_email in orders table
    $customer_name = $order['customer_name'];   // Assuming the order has a customer_name field

    // Update the order status to 'Cancelled'
    $update_sql = "UPDATE orders SET status = 'Cancelled' WHERE id = '$order_id'";
    if (mysqli_query($conn, $update_sql)) {
        // Prepare PHPMailer to send the emails

        // Send cancellation email to customer
        $mail = new PHPMailer(true);
        try {
            // Enable debugging
            $mail->SMTPDebug = 2; // Show detailed debug output

            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'von4delacruz@gmail.com';  // Replace with your email
            $mail->Password = 'xiqu veag hdzb ocne';  // Replace with your app password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Customer email settings
            $mail->setFrom('von4delacruz@gmail.com', 'Laundry System Services');
            $mail->addAddress($customer_email, $customer_name);  // Customer's email and name
            $mail->isHTML(true);
            $mail->Subject = 'Order Cancellation Confirmation';
            $mail->Body = 'Dear ' . $customer_name . ',<br><br>Your order with ID: ' . $order_id . ' has been successfully cancelled.<br><br>Thank you for using our service.';

            // Send email to customer
            if (!$mail->send()) {
                echo "Customer email could not be sent. Mailer Error: {$mail->ErrorInfo}";
            } else {
                echo "Customer email sent successfully!";
            }

            // Optionally, send email to the admin about the cancellation
            $mail->clearAddresses();  // Clear recipient addresses for the admin notification
            $mail->addAddress('admin_email@example.com', 'Admin');  // Replace with admin's email address
            $mail->Subject = 'Order Cancellation Notification';
            $mail->Body = 'An order with ID: ' . $order_id . ' has been cancelled by the customer ' . $customer_name . '.';

            // Send email to admin
            if (!$mail->send()) {
                echo "Admin email could not be sent. Mailer Error: {$mail->ErrorInfo}";
            } else {
                echo "Admin email sent successfully!";
            }

        } catch (Exception $e) {
            echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

        header("Location: order_history.php");
        exit();
    } else {
        echo "Error canceling order: " . mysqli_error($conn);
    }
} else {
    echo "Order not found or cannot be cancelled.";
}

mysqli_close($conn);
?>
