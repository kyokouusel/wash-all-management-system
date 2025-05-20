<?php
session_start();
include 'config.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];
$order_info = null;
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = $_POST['order_id'];

    // Check if the order belongs to the logged-in customer
    $query = "SELECT * FROM orders WHERE id = ? AND customer_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ii", $order_id, $customer_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $order_info = $row;
    } else {
        $error = "Order not found or doesn't belong to your account.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Track My Order</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 30px;
            background-color: #f2f2f2;
        }
        .container {
            max-width: 600px;
            background: white;
            padding: 20px;
            margin: auto;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        h2 {
            margin-bottom: 20px;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: white;
            padding: 10px 18px;
            border: none;
            border-radius: 5px;
        }
        .error {
            color: red;
        }
        .result-box {
            margin-top: 20px;
            padding: 15px;
            background: #f9f9f9;
            border-left: 5px solid #007bff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>🔍 Track Your Order</h2>
        <form method="POST" action="">
            <label for="order_id">Enter Order ID:</label>
            <input type="text" name="order_id" id="order_id" required>
            <input type="submit" value="Track Order">
        </form>

        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php elseif ($order_info): ?>
            <div class="result-box">
                <p><strong>Order ID:</strong> <?php echo $order_info['id']; ?></p>
                <p><strong>Service Type:</strong> <?php echo $order_info['service_type']; ?></p>
                <p><strong>Weight:</strong> <?php echo $order_info['weight']; ?> kg</p>
                <p><strong>Detergent Type:</strong> <?php echo $order_info['detergent_type']; ?></p>
                <p><strong>Measurement:</strong> <?php echo $order_info['detergent_amount']; ?></p>
                <p><strong>Order Date:</strong> <?php echo $order_info['order_date']; ?></p>
                <p><strong>Status:</strong> <?php echo $order_info['status']; ?></p>
            </div>
        <?php endif; ?>

        <br><a href="dashboard.php">⬅ Back to Dashboard</a>
        
    </div>
</body>
</html>
