<?php
session_start();
require 'config.php';
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;

$success = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);

    if (!$email) {
        $error = "Invalid email address.";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM customers WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            $error = "No account found.";
        } else {
            $user_id = $user['id'];
            $code = random_int(100000, 999999);
            $expires = date("Y-m-d H:i:s", strtotime('+15 minutes'));

            $pdo->prepare("REPLACE INTO password_resets (user_id, code, expires_at) VALUES (?, ?, ?)")
                ->execute([$user_id, $code, $expires]);

            $_SESSION['reset_email'] = $email;

            try {
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'von4delacruz@gmail.com';
                $mail->Password = 'gioj xyku azud pxxx'; // App Password
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                $mail->setFrom('von4delacruz@gmail.com', 'Laundry System');
                $mail->addAddress($email);
                $mail->Subject = 'Your Password Reset Code';
                $mail->Body = "Your reset code is: $code. It expires in 15 minutes.";

                $mail->send();

                // Redirect directly
                header("Location: customer_send_code.php");
                exit();
            } catch (Exception $e) {
                $error = "✖ Failed to send email.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password - Customer</title>
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
        <h1 class="login__title" style="margin-left: 50px;">Forgot Password</h1>
        <a style="margin-bottom: 30px;" class="back-link" href="login.php">← Back to Login</a>

        <div class="login__area">
            <form method="POST" action="" class="login__form">
                <div class="login__content grid">
                    <div class="login__box">
                        <input type="email" name="email" required placeholder=" " class="login__input">
                        <label for="email" class="login__label">Enter your email</label>
                        <i class="ri-mail-fill login__icon"></i>
                    </div>
                </div>

                <input class="login__button" type="submit" value="Send Verification Code">

                <?php if ($success): ?>
                    <p style="color:green; text-align:center; margin-top: 10px;">✔ <?= htmlspecialchars($success); ?></p>
                <?php elseif ($error): ?>
                    <p style="color:red; text-align:center; margin-top: 10px;">✖ <?= htmlspecialchars($error); ?></p>
                <?php endif; ?>
            </form>
        </div>
    </div>
</div>

</body>
</html>
