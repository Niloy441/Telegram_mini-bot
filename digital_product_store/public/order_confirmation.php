<?php
require_once '../config.php';
require_once '../src/includes/db.php';

$page_title = "Order Confirmation - " . SITE_NAME;
include '../templates/includes/header.php';

// User must be logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}

// Get order ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("location: index.php");
    exit;
}
$order_id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

// Fetch order details to confirm it belongs to the user
$user_id = $_SESSION['id'];
$sql = "SELECT * FROM orders WHERE id = ? AND user_id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows != 1) {
    // Order not found or doesn't belong to the user
    $_SESSION['error'] = "Order not found.";
    header("location: orders.php");
    exit;
}
$order = $result->fetch_assoc();
$stmt->close();
?>

<div class="text-center">
    <h1 class="text-success">Thank You!</h1>
    <h2>Your order has been placed successfully.</h2>
    <p>Your order number is <strong>#<?php echo $order['id']; ?></strong>.</p>
    <p>A confirmation email has been sent to <?php echo htmlspecialchars($_SESSION['email']); ?>.</p>
    <hr>
    <p>You can view your order details and download your products from your dashboard.</p>
    <a href="orders.php" class="btn btn-primary">View My Orders</a>
</div>


<?php include '../templates/includes/footer.php'; ?>
