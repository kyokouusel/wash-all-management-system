<?php
session_start();

// Ensure the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

include 'config.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_id = $_POST['customer_id'];

    // Fetch order details along with customer name
    $sql = "SELECT orders.id AS order_id, orders.service_type, orders.weight, orders.order_date, orders.status, customers.name AS customer_name
            FROM orders
            INNER JOIN customers ON orders.customer_id = customers.id
            WHERE orders.customer_id = ?";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $customer_id); // Bind the customer_id parameter
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Customer Orders</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
        }
        h2 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        a {
            color: #007BFF;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<h2>Customer Order History</h2>

<!-- Form to enter Customer ID -->
<form method="post" action="admin_customer_orders.php">
    <label for="customer_id">Enter Customer ID:</label>
    <input type="text" id="customer_id" name="customer_id" required>
    <input type="submit" value="Search Orders">
</form>

<?php
// Display orders if result exists
if (isset($result) && mysqli_num_rows($result) > 0) {
    echo "<table>";
    echo "<tr>
            <th>Order ID</th>
            <th>Customer Name</th>
            <th>Service Type</th>
            <th>Weight (kg)</th>
            <th>Order Date</th>
            <th>Status</th>
          </tr>";

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>" . $row['order_id'] . "</td>
                <td>" . $row['customer_name'] . "</td>
                <td>" . $row['service_type'] . "</td>
                <td>" . $row['weight'] . "</td>
                <td>" . $row['order_date'] . "</td>
                <td>" . $row['status'] . "</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p>No orders found for this customer.</p>";
}
?>

<br>
<a href="admin_dashboard.php">Back to Dashboard</a> | 
<a href="admin_orders.php">Manage Orders</a>

</body>
</html>
