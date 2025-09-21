<?php
require_once '../config.php';
require_once '../src/includes/db.php';

$page_title = "Forgot Password - " . SITE_NAME;
include '../templates/includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title text-center">Forgot Your Password?</h3>
                <p class="text-center">Enter your email address and we will send you a link to reset your password.</p>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>

                <form action="<?php echo SITE_URL; ?>/src/backend/send_reset_link.php" method="POST">
                    <!-- Email input -->
                    <div class="form-outline mb-4">
                        <input type="email" id="email" name="email" class="form-control" required />
                        <label class="form-label" for="email">Email address</label>
                    </div>

                    <!-- Submit button -->
                    <button type="submit" class="btn btn-primary btn-block mb-4">Send Reset Link</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../templates/includes/footer.php'; ?>
