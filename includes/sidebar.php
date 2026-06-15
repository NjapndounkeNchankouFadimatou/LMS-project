<?php
/**
 * File: includes/sidebar.php
 * Purpose: Sidebar menu shown on dashboard pages.
 * Menu links change depending on the logged-in user's role.
 */

$role = $_SESSION['role'];

// Get the current page filename to highlight the active link
$current_page = basename($_SERVER['PHP_SELF']);

// Get the first letter of the user's name for the avatar
$user_initial = strtoupper(substr($_SESSION['name'], 0, 1));
?>
<div class="dashboard-wrapper">
<aside class="sidebar">

    <nav class="sidebar-links">

        <?php if ($role === 'student') { ?>

            <a href="/LMS-project/dashboard/student/index.php" class="sidebar-link <?php echo $current_page === 'index.php' ? 'active' : ''; ?>">Home</a>
            <a href="/LMS-project/dashboard/student/modules.php" class="sidebar-link <?php echo $current_page === 'modules.php' ? 'active' : ''; ?>">Modules</a>
            <a href="/LMS-project/dashboard/student/certificates.php" class="sidebar-link <?php echo $current_page === 'certificates.php' ? 'active' : ''; ?>">Certificates</a>

        <?php } elseif ($role === 'teacher') { ?>

            <a href="/LMS-project/dashboard/teacher/index.php" class="sidebar-link <?php echo $current_page === 'index.php' ? 'active' : ''; ?>">Home</a>
            <a href="/LMS-project/dashboard/teacher/my_modules.php" class="sidebar-link <?php echo $current_page === 'my_modules.php' ? 'active' : ''; ?>">My Modules</a>
            <a href="/LMS-project/dashboard/teacher/statistics.php" class="sidebar-link <?php echo $current_page === 'statistics.php' ? 'active' : ''; ?>">Statistics</a>

        <?php } elseif ($role === 'admin') { ?>

            <a href="/LMS-project/dashboard/admin/index.php" class="sidebar-link <?php echo $current_page === 'index.php' ? 'active' : ''; ?>">Home</a>
            <a href="/LMS-project/dashboard/admin/manage.php" class="sidebar-link <?php echo $current_page === 'manage.php' ? 'active' : ''; ?>">Manage Platform</a>
            <a href="/LMS-project/dashboard/admin/certificates_manage.php" class="sidebar-link <?php echo $current_page === 'certificates_manage.php' ? 'active' : ''; ?>">Certificates</a>

        <?php } ?>

    </nav>

    <!-- User info at the bottom of the sidebar -->
    <div class="sidebar-user">
        <div class="sidebar-user-avatar"><?php echo htmlspecialchars($user_initial); ?></div>
        <div class="sidebar-user-info">
            <span class="sidebar-user-name"><?php echo htmlspecialchars($_SESSION['name']); ?></span>
            <span class="sidebar-user-role"><?php echo htmlspecialchars($_SESSION['role']); ?></span>
        </div>
    </div>

</aside>