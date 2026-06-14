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

if (isset($_POST['submit'])) {
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
        header("Location: ../index.php?error=email_exists");
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
    switch ($role) {
        case 'student':
            header("Location: ../dashboard/student/index.php");
            exit();
        case 'teacher':
            header("Location: ../dashboard/teacher/index.php");
            exit();
        default:
            header("Location: ../index.php");
            exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>LMS - Register</title>
    <link rel="stylesheet" href="../assets/css/login.css">
</head>
<body>

    <div class="login-container">
        <h1>Welcome to LMS</h1>
        <h2>Register</h2>

        <form action="" method="POST" class="login-form">

            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <label for="sex">Sex</label>
            <select id="sex" name="sex" required>
                <option value="M">Male</option>
                <option value="F">Female</option>
            </select>

            <label for="role">Role</label>
            <select id="role" name="role" required>
                <option value="student">Student</option>
                <option value="teacher">Teacher</option>
            </select>

            <button type="submit" name="submit">Register</button>

        </form>

        <p>Already have an account? <a href="../index.php">Login here</a></p>
    </div>

</body>
</html>