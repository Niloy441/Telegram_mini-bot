<?php
require_once '../config.php';
require_once '../src/includes/db.php';

$page_title = "Checkout - " . SITE_NAME;
include '../templates/includes/header.php';

// User must be logged in to checkout
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    $_SESSION['error'] = "Please login to checkout.";
    header("location: login.php");
    exit;
}

// Redirect if cart is empty
if (empty($_SESSION['cart'])) {
    header("location: cart.php");
    exit;
}

$cart_items = $_SESSION['cart'];
$total = 0;
foreach ($cart_items as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>

<h1 class="text-center mb-4">Checkout</h1>

<div class="row">
    <!-- Order Summary -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Order Summary</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <?php foreach ($cart_items as $item): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?php echo htmlspecialchars($item['name']); ?> (x<?php echo $item['quantity']; ?>)
                            <span>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                        </li>
                    <?php endforeach; ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center fw-bold">
                        Total
                        <span>$<?php echo number_format($total, 2); ?></span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Payment Form -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Payment Details</h5>
            </div>
            <div class="card-body">
                <p>This is a dummy payment form. In a real application, this would be a Stripe or Razorpay integration.</p>
                <form action="../src/backend/place_order.php" method="POST">
                    <!-- Dummy Card Details -->
                    <div class="form-outline mb-4">
                        <input type="text" id="card_number" class="form-control" value="4242 4242 4242 4242" />
                        <label class="form-label" for="card_number">Card Number</label>
                    </div>
                    <div class="row mb-4">
                        <div class="col">
                            <div class="form-outline">
                                <input type="text" id="expiry" class="form-control" value="12/25" />
                                <label class="form-label" for="expiry">MM/YY</label>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-outline">
                                <input type="text" id="cvc" class="form-control" value="123" />
                                <label class="form-label" for="cvc">CVC</label>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Pay $<?php echo number_format($total, 2); ?></button>
                </form>
            </div>
        </div>
    </div>
</div>


<?php include '../templates/includes/footer.php'; ?>
