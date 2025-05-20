<?php
require_once 'vendor/autoload.php';
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

try {
    // Initialize Google Client
    $client = new Google_Client();

    // Configure Guzzle HTTP client
    $guzzleClient = new \GuzzleHttp\Client([
        'verify' => false,  // Disable SSL verification (for development only)
        'timeout' => 30     // Set timeout in seconds
    ]);
    $client->setHttpClient($guzzleClient);

    // Set Google Client configuration
    $client->setClientId('829527123354-0og58s4jhs5atrbbj7bfvab1n08th0c8.apps.googleusercontent.com');
    $client->setClientSecret('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');
    $client->setRedirectUri('http://localhost/laundry_system/google-callback.php');
    $client->addScope('email');
    $client->addScope('profile');

    // Case 1: First time user clicks "Login with Google"
    if (!isset($_GET['code'])) {
        header('Location: ' . $client->createAuthUrl());
        exit();
    }

    // Case 2: Google redirects back with auth code
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

    if (isset($token['error'])) {
        throw new Exception("Google API Error: " . ($token['error_description'] ?? $token['error']));
    }

    $client->setAccessToken($token);
    
    // Store token in session for later use
    $_SESSION['google_token'] = $token;

    // Get user info
    $oauth2 = new Google\Service\Oauth2($client);
    $user = $oauth2->userinfo->get();

    // Validate required fields
    if (empty($user->email) || empty($user->id)) {
        throw new Exception("Required user information missing from Google");
    }

    include 'config.php';

    // Check if user exists (by google_id OR email)
    $stmt = $conn->prepare("SELECT id, google_id FROM customers WHERE google_id = ? OR email = ?");
    $stmt->bind_param("ss", $user->id, $user->email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        // New user - insert
        $insert = $conn->prepare("INSERT INTO customers (google_id, name, email, created_at) VALUES (?, ?, ?, NOW())");
        $insert->bind_param("sss", $user->id, $user->name, $user->email);
        
        if (!$insert->execute()) {
            throw new Exception("Failed to create user account: " . $conn->error);
        }
        $user_id = $conn->insert_id;
    } else {
        // Existing user
        $row = $result->fetch_assoc();
        $user_id = $row['id'];
        
        // Update google_id if missing
        if (empty($row['google_id'])) {
            $update = $conn->prepare("UPDATE customers SET google_id = ? WHERE id = ?");
            $update->bind_param("si", $user->id, $user_id);
            if (!$update->execute()) {
                throw new Exception("Failed to update user record: " . $conn->error);
            }
        }
    }

    // Set session variables
    $_SESSION['customer_id'] = $user_id;
    $_SESSION['customer_name'] = $user->name;
    $_SESSION['customer_email'] = $user->email;
    $_SESSION['logged_in'] = true;
    $_SESSION['auth_provider'] = 'google';
    
    // Regenerate session ID for security
    session_regenerate_id(true);

    // Redirect to dashboard
    header("Location: dashboard.php");
    exit();

} catch (Exception $e) {
    // Log error
    error_log("Google Login Error: " . $e->getMessage());
    
    // Redirect with error message
    header('Location: login.php?error=google_login&message=' . urlencode($e->getMessage()));
    exit();
}
?>