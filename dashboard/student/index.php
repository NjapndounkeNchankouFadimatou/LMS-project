<?php
/**
 * File: dashboard/student/index.php
 * Purpose: Home page. Shows ALL available modules on the platform.
 * Student can enroll in a module or continue an enrolled one.
 */

require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
require_once '../../config/db.php';

$user_id = $_SESSION['user_id'];

// Handle enrollment form submission
if (isset($_POST['enroll']) && isset($_POST['module_id'])) {
    $module_id = $_POST['module_id'];

    // Check if already enrolled (avoid duplicates)
    $check = $pdo->prepare("SELECT id FROM enrollments WHERE user_id = ? AND module_id = ?");
    $check->execute([$user_id, $module_id]);

    if ($check->rowCount() === 0) {
        $insert = $pdo->prepare("INSERT INTO enrollments (user_id, module_id) VALUES (?, ?)");
        $insert->execute([$user_id, $module_id]);
    }

    // Redirect to avoid form resubmission on refresh
    header("Location: index.php");
    exit();
}

// Get all modules
$stmt = $pdo->query("SELECT * FROM modules");
$all_modules = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get the list of module ids the student is already enrolled in
$stmt2 = $pdo->prepare("SELECT module_id FROM enrollments WHERE user_id = ?");
$stmt2->execute([$user_id]);
$enrolled_ids = $stmt2->fetchAll(PDO::FETCH_COLUMN);
?>

<main class="content">

    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?></h1>
    <p>Browse all available modules and enroll to start learning.</p>

    <div class="module-list">

        <?php if (count($all_modules) === 0) { ?>

            <p>No modules available yet.</p>

        <?php } else { ?>

            <?php foreach ($all_modules as $module) {

                // Count total courses in this module
                $stmt3 = $pdo->prepare("SELECT COUNT(*) FROM courses WHERE module_id = ?");
                $stmt3->execute([$module['id']]);
                $total_courses = $stmt3->fetchColumn();

                $is_enrolled = in_array($module['id'], $enrolled_ids);
            ?>

                <div class="module-card">
                    <h3><?php echo htmlspecialchars($module['description']); ?></h3>
                    <p><?php echo $total_courses; ?> course(s) in this module</p>

                    <?php if ($is_enrolled) { ?>

                        <a href="/LMS-project/dashboard/student/module_detail.php?module_id=<?php echo $module['id']; ?>" class="btn-primary">
                            Continue
                        </a>

                    <?php } else { ?>

                        <form action="" method="POST">
                            <input type="hidden" name="module_id" value="<?php echo $module['id']; ?>">
                            <button type="submit" name="enroll" class="btn-primary">Enroll</button>
                        </form>

                    <?php } ?>
                </div>

            <?php } ?>

        <?php } ?>

    </div>

</main>

<?php
require_once '../../includes/footer.php';
?>