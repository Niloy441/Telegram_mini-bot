<?php
require_once '../config.php';
require_once '../src/includes/db.php';

$page_title = "Order Details - " . SITE_NAME;
include '../templates/includes/header.php';

// User must be logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}

// Get order ID from URL and validate
if (!isset($_GET['id']) || !($order_id = filter_var($_GET['id'], FILTER_VALIDATE_INT))) {
    header("location: orders.php");
    exit;
}

// Fetch order details and verify ownership
$user_id = $_SESSION['id'];
$sql_order = "SELECT * FROM orders WHERE id = ? AND user_id = ?";
$stmt_order = $mysqli->prepare($sql_order);
$stmt_order->bind_param("ii", $order_id, $user_id);
$stmt_order->execute();
$order_result = $stmt_order->get_result();
if ($order_result->num_rows != 1) {
    $_SESSION['error'] = "Order not found.";
    header("location: orders.php");
    exit;
}
$order = $order_result->fetch_assoc();
$stmt_order->close();

// Fetch order items
$sql_items = "SELECT oi.price, oi.quantity, p.name, p.id as product_id
              FROM order_items oi
              JOIN products p ON oi.product_id = p.id
              WHERE oi.order_id = ?";
$stmt_items = $mysqli->prepare($sql_items);
$stmt_items->bind_param("i", $order_id);
$stmt_items->execute();
$items_result = $stmt_items->get_result();
$stmt_items->close();
?>

<h1>Order Details</h1>
<p><strong>Order ID:</strong> #<?php echo $order['id']; ?></p>
<p><strong>Order Date:</strong> <?php echo date("F j, Y", strtotime($order['created_at'])); ?></p>
<p><strong>Total Amount:</strong> $<?php echo number_format($order['total_amount'], 2); ?></p>

<div class="card">
    <div class="card-header">
        <h5>Purchased Items</h5>
    </div>
    <div class="card-body">
        <ul class="list-group list-group-flush">
            <?php while($item = $items_result->fetch_assoc()): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong><?php echo htmlspecialchars($item['name']); ?></strong>
                        <br>
                        <small>Quantity: <?php echo $item['quantity']; ?> | Price: $<?php echo number_format($item['price'], 2); ?></small>
                    </div>
                    <a href="../src/backend/download.php?order_id=<?php echo $order_id; ?>&product_id=<?php echo $item['product_id']; ?>" class="btn btn-success">
                        <i class="fas fa-download"></i> Download
                    </a>
                </li>
            <?php endwhile; ?>
        </ul>
    </div>
</div>

<a href="orders.php" class="btn btn-secondary mt-3">Back to My Orders</a>

<?php include '../templates/includes/footer.php'; ?>
