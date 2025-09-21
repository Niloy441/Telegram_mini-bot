<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate product ID and quantity
    $product_id = filter_var($_POST['product_id'], FILTER_VALIDATE_INT);
    $quantity = filter_var($_POST['quantity'], FILTER_VALIDATE_INT);

    if ($product_id && isset($_SESSION['cart'][$product_id])) {
        if ($quantity > 0) {
            $_SESSION['cart'][$product_id]['quantity'] = $quantity;
            $_SESSION['success'] = "Cart updated successfully.";
        } else {
            // If quantity is 0 or less, remove the item
            unset($_SESSION['cart'][$product_id]);
            $_SESSION['success'] = "Item removed from cart.";
        }
    } else {
        $_SESSION['error'] = "Invalid request.";
    }
}

header("location: ../../public/cart.php");
exit;
?>
