<?php
require_once '../config.php';
require_once '../src/includes/db.php';

// Get token from URL
$token = isset($_GET['token']) ? $_GET['token'] : '';
if (empty($token)) {
    die("Invalid reset token.");
}

$page_title = "Reset Password - " . SITE_NAME;
include '../templates/includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title text-center">Reset Your Password</h3>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>

                <form action="<?php echo SITE_URL; ?>/src/backend/update_password.php" method="POST">
                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

                    <!-- New Password input -->
                    <div class="form-outline mb-4">
                        <input type="password" id="new_password" name="new_password" class="form-control" required />
                        <label class="form-label" for="new_password">New Password</label>
                    </div>

                    <!-- Confirm New Password input -->
                    <div class="form-outline mb-4">
                        <input type="password" id="confirm_new_password" name="confirm_new_password" class="form-control" required />
                        <label class="form-label" for="confirm_new_password">Confirm New Password</label>
                    </div>

                    <!-- Submit button -->
                    <button type="submit" class="btn btn-primary btn-block mb-4">Reset Password</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../templates/includes/footer.php'; ?>
