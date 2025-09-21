<?php
session_start();
require_once '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format.";
        header("location: ../../public/forgot_password.php");
        exit;
    }

    // Check if user exists
    $sql_check = "SELECT id FROM users WHERE email = ?";
    $stmt_check = $mysqli->prepare($sql_check);
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    $stmt_check->store_result();
    if ($stmt_check->num_rows == 0) {
        $_SESSION['error'] = "No user found with that email address.";
        header("location: ../../public/forgot_password.php");
        exit;
    }
    $stmt_check->close();

    // Generate a secure token
    $token = bin2hex(random_bytes(32));
    $token_hash = hash('sha256', $token);

    // Store the token in the database (or update if one already exists)
    $sql_insert = "INSERT INTO password_resets (email, token) VALUES (?, ?) ON DUPLICATE KEY UPDATE token = ?";
    $stmt_insert = $mysqli->prepare($sql_insert);
    $stmt_insert->bind_param("sss", $email, $token_hash, $token_hash);
    $stmt_insert->execute();
    $stmt_insert->close();

    // Simulate sending an email
    $reset_link = SITE_URL . "/public/reset_password.php?token=" . $token;
    $_SESSION['success'] = "A password reset link has been generated. In a real application, this would be sent to your email. For now, please use this link: <a href='$reset_link'>$reset_link</a>";

    header("location: ../../public/forgot_password.php");
    exit;

} else {
    header("location: ../../public/forgot_password.php");
    exit;
}
?>
