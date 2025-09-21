<?php
session_start();
require_once '../includes/db.php';

// User must be logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: ../../public/login.php");
    exit;
}

// Cart must not be empty
if (empty($_SESSION['cart'])) {
    header("location: ../../public/cart.php");
    exit;
}

// Database transaction starts
$mysqli->begin_transaction();

try {
    // Calculate total amount
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    // 1. Create order in `orders` table
    $user_id = $_SESSION['id'];
    $transaction_id = "DUMMY_" . uniqid(); // Dummy transaction ID
    $payment_status = "completed";

    $sql_order = "INSERT INTO orders (user_id, total_amount, transaction_id, payment_status) VALUES (?, ?, ?, ?)";
    $stmt_order = $mysqli->prepare($sql_order);
    $stmt_order->bind_param("idss", $user_id, $total, $transaction_id, $payment_status);
    $stmt_order->execute();

    $order_id = $stmt_order->insert_id;
    $stmt_order->close();

    // 2. Insert items into `order_items` table
    $sql_items = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
    $stmt_items = $mysqli->prepare($sql_items);

    foreach ($_SESSION['cart'] as $product_id => $item) {
        $stmt_items->bind_param("iiid", $order_id, $product_id, $item['quantity'], $item['price']);
        $stmt_items->execute();
    }
    $stmt_items->close();

    // If all queries were successful, commit the transaction
    $mysqli->commit();

    // 3. Clear the cart
    unset($_SESSION['cart']);

    // 4. Redirect to order confirmation
    $_SESSION['success'] = "Your order has been placed successfully!";
    header("location: ../../public/order_confirmation.php?id=" . $order_id);
    exit;

} catch (mysqli_sql_exception $exception) {
    $mysqli->rollback();
    $_SESSION['error'] = "There was an error placing your order. Please try again.";
    header("location: ../../public/checkout.php");
    exit;
}
?>
