<style>
.bottom-nav {
    display: none; /* Hidden by default */
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100%;
    background-color: #f8f9fa;
    border-top: 1px solid #dee2e6;
    z-index: 1030;
}
.bottom-nav a {
    flex-grow: 1;
    text-align: center;
    padding: 10px 0;
    color: #6c757d;
    font-size: 12px;
}
.bottom-nav a i {
    display: block;
    font-size: 20px;
    margin-bottom: 4px;
}
.bottom-nav a.active {
    color: #007bff;
}

/* Show only on small screens */
@media (max-width: 767.98px) {
    .bottom-nav {
        display: flex;
    }
    body {
        padding-bottom: 60px; /* Add padding to body to prevent content from being hidden by the nav bar */
    }
    .navbar {
        display: none; /* Hide the top navbar on mobile */
    }
}
</style>

<div class="bottom-nav">
    <a href="<?php echo SITE_URL; ?>/public/index.php" class="<?php echo ($page == 'home') ? 'active' : ''; ?>">
        <i class="fas fa-home"></i> Home
    </a>
    <a href="<?php echo SITE_URL; ?>/public/products.php" class="<?php echo ($page == 'products') ? 'active' : ''; ?>">
        <i class="fas fa-store"></i> Products
    </a>
    <a href="<?php echo SITE_URL; ?>/public/cart.php" class="<?php echo ($page == 'cart') ? 'active' : ''; ?>">
        <i class="fas fa-shopping-cart"></i> Cart
    </a>
    <a href="<?php echo SITE_URL; ?>/public/profile.php" class="<?php echo ($page == 'profile') ? 'active' : ''; ?>">
        <i class="fas fa-user"></i> Profile
    </a>
</div>
