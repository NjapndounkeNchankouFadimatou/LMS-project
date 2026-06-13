<?php
/**
 * File: auth/register.php
 * Purpose: Process the registration form, create a new user,
 * log them in automatically, and redirect to their dashboard.
 */

// Start the session
session_start();

// Include database connection
require_once '../config/db.php';

// Get form data
$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
$sex = $_POST['sex'];
$role = $_POST['role'];

// Check if email already exists
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$email]);

if ($stmt->rowCount() > 0) {
    // Email already used, redirect back with error
    header("Location: register.html?error=email_exists");
    exit();
}

// Hash the password before storing it
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert the new user into the database
$stmt = $pdo->prepare("INSERT INTO users (name, email, password, role, sex) VALUES (?, ?, ?, ?, ?)");
$stmt->execute([$name, $email, $hashed_password, $role, $sex]);

// Get the id of the newly created user
$user_id = $pdo->lastInsertId();

// Create session variables (auto-login)
$_SESSION['user_id'] = $user_id;
$_SESSION['name'] = $name;
$_SESSION['role'] = $role;

// Redirect to the correct dashboard based on role
header("Location: ../dashboard/$role/index.php");
exit();
?>