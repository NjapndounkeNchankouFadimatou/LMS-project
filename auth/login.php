<?php
/**
 * File: auth/login.php
 * Purpose: Verify user credentials and start a session.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/db.php';

// Define the admin email (only this email can access the admin dashboard)
$admin_email = "admin@lms.com";

$email = $_POST['email'];
$password = $_POST['password'];

// Find the user by email
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if user exists and password matches
if ($user && password_verify($password, $user['password'])) {

    // Create session variables
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['name'] = $user['name'];

    // If this email matches the admin email, force admin role
    if ($email === $admin_email) {
        $_SESSION['role'] = 'admin';
    } else {
        $_SESSION['role'] = $user['role'];
    }

    // Redirect to the correct dashboard based on role
    header("Location: /LMS-project/dashboard/" . $_SESSION['role'] . "/index.php");
    exit();

} else {
    // Invalid credentials, redirect back to login with error
    header("Location: /LMS-project/index.php?error=1");
    exit();
}
?>