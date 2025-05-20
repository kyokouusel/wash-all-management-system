<?php
session_start();
require 'config.php';

$success = '';
$error = '';

if (!isset($_SESSION['admin_reset_email']) || !isset($_SESSION['admin_reset_verified'])) {
    header("Location: admin_forgot_password.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];
    $email = $_SESSION['admin_reset_email'];

    if ($password !== $confirm) {
        $error = "✖ Passwords do not match.";
    } elseif (
        strlen($password) < 8 ||
        !preg_match('/[A-Z]/', $password) ||
        !preg_match('/[a-z]/', $password) ||
        !preg_match('/[0-9]/', $password) ||
        !preg_match('/[\W]/', $password)
    ) {
        $error = "Password must be at least 8 characters and include uppercase, lowercase, number, and symbol.";
    } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("UPDATE admin SET password = ? WHERE email = ?");
        $stmt->execute([$hashed, $email]);

        $stmt = $pdo->prepare("SELECT id FROM admin WHERE email = ?");
        $stmt->execute([$email]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin) {
            $pdo->prepare("DELETE FROM admin_password_resets WHERE admin_id = ?")
                ->execute([$admin['id']]);
        }

        session_unset();
        session_destroy();
        header("Location: admin_login.php?reset=success");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Set New Password - Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        .login__access {
            max-width: 500px;
            width: 100%;
            margin: auto;
            padding: 30px;
            border-radius: 10px;
            background-color: rgba(255, 255, 255, 0.04);
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
            color: white;
        }
    </style>
</head>
<body>
    <div class="login container" style="display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px;">
        <div class="login__access">
            <h1 class="login__title" style="text-align: center;">Set New Password</h1>
            <div class="login__area">
                <form method="POST" class="login__form">
                    <div class="login__content grid" style="gap: 15px;">
                        <div class="login__box">
                            <input type="password" name="password" id="password" required placeholder=" " class="login__input">
                            <label class="login__label">New Password</label>
                            <i class="ri-lock-password-line login__icon"></i>
                        </div>

                        <div id="passwordRules">
                            <ul>
                                <li id="length" style="color:red;">At least 8 characters</li>
                                <li id="uppercase" style="color:red;">At least one uppercase letter</li>
                                <li id="lowercase" style="color:red;">At least one lowercase letter</li>
                                <li id="number" style="color:red;">At least one number</li>
                                <li id="special" style="color:red;">At least one special character</li>
                            </ul>
                        </div>

                        <div class="login__box">
                            <input type="password" name="confirm_password" required placeholder=" " class="login__input">
                            <label class="login__label">Confirm Password</label>
                            <i class="ri-lock-password-line login__icon"></i>
                        </div>
                    </div>
                    <input class="login__button" type="submit" value="Reset Password">
                    <?php if ($error): ?>
                        <p style="color:red; text-align:center; margin-top: 10px;"><?= htmlspecialchars($error); ?></p>
                    <?php elseif ($success): ?>
                        <p style="color:green; text-align:center; margin-top: 10px;"><?= htmlspecialchars($success); ?></p>
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
