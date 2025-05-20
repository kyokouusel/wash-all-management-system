<?php
session_start();
require 'config.php';

if (!isset($_SESSION['reset_email'])) {
    header("Location: customer_forgot_password.php");
    exit();
}

$email = $_SESSION['reset_email'];
$error = '';

// Fetch user ID
$stmt = $pdo->prepare("SELECT id FROM customers WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    $_SESSION['error'] = "Account not found.";
    header("Location: customer_forgot_password.php");
    exit();
}

$user_id = $user['id'];

// Fetch code from DB
$stmt = $pdo->prepare("SELECT code, expires_at FROM password_resets WHERE user_id = ?");
$stmt->execute([$user_id]);
$reset = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input_code = trim($_POST['code']);

    if (!$reset) {
        $error = "No code found. Please request again.";
    } elseif ($input_code !== $reset['code']) {
        $error = "✖ Invalid code.";
    } elseif (strtotime($reset['expires_at']) < time()) {
        $error = "⏰ Code has expired.";
    } else {
        $_SESSION['code_verified'] = true;
        header("Location: customer_new_password.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Enter Verification Code</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>

<svg class="login__blob" viewBox="0 0 566 840" xmlns="http://www.w3.org/2000/svg">
    <mask id="mask0" mask-type="alpha">
        <path d="M342.407 73.6315C388.53 56.4007 394.378 17.3643 391.538 
        0H566V840H0C14.5385 834.991 100.266 804.436 77.2046 707.263C49.6393 
        591.11 115.306 518.927 176.468 488.873C363.385 397.026 156.98 302.824 
        167.945 179.32C173.46 117.209 284.755 95.1699 342.407 73.6315Z"/>
    </mask>
    <g mask="url(#mask0)">
        <path d="M342.407 73.6315C388.53 56.4007 394.378 17.3643 391.538 
        0H566V840H0C14.5385 834.991 100.266 804.436 77.2046 707.263C49.6393 
        591.11 115.306 518.927 176.468 488.873C363.385 397.026 156.98 302.824 
        167.945 179.32C173.46 117.209 284.755 95.1699 342.407 73.6315Z"/>
        <image class="login__img" href="assets/img/bg-img.jpg"/>
    </g>
</svg>

<div class="login container grid" id="loginAccessRegister">
    <div class="login__access">
        <h1 class="login__title" style="margin-left: 100px;">Enter Code</h1>
        <a style="margin-bottom: 30px;" class="back-link" href="login.php">← Back to Login</a>
        <div class="login__area">
            <form method="POST" action="" class="login__form">
                <div class="login__content grid">
                    <div class="login__box">
                        <input type="text" name="code" maxlength="6" required placeholder=" " class="login__input">
                        <label for="code" class="login__label">Enter the 6-digit code</label>
                        <i class="ri-lock-2-fill login__icon"></i>
                    </div>
                </div>
                <input class="login__button" type="submit" value="Verify Code">

                <?php if ($error): ?>
                    <p style="color:red; text-align:center; margin-top: 10px;"><?php echo htmlspecialchars($error); ?></p>
                <?php endif; ?>
            </form>
        </div>
    </div>
</div>

</body>
</html>
