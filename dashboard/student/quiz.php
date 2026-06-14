<?php
/**
 * File: dashboard/student/quiz.php
 * Purpose: Display quiz questions for a course. Submission is handled via AJAX.
 */

require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
require_once '../../config/db.php';

$course_id = $_GET['course_id'];

// Get course info
$stmt = $pdo->prepare("SELECT * FROM courses WHERE id = ?");
$stmt->execute([$course_id]);
$course = $stmt->fetch(PDO::FETCH_ASSOC);

// Get all questions for this course's quiz
$stmt2 = $pdo->prepare("SELECT * FROM quiz WHERE course_id = ?");
$stmt2->execute([$course_id]);
$questions = $stmt2->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="content">

    <h1>Quiz: <?php echo htmlspecialchars($course['name']); ?></h1>

    <?php if (count($questions) === 0) { ?>

        <p>No questions available for this quiz.</p>

    <?php } else { ?>

        <form id="quiz-form">

            <input type="hidden" name="course_id" value="<?php echo $course_id; ?>">

            <?php foreach ($questions as $index => $question) {

                // Split answer options string into an array
                $options = explode(",", $question['answer_options']);
            ?>

                <div class="quiz-question">
                    <p><strong>Question <?php echo $index + 1; ?>:</strong> <?php echo htmlspecialchars($question['question_text']); ?></p>

                    <?php foreach ($options as $option) {
                        $option = trim($option);
                    ?>
                        <label class="quiz-option">
                            <input type="radio" 
                                   name="answer_<?php echo $question['id']; ?>" 
                                   value="<?php echo htmlspecialchars($option); ?>">
                            <?php echo htmlspecialchars($option); ?>
                        </label>
                    <?php } ?>

                </div>

            <?php } ?>

            <button type="button" id="submit-quiz-btn" class="btn-primary">Submit Quiz</button>

        </form>

        <!-- Result will be displayed here after submission -->
        <div id="quiz-result"></div>

    <?php } ?>

    <a href="/LMS-project/dashboard/student/course.php?course_id=<?php echo $course_id; ?>" class="btn-secondary">Back to Course</a>

</main>

<script src="/LMS-project/assets/js/quiz.js"></script>

<?php
require_once '../../includes/footer.php';
?>