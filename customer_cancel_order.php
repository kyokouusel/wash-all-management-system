<?php
session_start();
include 'config.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_id = $_SESSION['customer_id'];
    $order_id = $_POST['order_id'];
    $page = $_POST['page'] ?? 1;

    // Verify the order belongs to the customer and is pending
    $check_sql = "SELECT id FROM orders WHERE id = '$order_id' AND customer_id = '$customer_id' AND status = 'Pending' LIMIT 1";
    $check_result = mysqli_query($conn, $check_sql);

    if (mysqli_num_rows($check_result) > 0) {
        // Update the order status to Cancelled
        $update_sql = "UPDATE orders SET status = 'Cancelled' WHERE id = '$order_id'";
        if (mysqli_query($conn, $update_sql)) {
            $_SESSION['success_message'] = "Order #$order_id has been cancelled successfully.";
        } else {
            $_SESSION['error_message'] = "Failed to cancel the order. Please try again.";
        }
    } else {
        $_SESSION['error_message'] = "Order not found or cannot be cancelled.";
    }

    // Redirect back to the order history page with the same pagination
    header("Location: order_history.php?page=$page");
    exit();
} else {
    header("Location: order_history.php");
    exit();
}