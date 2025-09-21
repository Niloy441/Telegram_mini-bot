</div> <!-- Closing container -->

<footer class="bg-light text-center text-lg-start mt-5">
    <div class="container p-4">
        <div class="row">
            <div class="col-lg-6 col-md-12 mb-4 mb-md-0">
                <h5 class="text-uppercase"><?php echo SITE_NAME; ?></h5>
                <p>
                    Your one-stop shop for high-quality digital products.
                </p>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                <h5 class="text-uppercase">Links</h5>
                <ul class="list-unstyled mb-0">
                    <li><a href="products.php" class="text-dark">Products</a></li>
                    <li><a href="faq.php" class="text-dark">FAQ</a></li>
                    <li><a href="contact.php" class="text-dark">Contact Us</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
        © <?php echo date("Y"); ?> Copyright:
        <a class="text-dark" href="<?php echo SITE_URL; ?>"><?php echo SITE_NAME; ?></a>
    </div>
</footer>

<!-- MDBootstrap JS -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.1/mdb.min.js"></script>

<?php include 'bottom_nav.php'; ?>

</body>
</html>
