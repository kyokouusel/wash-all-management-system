<?php
session_start();
include 'config.php';

$correct_key = 'VONLOUDYLL';

// ✅ Step 1: Show secret key form if it hasn’t been entered yet
if (!isset($_SESSION['secret_key_passed'])) {
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['secret_key'])) {
        if ($_POST['secret_key'] === $correct_key) {
            $_SESSION['secret_key_passed'] = true; // Store that the secret key was verified
            header("Location: admin_login.php"); // Refresh to show login form
            exit();
        } else {
            $error = "Invalid secret key.";
        }
    }

    // ✅ Secret key form
    ?>
    <!DOCTYPE html>
   <html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css">
      <link rel="stylesheet" href="assets/css/styles.css">
      
      <title>Wash All Laundry</title>
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
      
            <!-- Insert your image (recommended size: 1000 x 1200) -->
            <image class="login__img" href="assets/img/bg-img.jpg"/>
         </g>
      </svg>      
    <div class="login container grid">
        <div class="login__access">
            <h1 class="login__title" style="margin-left: 100px;">Admin Access</h1>
            <a class="back-link" href="index.php" style="margin-bottom: 30px;">← Back to Homepage</a>

            <div class="login__area">
                <form method="POST" class="login__form">
                    <div class="login__content grid">
                        <div class="login__box">
                            <input type="password" name="secret_key" required placeholder=" " class="login__input">
                            <label class="login__label">Enter Secret Key</label>
                            <i class="ri-lock-password-fill login__icon"></i>
                        </div>
                    </div>
                    <input class="login__button" type="submit" value="Enter">
                </form>
                <?php if (isset($error)) echo "<p style='color:red; margin-top: 10px;'>$error</p>"; ?>
            </div>
        </div>
    </div>
    </body>
    </html>
    <?php
    exit();
}

// Handle login form after key is verified
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username'])) {
    $recaptchaSecret = '6Leydi0rAAAAAPhG5neUxrsdvX933XlQNUWd0qpl';
    $recaptchaResponse = $_POST['g-recaptcha-response'];
    $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$recaptchaSecret}&response={$recaptchaResponse}");
    $captchaSuccess = json_decode($verify);

    if ($captchaSuccess->success) {
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        $sql = "SELECT * FROM admin WHERE username = '$username'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            if (password_verify($password, $row['password'])) {
                $_SESSION['admin_id'] = $row['id'];
                header("Location: admin_dashboard.php");
                exit();
            } else {
                echo "<script>
                    document.addEventListener('DOMContentLoaded', function () {
                        showPopup('Invalid password.');
                    });
                </script>";
            }
        } else {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function () {
                    showPopup('No admin found with that username.');
                });
            </script>";
        }

        mysqli_close($conn);
    } else {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function () {
                showPopup('Please verify you are not a robot.');
            });
        </script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
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
        <h1 style="margin-left: 140px;" class="login__title">Log in</h1>
        <a style="margin-bottom: 30px;" class="back-link" href="index.php">← Back to Homepage</a>
        <div class="login__area">
            <form method="POST" action="" class="login__form">
                <div class="login__content grid">
                    <div class="login__box">
                        <input type="text" name="username" required placeholder=" " class="login__input">
                        <label for="username" class="login__label">Username</label>
                        <i class="ri-mail-fill login__icon"></i>
                    </div>

                    <div class="login__box">
                        <input type="password" name="password" required placeholder=" " class="login__input">
                        <label for="password" class="login__label">Password</label>
                        <i class="ri-eye-off-fill login__icon login__password" id="loginPassword"></i>
                    </div>
                </div>

                <div style="margin-top: 15px; width:100%;" class="g-recaptcha" data-sitekey="6Leydi0rAAAAAKOs-u_0z_2MNXuQha8MrMAQ6yOf"></div>

                <input class="login__button" type="submit" value="Login">
            </form>

            <a style="margin-top: -25px;" href="admin_forgot_password.php" class="login__forgot">Forgot your password?</a>
            <p style="margin-top: 15px;" class="login__switch">
                Don't have an account? 
                <a style="color: blue; margin-top: -25px;" id="loginButtonRegister" href="admin_register.php">Register Now</a>
            </p>
        </div>
    </div>
</div>

<div id="popupMessage" style="display: none; background: rgba(0,0,0,0.8); color: white; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); padding: 20px; border-radius: 8px; z-index: 9999;"></div>

<script>
function showPopup(message) {
    var popup = document.getElementById('popupMessage');
    popup.textContent = message;
    popup.style.display = 'block';
    setTimeout(function () {
        popup.style.display = 'none';
    }, 3000);
}
</script>
</body>
</html>
