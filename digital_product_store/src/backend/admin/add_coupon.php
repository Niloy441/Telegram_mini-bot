<?php
session_start();
require_once '../../includes/db.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header("location: " . SITE_URL . "/public/login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $code = strtoupper(trim($_POST['code']));
    $type = in_array($_POST['type'], ['percentage', 'flat']) ? $_POST['type'] : 'percentage';
    $value = filter_var($_POST['value'], FILTER_VALIDATE_FLOAT);
    $expiry_date = !empty($_POST['expiry_date']) ? $_POST['expiry_date'] : null;

    if (empty($code) || $value === false) {
        $_SESSION['error'] = "Please provide a valid code and value.";
        header("location: ../../templates/admin/coupons.php");
        exit;
    }

    // Insert new coupon into database
    $sql = "INSERT INTO coupons (code, type, value, expiry_date) VALUES (?, ?, ?, ?)";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("ssds", $code, $type, $value, $expiry_date);
        if ($stmt->execute()) {
            $_SESSION['success'] = "Coupon added successfully.";
        } else {
            $_SESSION['error'] = "Failed to add coupon. The code might already exist.";
        }
        $stmt->close();
    } else {
        $_SESSION['error'] = "Failed to prepare the coupon query.";
    }
    $mysqli->close();
}

header("location: ../../templates/admin/coupons.php");
exit;
?>
