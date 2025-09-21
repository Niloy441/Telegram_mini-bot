<?php
require_once '../config.php';
require_once '../src/includes/db.php';

$page_title = "My Profile - " . SITE_NAME;
$page = 'profile';
include '../templates/includes/header.php';

// User must be logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}
?>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Update Profile Information</h5>
            </div>
            <div class="card-body">
                <form action="<?php echo SITE_URL; ?>/src/backend/update_profile.php" method="POST">
                    <!-- Name -->
                    <div class="form-outline mb-4">
                        <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($_SESSION['name']); ?>" required />
                        <label class="form-label" for="name">Name</label>
                    </div>

                    <!-- Email -->
                    <div class="form-outline mb-4">
                        <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($_SESSION['email']); ?>" required />
                        <label class="form-label" for="email">Email address</label>
                    </div>

                    <button type="submit" class="btn btn-primary">Update Profile</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Change Password</h5>
            </div>
            <div class="card-body">
                <form action="<?php echo SITE_URL; ?>/src/backend/change_password.php" method="POST">
                    <!-- Current Password -->
                    <div class="form-outline mb-4">
                        <input type="password" id="current_password" name="current_password" class="form-control" required />
                        <label class="form-label" for="current_password">Current Password</label>
                    </div>

                    <!-- New Password -->
                    <div class="form-outline mb-4">
                        <input type="password" id="new_password" name="new_password" class="form-control" required />
                        <label class="form-label" for="new_password">New Password</label>
                    </div>

                    <!-- Confirm New Password -->
                    <div class="form-outline mb-4">
                        <input type="password" id="confirm_new_password" name="confirm_new_password" class="form-control" required />
                        <label class="form-label" for="confirm_new_password">Confirm New Password</label>
                    </div>

                    <button type="submit" class="btn btn-primary">Change Password</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../templates/includes/footer.php'; ?>
