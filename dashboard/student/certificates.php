<?php
/**
 * File: dashboard/student/certificates.php
 * Purpose: Show all certificates earned by the student.
 */

require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
require_once '../../config/db.php';

$user_id = $_SESSION['user_id'];

// Get all certificates for this student, with module description
$stmt = $pdo->prepare("
    SELECT c.id, c.description, c.issued_date, m.description AS module_name
    FROM certification c
    INNER JOIN modules m ON c.module_id = m.id
    WHERE c.user_id = ?
    ORDER BY c.issued_date DESC
");
$stmt->execute([$user_id]);
$certificates = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="content">

    <h1>My Certificates</h1>

    <?php if (count($certificates) === 0) { ?>

        <p>You have not earned any certificate yet. Complete all courses in a module to get one.</p>

    <?php } else { ?>

        <div class="certificate-list">

            <?php foreach ($certificates as $cert) { ?>

                <div class="certificate-card">
                    <h3>Module: <?php echo htmlspecialchars($cert['module_name']); ?></h3>
                    <p><?php echo htmlspecialchars($cert['description']); ?></p>
                    <p class="cert-date">Issued on: <?php echo date("d/m/Y", strtotime($cert['issued_date'])); ?></p>
                </div>

            <?php } ?>

        </div>

    <?php } ?>

</main>

<?php
require_once '../../includes/footer.php';
?>