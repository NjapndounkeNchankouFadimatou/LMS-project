<?php
/**
 * File: dashboard/teacher/add_course.php
 * Purpose: Add a new course (with quiz) to an existing module owned by this teacher.
 */

require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
require_once '../../config/db.php';

$user_id = $_SESSION['user_id'];
$module_id = $_GET['module_id'];
$error = "";

// Verify that this module belongs to the logged-in teacher
$stmt = $pdo->prepare("SELECT * FROM modules WHERE id = ? AND user_id = ?");
$stmt->execute([$module_id, $user_id]);
$module = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$module) {
    // Not the owner, redirect back
    header("Location: my_modules.php");
    exit();
}

if (isset($_POST['submit'])) {

    $course_name = $_POST['course_name'];
    $course_description = $_POST['course_description'];
    $course_type = $_POST['course_type'];

    $file = $_FILES['course_file'];
    $upload_ok = true;
    $file_path = '';

    if ($file['error'] === UPLOAD_ERR_OK) {

        $folder = ($course_type === 'pdf') ? 'pdfs' : 'videos';
        $upload_dir = '../../uploads/' . $folder . '/';

        $file_name = time() . '_' . basename($file['name']);
        $destination = $upload_dir . $file_name;

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            $file_path = 'uploads/' . $folder . '/' . $file_name;
        } else {
            $upload_ok = false;
            $error = "File upload failed.";
        }

    } else {
        $upload_ok = false;
        $error = "Please select a file to upload.";
    }

    if ($upload_ok) {

        // Insert the new course into the existing module
        $stmt2 = $pdo->prepare("
            INSERT INTO courses (module_id, user_id, name, type, file_path, description) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt2->execute([$module_id, $user_id, $course_name, $course_type, $file_path, $course_description]);
        $course_id = $pdo->lastInsertId();

        // Insert quiz questions
        if (isset($_POST['question_text'])) {

            $questions = $_POST['question_text'];
            $options = $_POST['answer_options'];
            $correct = $_POST['correct_answer'];

            for ($i = 0; $i < count($questions); $i++) {

                if (trim($questions[$i]) === '') {
                    continue;
                }

                $stmt3 = $pdo->prepare("
                    INSERT INTO quiz (course_id, question_text, answer_options, correct_answer) 
                    VALUES (?, ?, ?, ?)
                ");
                $stmt3->execute([$course_id, $questions[$i], $options[$i], $correct[$i]]);
            }
        }

        header("Location: my_modules.php");
        exit();
    }
}
?>

<main class="content">

    <h1>Add Course to: <?php echo htmlspecialchars($module['description']); ?></h1>

    <?php if ($error) { ?>
        <p class="status-failed"><?php echo htmlspecialchars($error); ?></p>
    <?php } ?>

    <form action="" method="POST" enctype="multipart/form-data" class="publish-form">

        <!-- Course info -->
        <div class="form-section">
            <h2>Course Information</h2>

            <label for="course_name">Course Name</label>
            <input type="text" id="course_name" name="course_name" required>

            <label for="course_description">Course Description</label>
            <textarea id="course_description" name="course_description" required></textarea>

            <label for="course_type">Course Type</label>
            <select id="course_type" name="course_type" required>
                <option value="pdf">PDF</option>
                <option value="video">Video</option>
            </select>

            <label for="course_file">Upload File</label>
            <input type="file" id="course_file" name="course_file" required>
        </div>

        <!-- Quiz questions (dynamic) -->
        <div class="form-section">
            <h2>Quiz Questions</h2>

            <div id="questions-container">
                <div class="question-block">
                    <label>Question Text</label>
                    <input type="text" name="question_text[]" required>

                    <label>Answer Options (comma separated)</label>
                    <input type="text" name="answer_options[]" placeholder="Option A, Option B, Option C" required>

                    <label>Correct Answer (must match one option exactly)</label>
                    <input type="text" name="correct_answer[]" required>
                </div>
            </div>

            <button type="button" id="add-question-btn" class="btn-secondary">+ Add Question</button>
        </div>

        <button type="submit" name="submit" class="btn-primary">Add Course</button>

    </form>

</main>

<script src="/LMS-project/assets/js/publish.js"></script>

<?php
require_once '../../includes/footer.php';
?>