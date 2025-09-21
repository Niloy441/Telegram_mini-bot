<?php
session_start();
require_once '../../includes/db.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header("location: ../../templates/user/login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate text inputs
    $name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
    $description = filter_var(trim($_POST['description']), FILTER_SANITIZE_STRING);
    $price = filter_var($_POST['price'], FILTER_VALIDATE_FLOAT);
    $category_id = filter_var($_POST['category_id'], FILTER_VALIDATE_INT);
    $status = in_array($_POST['status'], ['active', 'inactive']) ? $_POST['status'] : 'active';

    // Basic validation
    if (empty($name) || empty($description) || $price === false || $category_id === false) {
        $_SESSION['error'] = "Please fill in all required fields with valid data.";
        header("location: ../../templates/admin/add_product.php");
        exit;
    }

    // --- File Upload Handling ---
    $image_path = null;
    $file_path = null;
    $upload_dir_images = "../../../public/uploads/images/";
    $upload_dir_files = "../../../private/uploads/files/";

    // Handle Product Image
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_name = uniqid() . '-' . basename($_FILES["image"]["name"]);
        $target_file_image = $upload_dir_images . $image_name;
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file_image)) {
            $image_path = "uploads/images/" . $image_name;
        } else {
            $_SESSION['error'] = "Sorry, there was an error uploading your image.";
            header("location: ../../templates/admin/add_product.php");
            exit;
        }
    }

    // Handle Digital File (Required)
    if (isset($_FILES['digital_file']) && $_FILES['digital_file']['error'] == 0) {
        $file_name = uniqid() . '-' . basename($_FILES["digital_file"]["name"]);
        $target_file_digital = $upload_dir_files . $file_name;
        if (move_uploaded_file($_FILES["digital_file"]["tmp_name"], $target_file_digital)) {
            $file_path = $target_file_digital; // Store the full path for the private file
        } else {
            $_SESSION['error'] = "Sorry, there was an error uploading your digital file.";
            header("location: ../../templates/admin/add_product.php");
            exit;
        }
    } else {
        $_SESSION['error'] = "A digital file is required.";
        header("location: ../../templates/admin/add_product.php");
        exit;
    }

    // Insert product into database
    $sql = "INSERT INTO products (name, description, price, category_id, image, file_path, status) VALUES (?, ?, ?, ?, ?, ?, ?)";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("ssdisss", $name, $description, $price, $category_id, $image_path, $file_path, $status);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Product added successfully.";
            header("location: ../../templates/admin/products.php");
            exit;
        } else {
            $_SESSION['error'] = "Error: Could not execute the query.";
            header("location: ../../templates/admin/add_product.php");
            exit;
        }
        $stmt->close();
    } else {
        $_SESSION['error'] = "Error: Could not prepare the query.";
        header("location: ../../templates/admin/add_product.php");
        exit;
    }
    $mysqli->close();
} else {
    header("location: ../../templates/admin/add_product.php");
    exit;
}
?>
