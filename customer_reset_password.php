<?php
session_start();
require 'config.php';

if (!isset($_SESSION['reset_email'])) {
    header("Location: customer_forgot_password.php");
    exit();
}

$email = $_SESSION['reset_email'];
$error = "";

$stmt = $pdo->prepare("SELECT id FROM customers WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$user_id = $user['id'] ?? null;

if (!$user_id) {
    $_SESSION['error'] = "No customer found.";
    header("Location: customer_forgot_password.php");
    exit();
}

$stmt = $pdo->prepare("SELECT code, expires_at FROM password_resets WHERE user_id = ?");
$stmt->execute([$user_id]);
$reset = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entered_code = $_POST['reset_code'] ?? '';

    if ($entered_code !== $reset['code']) {
        $error = "❌ Invalid code.";
    } elseif (strtotime($reset['expires_at']) < time()) {
        $error = "⏰ Code has expired.";
    } else {
        $_SESSION['code_verified'] = true;
        header("Location: new_password.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #eef2f3; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .container { background: white; padding: 25px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); text-align: center; width: 350px; }
        input { width: 100%; padding: 10px; margin-top: 10px; border-radius: 5px; border: 1px solid #ccc; }
        button { width: 100%; padding: 10px; margin-top: 20px; border: none; background-color: #007bff; color: white; border-radius: 5px; cursor: pointer; }
        .error { color: red; font-size: 14px; margin-top: 10px; }
        .success { color: green; font-size: 14px; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>🔐 Reset Your Password</h2>

        <?php if ($error): ?>
            <p class="error"><?= $error ?></p>
        <?php elseif ($success): ?>
            <p class="success"><?= $success ?></p>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="reset_code" placeholder="Enter 6-digit Code" required maxlength="6">
            <input type="password" name="new_password" placeholder="New Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <button type="submit">Reset Password</button>
        </form>
    </div>
</body>
</html>
