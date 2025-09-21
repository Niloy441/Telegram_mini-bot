<?php
session_start();
require_once '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $token = $_POST['token'];
    $new_password = $_POST['new_password'];
    $confirm_new_password = $_POST['confirm_new_password'];

    // Validate input
    if (empty($token) || empty($new_password) || empty($confirm_new_password)) {
        $_SESSION['error'] = "Please fill in all fields.";
        header("location: ../../public/reset_password.php?token=" . urlencode($token));
        exit;
    }

    if ($new_password !== $confirm_new_password) {
        $_SESSION['error'] = "Passwords do not match.";
        header("location: ../../public/reset_password.php?token=" . urlencode($token));
        exit;
    }

    // Validate token
    $token_hash = hash('sha256', $token);

    $sql_select = "SELECT * FROM password_resets WHERE token = ?";
    $stmt_select = $mysqli->prepare($sql_select);
    $stmt_select->bind_param("s", $token_hash);
    $stmt_select->execute();
    $result = $stmt_select->get_result();

    if ($result->num_rows == 1) {
        $reset_request = $result->fetch_assoc();
        $email = $reset_request['email'];

        // Optional: Check if token is expired (e.g., within 1 hour)
        $token_timestamp = strtotime($reset_request['created_at']);
        if (time() - $token_timestamp > 3600) {
            $_SESSION['error'] = "This password reset token has expired.";
            header("location: ../../public/forgot_password.php");
            exit;
        }

        // Hash new password and update user
        $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $sql_update = "UPDATE users SET password = ? WHERE email = ?";
        $stmt_update = $mysqli->prepare($sql_update);
        $stmt_update->bind_param("ss", $new_hashed_password, $email);
        $stmt_update->execute();
        $stmt_update->close();

        // Delete the reset token
        $sql_delete = "DELETE FROM password_resets WHERE email = ?";
        $stmt_delete = $mysqli->prepare($sql_delete);
        $stmt_delete->bind_param("s", $email);
        $stmt_delete->execute();
        $stmt_delete->close();

        $_SESSION['success'] = "Your password has been reset successfully. Please login.";
        header("location: ../../public/login.php");
        exit;

    } else {
        $_SESSION['error'] = "Invalid or expired password reset token.";
        header("location: ../../public/forgot_password.php");
        exit;
    }
    $stmt_select->close();
} else {
    header("location: ../../public/index.php");
    exit;
}
?>
