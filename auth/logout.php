<?php
/**
 * File: auth/logout.php
 * Purpose: Destroy the user session and redirect to the login page.
 */

session_start();

// Remove all session variables
session_unset();

// Destroy the session
session_destroy();

// Redirect to the login page
header("Location: ../index.php");
exit();
?>