<?php
session_start();
require_once '../../includes/db.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header("location: ../../templates/user/login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate product ID
    $product_id = filter_var($_POST['id'], FILTER_VALIDATE_INT);
    if ($product_id === false) {
        $_SESSION['error'] = "Invalid product ID.";
        header("location: ../../templates/admin/products.php");
        exit;
    }

    // Sanitize and validate text inputs
    $name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
    $description = filter_var(trim($_POST['description']), FILTER_SANITIZE_STRING);
    $price = filter_var($_POST['price'], FILTER_VALIDATE_FLOAT);
    $category_id = filter_var($_POST['category_id'], FILTER_VALIDATE_INT);
    $status = in_array($_POST['status'], ['active', 'inactive']) ? $_POST['status'] : 'active';

    if (empty($name) || empty($description) || $price === false || $category_id === false) {
        $_SESSION['error'] = "Please fill in all required fields with valid data.";
        header("location: ../../templates/admin/edit_product.php?id=" . $product_id);
        exit;
    }

    // Fetch existing product data to get old file paths
    $sql_select = "SELECT image, file_path FROM products WHERE id = ?";
    $stmt_select = $mysqli->prepare($sql_select);
    $stmt_select->bind_param("i", $product_id);
    $stmt_select->execute();
    $result = $stmt_select->get_result();
    $product = $result->fetch_assoc();
    $stmt_select->close();

    $image_path = $product['image'];
    $file_path = $product['file_path'];

    // --- File Upload Handling ---
    $upload_dir_images = "../../../public/uploads/images/";
    $upload_dir_files = "../../../private/uploads/files/";

    // Handle new Product Image
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        // ... (code to delete old image if it exists) ...
        $image_name = uniqid() . '-' . basename($_FILES["image"]["name"]);
        $target_file_image = $upload_dir_images . $image_name;
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file_image)) {
            $image_path = "uploads/images/" . $image_name;
        }
    }

    // Handle new Digital File
    if (isset($_FILES['digital_file']) && $_FILES['digital_file']['error'] == 0) {
        // ... (code to delete old file if it exists) ...
        $file_name = uniqid() . '-' . basename($_FILES["digital_file"]["name"]);
        $target_file_digital = $upload_dir_files . $file_name;
        if (move_uploaded_file($_FILES["digital_file"]["tmp_name"], $target_file_digital)) {
            $file_path = $target_file_digital;
        }
    }

    // Update product in database
    $sql = "UPDATE products SET name = ?, description = ?, price = ?, category_id = ?, image = ?, file_path = ?, status = ? WHERE id = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("ssdisssi", $name, $description, $price, $category_id, $image_path, $file_path, $status, $product_id);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Product updated successfully.";
            header("location: ../../templates/admin/products.php");
            exit;
        } else {
            $_SESSION['error'] = "Error: Could not execute the update query.";
            header("location: ../../templates/admin/edit_product.php?id=" . $product_id);
            exit;
        }
        $stmt->close();
    } else {
        $_SESSION['error'] = "Error: Could not prepare the update query.";
        header("location: ../../templates/admin/edit_product.php?id=" . $product_id);
        exit;
    }
    $mysqli->close();
} else {
    header("location: ../../templates/admin/products.php");
    exit;
}
?>
