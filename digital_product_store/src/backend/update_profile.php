<?php
session_start();
require_once '../includes/db.php';

// User must be logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: ../../public/login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input
    $name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $user_id = $_SESSION['id'];

    if (empty($name) || empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Please provide a valid name and email.";
        header("location: ../../public/profile.php");
        exit;
    }

    // Check if email already exists for another user
    $sql_check = "SELECT id FROM users WHERE email = ? AND id != ?";
    $stmt_check = $mysqli->prepare($sql_check);
    $stmt_check->bind_param("si", $email, $user_id);
    $stmt_check->execute();
    $stmt_check->store_result();
    if ($stmt_check->num_rows > 0) {
        $_SESSION['error'] = "This email is already registered to another account.";
        header("location: ../../public/profile.php");
        exit;
    }
    $stmt_check->close();

    // Update user's profile
    $sql_update = "UPDATE users SET name = ?, email = ? WHERE id = ?";
    if ($stmt_update = $mysqli->prepare($sql_update)) {
        $stmt_update->bind_param("ssi", $name, $email, $user_id);
        if ($stmt_update->execute()) {
            // Update session variables
            $_SESSION['name'] = $name;
            $_SESSION['email'] = $email;
            $_SESSION['success'] = "Profile updated successfully.";
        } else {
            $_SESSION['error'] = "Something went wrong. Please try again later.";
        }
        $stmt_update->close();
    }
    $mysqli->close();
}

header("location: ../../public/profile.php");
exit;
?>
