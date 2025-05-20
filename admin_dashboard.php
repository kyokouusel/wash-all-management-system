<?php
session_start();
include 'config.php';
date_default_timezone_set('Asia/Manila');

// Ensure admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

function getCount($conn, $query) {
    $stmt = mysqli_prepare($conn, $query);
    if ($stmt) {
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result)['total'] ?? 0;
    }
    return 0;
}

// Total orders
$total_orders = getCount($conn, "SELECT COUNT(*) AS total FROM orders");

// Pending orders
$pending_orders = getCount($conn, "SELECT COUNT(*) AS total FROM orders WHERE status = 'Pending'");

// Processing orders
$processing_orders = getCount($conn, "SELECT COUNT(*) AS total FROM orders WHERE status = 'Processing'");

// Completed orders
$completed_orders = getCount($conn, "SELECT COUNT(*) AS total FROM orders WHERE status = 'Completed'");

// Total customers
$total_customers = getCount($conn, "SELECT COUNT(*) AS total FROM customers");

// Get daily revenue (only 'Complete' orders)
$today = date('Y-m-d');
$daily_revenue = 0;

$daily_stmt = mysqli_prepare($conn, "SELECT COALESCE(SUM(total_amount), 0) AS daily_revenue FROM orders WHERE DATE(order_date) = ? AND status = 'Complete'");
if ($daily_stmt) {
    mysqli_stmt_bind_param($daily_stmt, "s", $today);
    mysqli_stmt_execute($daily_stmt);
    $result = mysqli_stmt_get_result($daily_stmt);
    $daily_revenue = mysqli_fetch_assoc($result)['daily_revenue'] ?? 0;
}

// Get monthly revenue (only 'Complete' orders)
$current_month = date('Y-m');
$monthly_revenue = 0;

$monthly_stmt = mysqli_prepare($conn, "SELECT COALESCE(SUM(total_amount), 0) AS monthly_revenue FROM orders WHERE DATE_FORMAT(order_date, '%Y-%m') = ? AND status = 'Complete'");
if ($monthly_stmt) {
    mysqli_stmt_bind_param($monthly_stmt, "s", $current_month);
    mysqli_stmt_execute($monthly_stmt);
    $result = mysqli_stmt_get_result($monthly_stmt);
    $monthly_revenue = mysqli_fetch_assoc($result)['monthly_revenue'] ?? 0;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="adminstyle/style.css">
    <meta name="viewport">
    <link rel="icon" type="image/png" href="../User/image/Washalllogo.png">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>
    <div class="dashboard">
        <div class="sidebar">
            <div class="heads">
                <img src="img/Washalllogo.png" alt="Logo">
            </div>
            <ul class="menu">
                <li>
                    <a href="admin_dashboard.php" class="active">
                        <img src="img/dashboardwhite.png" alt="dashboard">
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="admin_orders.php">
                        <img src="img/orderswhite.png" alt="products">
                        <span>Manage Orders</span>
                    </a>
                </li>
                <li>
                    <a href="admin_customer_profile.php">
                        <img src="img/costumerorder.png" alt="reports">
                        <span>Customer Profile</span>
                    </a>
                </li>
                <li>
                    <a href="index.php">
                        <img src="img/logoutwhite.png" alt="logout">
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="main-content">
            <h3>Dashboard</h3>
            <div class="topbar">
                <div class="search">
                    <input type="text" placeholder="Search. . .">
                </div>

                <div class="user-profile">
                    <span> Welcome, Admin</span>
                        <img src="img/usericonwhite.png" alt="user" class="click-user"></a>
                </div>
            </div>
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
                ul {
                    list-style-type: none;
                    padding: 0;
                }
               
                a {
                    color: #007BFF;
                    text-decoration: none;
                    margin: 0 10px;
                }
                a:hover {
                    text-decoration: underline;
                }
                .nav-links {
                    margin-bottom: 20px;
                }
        
        ul {
            list-style-type: none;
            padding: 0;
        }
        ul li {
            margin: 5px 0;
            padding: 10px;
            border-radius: 5px;
        }
        .analytics-container {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }
        .box {
            flex: 1;
            background-color: #007BFF;
            color: white;
            padding: 20px;
            border-radius: 8px;
        }
        .box h3 {
            margin: 0;
            font-size: 18px;
        }
        .box p {
            font-size: 24px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    

    <!-- Revenue Analytics -->
    <div class="analytics-container">
        <div class="box" style="background-color: #28a745;">
            <h3>Today's Revenue</h3>
            <p>₱<?php echo number_format($daily_revenue, 2); ?></p>
        </div>
        <div class="box" style="background-color: #17a2b8;">
            <h3>This Month's Revenue</h3>
            <p>₱<?php echo number_format($monthly_revenue, 2); ?></p>
        </div>
    </div>

    <!-- Order and Customer Summary -->
    <ul  >
        <li style="background-color: #0B1C3A;" ><strong>Total Orders:</strong> <?php echo $total_orders; ?></li>
        <li style="background-color: #0B1C3A;"><strong>Pending Orders:</strong> <?php echo $pending_orders; ?></li>
        <li style="background-color: #0B1C3A;" ><strong>Processing Orders:</strong> <?php echo $processing_orders; ?></li>
        <li style="background-color: #0B1C3A;"><strong>Completed Orders:</strong> <?php echo $completed_orders; ?></li>
        <li style="background-color: #0B1C3A;"><strong>Total Customers:</strong> <?php echo $total_customers; ?></li>
    </ul>

</body>
</html>