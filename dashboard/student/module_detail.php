<?php
/**
 * File: dashboard/student/module_detail.php
 * Purpose: Show all courses of a module, with lock/unlock logic based on quiz scores.
 */

require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
require_once '../../config/db.php';

$user_id = $_SESSION['user_id'];
$passing_score = 50;

// Get module_id from URL
$module_id = $_GET['module_id'];

// Check that the student is enrolled in this module
$check = $pdo->prepare("SELECT id FROM enrollments WHERE user_id = ? AND module_id = ?");
$check->execute([$user_id, $module_id]);

if ($check->rowCount() === 0) {
    // Not enrolled, redirect back
    header("Location: modules.php");
    exit();
}

// Get module info
$stmt = $pdo->prepare("SELECT * FROM modules WHERE id = ?");
$stmt->execute([$module_id]);
$module = $stmt->fetch(PDO::FETCH_ASSOC);

// Get all courses of this module, ordered by id (defines the course order)
$stmt2 = $pdo->prepare("SELECT * FROM courses WHERE module_id = ? ORDER BY id ASC");
$stmt2->execute([$module_id]);
$courses = $stmt2->fetchAll(PDO::FETCH_ASSOC);

// Get all scores of this student for courses in this module
$stmt3 = $pdo->prepare("
    SELECT course_id, score FROM score 
    WHERE user_id = ? AND course_id IN (SELECT id FROM courses WHERE module_id = ?)
");
$stmt3->execute([$user_id, $module_id]);
$scores = $stmt3->fetchAll(PDO::FETCH_KEY_PAIR); // [course_id => score]
?>

<main class="content">

    <h1><?php echo htmlspecialchars($module['description']); ?></h1>
    <a href="/LMS-project/dashboard/student/index.php" class="btn-secondary">Back to Home</a>

    <div class="course-list">

        <?php
        $previous_passed = true; // first course is always unlocked

        foreach ($courses as $course) {

            $course_id = $course['id'];
            $has_score = isset($scores[$course_id]);
            $score_value = $has_score ? $scores[$course_id] : null;
            $is_completed = $has_score && $score_value >= $passing_score;
            $is_locked = !$previous_passed;
        ?>

            <div class="course-card <?php echo $is_locked ? 'locked' : ''; ?>">

                <h3><?php echo htmlspecialchars($course['name']); ?></h3>
                <p><?php echo htmlspecialchars($course['description']); ?></p>
                <p>Type: <?php echo strtoupper($course['type']); ?></p>

                <?php if ($is_completed) { ?>
                    <p class="status-completed">Completed - Score: <?php echo $score_value; ?>%</p>
                    <a href="/LMS-project/dashboard/student/course.php?course_id=<?php echo $course_id; ?>" class="btn-secondary">Review</a>

                <?php } elseif ($is_locked) { ?>
                    <p class="status-locked">Locked - Complete the previous course first</p>

                <?php } else { ?>
                    <?php if ($has_score) { ?>
                        <p class="status-failed">Score: <?php echo $score_value; ?>% (try again)</p>
                    <?php } ?>
                    <a href="/LMS-project/dashboard/student/course.php?course_id=<?php echo $course_id; ?>" class="btn-primary">Start Course</a>
                <?php } ?>

            </div>

        <?php
            // Update previous_passed for the next iteration
            $previous_passed = $is_completed;
        }
        ?>

    </div>

</main>

<?php
require_once '../../includes/footer.php';
?>