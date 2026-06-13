<?php
/**
 * File: config/db.php
 * Purpose: Create a reusable PDO connection to the lms_db database.
 * This file is included by other PHP files that need database access.
 */

// Database connection settings (default XAMPP values)
$host = "localhost";
$dbname = "lms_db";
$username = "root";
$password = ""; // default XAMPP password is empty

try {
    // Create a new PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);

    // Make PDO throw exceptions on errors (easier to debug)
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    // If connection fails, stop the script and show the error
    die("Database connection failed: " . $e->getMessage());
}
?>