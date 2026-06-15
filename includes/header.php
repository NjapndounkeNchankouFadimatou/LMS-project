<?php
/**
 * File: includes/header.php
 * Purpose: Common header for all logged-in pages (dashboards + documentation).
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: /LMS-project/index.php");
    exit();
}

$dashboard_link = "/LMS-project/dashboard/" . $_SESSION['role'] . "/index.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>LMS - Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/LMS-project/assets/css/dashboard.css">
</head>
<body>

    <header class="navbar">

        <div class="navbar-logo">
            <h2>Lebano</h2>
        </div>

        <nav class="navbar-links">
            <a href="<?php echo $dashboard_link; ?>" class="navbar-link">Dashboard</a>
            <a href="/LMS-project/documentation/index.php" class="navbar-link">Documentation</a>
        </nav>

        <div class="navbar-right">
            <a href="/LMS-project/auth/logout.php" class="navbar-link logout-btn">Logout</a>
        </div>

    </header>