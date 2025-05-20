<?php
include __DIR__ . '/../config.php';
require_once __DIR__ . '/../vendor/autoload.php';
session_start();

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$client = new Google_Client();
$client->setClientId($_ENV['GOOGLE_CLIENT_ID']);
$client->setClientSecret($_ENV['GOOGLE_CLIENT_SECRET']);
$client->setRedirectUri($_ENV['GOOGLE_REDIRECT']);
$client->addScope('email');
$client->addScope('profile');

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

    if (!isset($token['error'])) {
        $client->setAccessToken($token['access_token']);
        $oauth2 = new \Google\Service\Oauth2($client);
        $userInfo = $oauth2->userinfo->get();

        $email = $userInfo->email;
        $name = $userInfo->name;

        // ✅ Check if user exists by email
        $stmt = $conn->prepare("SELECT * FROM customers WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if (!$user) {
            // ✅ Insert new user if email not found
            $stmt = $conn->prepare("INSERT INTO customers (name, email) VALUES (?, ?)");
            $stmt->bind_param("ss", $name, $email);
            $stmt->execute();

            // Fetch again after insert
            $stmt = $conn->prepare("SELECT * FROM customers WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
        }

        // ✅ Set required session variables for consistency
        session_regenerate_id(true);
        $_SESSION['user_type'] = 'google';
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['customer_id'] = $user['id'];       // Used in place_order.php
        $_SESSION['customer_name'] = $user['name'];   // Used in dashboard.php
        $_SESSION['success'] = 'Login with Google successful.';

        // ✅ Redirect to profile completion if info missing
        if (empty($user['address']) || empty($user['phone']) || empty($user['password'])) {
            header('Location: ../User/complete-profile.php');
            exit();
        }

        // ✅ Redirect to dashboard
        header('Location: ../User/index.php');
        exit();
    } else {
        $_SESSION['error'] = 'Login Failed.';
        header('Location: login.php');
        exit();
    }
} else {
    $_SESSION['error'] = 'Invalid Login.';
    header('Location: login.php');
    exit();
}
