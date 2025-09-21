<?php
session_start();
require_once '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Please fill in all fields.";
        header("location: ../../templates/user/login.php");
        exit;
    }

    $sql = "SELECT id, name, email, password, role FROM users WHERE email = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($id, $name, $db_email, $hashed_password, $role);
            if ($stmt->fetch()) {
                if (password_verify($password, $hashed_password)) {
                    // Password is correct, so start a new session
                    session_regenerate_id();
                    $_SESSION['loggedin'] = true;
                    $_SESSION['id'] = $id;
                    $_SESSION['name'] = $name;
                    $_SESSION['email'] = $db_email;
                    $_SESSION['role'] = $role;

                    // Redirect to user profile or admin dashboard
                    if ($role == 'admin') {
                        header("location: ../../templates/admin/dashboard.php");
                    } else {
                        header("location: ../../templates/user/profile.php");
                    }
                    exit;
                } else {
                    // Password is not valid
                    $_SESSION['error'] = "The password you entered was not valid.";
                    header("location: ../../templates/user/login.php");
                    exit;
                }
            }
        } else {
            // Email doesn't exist
            $_SESSION['error'] = "No account found with that email.";
            header("location: ../../templates/user/login.php");
            exit;
        }
        $stmt->close();
    }
    $mysqli->close();
} else {
    header("location: ../../templates/user/login.php");
    exit;
}
?>
