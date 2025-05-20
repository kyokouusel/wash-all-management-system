<?php
require_once 'vendor/autoload.php';
include 'config.php';
session_start();

use Dompdf\Dompdf;

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Pagination settings
$limit = 10; // Orders per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Fetch orders for the current page
$sql = "SELECT orders.id AS order_id, orders.customer_id, orders.service_type, orders.weight, orders.order_date, orders.status, customers.name AS customer_name
        FROM orders
        INNER JOIN customers ON orders.customer_id = customers.id
        ORDER BY orders.order_date DESC
        LIMIT ?, ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $offset, $limit);
$stmt->execute();
$result = $stmt->get_result();

// Start generating the HTML for the PDF
$html = '<h2>Admin Orders Report</h2><table border="1" cellpadding="5" cellspacing="0" width="100%">
<tr><th>Order ID</th><th>Customer Name</th><th>Service Type</th><th>Weight (kg)</th><th>Order Date</th><th>Status</th></tr>';

while ($row = $result->fetch_assoc()) {
    $html .= "<tr>
        <td>{$row['order_id']}</td>
        <td>{$row['customer_name']}</td>
        <td>{$row['service_type']}</td>
        <td>{$row['weight']}</td>
        <td>{$row['order_date']}</td>
        <td>{$row['status']}</td>
    </tr>";
}

$html .= '</table>';

// Generate PDF
$pdf = new Dompdf();
$pdf->loadHtml($html);
$pdf->setPaper('A4', 'portrait');
$pdf->render();
$pdf->stream("orders_page_{$page}.pdf", ["Attachment" => true]);
exit;
?>
