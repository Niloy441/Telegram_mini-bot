<?php
session_start();
require_once '../../includes/db.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    $_SESSION['error'] = "You do not have permission to access this page.";
    header("location: ../../templates/user/login.php");
    exit;
}

// Check if product ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "Invalid request.";
    header("location: ../../templates/admin/products.php");
    exit;
}

$product_id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
if ($product_id === false) {
    $_SESSION['error'] = "Invalid product ID.";
    header("location: ../../templates/admin/products.php");
    exit;
}

// First, get the file paths from the database to delete the files from the server
$sql_select = "SELECT image, file_path FROM products WHERE id = ?";
if ($stmt_select = $mysqli->prepare($sql_select)) {
    $stmt_select->bind_param("i", $product_id);
    $stmt_select->execute();
    $result = $stmt_select->get_result();
    if ($product = $result->fetch_assoc()) {
        // Delete files from server
        if ($product['image'] && file_exists("../../../public/" . $product['image'])) {
            unlink("../../../public/" . $product['image']);
        }
        if (file_exists($product['file_path'])) {
            unlink($product['file_path']);
        }
    }
    $stmt_select->close();
}

// Now, delete the product from the database
$sql_delete = "DELETE FROM products WHERE id = ?";
if ($stmt_delete = $mysqli->prepare($sql_delete)) {
    $stmt_delete->bind_param("i", $product_id);

    if ($stmt_delete->execute()) {
        $_SESSION['success'] = "Product deleted successfully.";
    } else {
        $_SESSION['error'] = "Error: Could not delete the product.";
    }
    $stmt_delete->close();
} else {
    $_SESSION['error'] = "Error: Could not prepare the delete query.";
}

$mysqli->close();
header("location: ../../templates/admin/products.php");
exit;
?>
