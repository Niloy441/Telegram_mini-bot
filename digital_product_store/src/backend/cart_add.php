<?php
session_start();
require_once '../../config.php';
require_once '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate product ID and quantity
    $product_id = filter_var($_POST['product_id'], FILTER_VALIDATE_INT);
    $quantity = filter_var($_POST['quantity'], FILTER_VALIDATE_INT);

    if ($product_id && $quantity > 0) {
        // Fetch product details to ensure it exists and is active
        $sql = "SELECT id, name, price FROM products WHERE id = ? AND status = 'active'";
        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("i", $product_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                $product = $result->fetch_assoc();

                // Initialize cart if it doesn't exist
                if (!isset($_SESSION['cart'])) {
                    $_SESSION['cart'] = array();
                }

                // If product is already in cart, update quantity
                if (isset($_SESSION['cart'][$product_id])) {
                    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
                } else {
                    // Add new product to cart
                    $_SESSION['cart'][$product_id] = array(
                        "name" => $product['name'],
                        "price" => $product['price'],
                        "quantity" => $quantity
                    );
                }

                $_SESSION['success'] = "Product added to cart.";
                header("location: ../../public/cart.php");
                exit;

            } else {
                $_SESSION['error'] = "Product not found.";
                header("location: ../../public/products.php");
                exit;
            }
            $stmt->close();
        }
    } else {
        $_SESSION['error'] = "Invalid product data.";
        header("location: ../../public/products.php");
        exit;
    }
} else {
    header("location: ../../public/index.php");
    exit;
}
?>
