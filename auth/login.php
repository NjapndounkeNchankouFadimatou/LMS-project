<?php
/**
 * File: auth/login.php
 * Purpose: Verify user credentials and start a session.
 */

session_start();

require_once '../config/db.php';

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
    $_SESSION['role'] = $user['role'];

    // Redirect to the correct dashboard based on role
    header("Location: ../dashboard/" . $user['role'] . "/index.php");
    exit();

} else {
    // Invalid credentials, redirect back to login with error
    header("Location: ../index.php?error=1");
    exit();
}
?>