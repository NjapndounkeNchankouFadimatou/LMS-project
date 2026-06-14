<?php
/**
 * File: dashboard/teacher/statistics.php
 * Purpose: Show statistics for each module created by this teacher:
 * average score, success rate, and per-student progress.
 */

require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
require_once '../../config/db.php';

$user_id = $_SESSION['user_id'];
$passing_score = 50;

// Get all modules created by this teacher
$stmt = $pdo->prepare("SELECT * FROM modules WHERE user_id = ? ORDER BY creation_date DESC");
$stmt->execute([$user_id]);
$modules = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="content">

    <h1>Statistics</h1>

    <?php if (count($modules) === 0) { ?>

        <p>You haven't published any module yet.</p>

    <?php } else { ?>

        <?php foreach ($modules as $module) {

            // Total courses in this module
            $stmt2 = $pdo->prepare("SELECT COUNT(*) FROM courses WHERE module_id = ?");
            $stmt2->execute([$module['id']]);
            $total_courses = $stmt2->fetchColumn();

            // Enrolled students
            $stmt3 = $pdo->prepare("
                SELECT u.id, u.name 
                FROM enrollments e
                INNER JOIN users u ON e.user_id = u.id
                WHERE e.module_id = ?
            ");
            $stmt3->execute([$module['id']]);
            $students = $stmt3->fetchAll(PDO::FETCH_ASSOC);

            // Average score of all students for this module's courses
            $stmt4 = $pdo->prepare("
                SELECT AVG(score) FROM score 
                WHERE course_id IN (SELECT id FROM courses WHERE module_id = ?)
            ");
            $stmt4->execute([$module['id']]);
            $average_score = $stmt4->fetchColumn();
            $average_score = $average_score !== null ? round($average_score) : 0;
        ?>

            <div class="module-card-large">

                <h2><?php echo htmlspecialchars($module['description']); ?></h2>

                <div class="stats-summary">
                    <div class="stat-box">
                        <span class="stat-value"><?php echo count($students); ?></span>
                        <span class="stat-label">Enrolled Students</span>
                    </div>
                    <div class="stat-box">
                        <span class="stat-value"><?php echo $average_score; ?>%</span>
                        <span class="stat-label">Average Score</span>
                    </div>
                    <div class="stat-box">
                        <span class="stat-value"><?php echo $total_courses; ?></span>
                        <span class="stat-label">Total Courses</span>
                    </div>
                </div>

                <h3>Student Progress</h3>

                <?php if (count($students) === 0) { ?>

                    <p>No students enrolled yet.</p>

                <?php } else { ?>

                    <table class="stats-table">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Courses Completed</th>
                                <th>Progress</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($students as $student) {

                                // Courses completed by this student in this module
                                $stmt5 = $pdo->prepare("
                                    SELECT COUNT(DISTINCT course_id) 
                                    FROM score 
                                    WHERE user_id = ? 
                                    AND score >= ?
                                    AND course_id IN (SELECT id FROM courses WHERE module_id = ?)
                                ");
                                $stmt5->execute([$student['id'], $passing_score, $module['id']]);
                                $completed = $stmt5->fetchColumn();

                                $progress = $total_courses > 0 ? round(($completed / $total_courses) * 100) : 0;
                            ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($student['name']); ?></td>
                                    <td><?php echo $completed; ?> / <?php echo $total_courses; ?></td>
                                    <td>
                                        <div class="progress-bar">
                                            <div class="progress-bar-fill" style="width: <?php echo $progress; ?>%;">
                                                <?php echo $progress; ?>%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>

                <?php } ?>

            </div>

        <?php } ?>

    <?php } ?>

</main>

<?php
require_once '../../includes/footer.php';
?>