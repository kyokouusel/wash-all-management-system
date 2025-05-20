<?php
session_start();
include 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = $_POST['order_id'];

    // Delete order from database
    $sql = "DELETE FROM orders WHERE id = '$order_id'";

    if (mysqli_query($conn, $sql)) {
        // Redirect to the orders page after deletion
        header("Location: admin_orders.php");
        exit();
    } else {
        echo "Error deleting order: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>
