<?php
require_once '../config.php';
require_once '../src/includes/db.php';

$page_title = "My Orders - " . SITE_NAME;
include '../templates/includes/header.php';

// User must be logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}

// Fetch user's orders
$user_id = $_SESSION['id'];
$sql = "SELECT id, created_at, total_amount, payment_status FROM orders WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$orders_result = $stmt->get_result();
$stmt->close();
?>

<h1>My Orders</h1>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
<?php endif; ?>
<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
<?php endif; ?>

<?php if ($orders_result && $orders_result->num_rows > 0): ?>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Date</th>
                <th>Total</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php while($order = $orders_result->fetch_assoc()): ?>
                <tr>
                    <td>#<?php echo $order['id']; ?></td>
                    <td><?php echo date("F j, Y", strtotime($order['created_at'])); ?></td>
                    <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                    <td><span class="badge badge-success"><?php echo ucfirst($order['payment_status']); ?></span></td>
                    <td>
                        <a href="order_detail.php?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-primary">View Details</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>You have not placed any orders yet.</p>
<?php endif; ?>


<?php include '../templates/includes/footer.php'; ?>
