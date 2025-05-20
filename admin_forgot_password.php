<?php
session_start();
require 'config.php';
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);

    if (!$email) {
        $error = "Invalid email address.";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM admin WHERE email = ?");
        $stmt->execute([$email]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$admin) {
            $error = "No account found with that email.";
        } else {
            $admin_id = $admin['id'];
            $code = random_int(100000, 999999);
            $expires = date("Y-m-d H:i:s", strtotime('+15 minutes'));

            $pdo->prepare("REPLACE INTO admin_password_resets (admin_id, code, expires_at) VALUES (?, ?, ?)")
                ->execute([$admin_id, $code, $expires]);

            $_SESSION['admin_reset_email'] = $email;

            try {
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'von4delacruz@gmail.com'; // change this
                $mail->Password = 'gioj xyku azud pxxx';    // change this
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                $mail->setFrom('von4delacruz@gmail.com', 'Laundry Admin');
                $mail->addAddress($email);
                $mail->Subject = 'Admin Password Reset Code';
                $mail->Body = "Your verification code is: $code. It expires in 15 minutes.";

                $mail->send();

                header("Location: admin_send_code.php");
                exit();
            } catch (Exception $e) {
                $error = "Failed to send verification email.";
            }
        }
    }
}
?>


<!-- HTML below is identical to customer_forgot_password with updated headings -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password - Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <!-- same SVG and structure as customer_forgot_password -->
    <div class="login container grid" id="loginAccessRegister">
        <div class="login__access">
            <h1 class="login__title" style="margin-left: 50px;">Admin Forgot Password</h1>
            <a style="margin-bottom: 30px;" class="back-link" href="admin_login.php">← Back to Login</a>
            <div class="login__area">
                <form method="POST" action="" class="login__form">
                    <div class="login__content grid">
                        <div class="login__box">
                            <input type="email" name="email" required placeholder=" " class="login__input">
                            <label for="email" class="login__label">Enter your admin email</label>
                            <i class="ri-mail-fill login__icon"></i>
                        </div>
                    </div>
                    <input class="login__button" type="submit" value="Send Verification Code">
                    <?php if ($success): ?>
                        <p style="color:green; text-align:center; margin-top: 10px;">✔ <?= htmlspecialchars($success); ?></p>
                    <?php elseif ($error): ?>
                        <p style="color:red; text-align:center; margin-top: 10px;"><?= htmlspecialchars($error); ?></p>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
