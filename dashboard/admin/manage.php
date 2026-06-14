<?php
/**
 * File: dashboard/admin/manage.php
 * Purpose: Manage users (edit role, delete) and modules (delete).
 */

require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
require_once '../../config/db.php';

// Security check: only admin can access this page
if ($_SESSION['role'] !== 'admin') {
    header("Location: /LMS-project/index.php");
    exit();
}

$current_admin_id = $_SESSION['user_id'];

// ============================================
// Handle actions (update role, delete user, delete module)
// ============================================

// Update user role
if (isset($_POST['update_role'])) {
    $target_user_id = $_POST['user_id'];
    $new_role = $_POST['role'];

    $stmt = $pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
    $stmt->execute([$new_role, $target_user_id]);

    header("Location: manage.php");
    exit();
}

// Delete user
if (isset($_POST['delete_user'])) {
    $target_user_id = $_POST['user_id'];

    // Prevent admin from deleting their own account
    if ($target_user_id != $current_admin_id) {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$target_user_id]);
    }

    header("Location: manage.php");
    exit();
}

// Delete module
if (isset($_POST['delete_module'])) {
    $module_id = $_POST['module_id'];

    $stmt = $pdo->prepare("DELETE FROM modules WHERE id = ?");
    $stmt->execute([$module_id]);

    header("Location: manage.php");
    exit();
}

// ============================================
// Get data for display
// ============================================

// All users
$users = $pdo->query("SELECT * FROM users ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);

// All modules with creator name and course count
$modules = $pdo->query("
    SELECT m.id, m.description, u.name AS creator_name,
           (SELECT COUNT(*) FROM courses WHERE module_id = m.id) AS course_count
    FROM modules m
    INNER JOIN users u ON m.user_id = u.id
    ORDER BY m.creation_date DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="content">

    <h1>Manage Platform</h1>

    <!-- ============================================
         Users table
    ============================================ -->
    <h2>Users</h2>

    <table class="stats-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['name']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td>

                        <!-- Update role form -->
                        <form action="" method="POST" class="inline-form">
                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">

                            <select name="role">
                                <option value="student" <?php echo $user['role'] === 'student' ? 'selected' : ''; ?>>Student</option>
                                <option value="teacher" <?php echo $user['role'] === 'teacher' ? 'selected' : ''; ?>>Teacher</option>
                                <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                            </select>

                            <button type="submit" name="update_role" class="btn-small btn-primary">Update</button>
                        </form>

                    </td>
                    <td>

                        <!-- Delete user form -->
                        <?php if ($user['id'] != $current_admin_id) { ?>
                            <form action="" method="POST" class="inline-form">
                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                <button type="submit" name="delete_user" class="btn-small btn-danger">Delete</button>
                            </form>
                        <?php } else { ?>
                            <span class="status-locked">(You)</span>
                        <?php } ?>

                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <!-- ============================================
         Modules table
    ============================================ -->
    <h2>Modules</h2>

    <table class="stats-table">
        <thead>
            <tr>
                <th>Description</th>
                <th>Created By</th>
                <th>Courses</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($modules as $module) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($module['description']); ?></td>
                    <td><?php echo htmlspecialchars($module['creator_name']); ?></td>
                    <td><?php echo $module['course_count']; ?></td>
                    <td>
                        <form action="" method="POST" class="inline-form" onsubmit="return confirm('Delete this module and all its courses?');">
                            <input type="hidden" name="module_id" value="<?php echo $module['id']; ?>">
                            <button type="submit" name="delete_module" class="btn-small btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

</main>

<?php
require_once '../../includes/footer.php';
?>