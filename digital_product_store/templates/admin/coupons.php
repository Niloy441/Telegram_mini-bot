<?php
require_once '../../config.php';
require_once '../../src/includes/db.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header("location: " . SITE_URL . "/public/login.php");
    exit;
}

// Fetch all coupons
$sql = "SELECT * FROM coupons ORDER BY created_at DESC";
$coupons_result = $mysqli->query($sql);

$page = 'coupons';
$page_title = "Manage Coupons - " . SITE_NAME;
include '../includes/header.php';
?>

<div class="row">
    <div class="col-md-3">
        <?php include 'includes/sidebar.php'; ?>
    </div>
    <div class="col-md-9">
        <!-- Add Coupon Form -->
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">Add New Coupon</h3>
            </div>
            <div class="card-body">
                <form action="<?php echo SITE_URL; ?>/src/backend/admin/add_coupon.php" method="POST">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-outline mb-4">
                                <input type="text" id="code" name="code" class="form-control" required />
                                <label class="form-label" for="code">Coupon Code</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <select class="form-select mb-4" name="type" required>
                                <option value="percentage">Percentage</option>
                                <option value="flat">Flat Rate</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-outline mb-4">
                                <input type="number" id="value" name="value" class="form-control" step="0.01" required />
                                <label class="form-label" for="value">Value (%) or ($)</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-outline mb-4">
                                <input type="date" id="expiry_date" name="expiry_date" class="form-control" />
                                <label class="form-label" for="expiry_date">Expiry Date</label>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Coupon</button>
                </form>
            </div>
        </div>

        <!-- List Coupons -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Existing Coupons</h3>
            </div>
            <div class="card-body">
                <table class="table align-middle mb-0 bg-white">
                    <thead class="bg-light">
                        <tr>
                            <th>Code</th>
                            <th>Type</th>
                            <th>Value</th>
                            <th>Expiry Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($coupons_result && $coupons_result->num_rows > 0): ?>
                            <?php while($coupon = $coupons_result->fetch_assoc()): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($coupon['code']); ?></strong></td>
                                    <td><?php echo ucfirst($coupon['type']); ?></td>
                                    <td><?php echo ($coupon['type'] == 'percentage') ? $coupon['value'] . '%' : '$' . $coupon['value']; ?></td>
                                    <td><?php echo $coupon['expiry_date'] ? date("M j, Y", strtotime($coupon['expiry_date'])) : 'N/A'; ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo $coupon['status'] == 'active' ? 'success' : 'danger'; ?> d-inline">
                                            <?php echo ucfirst($coupon['status']); ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">No coupons found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
