<?php
/**
 * File: dashboard/student/modules.php
 * Purpose: Show modules the student is enrolled in, with progress bars.
 */

require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
require_once '../../config/db.php';

// Get the logged-in student's id
$user_id = $_SESSION['user_id'];

// Minimum score to consider a course "completed" (out of 100)
$passing_score = 50;

// Get all modules this student is enrolled in
$stmt = $pdo->prepare("
    SELECT m.id, m.description 
    FROM modules m
    INNER JOIN enrollments e ON m.id = e.module_id
    WHERE e.user_id = ?
");
$stmt->execute([$user_id]);
$enrolled_modules = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="content">

    <h1>My Modules</h1>
    <p>Here are the modules you are currently following.</p>

    <div class="module-list">

        <?php if (count($enrolled_modules) === 0) { ?>

            <p>You are not enrolled in any module yet.</p>

        <?php } else { ?>

            <?php foreach ($enrolled_modules as $module) {

                // Count total courses in this module
                $stmt2 = $pdo->prepare("SELECT COUNT(*) FROM courses WHERE module_id = ?");
                $stmt2->execute([$module['id']]);
                $total_courses = $stmt2->fetchColumn();

                // Count courses completed by the student (score >= passing_score)
                    $stmt3 = $pdo->prepare("
                        SELECT COUNT(DISTINCT course_id) 
                        FROM score 
                        WHERE user_id = ? 
                        AND course_id IN (SELECT id FROM courses WHERE module_id = ?)
                    ");
                    $stmt3->execute([$user_id, $module['id']]);
                    $completed_courses = $stmt3->fetchColumn();

                // Calculate progress percentage
                $progress = $total_courses > 0 ? round(($completed_courses / $total_courses) * 100) : 0;
            ?>

                <div class="module-card">
                    <h3><?php echo htmlspecialchars($module['description']); ?></h3>
                    <p><?php echo $completed_courses; ?> / <?php echo $total_courses; ?> courses completed</p>

                    <div class="progress-bar">
                        <div class="progress-bar-fill" style="width: <?php echo $progress; ?>%;">
                            <?php echo $progress; ?>%
                        </div>
                    </div>

                    <a href="/LMS-project/dashboard/student/module_detail.php?module_id=<?php echo $module['id']; ?>" class="btn-primary">
                        Continue
                    </a>
                </div>

            <?php } ?>

        <?php } ?>

    </div>

    <a href="/LMS-project/dashboard/student/index.php" class="btn-secondary">Browse More Modules</a>

</main>

<?php
require_once '../../includes/footer.php';
?>