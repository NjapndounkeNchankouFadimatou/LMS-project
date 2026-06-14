<?php
/**
 * File: dashboard/teacher/my_modules.php
 * Purpose: List modules created by this teacher, their courses, and enrolled students.
 */

require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
require_once '../../config/db.php';

$user_id = $_SESSION['user_id'];

// Get all modules created by this teacher
$stmt = $pdo->prepare("SELECT * FROM modules WHERE user_id = ? ORDER BY creation_date DESC");
$stmt->execute([$user_id]);
$modules = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="content">

    <h1>My Modules</h1>

    <?php if (count($modules) === 0) { ?>

        <p>You haven't published any module yet.</p>
        <a href="/LMS-project/dashboard/teacher/publish.php" class="btn-primary">+ Publish New Module</a>

    <?php } else { ?>

        <?php foreach ($modules as $module) {

            // Get courses of this module
            $stmt2 = $pdo->prepare("SELECT * FROM courses WHERE module_id = ?");
            $stmt2->execute([$module['id']]);
            $courses = $stmt2->fetchAll(PDO::FETCH_ASSOC);

            // Get enrolled students for this module
            $stmt3 = $pdo->prepare("
                SELECT u.id, u.name 
                FROM enrollments e
                INNER JOIN users u ON e.user_id = u.id
                WHERE e.module_id = ?
            ");
            $stmt3->execute([$module['id']]);
            $students = $stmt3->fetchAll(PDO::FETCH_ASSOC);
        ?>

            <div class="module-card-large">

                <h2><?php echo htmlspecialchars($module['description']); ?></h2>

                <!-- Courses -->
                <h3>Courses (<?php echo count($courses); ?>)</h3>
                <ul class="simple-list">
                    <?php foreach ($courses as $course) { ?>
                        <li><?php echo htmlspecialchars($course['name']); ?> (<?php echo strtoupper($course['type']); ?>)</li>
                    <?php } ?>
                </ul>

                <a href="/LMS-project/dashboard/teacher/add_course.php?module_id=<?php echo $module['id']; ?>" class="btn-secondary">
                    + Add Course
                </a>

                <!-- Enrolled students -->
                <h3>Enrolled Students (<?php echo count($students); ?>)</h3>

                <?php if (count($students) === 0) { ?>
                    <p>No students enrolled yet.</p>
                <?php } else { ?>
                    <ul class="simple-list">
                        <?php foreach ($students as $student) { ?>
                            <li><?php echo htmlspecialchars($student['name']); ?></li>
                        <?php } ?>
                    </ul>
                <?php } ?>

            </div>

        <?php } ?>

    <?php } ?>

</main>

<?php
require_once '../../includes/footer.php';
?>