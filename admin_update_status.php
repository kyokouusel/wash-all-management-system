<?php
session_start();
include 'config.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Ensure the PHPMailer class is loaded

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    // Get customer email from the database
    $sql = "SELECT customers.email, customers.name AS customer_name, customers.id AS customer_id
            FROM orders
            INNER JOIN customers ON orders.customer_id = customers.id
            WHERE orders.id = '$order_id'";

    $result = mysqli_query($conn, $sql);
    if ($row = mysqli_fetch_assoc($result)) {
        $customer_email = $row['email'];
        $customer_name = $row['customer_name'];
        $customer_id = $row['customer_id'];

        // Update the order status in the database
        $update_sql = "UPDATE orders SET status = '$status' WHERE id = '$order_id'";
        if (mysqli_query($conn, $update_sql)) {

            // ✅ Auto-update customer's total_spend and order_frequency if status is COMPLETE
            if (strtolower($status) === 'complete') {
                $update_customer_sql = "
                    UPDATE customers c
                    LEFT JOIN (
                        SELECT customer_id, COUNT(*) AS frequency, SUM(total_amount) AS total
                        FROM orders
                        WHERE status = 'Complete'
                        GROUP BY customer_id
                    ) o ON c.id = o.customer_id
                    SET 
                        c.order_frequency = IFNULL(o.frequency, 0),
                        c.total_spend = IFNULL(o.total, 0.00)
                    WHERE c.id = $customer_id
                ";
                mysqli_query($conn, $update_customer_sql);
            }

            // Send email notification to the customer
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'von4delacruz@gmail.com';
                $mail->Password = 'mwjf uhbu syrk lkyr';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('von4delacruz@gmail.com', 'Laundry System Services');
                $mail->addAddress($customer_email, $customer_name);
                $mail->isHTML(true);
                $mail->Subject = 'Order Status Update';
                $mail->Body = 'Dear ' . $customer_name . ',<br><br>Your order with ID: ' . $order_id . ' has been updated to the following status: ' . $status . '.<br><br>Thank you for using our service.';

                $mail->send();
            } catch (Exception $e) {
                echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }

            header("Location: admin_orders.php");
            exit();
        } else {
            echo "Error updating status: " . mysqli_error($conn);
        }
    } else {
        echo "Customer information not found.";
    }
}

mysqli_close($conn);
?>
