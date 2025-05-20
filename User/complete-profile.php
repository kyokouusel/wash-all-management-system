<?php
session_start();
include '../config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../googleAuth/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $userId = $_SESSION['user_id'];

    $stmt = $conn->prepare("UPDATE customers SET address = ?, phone = ?, password = ? WHERE id = ?");
    $stmt->bind_param("sssi", $address, $phone, $password, $userId);
    $stmt->execute();

    $_SESSION['success'] = 'Profile completed!';
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <title>Complete Profile</title>
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
        <image class="login__img" href="../assets/img/bg-img.jpg"/>
    </g>
</svg>

<div class="login container grid" id="loginAccessRegister">
    <div class="login__access">
        <h1 style="margin-left: 100px;" class="login__title">Complete Your Profile</h1>
        <div class="login__area">
            <form method="POST" action="" class="login__form">
                <div class="login__content grid">

                    <div class="login__box">
                        <input type="text" name="address" required placeholder=" " class="login__input">
                        <label for="address" class="login__label">Address</label>
                        <i class="ri-home-2-line login__icon"></i>
                    </div>

                    <div class="login__box">
                        <input type="text" name="phone" required placeholder=" " class="login__input">
                        <label for="phone" class="login__label">Phone Number</label>
                        <i class="ri-phone-fill login__icon"></i>
                    </div>

                    <div class="login__box">
                        <input type="password" name="password" required placeholder=" " class="login__input">
                        <label for="password" class="login__label">Create Password</label>
                        <i class="ri-lock-password-fill login__icon"></i>
                    </div>
                </div>

                <input class="login__button" type="submit" value="Save Profile">
            </form>
        </div>
    </div>
</div>

<?php if (!empty($message)): ?>
    <div id="popupMessage" style="display: block; background: rgba(0,0,0,0.8); color: white; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); padding: 20px; border-radius: 8px; z-index: 9999;">
        <?php echo $message; ?>
    </div>
<?php endif; ?>

</body>
</html>
