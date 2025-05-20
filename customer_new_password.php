<?php
session_start();
require 'config.php';

if (!isset($_SESSION['reset_email']) || !isset($_SESSION['code_verified'])) {
    header("Location: customer_forgot_password.php");
    exit();
}

$email = $_SESSION['reset_email'];
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pass = $_POST['password'];
    $confirm = $_POST['confirm'];

    if ($pass !== $confirm) {
        $error = "✖ Passwords do not match.";
    } elseif (
        strlen($pass) < 8 ||
        !preg_match('/[A-Z]/', $pass) ||
        !preg_match('/[a-z]/', $pass) ||
        !preg_match('/[0-9]/', $pass) ||
        !preg_match('/[\W]/', $pass)
    ) {
        $error = "Password must be at least 8 characters and include uppercase, lowercase, number, and symbol.";
    } else {
        $hashed = password_hash($pass, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("UPDATE customers SET password = ? WHERE email = ?");
        $stmt->execute([$hashed, $email]);

        $pdo->prepare("DELETE FROM password_resets WHERE user_id = (SELECT id FROM customers WHERE email = ?)")->execute([$email]);

        session_unset();
        session_destroy();

        header("Location: login.php?reset=success");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        .login__access {
            width: 100%;
            max-width: 500px;
            margin: auto;
            padding: 30px;
            border-radius: 10px;
            background-color: rgba(255, 255, 255, 0.03);
            box-shadow: 0 0 10px rgba(0,0,0,0.4);
        }

        @media (max-width: 600px) {
            .login__access {
                padding: 20px;
            }

            .login__title {
                font-size: 1.5rem;
            }
        }

        #passwordRules ul {
            margin-left: 20px;
            font-size: 12px;
        }
    </style>
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

<div class="login container" id="loginAccessRegister" style="display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px;">
    <div class="login__access">
        <h1 class="login__title" style="text-align: center;">Reset Password</h1>
        <a style="margin-bottom: 20px; display: block; text-align: center;" class="back-link" href="login.php">← Back to Login</a>

        <div class="login__area">
            <form method="POST" action="" class="login__form" id="resetForm">
                <div class="login__content grid" style="gap: 15px;">
                    <div class="login__box">
                        <input type="password" name="password" id="password" required placeholder=" " class="login__input">
                        <label class="login__label">New Password</label>
                        <i class="ri-lock-password-fill login__icon"></i>
                    </div>

                    <div id="passwordRules" style="color: #fff;">
                        <ul>
                            <li id="length" style="color:red;">At least 8 characters</li>
                            <li id="uppercase" style="color:red;">At least one uppercase letter</li>
                            <li id="lowercase" style="color:red;">At least one lowercase letter</li>
                            <li id="number" style="color:red;">At least one number</li>
                            <li id="special" style="color:red;">At least one special character</li>
                        </ul>
                    </div>

                    <div class="login__box">
                        <input type="password" name="confirm" required placeholder=" " class="login__input">
                        <label class="login__label">Confirm Password</label>
                        <i class="ri-lock-2-fill login__icon"></i>
                    </div>
                </div>

                <input class="login__button" type="submit" value="Update Password">

                <?php if ($error): ?>
                    <p style="color:red; text-align:center; margin-top: 10px;">
                        <?= htmlspecialchars($error) ?>
                    </p>
                <?php endif; ?>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('password').addEventListener('input', function () {
        const val = this.value;
        document.getElementById('length').style.color = val.length >= 8 ? 'limegreen' : 'red';
        document.getElementById('uppercase').style.color = /[A-Z]/.test(val) ? 'limegreen' : 'red';
        document.getElementById('lowercase').style.color = /[a-z]/.test(val) ? 'limegreen' : 'red';
        document.getElementById('number').style.color = /[0-9]/.test(val) ? 'limegreen' : 'red';
        document.getElementById('special').style.color = /[\W]/.test(val) ? 'limegreen' : 'red';
    });
</script>

</body>
</html>
