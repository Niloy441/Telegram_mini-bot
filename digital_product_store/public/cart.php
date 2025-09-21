<?php
require_once '../config.php';
require_once '../src/includes/db.php';

$page_title = "Your Shopping Cart - " . SITE_NAME;
$page = 'cart';
include '../templates/includes/header.php';

$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
$total = 0;
?>

<h1 class="text-center mb-4">Shopping Cart</h1>

<?php if (!empty($cart_items)): ?>
    <table class="table align-middle">
        <thead>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th class="text-center">Quantity</th>
                <th>Subtotal</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cart_items as $product_id => $item): ?>
                <?php
                    $subtotal = $item['price'] * $item['quantity'];
                    $total += $subtotal;
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                    <td>$<?php echo htmlspecialchars($item['price']); ?></td>
                    <td class="text-center">
                        <form action="../src/backend/cart_update.php" method="POST" class="d-inline-flex">
                            <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                            <input type="number" name="quantity" class="form-control" value="<?php echo $item['quantity']; ?>" min="1" style="width: 70px;">
                            <button type="submit" class="btn btn-sm btn-outline-primary ms-2">Update</button>
                        </form>
                    </td>
                    <td>$<?php echo number_format($subtotal, 2); ?></td>
                    <td>
                        <a href="../src/backend/cart_remove.php?id=<?php echo $product_id; ?>" class="btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="text-end"><strong>Total</strong></td>
                <td><strong>$<?php echo number_format($total, 2); ?></strong></td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div class="d-flex justify-content-end">
        <a href="checkout.php" class="btn btn-primary">Proceed to Checkout</a>
    </div>

<?php else: ?>
    <div class="alert alert-info text-center">
        Your cart is empty. <a href="products.php">Continue shopping</a>.
    </div>
<?php endif; ?>


<?php include '../templates/includes/footer.php'; ?>
