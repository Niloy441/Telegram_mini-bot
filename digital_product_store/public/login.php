<?php
require_once '../config.php';
require_once '../src/includes/db.php';

$page_title = "Login - " . SITE_NAME;
include '../templates/includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title text-center">Login to your Account</h3>

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

                <form action="<?php echo SITE_URL; ?>/src/backend/login.php" method="POST">
                    <!-- Email input -->
                    <div class="form-outline mb-4">
                        <input type="email" id="email" name="email" class="form-control" required />
                        <label class="form-label" for="email">Email address</label>
                    </div>

                    <!-- Password input -->
                    <div class="form-outline mb-4">
                        <input type="password" id="password" name="password" class="form-control" required />
                        <label class="form-label" for="password">Password</label>
                    </div>

                    <!-- Submit button -->
                    <button type="submit" class="btn btn-primary btn-block mb-4">Sign in</button>

                    <!-- Register link -->
                    <div class="text-center">
                        <p>Not a member? <a href="register.php">Register</a></p>
                        <p><a href="forgot_password.php">Forgot password?</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../templates/includes/footer.php'; ?>
