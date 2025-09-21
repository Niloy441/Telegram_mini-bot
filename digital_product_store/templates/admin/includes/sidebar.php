<div class="list-group">
    <a href="<?php echo SITE_URL; ?>/templates/admin/dashboard.php" class="list-group-item list-group-item-action <?php echo ($page == 'dashboard') ? 'active' : ''; ?>">Dashboard</a>
    <a href="<?php echo SITE_URL; ?>/templates/admin/products.php" class="list-group-item list-group-item-action <?php echo ($page == 'products') ? 'active' : ''; ?>">Products</a>
    <a href="<?php echo SITE_URL; ?>/templates/admin/orders.php" class="list-group-item list-group-item-action <?php echo ($page == 'orders') ? 'active' : ''; ?>">Orders</a>
    <a href="#" class="list-group-item list-group-item-action <?php echo ($page == 'users') ? 'active' : ''; ?>">Users</a>
    <a href="<?php echo SITE_URL; ?>/src/backend/logout.php" class="list-group-item list-group-item-action">Logout</a>
</div>
