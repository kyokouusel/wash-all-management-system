<?php
include 'config.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Pagination settings
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Filtering
$search_query = "";
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = $conn->real_escape_string($_GET['search']);
    $search_query = "WHERE name LIKE '%$search%' OR email LIKE '%$search%'";
}

// Total count
$count_sql = "SELECT COUNT(*) FROM customers $search_query";
$count_result = $conn->query($count_sql);
$count = $count_result->fetch_row()[0];
$total_pages = ceil($count / $limit);

// Fetch customers with new columns
$sql = "SELECT id, name, email, phone, address, order_frequency, total_spend 
        FROM customers $search_query 
        ORDER BY name ASC 
        LIMIT $start, $limit";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer Profile</title>
    <link rel="stylesheet" href="adminstyle/style.css">
    <meta name="viewport">
    <link rel="icon" type="image/png" href="../User/image/Washalllogo.png">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 30px;
            background-color: #f4f4f4;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            max-width: 1100px;
            margin: auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        .search-box {
            margin-bottom: 20px;
        }
        .search-box input {
            padding: 8px;
            width: 300px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .pagination {
            margin-top: 20px;
            display: flex;
            justify-content: center;
        }
        .pagination a {
            padding: 8px 15px;
            margin: 0 5px;
            text-decoration: none;
            background-color: #4CAF50;
            color: white;
            border-radius: 5px;
        }
        .pagination a:hover {
            background-color: #45a049;
        }
        .pagination a.active {
            background-color: #2196F3;
        }
        .buttons a {
            padding: 10px 15px;
            background-color: #4CAF50;
            border: none;
            color: white;
            text-decoration: none;
            margin-top: 20px;
            border-radius: 5px;
        }
        .buttons a:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
<div class="dashboard">
    <div class="sidebar">
        <div class="heads">
            <img src="img/Washalllogo.png" alt="Logo">
        </div>
        <ul class="menu">
            <li><a href="admin_dashboard.php" class="active"><img src="img/dashboardwhite.png" alt="dashboard"><span>Dashboard</span></a></li>
            <li><a href="admin_orders.php"><img src="img/orderswhite.png" alt="products"><span>Manage Orders</span></a></li>
            <li><a href="admin_customer_profile.php"><img src="img/costumerorder.png" alt="reports"><span>Customer Profile</span></a></li>
            <li><a href="index.php"><img src="img/logoutwhite.png" alt="logout"><span>Logout</span></a></li>
        </ul>
    </div>
    <div class="main-content">
        <h3>Customer List</h3>
        <div class="topbar">
            <div class="search">
                <input type="text" placeholder="Search. . .">
            </div>
            <div class="user-profile">
                <span>Welcome, Admin</span>
                <a href="#"><img src="img/usericonwhite.png" alt="user" class="click-user"></a>
            </div>
        </div>

        <div style="color: black;" class="container">
            <!-- Search -->
            <form method="GET" class="search-box">
                <input type="text" name="search" placeholder="Search by name or email" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                <button type="submit">Search</button>
            </form>

            <!-- Back Button -->
            <?php if (isset($_GET['search']) && !empty($_GET['search'])): ?>
                <div class="buttons">
                    <a href="admin_customer_profile.php" style="background-color: #ff5722;">🔙 Back to All Customers</a>
                </div>
            <?php endif; ?>

            <!-- Table -->
            <table>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Order Frequency</th>
                    <th>Total Spend (₱)</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['phone']) ?></td>
                    <td><?= htmlspecialchars($row['address']) ?></td>
                    <td><?= (int)$row['order_frequency'] ?></td>
                    <td>₱<?= number_format($row['total_spend'], 2) ?></td>
                </tr>
                <?php endwhile; ?>
            </table>

            <!-- Pagination -->
            <div class="pagination">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?page=<?= $i ?>&search=<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>" class="<?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
                <?php endfor; ?>
            </div>

            <!-- Export -->
            <div class="buttons">
                <a href="export_customers_pdf.php?search=<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>" target="_blank">📄 Export to PDF</a>
                <a href="admin_dashboard.php">Back to Dashboard</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
