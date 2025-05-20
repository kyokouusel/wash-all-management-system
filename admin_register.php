<?php
session_start();
include 'config.php';

$popupMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim(mysqli_real_escape_string($conn, $_POST['username']));
    $email = trim(mysqli_real_escape_string($conn, $_POST['email']));
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Server-side validation
    if (strlen($password) < 8 ||
        !preg_match('/[A-Z]/', $password) || // Uppercase
        !preg_match('/[a-z]/', $password) || // Lowercase
        !preg_match('/[0-9]/', $password) || // Number
        !preg_match('/[\W]/', $password)     // Special character
    ) {
        $popupMessage = "Password must be at least 8 characters and contain uppercase, lowercase, number, and symbol.";
    } elseif ($password !== $confirm_password) {
        $popupMessage = "Passwords do not match.";
    } else {
        $checkQuery = "SELECT 1 FROM admin WHERE username = ? OR email = ? LIMIT 1";
        $stmt = $conn->prepare($checkQuery);
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $popupMessage = "Username or Email already exists.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insertQuery = "INSERT INTO admin (username, email, password) VALUES (?, ?, ?)";
            $insertStmt = $conn->prepare($insertQuery);
            $insertStmt->bind_param("sss", $username, $email, $hashed_password);

            if ($insertStmt->execute()) {
                $popupMessage = "✅ Admin registered successfully!";
            } else {
                $popupMessage = "❌ Error: " . addslashes($insertStmt->error);
            }
            $insertStmt->close();
        }

        $stmt->close();
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <svg class="login__blob" viewBox="0 0 566 840" xmlns="http://www.w3.org/2000/svg">
        <mask id="mask0" mask-type="alpha">
            <path d="..."/>
        </mask>
        <g mask="url(#mask0)">
            <path d="..."/>
            <image class="login__img" href="assets/img/bg-img.jpg"/>
        </g>
    </svg>

    <div class="login container grid" id="loginAccessRegister">
        <div class="login__access">
            <h1 style="margin-left: 140px;" class="login__title">Register</h1>
            <a style="margin-bottom: 30px;" class="back-link" href="index.php">← Back to Homepage</a>

            <div class="login__area">
                <form method="POST" class="login__form" id="registerForm">
                    <div class="login__content grid">
                        <div class="login__box">
                            <input type="text" name="username" required placeholder=" " class="login__input">
                            <label for="username" class="login__label">Username</label>
                            <i class="ri-user-fill login__icon"></i>
                        </div>
                        <div class="login__box">
                            <input type="email" name="email" required placeholder=" " class="login__input">
                            <label for="email" class="login__label">Email</label>
                            <i class="ri-mail-fill login__icon"></i>
                        </div>
                        <div class="login__box">
                            <input type="password" name="password" required placeholder=" " class="login__input" id="password" minlength="8">
                            <label for="password" class="login__label">Password</label>
                            <i class="ri-eye-off-fill login__icon login__password" id="togglePassword"></i>
                        </div>
                        <div id="passwordRules" style="color: #fff; font-size: 12px; margin-bottom: 10px;">
                            <ul>
                                <li id="length" style="color:red;">At least 8 characters</li>
                                <li id="uppercase" style="color:red;">At least one uppercase letter</li>
                                <li id="lowercase" style="color:red;">At least one lowercase letter</li>
                                <li id="number" style="color:red;">At least one number</li>
                                <li id="special" style="color:red;">At least one special character</li>
                            </ul>
                        </div>
                        <div class="login__box">
                            <input type="password" name="confirm_password" required placeholder=" " class="login__input" id="confirm_password" minlength="8">
                            <label for="confirm_password" class="login__label">Confirm Password</label>
                            <i class="ri-eye-off-fill login__icon login__password" id="toggleConfirmPassword"></i>
                        </div>
                        <input type="submit" class="login__button" value="Register">
                    </div>
                </form>
            </div>
        </div>

        <div id="popupMessage" style="display: none; background: rgba(0,0,0,0.8); color: white; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); padding: 20px; border-radius: 8px; z-index: 9999;"></div>

        <script>
        function showPopup(message) {
            var popup = document.getElementById('popupMessage');
            popup.textContent = message;
            popup.style.display = 'block';
            setTimeout(() => popup.style.display = 'none', 3000);
        }

        document.getElementById('togglePassword').addEventListener('click', function () {
            const field = document.getElementById('password');
            field.type = field.type === 'password' ? 'text' : 'password';
        });

        document.getElementById('toggleConfirmPassword').addEventListener('click', function () {
            const field = document.getElementById('confirm_password');
            field.type = field.type === 'password' ? 'text' : 'password';
        });

        // Password strength checker
        document.getElementById('password').addEventListener('input', function () {
            const val = this.value;
            document.getElementById('length').style.color = val.length >= 8 ? 'limegreen' : 'red';
            document.getElementById('uppercase').style.color = /[A-Z]/.test(val) ? 'limegreen' : 'red';
            document.getElementById('lowercase').style.color = /[a-z]/.test(val) ? 'limegreen' : 'red';
            document.getElementById('number').style.color = /[0-9]/.test(val) ? 'limegreen' : 'red';
            document.getElementById('special').style.color = /[\W]/.test(val) ? 'limegreen' : 'red';
        });

        // Final client-side validation before submit
        document.getElementById('registerForm').addEventListener('submit', function (e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;

            const valid = password.length >= 8 &&
                        /[A-Z]/.test(password) &&
                        /[a-z]/.test(password) &&
                        /[0-9]/.test(password) &&
                        /[\W]/.test(password);

            if (!valid) {
                e.preventDefault();
                showPopup("Password must be at least 8 characters and include uppercase, lowercase, number, and symbol.");
            } else if (password !== confirmPassword) {
                e.preventDefault();
                showPopup("Passwords do not match.");
            }
        });

        <?php if (!empty($popupMessage)): ?>
            document.addEventListener('DOMContentLoaded', function () {
                showPopup("<?= $popupMessage ?>");
            });
        <?php endif; ?>
        </script>
    </div>
</body>
</html>
