<?php
require_once '../config.php';
require_once '../src/includes/db.php';

// --- Filtering and Searching Logic ---
$where_clauses = ["status = 'active'"];
$params = [];
$types = '';

// Search by name
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
if (!empty($search)) {
    $where_clauses[] = "name LIKE ?";
    $params[] = "%" . $search . "%";
    $types .= 's';
}

// Filter by category
$category_id = isset($_GET['category']) ? (int)$_GET['category'] : 0;
if ($category_id > 0) {
    $where_clauses[] = "category_id = ?";
    $params[] = $category_id;
    $types .= 'i';
}

$where_sql = count($where_clauses) > 0 ? "WHERE " . implode(' AND ', $where_clauses) : '';

// --- Pagination Logic ---
$limit = 6; // Products per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Get total number of products for pagination
$total_products_sql = "SELECT COUNT(*) FROM products " . $where_sql;
$stmt_total = $mysqli->prepare($total_products_sql);
if (!empty($params)) {
    $stmt_total->bind_param($types, ...$params);
}
$stmt_total->execute();
$total_products = $stmt_total->get_result()->fetch_row()[0];
$total_pages = ceil($total_products / $limit);
$stmt_total->close();


// Fetch products for the current page
$products_sql = "SELECT id, name, price, image FROM products " . $where_sql . " ORDER BY created_at DESC LIMIT ? OFFSET ?";
$stmt_products = $mysqli->prepare($products_sql);
$product_params = $params;
$product_params[] = $limit;
$product_params[] = $offset;
$product_types = $types . 'ii';
$stmt_products->bind_param($product_types, ...$product_params);
$stmt_products->execute();
$products_result = $stmt_products->get_result();
$stmt_products->close();


// Fetch all categories for the filter list
$categories_result = $mysqli->query("SELECT id, name FROM categories ORDER BY name ASC");

$page_title = "Our Products - " . SITE_NAME;
$page = 'products';
include '../templates/includes/header.php';
?>

<div class="row">
    <!-- Filters and Search -->
    <div class="col-md-3">
        <h4>Search</h4>
        <form action="products.php" method="GET">
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Product name..." name="search" value="<?php echo htmlspecialchars($search); ?>">
                <button class="btn btn-outline-primary" type="submit">Go</button>
            </div>
        </form>

        <h4>Categories</h4>
        <div class="list-group">
            <a href="products.php" class="list-group-item list-group-item-action <?php echo ($category_id == 0) ? 'active' : ''; ?>">All Categories</a>
            <?php if ($categories_result && $categories_result->num_rows > 0): ?>
                <?php while($category = $categories_result->fetch_assoc()): ?>
                    <a href="products.php?category=<?php echo $category['id']; ?>" class="list-group-item list-group-item-action <?php echo ($category_id == $category['id']) ? 'active' : ''; ?>">
                        <?php echo htmlspecialchars($category['name']); ?>
                    </a>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Product Grid -->
    <div class="col-md-9">
        <h1 class="mb-4">All Products</h1>
        <div class="row">
            <?php if ($products_result && $products_result->num_rows > 0): ?>
                <?php while($product = $products_result->fetch_assoc()): ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100">
                            <img src="<?php echo SITE_URL . '/public/' . htmlspecialchars($product['image'] ? $product['image'] : 'https://via.placeholder.com/300'); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['name']); ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                                <p class="card-text">$<?php echo htmlspecialchars($product['price']); ?></p>
                                <a href="product_detail.php?id=<?php echo $product['id']; ?>" class="btn btn-primary">View Details</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-center">No products found matching your criteria.</p>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <?php
                // Build query string for pagination links
                $query_params = [];
                if (!empty($search)) $query_params['search'] = $search;
                if ($category_id > 0) $query_params['category'] = $category_id;
                $query_string = http_build_query($query_params);
                ?>
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                        <a class="page-link" href="products.php?page=<?php echo $i; ?>&<?php echo $query_string; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>
</div>

<?php include '../templates/includes/footer.php'; ?>
