<?php
session_start();
require_once '../../includes/db.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header("location: " . SITE_URL . "/public/login.php");
    exit;
}

// Get user ID and status from URL
$user_id = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_VALIDATE_INT) : 0;
$status = isset($_GET['status']) ? $_GET['status'] : '';

// Validate data
if ($user_id > 0 && in_array($status, ['active', 'blocked'])) {
    // Prevent admin from blocking themselves
    if ($user_id == $_SESSION['id']) {
        $_SESSION['error'] = "You cannot block yourself.";
    } else {
        // Update user status
        $sql = "UPDATE users SET status = ? WHERE id = ?";
        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("si", $status, $user_id);
            if ($stmt->execute()) {
                $_SESSION['success'] = "User status updated successfully.";
            } else {
                $_SESSION['error'] = "Failed to update user status.";
            }
            $stmt->close();
        }
    }
} else {
    $_SESSION['error'] = "Invalid request.";
}

$mysqli->close();
header("location: ../../templates/admin/users.php");
exit;
?>
