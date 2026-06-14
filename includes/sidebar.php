<?php
/**
 * File: includes/sidebar.php
 * Purpose: Sidebar menu shown on dashboard pages.
 * Menu links change depending on the logged-in user's role.
 */

$role = $_SESSION['role'];
?>

<aside class="sidebar">

    <nav class="sidebar-links">

        <?php if ($role === 'student') { ?>

            <a href="/LMS-project/dashboard/student/index.php" class="sidebar-link">Home</a>
            <a href="/LMS-project/dashboard/student/modules.php" class="sidebar-link">Modules</a>
            <a href="/LMS-project/dashboard/student/certificates.php" class="sidebar-link">Certificates</a>
        <?php } elseif ($role === 'teacher') { ?>

            <a href="/LMS-project/dashboard/teacher/index.php" class="sidebar-link">Home</a>
            <a href="/LMS-project/dashboard/teacher/my_modules.php" class="sidebar-link">My Modules</a>
            <a href="/LMS-project/dashboard/teacher/statistics.php" class="sidebar-link">Statistics</a>

<?php } elseif ($role === 'admin') { ?>

    <a href="/LMS-project/dashboard/admin/index.php" class="sidebar-link">Home</a>
    <a href="/LMS-project/dashboard/admin/manage.php" class="sidebar-link">Manage Platform</a>
    <a href="/LMS-project/dashboard/admin/certificates_manage.php" class="sidebar-link">Certificates</a>

<?php }?>

    </nav>

</aside>