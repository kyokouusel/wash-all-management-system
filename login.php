<?php
include 'config.php';
session_start();

$message = "";

// Verify reCAPTCHA and Login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $recaptchaResponse = $_POST['g-recaptcha-response'];

    // 1. Verify reCAPTCHA
    $secretKey = '6Leydi0rAAAAAPhG5neUxrsdvX933XlQNUWd0qpl';
    $verifyURL = 'https://www.google.com/recaptcha/api/siteverify';
    $data = [
        'secret' => $secretKey,
        'response' => $recaptchaResponse,
    ];
    $options = [
        'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),
        ],
    ];
    $context  = stream_context_create($options);
    $verify = file_get_contents($verifyURL, false, $context);
    $captchaSuccess = json_decode($verify);

    if ($captchaSuccess->success) {
        // Proceed with login if reCAPTCHA passed
        $sql = "SELECT * FROM customers WHERE email = '$email'";
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['customer_name'] = $row['name'];
                $_SESSION['customer_id'] = $row['id'];
                header("Location: User/index.php");
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
                showPopup('No user found with that email.');
            });
        </script>";
        }
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
            <image class="login__img" href="assets/img/bg-img.jpg"/>
         </g>
      </svg>      

   
      <div class="login container grid" id="loginAccessRegister">
       
         <div class="login__access">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <div class="container">
        </form>
   
<div class="login container grid" id="loginAccessRegister">
   
    <div class="login__access">
        <h1 style="margin-left: 140px;" class="login__title">Log in</h1>
        <a style="margin-bottom: 30px;" class="back-link" href="index.php">← Back to Homepage</a>
        <div class="login__area">
            <form method="POST" action="" class="login__form">
                <div class="login__content grid">
                    
                <div class="login__box">
                        <input type="email" name="email" required placeholder=" " class="login__input">
                        <label for="email" class="login__label">Email</label>
                        <i class="ri-mail-fill login__icon"></i>
                    </div>

                   
                    <div class="login__box">
                        <input type="password" name="password" required placeholder=" " class="login__input">
                        <label for="password" class="login__label">Password</label>
                        <i class="ri-eye-off-fill login__icon login__password" id="loginPassword"></i>
                    </div>
                </div>

                <!-- reCAPTCHA Widget -->
                <div style="margin-top:  15px; width:100%;" class="g-recaptcha" data-sitekey="6Leydi0rAAAAAKOs-u_0z_2MNXuQha8MrMAQ6yOf"></div>
                
                <input class="login__button" type="submit" value="Login">
            </form>

                <div class="mt-3 text-center">
                <a href="googleAuth/google-login.php" class="btn btn-outline-danger w-100">
                    <i class="mdi mdi-google me-2"></i> Sign in with Google
                </a>
                </div>

            <a style="margin-top: -25px;" href="customer_forgot_password.php" class="login__forgot">Forgot your password?</a>

            <p style="margin-top: 15px;" class="login__switch">
                Don't have an account? 
                <a style=" color: blue; margin-top: -25px;" id="loginButtonRegister" href="register.php" >Register Now</a>
            </p>
        </div>
    </div>
</div>
    </div>

    <div id="popupMessage" style="display: none; background: rgba(0,0,0,0.8); color: white; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); padding: 20px; border-radius: 8px; z-index: 9999;"></div>

<script>
function showPopup(message) {
    var popup = document.getElementById('popupMessage');
    popup.textContent = message;
    popup.style.display = 'block';
    
    // Auto-hide after 3 seconds
    setTimeout(function () {
        popup.style.display = 'none';
    }, 3000);
}
</script>
</body>
</html>

