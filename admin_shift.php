<?php
session_start();
include 'config.php';
date_default_timezone_set('Asia/Manila');

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$orders = [];
$total = 0;

// Time In
if (isset($_POST['time_in'])) {
    $_SESSION['shift_start'] = date('Y-m-d H:i:s');
}

// Time Out
if (isset($_POST['time_out']) && !empty($_SESSION['shift_start'])) {
    $shift_start = $_SESSION['shift_start'];
    $shift_end   = date('Y-m-d H:i:s');

    $stmt = $conn->prepare(
        "SELECT id, service_type, weight, total_amount, created_at 
         FROM orders 
         WHERE created_at BETWEEN ? AND ?"
    );
    $stmt->bind_param("ss", $shift_start, $shift_end);
    $stmt->execute();
    $res = $stmt->get_result();

    while ($row = $res->fetch_assoc()) {
        $orders[] = $row;
        $total += $row['total_amount'];
    }
    $stmt->close();

    $admin_id = $_SESSION['admin_id'];
    $insert = $conn->prepare("INSERT INTO admin_shifts (admin_id, shift_start, shift_end, total_revenue) VALUES (?, ?, ?, ?)");
    $insert->bind_param("issd", $admin_id, $shift_start, $shift_end, $total);
    $insert->execute();
    $insert->close();

    unset($_SESSION['shift_start']);
}

// Pagination
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Fetch shifts
$admin_id = $_SESSION['admin_id'];
$result = $conn->prepare("SELECT COUNT(*) as total FROM admin_shifts WHERE admin_id = ?");
$result->bind_param("i", $admin_id);
$result->execute();
$result->bind_result($total_shifts);
$result->fetch();
$result->close();

$total_pages = ceil($total_shifts / $limit);

$stmt = $conn->prepare("SELECT * FROM admin_shifts WHERE admin_id = ? ORDER BY shift_end DESC LIMIT ?, ?");
$stmt->bind_param("iii", $admin_id, $offset, $limit);
$stmt->execute();
$shifts = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Shift</title>
    <style>
        body { font-family: Arial; padding:20px; }
        .btn { padding:10px 20px; margin:5px; background:#007bff; color:#fff; border:none; border-radius:4px; cursor:pointer; }
        .btn:disabled { background:#aaa; }
        table { width:100%; border-collapse: collapse; margin-top:20px; }
        th, td { border:1px solid #ddd; padding:8px; text-align:left; }
        th { background:#007bff; color:#fff; }
        .summary { margin-top:20px; font-weight:bold; }
        .pagination a { margin: 0 5px; text-decoration: none; color: #007bff; }
        .pagination strong { margin: 0 5px; }
    </style>
</head>
<body>
    <h2>Admin Shift Clock</h2>

    <?php if (empty($_SESSION['shift_start'])): ?>
        <form method="post">
            <button name="time_in" class="btn">🕑 Time In</button>
        </form>
    <?php else: ?>
        <p>Shift started at: <strong><?php echo $_SESSION['shift_start']; ?></strong></p>
        <form method="post">
            <button name="time_out" class="btn">⏱️ Time Out</button>
        </form>
    <?php endif; ?>

    <?php if (isset($orders) && count($orders)): ?>
        <h3>Receipt for Shift</h3>
        <table>
            <tr>
                <th>Order ID</th>
                <th>Service</th>
                <th>Weight</th>
                <th>Amount</th>
                <th>Timestamp</th>
            </tr>
            <?php foreach ($orders as $o): ?>
                <tr>
                    <td><?= $o['id'] ?></td>
                    <td><?= htmlspecialchars($o['service_type']) ?></td>
                    <td><?= (int)$o['weight'] ?></td>
                    <td>₱<?= number_format($o['total_amount'], 2) ?></td>
                    <td><?= $o['created_at'] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <p class="summary">Total revenue this shift: ₱<?= number_format($total, 2) ?></p>
    <?php elseif (isset($_POST['time_out'])): ?>
        <p>No orders placed during this shift.</p>
    <?php endif; ?>

    <h3>📋 Past Admin Shifts</h3>
    <table>
        <tr>
            <th>#</th>
            <th>Start</th>
            <th>End</th>
            <th>Revenue</th>
        </tr>
        <?php $i = 1 + $offset; while ($row = $shifts->fetch_assoc()): ?>
        <tr>
            <td><?= $i++ ?></td>
            <td><?= $row['shift_start'] ?></td>
            <td><?= $row['shift_end'] ?></td>
            <td>₱<?= number_format($row['total_revenue'], 2) ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <div class="pagination">
        <?php for ($p = 1; $p <= $total_pages; $p++): ?>
            <?php if ($p == $page): ?>
                <strong><?= $p ?></strong>
            <?php else: ?>
                <a href="?page=<?= $p ?>"><?= $p ?></a>
            <?php endif; ?>
        <?php endfor; ?>
    </div>

    <form action="export_shifts_pdf.php" method="post">
        <button type="submit" class="btn">📄 Export to PDF</button>
    </form>

    <br><a href="admin_dashboard.php">← Back to Dashboard</a>
</body>
</html>
