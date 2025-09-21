<?php
require_once '../../config.php';
require_once '../../src/includes/db.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header("location: " . SITE_URL . "/public/login.php");
    exit;
}

$page = 'dashboard';
$page_title = "Admin Dashboard - " . SITE_NAME;
include '../includes/header.php';
?>

<div class="row">
    <div class="col-md-3">
        <?php include 'includes/sidebar.php'; ?>
    </div>
    <div class="col-md-9">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Admin Dashboard</h3>
            </div>
            <div class="card-body">
                <p>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?>!</p>
                <p>From this dashboard, you can manage products, view orders, and manage users.</p>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
