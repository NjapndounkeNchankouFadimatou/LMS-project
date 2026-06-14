<?php
/**
 * File: dashboard/teacher/index.php
 * Purpose: Teacher dashboard home page.
 * Shows all modules on the platform and a "Publish" button.
 */

require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
require_once '../../config/db.php';

// Get all modules with the creator's name and course count
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

    <div class="page-header">
        <h1>All Modules</h1>
        <a href="/LMS-project/dashboard/teacher/publish.php" class="btn-primary">+ Publish New Module</a>
    </div>

    <div class="module-list">

        <?php if (count($modules) === 0) { ?>

            <p>No modules available yet. Be the first to publish one!</p>

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