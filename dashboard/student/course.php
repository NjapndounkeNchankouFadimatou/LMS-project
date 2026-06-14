<?php
/**
 * File: dashboard/student/course.php
 * Purpose: Display a course's content (PDF or video) and link to its quiz.
 */

require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
require_once '../../config/db.php';

$user_id = $_SESSION['user_id'];

// Get course_id from URL
$course_id = $_GET['course_id'];

// Get course info
$stmt = $pdo->prepare("SELECT * FROM courses WHERE id = ?");
$stmt->execute([$course_id]);
$course = $stmt->fetch(PDO::FETCH_ASSOC);

// If course doesn't exist, redirect back
if (!$course) {
    header("Location: index.php");
    exit();
}

// Check if a quiz exists for this course
$stmt2 = $pdo->prepare("SELECT COUNT(*) FROM quiz WHERE course_id = ?");
$stmt2->execute([$course_id]);
$has_quiz = $stmt2->fetchColumn() > 0;

// Check if the student already has a score for this course
$stmt3 = $pdo->prepare("SELECT score FROM score WHERE user_id = ? AND course_id = ?");
$stmt3->execute([$user_id, $course_id]);
$existing_score = $stmt3->fetchColumn();
?>

<main class="content">

    <h1><?php echo htmlspecialchars($course['name']); ?></h1>
    <p><?php echo htmlspecialchars($course['description']); ?></p>

    <div class="course-content">

        <?php if ($course['type'] === 'pdf') { ?>

            <embed src="/LMS-project/<?php echo htmlspecialchars($course['file_path']); ?>" 
                   type="application/pdf" 
                   width="100%" 
                   height="600px">

        <?php } elseif ($course['type'] === 'video') { ?>

            <video width="100%" height="500px" controls>
                <source src="/LMS-project/<?php echo htmlspecialchars($course['file_path']); ?>" type="video/mp4">
                Your browser does not support the video tag.
            </video>

        <?php } ?>

    </div>

    <div class="course-actions">

        <?php if ($existing_score !== false) { ?>
            <p>Your current score: <strong><?php echo $existing_score; ?>%</strong></p>
        <?php } ?>

        <?php if ($has_quiz) { ?>
            <a href="/LMS-project/dashboard/student/quiz.php?course_id=<?php echo $course_id; ?>" class="btn-primary">
                <?php echo $existing_score !== false ? 'Retake Quiz' : 'Take Quiz'; ?>
            </a>
        <?php } else { ?>
            <p>No quiz available for this course yet.</p>
        <?php } ?>

        <a href="/LMS-project/dashboard/student/module_detail.php?module_id=<?php echo $course['module_id']; ?>" class="btn-secondary">
            Back to Module
        </a>

    </div>

</main>

<?php
require_once '../../includes/footer.php';
?>