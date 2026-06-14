<?php
/**
 * File: index.php
 * Purpose: Home page with the login form.
 * Sends data to auth/login.php for verification.
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>LMS - Login</title>
    <link rel="stylesheet" href="assets/css/login.css">
</head>
<body>

    <div class="login-container">
        <h1>Welcome to LMS</h1>
        <h2>Login</h2>

        <?php
        // Show error message if login failed (passed via URL parameter)
        if (isset($_GET['error'])) {
            echo '<p class="error-message">Invalid email or password.</p>';
        }
        ?>

        <form action="auth/login.php" method="POST" class="login-form">

            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Login</button>

        </form>

        <p>Don't have an account? <a href="auth/register.php">Register here</a></p>
        <p><a href="documentation/index.html">Documentation</a></p>
    </div>

</body>
</html>