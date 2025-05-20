<?php
require_once 'vendor/autoload.php';
session_start();

// 1. Validate session token first
if (empty($_SESSION['google_token'])) {
    header('Location: login.php?error=invalid_access');
    exit();
}

try {
    // 2. Initialize Google Client and validate token
    $client = new Google_Client();
    $client->setAccessToken($_SESSION['google_token']);
    
    // Verify token is still valid
    if ($client->isAccessTokenExpired()) {
        throw new Exception("Google session expired");
    }

    // 3. Get user info
    $oauth2 = new Google\Service\Oauth2($client);
    $user = $oauth2->userinfo->get();

    // 4. Validate required user data
    if (empty($user->id) || empty($user->email)) {
        throw new Exception("Invalid user data from Google");
    }

    include 'config.php';

    // 5. Check if user exists in DB
    $stmt = $conn->prepare("SELECT id, name FROM customers WHERE google_id = ? OR email = ?");
    $stmt->bind_param("ss", $user->id, $user->email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        // New user - insert
        $insert = $conn->prepare("INSERT INTO customers (google_id, name, email, created_at) VALUES (?, ?, ?, NOW())");
        $insert->bind_param("sss", $user->id, $user->name, $user->email);
        
        if (!$insert->execute()) {
            throw new Exception("Failed to create user account");
        }
        
        $user_id = $conn->insert_id;
    } else {
        // Existing user - update google_id if missing
        $existing = $result->fetch_assoc();
        $user_id = $existing['id'];
        
        if (empty($existing['google_id'])) {
            $update = $conn->prepare("UPDATE customers SET google_id = ? WHERE id = ?");
            $update->bind_param("si", $user->id, $user_id);
            $update->execute();
        }
    }

    // 6. Set session variables
    $_SESSION['customer_id'] = $user_id;
    $_SESSION['customer_name'] = $user->name;
    $_SESSION['customer_email'] = $user->email;
    $_SESSION['logged_in'] = true;
    $_SESSION['auth_provider'] = 'google';
    
    // 7. Redirect to dashboard
    header('Location: User/index.php');
    exit();

} catch (Exception $e) {
    // Log error and redirect
    error_log("Login Validation Error: " . $e->getMessage());
    header('Location: login.php?error=auth_failed&message=' . urlencode($e->getMessage()));
    exit();
}