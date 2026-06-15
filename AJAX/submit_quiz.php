<?php
/**
 * File: ajax/submit_quiz.php
 * Purpose: Receive quiz answers via AJAX, calculate the score,
 * save it to the database, check for module completion (certificate),
 * and return the result as JSON.
 */

session_start();
require_once '../config/db.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];
$course_id = $_POST['course_id'];

// Decode the JSON string into a PHP associative array
$answers = json_decode($_POST['answers'], true);

// Minimum score to consider a course "completed"
$passing_score = 50;

// Get all questions for this course
$stmt = $pdo->prepare("SELECT * FROM quiz WHERE course_id = ?");
$stmt->execute([$course_id]);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total_questions = count($questions);
$correct_count = 0;

// Compare each answer with the correct one
foreach ($questions as $question) {
    $question_id = $question['id'];
    $correct_answer = trim($question['correct_answer']);

    if (isset($answers[$question_id]) && trim($answers[$question_id]) === $correct_answer) {
        $correct_count++;
    }
}

// Calculate score percentage
$score = $total_questions > 0 ? round(($correct_count / $total_questions) * 100) : 0;

// Check if a score already exists for this user/course
$check = $pdo->prepare("SELECT id FROM score WHERE user_id = ? AND course_id = ?");
$check->execute([$user_id, $course_id]);

if ($check->rowCount() > 0) {
    // Update existing score
    $update = $pdo->prepare("UPDATE score SET score = ?, date = NOW() WHERE user_id = ? AND course_id = ?");
    $update->execute([$score, $user_id, $course_id]);
} else {
    // Insert new score
    $insert = $pdo->prepare("INSERT INTO score (course_id, user_id, score) VALUES (?, ?, ?)");
    $insert->execute([$course_id, $user_id, $score]);
}

// ============================================
// Check if the module is now fully completed
// ============================================
$certificate_earned = false;

if ($score >= $passing_score) {

    // Get the module_id of this course
    $stmt2 = $pdo->prepare("SELECT module_id FROM courses WHERE id = ?");
    $stmt2->execute([$course_id]);
    $module_id = $stmt2->fetchColumn();

    // Count total courses in this module
    $stmt3 = $pdo->prepare("SELECT COUNT(*) FROM courses WHERE module_id = ?");
    $stmt3->execute([$module_id]);
    $total_courses = $stmt3->fetchColumn();

    // Count courses completed by the student in this module
    $stmt4 = $pdo->prepare("
        SELECT COUNT(DISTINCT course_id) 
        FROM score 
        WHERE user_id = ? 
        AND score >= ?
        AND course_id IN (SELECT id FROM courses WHERE module_id = ?)
    ");
    $stmt4->execute([$user_id, $passing_score, $module_id]);
    $completed_courses = $stmt4->fetchColumn();

    // If all courses are completed, issue a certificate (if not already issued)
    if ($total_courses > 0 && $completed_courses == $total_courses) {

        $check_cert = $pdo->prepare("SELECT id FROM certification WHERE user_id = ? AND module_id = ?");
        $check_cert->execute([$user_id, $module_id]);

        if ($check_cert->rowCount() === 0) {
            $insert_cert = $pdo->prepare("
                INSERT INTO certification (module_id, user_id, description) 
                VALUES (?, ?, ?)
            ");
            $insert_cert->execute([$module_id, $user_id, "Module completed successfully"]);
            $certificate_earned = true;
        }
    }
}

// Return result as JSON
echo json_encode([
    'success' => true,
    'score' => $score,
    'correct_count' => $correct_count,
    'total_questions' => $total_questions,
    'certificate_earned' => $certificate_earned
]);
?>