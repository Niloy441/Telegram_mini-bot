<?php
session_start();
require_once '../includes/db.php';

// User must be logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    die("Access denied.");
}

// Get IDs from URL and validate
$order_id = filter_input(INPUT_GET, 'order_id', FILTER_VALIDATE_INT);
$product_id = filter_input(INPUT_GET, 'product_id', FILTER_VALIDATE_INT);
$user_id = $_SESSION['id'];

if (!$order_id || !$product_id) {
    die("Invalid request.");
}

// Verify purchase
$sql = "SELECT p.file_path
        FROM order_items oi
        JOIN orders o ON oi.order_id = o.id
        JOIN products p ON oi.product_id = p.id
        WHERE oi.order_id = ? AND oi.product_id = ? AND o.user_id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("iii", $order_id, $product_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $product = $result->fetch_assoc();
    $file_path = $product['file_path'];

    if (file_exists($file_path)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file_path));
        flush(); // Flush system output buffer
        readfile($file_path);
        exit;
    } else {
        $_SESSION['error'] = "File not found.";
        header("location: ../../public/orders.php");
        exit;
    }
} else {
    $_SESSION['error'] = "You do not have permission to download this file.";
    header("location: ../../public/orders.php");
    exit;
}
$stmt->close();
?>
