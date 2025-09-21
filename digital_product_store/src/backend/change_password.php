<?php
session_start();
require_once '../includes/db.php';

// User must be logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: ../../public/login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_new_password = $_POST['confirm_new_password'];
    $user_id = $_SESSION['id'];

    // Validate input
    if (empty($current_password) || empty($new_password) || empty($confirm_new_password)) {
        $_SESSION['error'] = "Please fill in all password fields.";
        header("location: ../../public/profile.php");
        exit;
    }

    if ($new_password !== $confirm_new_password) {
        $_SESSION['error'] = "New passwords do not match.";
        header("location: ../../public/profile.php");
        exit;
    }

    // Get current hashed password from DB
    $sql_select = "SELECT password FROM users WHERE id = ?";
    $stmt_select = $mysqli->prepare($sql_select);
    $stmt_select->bind_param("i", $user_id);
    $stmt_select->execute();
    $result = $stmt_select->get_result();
    $user = $result->fetch_assoc();
    $hashed_password = $user['password'];
    $stmt_select->close();

    // Verify current password
    if (password_verify($current_password, $hashed_password)) {
        // Hash new password
        $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update password in DB
        $sql_update = "UPDATE users SET password = ? WHERE id = ?";
        if ($stmt_update = $mysqli->prepare($sql_update)) {
            $stmt_update->bind_param("si", $new_hashed_password, $user_id);
            if ($stmt_update->execute()) {
                $_SESSION['success'] = "Password changed successfully.";
            } else {
                $_SESSION['error'] = "Something went wrong. Please try again later.";
            }
            $stmt_update->close();
        }
    } else {
        $_SESSION['error'] = "Incorrect current password.";
    }
    $mysqli->close();
}

header("location: ../../public/profile.php");
exit;
?>
