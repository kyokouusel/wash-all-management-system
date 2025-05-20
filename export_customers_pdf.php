<?php
require_once 'vendor/autoload.php';
include 'config.php';
session_start();

use Dompdf\Dompdf;

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Handle optional search
$search_query = "";
$params = [];

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = "%{$_GET['search']}%";
    $search_query = "WHERE name LIKE ? OR email LIKE ?";
    $params[] = $search;
    $params[] = $search;
}

// Prepare SQL
$sql = "SELECT name, email, phone, address FROM customers $search_query ORDER BY name ASC";
$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $types = str_repeat('s', count($params));
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

// Start HTML for PDF
$html = '<h2>Customer List Report</h2>';
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $html .= '<p><strong>Filtered by:</strong> ' . htmlspecialchars($_GET['search']) . '</p>';
}
$html .= '<table border="1" cellpadding="5" cellspacing="0" width="100%">
<tr>
    <th>Name</th>
    <th>Email</th>
    <th>Phone</th>
    <th>Address</th>
</tr>';

while ($row = $result->fetch_assoc()) {
    $html .= "<tr>
        <td>" . htmlspecialchars($row['name']) . "</td>
        <td>" . htmlspecialchars($row['email']) . "</td>
        <td>" . htmlspecialchars($row['phone']) . "</td>
        <td>" . htmlspecialchars($row['address']) . "</td>
    </tr>";
}

$html .= '</table>';

// Generate PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Name PDF file
$filename = 'customers_report' . (isset($_GET['search']) ? '_filtered' : '') . '.pdf';
$dompdf->stream($filename, ["Attachment" => true]);
exit;
?>
