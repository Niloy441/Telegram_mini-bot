<?php
session_start();

// Validate product ID from GET request
if (isset($_GET['id'])) {
    $product_id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

    if ($product_id && isset($_SESSION['cart'][$product_id])) {
        // Remove the item from the cart
        unset($_SESSION['cart'][$product_id]);
        $_SESSION['success'] = "Item removed from cart.";
    } else {
        $_SESSION['error'] = "Invalid request.";
    }
}

header("location: ../../public/cart.php");
exit;
?>
