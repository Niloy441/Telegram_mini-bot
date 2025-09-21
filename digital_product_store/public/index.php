<?php
require_once '../config.php';
require_once '../src/includes/db.php';

// Fetch featured products (e.g., the 3 most recent)
$featured_sql = "SELECT id, name, price, image FROM products WHERE status = 'active' ORDER BY created_at DESC LIMIT 3";
$featured_result = $mysqli->query($featured_sql);

$page_title = "Welcome to " . SITE_NAME;
$page = 'home';
include '../templates/includes/header.php';
?>

<!-- Hero Section -->
<div class="p-5 text-center bg-light">
    <h1 class="mb-3">High-Quality Digital Products</h1>
    <h4 class="mb-3">Instantly downloadable, ready to use.</h4>
    <a class="btn btn-primary" href="products.php" role="button">Explore Products</a>
</div>

<!-- Featured Products Section -->
<div class="mt-5">
    <h2 class="text-center mb-4">Featured Products</h2>
    <div class="row">
        <?php if ($featured_result && $featured_result->num_rows > 0): ?>
            <?php while($product = $featured_result->fetch_assoc()): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100">
                        <img src="<?php echo htmlspecialchars($product['image'] ? $product['image'] : 'https://via.placeholder.com/300'); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                            <p class="card-text">$<?php echo htmlspecialchars($product['price']); ?></p>
                            <a href="product_detail.php?id=<?php echo $product['id']; ?>" class="btn btn-primary">View Details</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-center">No featured products available at the moment.</p>
        <?php endif; ?>
    </div>
</div>

<?php include '../templates/includes/footer.php'; ?>
