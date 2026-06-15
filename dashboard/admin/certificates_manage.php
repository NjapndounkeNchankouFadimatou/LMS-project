<?php
/**
 * File: dashboard/admin/certificates_manage.php
 * Purpose: Show student averages per module and allow admin to manually issue certificates.
 */

require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
require_once '../../config/db.php';

// Security check: only admin can access this page
if ($_SESSION['role'] !== 'admin') {
    header("Location: /LMS-project/index.php");
    exit();
}

$passing_average = 50;

// Handle certificate issuance
if (isset($_POST['issue_certificate'])) { //if admin post form 
    $student_id = $_POST['student_id'];//take the student id enter
    $module_id = $_POST['module_id']; // take the module id

    // Check if certificate already exists
    $check = $pdo->prepare("SELECT id FROM certification WHERE user_id = ? AND module_id = ?");
    $check->execute([$student_id, $module_id]);

    if ($check->rowCount() === 0) { //assures that no certificates has been issued to this student for this module
        $stmt = $pdo->prepare("
            INSERT INTO certification (module_id, user_id, description) 
            VALUES (?, ?, ?)
        ");//insere a new ligne
        $stmt->execute([$module_id, $student_id, "Certificate issued by admin"]);
    }

    header("Location: certificates_manage.php");
    exit();// else go back to manage
}

// Get all modules
$modules = $pdo->query("SELECT * FROM modules ORDER BY creation_date DESC")->fetchAll(PDO::FETCH_ASSOC);// execute as en seul seul bloque et renvoie la reponse tout les module(fetchAll) 
?>

<main class="content">

    <h1>Certificates Management</h1>

    <?php foreach ($modules as $module) {

        // Get enrolled students for this module
        $stmt = $pdo->prepare("
            SELECT u.id, u.name 
            FROM enrollments e
            INNER JOIN users u ON e.user_id = u.id
            WHERE e.module_id = ?
        ");
        $stmt->execute([$module['id']]);//search a special module
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

        <div class="module-card-large">

            <h2><?php echo htmlspecialchars($module['description']); ?></h2>

            <?php if (count($students) === 0) { ?>

                <p>No students enrolled in this module.</p>

            <?php } else { ?>

                <table class="stats-table">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Average Score</th>
                            <th>Certificate</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student) {

                            // Calculate average score for this student in this module
                            $stmt2 = $pdo->prepare("
                                SELECT AVG(score) FROM score 
                                WHERE user_id = ? 
                                AND course_id IN (SELECT id FROM courses WHERE module_id = ?)
                            ");
                            $stmt2->execute([$student['id'], $module['id']]);
                            $average = $stmt2->fetchColumn();
                            $average = $average !== null ? round($average) : 0;

                            // Check if certificate already issued
                            $check_cert = $pdo->prepare("SELECT id FROM certification WHERE user_id = ? AND module_id = ?");
                            $check_cert->execute([$student['id'], $module['id']]);
                            $has_certificate = $check_cert->rowCount() > 0;
                        ?>
                            <tr>
                                <td><?php echo htmlspecialchars($student['name']); ?></td>
                                <td><?php echo $average; ?>%</td>
                                <td>

                                    <?php if ($has_certificate) { ?>

                                        <span class="status-completed">Issued</span>

                                    <?php } elseif ($average >= $passing_average) { ?>

                                        <form action="" method="POST" class="inline-form">
                                            <input type="hidden" name="student_id" value="<?php echo $student['id']; ?>">
                                            <input type="hidden" name="module_id" value="<?php echo $module['id']; ?>">
                                            <button type="submit" name="issue_certificate" class="btn-small btn-primary">Issue Certificate</button>
                                        </form>

                                    <?php } else { ?>

                                        <span class="status-locked">Average too low (min <?php echo $passing_average; ?>%)</span>

                                    <?php } ?>

                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>

            <?php } ?>

        </div>

    <?php } ?>

</main>

<?php
require_once '../../includes/footer.php';
?>