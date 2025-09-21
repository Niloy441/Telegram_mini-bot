<?php
require_once '../../config.php';
require_once '../../src/includes/db.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header("location: " . SITE_URL . "/public/login.php");
    exit;
}

// Fetch categories for the dropdown
$categories_result = $mysqli->query("SELECT id, name FROM categories ORDER BY name ASC");

$page = 'products';
$page_title = "Add New Product - " . SITE_NAME;
include '../includes/header.php';
?>

<div class="row">
    <div class="col-md-3">
        <?php include 'includes/sidebar.php'; ?>
    </div>
    <div class="col-md-9">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Add New Product</h3>
            </div>
            <div class="card-body">
                <form action="<?php echo SITE_URL; ?>/src/backend/admin/add_product.php" method="POST" enctype="multipart/form-data">
                    <!-- Product Name -->
                    <div class="form-outline mb-4">
                        <input type="text" id="name" name="name" class="form-control" required />
                        <label class="form-label" for="name">Product Name</label>
                    </div>

                    <!-- Description -->
                    <div class="form-outline mb-4">
                        <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                        <label class="form-label" for="description">Description</label>
                    </div>

                    <!-- Price -->
                    <div class="form-outline mb-4">
                        <input type="number" id="price" name="price" class="form-control" step="0.01" required />
                        <label class="form-label" for="price">Price</label>
                    </div>

                    <!-- Category -->
                    <select class="form-select mb-4" name="category_id" required>
                        <option value="" disabled selected>Select Category</option>
                        <?php if ($categories_result->num_rows > 0): ?>
                            <?php while($category = $categories_result->fetch_assoc()): ?>
                                <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </select>

                    <!-- Product Image -->
                    <div class="mb-4">
                        <label for="image" class="form-label">Product Image</label>
                        <input class="form-control" type="file" id="image" name="image" accept="image/*" />
                    </div>

                    <!-- Digital File -->
                    <div class="mb-4">
                        <label for="digital_file" class="form-label">Digital File (e.g., zip, pdf)</label>
                        <input class="form-control" type="file" id="digital_file" name="digital_file" required />
                    </div>

                    <!-- Status -->
                    <select class="form-select mb-4" name="status" required>
                        <option value="active" selected>Active</option>
                        <option value="inactive">Inactive</option>
                    </select>

                    <button type="submit" class="btn btn-primary">Add Product</button>
                    <a href="<?php echo SITE_URL; ?>/templates/admin/products.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
