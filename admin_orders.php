<?php
session_start();
include 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Pagination settings
$limit = 10; // Orders per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Search functionality
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$search_query = "";
$param_types = "";
$params = [];

if (!empty($search)) {
    $search_query = " WHERE orders.id LIKE ? OR orders.customer_id LIKE ? ";
    $param_types = "ss";
    $search_param = "%" . $search . "%";
    $params[] = $search_param;
    $params[] = $search_param;
}

// Count total for pagination
$sql_count = "SELECT COUNT(*) AS total_orders FROM orders 
              INNER JOIN customers ON orders.customer_id = customers.id
              $search_query";
$stmt_count = $conn->prepare($sql_count);
if (!empty($search)) {
    $stmt_count->bind_param($param_types, ...$params);
}
$stmt_count->execute();
$result_count = $stmt_count->get_result();
$total_orders = $result_count->fetch_assoc()['total_orders'];
$total_pages = ceil($total_orders / $limit);

// Fetch filtered orders
$sql = "SELECT orders.id AS order_id, orders.customer_id, orders.service_type, orders.weight, orders.order_date, orders.status, orders.total_amount, customers.name AS customer_name
        FROM orders
        INNER JOIN customers ON orders.customer_id = customers.id
        $search_query
        ORDER BY orders.order_date DESC
        LIMIT ?, ?";
$stmt = $conn->prepare($sql);

if (!empty($search)) {
    $param_types .= "ii";
    $params[] = $offset;
    $params[] = $limit;
    $stmt->bind_param($param_types, ...$params);
} else {
    $stmt->bind_param("ii", $offset, $limit);
}
$stmt->execute();
$result = $stmt->get_result();
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
            <h3>Manage Orders</h3>
            <div class="topbar">
                <div class="search">
                    <input type="text" placeholder="Search. . .">
                </div>

                <div class="user-profile">
                    <span>Welcome, Admin</span>
                    <a href="">
                        <img src="img/usericonwhite.png" alt="user" class="click-user"></a>
                </div>
            </div>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background-color: #f4f4f4;
        }
        h2 {
            color: #4CAF50;
        }
        form.search-form {
            margin-bottom: 20px;
        }
        input[type="text"] {
            padding: 8px;
            width: 250px;
        }
        button.search-btn {
            padding: 8px 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        button.search-btn:hover {
            background-color: #45a049;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        .pagination {
            margin-top: 20px;
            text-align: center;
        }
        .pagination a {
            margin: 0 5px;
            padding: 8px 16px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        .pagination a:hover {
            background-color: #0056b3;
        }
        .delete-btn {
            background-color: #f44336; 
            color: white; 
            padding: 5px 10px; 
            border: none; 
            cursor: pointer;
        }
        .delete-btn:hover {
            background-color: #d32f2f;
        }
        .update-btn {
            background-color: #4CAF50;
            color: white;
            padding: 5px 10px;
            border: none;
            cursor: pointer;
        }
        .update-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

    <!-- Search Form -->
    <form method="get" action="admin_orders.php" class="search-form">
        <input type="text" name="search" placeholder="Search by Order ID or Customer ID" value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit" class="search-btn">Search</button>
    </form>

    <?php if ($result->num_rows > 0): ?>
        <table style=" color: black;">
            <tr>
                <th>Order ID</th>
                <th>Customer ID</th>
                <th>Customer Name</th>
                <th>Service Type</th>
                <th>Weight (kg)</th>
                <th>Total Amount</th>
                <th>Order Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>

            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['order_id']; ?></td>
                    <td><?php echo $row['customer_id']; ?></td>
                    <td><?php echo $row['customer_name']; ?></td>
                    <td><?php echo $row['service_type']; ?></td>
                    <td><?php echo $row['weight']; ?></td>
                    <td>₱<?php echo number_format($row['total_amount'], 2); ?></td>
                    <td><?php echo $row['order_date']; ?></td>
                    <td>
                        <?php echo $row['status']; ?>
                        <br><br>
                        <form method="post" action="admin_update_status.php">
                            <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                            <select name="status" required>
                                <option value="Pending" <?php if ($row['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                                <option value="In Progress" <?php if ($row['status'] == 'In Progress') echo 'selected'; ?>>In Progress</option>
                                <option value="Complete" <?php if ($row['status'] == 'Complete') echo 'selected'; ?>>Complete</option>
                            </select>
                            <button type="submit" class="update-btn">Update Status</button>
                        </form>
                    </td>
                    <td>
                        <form method="post" action="admin_delete_order.php" onsubmit="return confirm('Are you sure you want to delete this order?');">
                            <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                            <button type="submit" class="delete-btn">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>

        <!-- Pagination Links -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="admin_orders.php?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>">Previous</a>
            <?php endif; ?>
            
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="admin_orders.php?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>" <?php if ($i == $page) echo 'style="background-color: #0056b3;"'; ?>>
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>
            
            <?php if ($page < $total_pages): ?>
                <a href="admin_orders.php?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>">Next</a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <p>No orders found.</p>
    <?php endif; ?>

    <br>
    <a style="color: white;" href="export_orders_pdf.php?page=<?php echo $page; ?>&search=<?php echo urlencode($search); ?>" class="btn">Export to PDF</a>
    <a href="admin_dashboard.php">Back to Dashboard</a>
</body>
</html>
