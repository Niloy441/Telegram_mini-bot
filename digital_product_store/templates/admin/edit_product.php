<?php
require_once '../../config.php';
require_once '../../src/includes/db.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header("location: " . SITE_URL . "/public/login.php");
    exit;
}

// Check if product ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("location: " . SITE_URL . "/templates/admin/products.php");
    exit;
}

$product_id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
if ($product_id === false) {
    header("location: " . SITE_URL . "/templates/admin/products.php");
    exit;
}

// Fetch product data
$sql = "SELECT * FROM products WHERE id = ?";
if ($stmt = $mysqli->prepare($sql)) {
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 1) {
        $product = $result->fetch_assoc();
    } else {
        // Product not found
        header("location: " . SITE_URL . "/templates/admin/products.php");
        exit;
    }
    $stmt->close();
}

// Fetch categories for the dropdown
$categories_result = $mysqli->query("SELECT id, name FROM categories ORDER BY name ASC");

$page = 'products';
$page_title = "Edit Product - " . SITE_NAME;
include '../includes/header.php';
?>

<div class="row">
    <div class="col-md-3">
        <?php include 'includes/sidebar.php'; ?>
    </div>
    <div class="col-md-9">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Edit Product</h3>
            </div>
            <div class="card-body">
                <form action="<?php echo SITE_URL; ?>/src/backend/admin/edit_product.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo $product['id']; ?>">

                    <!-- Product Name -->
                    <div class="form-outline mb-4">
                        <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($product['name']); ?>" required />
                        <label class="form-label" for="name">Product Name</label>
                    </div>

                    <!-- Description -->
                    <div class="form-outline mb-4">
                        <textarea class="form-control" id="description" name="description" rows="4" required><?php echo htmlspecialchars($product['description']); ?></textarea>
                        <label class="form-label" for="description">Description</label>
                    </div>

                    <!-- Price -->
                    <div class="form-outline mb-4">
                        <input type="number" id="price" name="price" class="form-control" step="0.01" value="<?php echo htmlspecialchars($product['price']); ?>" required />
                        <label class="form-label" for="price">Price</label>
                    </div>

                    <!-- Category -->
                    <select class="form-select mb-4" name="category_id" required>
                        <?php if ($categories_result->num_rows > 0): ?>
                            <?php while($category = $categories_result->fetch_assoc()): ?>
                                <option value="<?php echo $category['id']; ?>" <?php echo ($product['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </option>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </select>

                    <!-- Product Image -->
                    <div class="mb-4">
                        <label for="image" class="form-label">Current Image: <?php echo $product['image'] ? basename($product['image']) : 'None'; ?></label>
                        <input class="form-control" type="file" id="image" name="image" accept="image/*" />
                        <small class="form-text text-muted">Leave blank to keep the current image.</small>
                    </div>

                    <!-- Digital File -->
                    <div class="mb-4">
                        <label for="digital_file" class="form-label">Current File: <?php echo basename($product['file_path']); ?></label>
                        <input class="form-control" type="file" id="digital_file" name="digital_file" />
                        <small class="form-text text-muted">Leave blank to keep the current file.</small>
                    </div>

                    <!-- Status -->
                    <select class="form-select mb-4" name="status" required>
                        <option value="active" <?php echo ($product['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
                        <option value="inactive" <?php echo ($product['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                    </select>

                    <button type="submit" class="btn btn-primary">Update Product</button>
                    <a href="<?php echo SITE_URL; ?>/templates/admin/products.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
