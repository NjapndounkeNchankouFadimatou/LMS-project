<?php
/**
 * File: dashboard/admin/index.php
 * Purpose: Admin dashboard home page. Shows global stats and all modules.
 */

require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
require_once '../../config/db.php';

// Security check: only admin can access this page
if ($_SESSION['role'] !== 'admin') {
    header("Location: /LMS-project/index.php");
    exit();
}

// Global stats
$total_users = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$total_modules = $pdo->query("SELECT COUNT(*) FROM modules")->fetchColumn();
$total_courses = $pdo->query("SELECT COUNT(*) FROM courses")->fetchColumn();

// Get all modules with creator name and course count
$stmt = $pdo->query("
    SELECT m.id, m.description, m.creation_date, u.name AS creator_name,
           (SELECT COUNT(*) FROM courses WHERE module_id = m.id) AS course_count
    FROM modules m
    INNER JOIN users u ON m.user_id = u.id
    ORDER BY m.creation_date DESC
");
$modules = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="content">

    <h1>Admin Dashboard</h1>

    <!-- Global stats -->
    <div class="stats-summary">
        <div class="stat-box">
            <span class="stat-value"><?php echo $total_users; ?></span>
            <span class="stat-label">Total Users</span>
        </div>
        <div class="stat-box">
            <span class="stat-value"><?php echo $total_modules; ?></span>
            <span class="stat-label">Total Modules</span>
        </div>
        <div class="stat-box">
            <span class="stat-value"><?php echo $total_courses; ?></span>
            <span class="stat-label">Total Courses</span>
        </div>
    </div>

    <!-- All modules -->
    <h2>All Modules</h2>

    <div class="module-list">

        <?php if (count($modules) === 0) { ?>

            <p>No modules on the platform yet.</p>

        <?php } else { ?>

            <?php foreach ($modules as $module) { ?>

                <div class="module-card">
                    <h3><?php echo htmlspecialchars($module['description']); ?></h3>
                    <p>Created by: <?php echo htmlspecialchars($module['creator_name']); ?></p>
                    <p><?php echo $module['course_count']; ?> course(s)</p>
                </div>

            <?php } ?>

        <?php } ?>

    </div>

</main>

<?php
require_once '../../includes/footer.php';
?>