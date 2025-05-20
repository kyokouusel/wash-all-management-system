<?php
session_start();
include 'config.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];

// Initialize filter variables
$start_date = '';
$end_date = '';

// Pagination setup
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $start_date = $_POST['start_date'] ?? '';
    $end_date = $_POST['end_date'] ?? '';
}

// Build the SQL query with filters
$sql = "SELECT * FROM orders WHERE customer_id = '$customer_id'";
if ($start_date && $end_date) {
    $sql .= " AND order_date BETWEEN '$start_date' AND '$end_date'";
}
$sql .= " ORDER BY order_date DESC LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $sql);

// Function to calculate price
function calculate_price($service_type, $weight) {
    $base_price = 0;
    $weight_kg = (int)$weight;
    switch ($service_type) {
        case 'Dry': $base_price = 70; break;
        case 'Wash': $base_price = 60; break;
        case 'Fold': $base_price = 30; break;
        case 'Dry + Fold': $base_price = 100; break;
        case 'Wash + Dry': $base_price = 130; break;
        case 'Wash + Fold': $base_price = 90; break;
        case 'Wash + Dry + Fold': $base_price = 160; break;
        case 'Wash + Dry + Fold with Detergent and Fabcon': $base_price = 185; break;
    }
    return in_array($weight_kg, [8, 16, 24, 32]) ? $base_price * ($weight_kg / 8) : 0;
}

// Total for pagination
$total_sql = "SELECT COUNT(*) as total_orders FROM orders WHERE customer_id = '$customer_id'";
if ($start_date && $end_date) {
    $total_sql .= " AND order_date BETWEEN '$start_date' AND '$end_date'";
}
$total_result = mysqli_query($conn, $total_sql);
$total_row = mysqli_fetch_assoc($total_result);
$total_orders = $total_row['total_orders'];
$total_pages = ceil($total_orders / $limit);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order History - Wash All Laundry</title>
    <link rel="stylesheet" href="User/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <style>
        .container {
            max-width: 800px;
            margin: auto;
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        .receipt-box {
            background-color: #f9f9f9;
            padding: 10px;
            border-radius: 5px;
            border: 1px dashed #ccc;
        }
        .cancel-btn {
            background-color: #dc3545;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .cancel-btn:hover {
            background-color: #c82333;
        }
        .pagination {
            margin-top: 20px;
            text-align: center;
        }
        .pagination a {
            color: #007bff;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 5px;
            margin: 0 4px;
            border: 1px solid #007bff;
        }
        .pagination a:hover {
            background-color: #007bff;
            color: white;
        }
        form.filter-form {
            margin-top: 20px;
        }
        form.filter-form input[type="date"],
        form.filter-form button {
            padding: 6px;
            margin-right: 10px;
        }
    </style>
</head>
<body>
<header>
    <a href="#" class="logo" data-aos="fade-down">Wash All Laundry</a>
    <div class="menuToggle" onclick="toggleMenu();"></div>
    <ul class="nav">
        <li data-aos="fade-down"><a href="User/index.php">Back to Dashboard</a></li>
        <li data-aos="fade-down"><a href="index.php">Log Out</a></li>
    </ul>
</header>

<section class="hero_section" id="home">
<div class="content">
            <h2 data-aos="fade-down" data-aos-delay="50">Order History</h2>
            <p data-aos="fade-down" data-aos-delay="100">
            </p>
    <div class="content container">
        

        <!-- Orders Table -->
        <?php if (mysqli_num_rows($result) > 0): ?>
        <table data-aos="fade-up" data-aos-delay="150">
            <tr>
                <th>Order ID</th>
                <th>🧾 Receipt</th>
                <th>Order Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td>
                    <div class="receipt-box">
                        <strong>Service:</strong> <?php echo htmlspecialchars($row['service_type']); ?><br>
                        <strong>Weight:</strong> <?php echo (int)$row['weight']; ?> kg<br>
                        <strong>Price:</strong> ₱<?php echo number_format(calculate_price($row['service_type'], $row['weight']), 2); ?><br><br>
                        <strong>Address:</strong> <?php echo htmlspecialchars($row['address']); ?><br>
                        <strong>Phone:</strong> <?php echo htmlspecialchars($row['phone']); ?><br>
                    </div>
                </td>
                <td><?php echo $row['order_date']; ?></td>
                <td><?php echo $row['status']; ?></td>
                <td>
                    <?php if ($row['status'] == 'Pending'): ?>
                    <form action="customer_cancel_order.php" method="post">
                        <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                        <input type="hidden" name="page" value="<?php echo $page; ?>">
                        <button type="submit" class="cancel-btn" onclick="return confirm('Cancel this order?')">Cancel</button>
                    </form>
                    <?php else: ?>
                    <span>-</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
        <?php else: ?>
        <p style="color: black;" data-aos="fade-in">You have no orders yet.</p>
        <?php endif; ?>

        <!-- Pagination -->
        <div class="pagination" data-aos="fade-up" data-aos-delay="200">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="order_history.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>
        </div>
            </div>
            <a style="color: white; margin-top: 15px;" href="dashboard.php" class="back-link">⬅️ Back to Dashboard</a>
            </div>
        </section>

        

        <div class="cp">
            <p>&copy; 2025 <a href="#">Splash Brothers</a>. All Rights Reserved</p>
        </div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script>
    AOS.init({ duration: 600 });
</script>
</body>
</html>
