<?php
session_start();
require 'config.php';

$error = '';
if (!isset($_SESSION['admin_reset_email'])) {
    header("Location: admin_forgot_password.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input_code = $_POST['code'];
    $email = $_SESSION['admin_reset_email'];

    $stmt = $pdo->prepare("SELECT id FROM admin WHERE email = ?");
    $stmt->execute([$email]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$admin) {
        $error = "Invalid session. Please restart the reset process.";
    } else {
        $admin_id = $admin['id'];
        $stmt = $pdo->prepare("SELECT * FROM admin_password_resets WHERE admin_id = ? AND code = ?");
        $stmt->execute([$admin_id, $input_code]);
        $reset = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($reset && strtotime($reset['expires_at']) > time()) {
            $_SESSION['admin_reset_verified'] = true;
            header("Location: admin_new_password.php");
            exit();
        } else {
            $error = "Invalid or expired verification code.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Enter Code - Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <div class="login container grid">
        <div class="login__access">
            <h1 class="login__title" style="margin-left: 50px;">Verify Code</h1>
            <a style="margin-bottom: 30px;" class="back-link" href="admin_forgot_password.php">← Back</a>
            <div class="login__area">
                <form method="POST" class="login__form">
                    <div class="login__content grid">
                        <div class="login__box">
                            <input type="text" name="code" required placeholder=" " class="login__input">
                            <label class="login__label">Enter verification code</label>
                            <i class="ri-lock-password-line login__icon"></i>
                        </div>
                    </div>
                    <input class="login__button" type="submit" value="Verify Code">
                    <?php if ($error): ?>
                        <p style="color:red; text-align:center; margin-top: 10px;"><?= htmlspecialchars($error); ?></p>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
