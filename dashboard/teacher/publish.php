<?php
/**
 * File: dashboard/teacher/publish.php
 * Purpose: Create a new module, its first course, and the course quiz questions.
 */

require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
require_once '../../config/db.php';

$user_id = $_SESSION['user_id'];
$error = "";

if (isset($_POST['submit'])) {

    // Get form data
    $module_description = $_POST['module_description'];
    $course_name = $_POST['course_name'];
    $course_description = $_POST['course_description'];
    $course_type = $_POST['course_type'];

    // Handle file upload
    $file = $_FILES['course_file'];
    $upload_ok = true;
    $file_path = '';

    if ($file['error'] === UPLOAD_ERR_OK) {

        // Choose folder based on course type
        $folder = ($course_type === 'pdf') ? 'pdfs' : 'videos';
        $upload_dir = '../../uploads/' . $folder . '/';

        // Generate a unique file name to avoid overwriting
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

        // 1. Insert the new module
        $stmt = $pdo->prepare("INSERT INTO modules (user_id, description) VALUES (?, ?)");
        $stmt->execute([$user_id, $module_description]);
        $module_id = $pdo->lastInsertId();

        // 2. Insert the course
        $stmt2 = $pdo->prepare("
            INSERT INTO courses (module_id, user_id, name, type, file_path, description) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt2->execute([$module_id, $user_id, $course_name, $course_type, $file_path, $course_description]);
        $course_id = $pdo->lastInsertId();

        // 3. Insert quiz questions
        if (isset($_POST['question_text'])) {

            $questions_text = $_POST['question_text'];
            $options_a = $_POST['option_a'];
            $options_b = $_POST['option_b'];
            $options_c = $_POST['option_c'];
            $options_d = $_POST['option_d'];

            for ($i = 0; $i < count($questions_text); $i++) {

                // Skip empty questions
                if (trim($questions_text[$i]) === '') {
                    continue;
                }

                // Build the options array for this question
                $options = [
                    'A' => trim($options_a[$i]),
                    'B' => trim($options_b[$i]),
                    'C' => trim($options_c[$i]),
                    'D' => trim($options_d[$i])
                ];

                // Get which option was marked as correct (A, B, C, or D)
                $correct_letter = $_POST['correct_' . $i];

                // Build the comma-separated options string
                $answer_options_string = implode(",", $options);

                // Get the actual text of the correct answer
                $correct_answer_text = $options[$correct_letter];

                $stmt3 = $pdo->prepare("
                    INSERT INTO quiz (course_id, question_text, answer_options, correct_answer) 
                    VALUES (?, ?, ?, ?)
                ");
                $stmt3->execute([$course_id, $questions_text[$i], $answer_options_string, $correct_answer_text]);
            }
        }

        // Redirect to "My Modules" after success
        header("Location: my_modules.php");
        exit();
    }
}
?>

<main class="content">

    <h1>Publish New Module</h1>

    <?php if ($error) { ?>
        <p class="status-failed"><?php echo htmlspecialchars($error); ?></p>
    <?php } ?>

    <form action="" method="POST" enctype="multipart/form-data" class="publish-form">

        <!-- Module info -->
        <div class="form-section">
            <h2>Module Information</h2>

            <label for="module_description">Module Description</label>
            <textarea id="module_description" name="module_description" required></textarea>
        </div>

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
                <!-- First question block (template repeated by JS) -->
                <div class="question-block">

                    <label>Question Text</label>
                    <input type="text" name="question_text[]" required>

                    <label>Options (select the radio button next to the correct one)</label>

                    <div class="option-row">
                        <input type="text" name="option_a[]" placeholder="Option A" required>
                        <label><input type="radio" name="correct_0" value="A" required> Correct</label>
                    </div>

                    <div class="option-row">
                        <input type="text" name="option_b[]" placeholder="Option B" required>
                        <label><input type="radio" name="correct_0" value="B"> Correct</label>
                    </div>

                    <div class="option-row">
                        <input type="text" name="option_c[]" placeholder="Option C" required>
                        <label><input type="radio" name="correct_0" value="C"> Correct</label>
                    </div>

                    <div class="option-row">
                        <input type="text" name="option_d[]" placeholder="Option D" required>
                        <label><input type="radio" name="correct_0" value="D"> Correct</label>
                    </div>

                </div>
            </div>

            <button type="button" id="add-question-btn" class="btn-secondary">+ Add Question</button>
        </div>

        <button type="submit" name="submit" class="btn-primary">Publish Module</button>

    </form>

</main>

<script src="/LMS-project/assets/js/publish.js"></script>

<?php
require_once '../../includes/footer.php';
?>