<?php
require_once '../config.php';
require_once '../src/includes/db.php';

// Check if product ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("location: products.php");
    exit;
}

$product_id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
if ($product_id === false) {
    header("location: products.php");
    exit;
}

// Fetch product data
$sql = "SELECT p.*, c.name as category_name
        FROM products p
        JOIN categories c ON p.category_id = c.id
        WHERE p.id = ? AND p.status = 'active'";
if ($stmt = $mysqli->prepare($sql)) {
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 1) {
        $product = $result->fetch_assoc();
    } else {
        // Product not found or inactive
        header("location: products.php");
        exit;
    }
    $stmt->close();
}

$page_title = htmlspecialchars($product['name']) . " - " . SITE_NAME;
include '../templates/includes/header.php';
?>

<div class="row">
    <div class="col-md-6">
        <img src="<?php echo htmlspecialchars($product['image'] ? $product['image'] : 'https://via.placeholder.com/500'); ?>" class="img-fluid" alt="<?php echo htmlspecialchars($product['name']); ?>">
    </div>
    <div class="col-md-6">
        <h2><?php echo htmlspecialchars($product['name']); ?></h2>
        <p class="lead">in <a href="products.php?category=<?php echo $product['category_id']; ?>"><?php echo htmlspecialchars($product['category_name']); ?></a></p>
        <h3 class="text-primary">$<?php echo htmlspecialchars($product['price']); ?></h3>
        <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>

        <form action="../src/backend/cart_add.php" method="POST">
            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
            <div class="d-flex">
                <input type="number" name="quantity" class="form-control" value="1" min="1" style="width: 70px;">
                <button type="submit" class="btn btn-primary ms-2">
                    <i class="fas fa-shopping-cart"></i> Add to Cart
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Related Products Section could go here -->

<?php include '../templates/includes/footer.php'; ?>
