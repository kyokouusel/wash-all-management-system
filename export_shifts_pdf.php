<?php
require_once 'vendor/autoload.php';
include 'config.php';
session_start();

use Dompdf\Dompdf;

// Ensure the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$admin_id = $_SESSION['admin_id'];

// Pagination Setup
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Fetch the total number of shifts
$total_result = $conn->query("SELECT COUNT(*) as total FROM admin_shifts WHERE admin_id = $admin_id");
$total_row = $total_result->fetch_assoc();
$total_shifts = $total_row['total'];
$total_pages = ceil($total_shifts / $limit);

// Fetch the last 10 shifts for the current admin
$result = $conn->prepare("SELECT * FROM admin_shifts WHERE admin_id = ? ORDER BY shift_end DESC LIMIT ?, ?");
$result->bind_param("iii", $admin_id, $start, $limit);
$result->execute();
$data = $result->get_result();

// Generate the HTML content for the PDF
$html = '<h2>Admin Shift Report</h2>';
$html .= '<p>Total Shifts: ' . $total_shifts . '</p>';
$html .= '<table border="1" cellpadding="5" cellspacing="0" width="100%">
<tr><th>#</th><th>Start</th><th>End</th><th>Revenue (₱)</th></tr>';

$index = $start + 1; // To show correct shift index
while ($row = $data->fetch_assoc()) {
    $html .= "<tr>
        <td>{$index}</td>
        <td>{$row['shift_start']}</td>
        <td>{$row['shift_end']}</td>
        <td>₱" . number_format($row['total_revenue'], 2) . "</td>
    </tr>";
    $index++;
}

$html .= '</table>';

// If there are more pages, include a pagination note
if ($total_pages > 1) {
    $html .= '<p>Page ' . $page . ' of ' . $total_pages . '</p>';
}

$pdf = new Dompdf();
$pdf->loadHtml($html);
$pdf->setPaper('A4', 'portrait');
$pdf->render();

// Output the PDF file for download
$pdf->stream("admin_shifts.pdf", ["Attachment" => true]);
exit;
?>
