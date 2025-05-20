<?php
// MySQLi connection (optional, if used elsewhere)
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "laundry_system";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("MySQLi Connection failed: " . $conn->connect_error);
}

// PDO connection (used in forgot password script)
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("PDO Connection failed: " . $e->getMessage());
}

// SMTP credentials for PHPMailer
$smtpEmail = 'von4delacruz@gmail.com';
$smtpPass = 'xiqu veag hdzb ocne'; // App-specific password (secure this in production)
?>
