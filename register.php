<?php
include 'config.php';

$popupMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = mysqli_real_escape_string($conn, $_POST['name']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'];
    $address  = mysqli_real_escape_string($conn, $_POST['address']);
    $phone    = mysqli_real_escape_string($conn, $_POST['phone']);

    // Check if passwords match
    if ($password !== $confirm) {
        $popupMessage = "Passwords do not match.";
    }
    // Validate password strength
    elseif (strlen($password) < 8 ||
        !preg_match('/[A-Z]/', $password) ||
        !preg_match('/[a-z]/', $password) ||
        !preg_match('/[0-9]/', $password) ||
        !preg_match('/[\W]/', $password)) {
        $popupMessage = "Password must be at least 8 characters and include uppercase, lowercase, number, and symbol.";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO customers (name, email, password, address, phone)
                VALUES ('$name', '$email', '$hashedPassword', '$address', '$phone')";

        if ($conn->query($sql) === TRUE) {
            $popupMessage = "✅ Registration successful!";
        } else {
            $popupMessage = "❌ Error: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Registration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>

<body>

<!-- SVG background -->
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

<!-- Registration form (Adjusted) -->
<div class="login container" id="loginAccessRegister" style="display: flex; justify-content: center; align-items: center; padding: 20px; min-height: 100vh;">
    <div class="login__access" style="background: rgba(255,255,255,0.1); padding: 30px 20px; border-radius: 12px; max-width: 400px; width: 100%;">
        <h1 class="login__title" style="text-align: center; font-size: 1.5rem; margin-bottom: 10px;">Customer Register</h1>
        <a class="back-link" href="index.php" style="display: block; text-align: center; margin-bottom: 20px;">← Back to Homepage</a>

        <form method="POST" action="" class="login__form" id="registerForm">
            <div class="login__content grid" style="display: grid; gap: 16px;">
            <div class="login__group grid">
                <div class="login__box">
                    <input type="text" name="name" required placeholder=" " class="login__input">
                    <label class="login__label">Name</label>
                    <i class="ri-user-fill login__icon"></i>
                </div>
                <div class="login__box">
                    <input type="email" name="email" required placeholder=" " class="login__input">
                    <label class="login__label">Email</label>
                    <i class="ri-mail-fill login__icon"></i>
                </div>
            </div>
                <div class="login__box">
                    <input type="password" name="password" required placeholder=" " class="login__input" id="password">
                    <label class="login__label">Password</label>
                    <i class="ri-lock-fill login__icon"></i>
                </div>
                <div class="login__box">
                    <input type="password" name="confirm_password" required placeholder=" " class="login__input" id="confirm_password">
                    <label class="login__label">Confirm Password</label>
                    <i class="ri-lock-password-line login__icon"></i>
                </div>
                <div id="passwordRules" style="color: #fff; font-size: 10px;">
                    <ul style="padding-left: 20px; margin-bottom: 10px;">
                        <li id="length" style="color:red;">At least 8 characters</li>
                        <li id="uppercase" style="color:red;">At least one uppercase letter</li>
                        <li id="lowercase" style="color:red;">At least one lowercase letter</li>
                        <li id="number" style="color:red;">At least one number</li>
                        <li id="special" style="color:red;">At least one special character</li>
                    </ul>
                    <div id="matchNotice" style="color:red;">Passwords do not match.</div>
                </div>
                <div class="login__group grid">
                <div class="login__box">
                    <input type="text" name="address" required placeholder=" " class="login__input">
                    <label class="login__label">Address</label>
                    <i class="ri-home-fill login__icon"></i>
                </div>
                <div class="login__box">
                    <input type="text" name="phone" required placeholder=" " class="login__input">
                    <label class="login__label">Phone</label>
                    <i class="ri-phone-fill login__icon"></i>
                </div>
                </div>
                <input type="submit" class="login__button" value="Register" style="padding: 10px 0; font-size: 1rem;">
            </div>
        </form>
    </div>
</div>

<!-- Popup -->
<div id="popupMessage" style="display: none; background: rgba(0,0,0,0.8); color: white; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); padding: 20px; border-radius: 8px; z-index: 9999;"></div>


<script>
    function showPopup(message) {
        const popup = document.getElementById('popupMessage');
        popup.textContent = message;
        popup.style.display = 'block';
        setTimeout(() => popup.style.display = 'none', 3000);
    }

    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('confirm_password');
    const matchNotice = document.getElementById('matchNotice');

    function validateMatch() {
        if (confirmInput.value === passwordInput.value) {
            matchNotice.textContent = "Passwords match.";
            matchNotice.style.color = "limegreen";
        } else {
            matchNotice.textContent = "Passwords do not match.";
            matchNotice.style.color = "red";
        }
    }

    passwordInput.addEventListener('input', function () {
        const val = this.value;
        document.getElementById('length').style.color = val.length >= 8 ? 'limegreen' : 'red';
        document.getElementById('uppercase').style.color = /[A-Z]/.test(val) ? 'limegreen' : 'red';
        document.getElementById('lowercase').style.color = /[a-z]/.test(val) ? 'limegreen' : 'red';
        document.getElementById('number').style.color = /[0-9]/.test(val) ? 'limegreen' : 'red';
        document.getElementById('special').style.color = /[\W]/.test(val) ? 'limegreen' : 'red';
        validateMatch();
    });

    confirmInput.addEventListener('input', validateMatch);

    document.getElementById('registerForm').addEventListener('submit', function (e) {
        const password = passwordInput.value;
        const confirm = confirmInput.value;
        const valid = password.length >= 8 && /[A-Z]/.test(password) && /[a-z]/.test(password) && /[0-9]/.test(password) && /[\W]/.test(password);

        if (!valid) {
            e.preventDefault();
            showPopup("Password must be at least 8 characters and include uppercase, lowercase, number, and symbol.");
        } else if (password !== confirm) {
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

</body>
</html>
