<!--   ////////.........start Footer tob bar................//////-->

<?php
require_once '../admin/pages/config.php';
require_once '../admin/classes/Database.php';


// Initialize Database
$dbInstance = Database::getInstance();
$conn = $dbInstance->getInstance()->getConnection();

// $db = new Database();
// $db->getInstance()->getConnection();


include '../include/Header.php';
?>
<!--   ////////......... end Footer tob bar................//////-->
<!--   -->
<!-- Start Hero Section -->
<div class="hero">
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-lg-5">
                <div class="intro-excerpt">
                    <h1>Modern Interior <span clsas="d-block">Design Studio</span></h1>
                    <p class="mb-4">Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam
                        vulputate velit imperdiet dolor tempor tristique.</p>
                    <p><a href="" class="btn btn-secondary me-2">products Now</a><a href="#"
                            class="btn btn-white-outline">Explore</a></p>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="hero-img-wrap">
                    <img src="../Public/images/couch.png" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Hero Section -->



<!-- Start Product Section -->
<div class="product-section">
    <div class="container">
        <div class="row">

            <!-- Start Column 1 -->
            <div class="col-md-12 col-lg-3 mb-5 mb-lg-0">
                <h2 class="mb-4 section-title">Crafted with excellent material.</h2>
                <p class="mb-4">Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate
                    velit imperdiet dolor tempor tristique. </p>
                <p><a href="products.php" class="btn">Explore</a></p>
            </div>
            <!-- End Column 1 -->

            <!-- Start Column 2 -->
            <?php
            try {
                // Fetch distinct product items from the database
                $sql = "SELECT * FROM products ORDER BY sales DESC LIMIT 3";
                $stmt = $db->query($sql);
                $products = $stmt->fetchAll();

                foreach ($products as $product) {
                    $productImage = $product['image'];
                    $productName = $product['name'];
                    $productPrice = $product['price'];
            ?>
                    <div class="col-12 col-md-4 col-lg-3 mb-5 mb-md-0">
                        <a class="product-item" href="cart.php">
                            <img src="../Public/images/<?php echo $productImage; ?>" class="img-fluid product-thumbnail">
                            <h3 class="product-title"><?php echo $productName; ?></h3>
                            <strong class="product-price"><?php echo $productPrice; ?></strong>

                            <span class="icon-cross">
                                <img src="../Public/images/cross.svg" class="img-fluid">
                            </span>
                        </a>
                    </div>
            <?php
                }
            } catch (PDOException $e) {
                // Handle database errors
                echo "Error: " . $e->getMessage();
            }
            ?>
            <!-- End Column 2 -->



        </div>
    </div>
</div>
<!-- End Product Section -->

<!-- Start Why Choose Us Section -->
<div class="why-choose-section">
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-lg-6">
                <h2 class="section-title">Why Choose Us</h2>
                <p>Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate velit
                    imperdiet dolor tempor tristique.</p>

                <div class="row my-5">
                    <div class="col-6 col-md-6">
                        <div class="feature">
                            <div class="icon">
                                <img src="../Public/images/truck.svg" alt="Image" class="imf-fluid">
                            </div>
                            <h3>Fast &amp; Free Shipping</h3>
                            <p>Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate.
                            </p>
                        </div>
                    </div>

                    <div class="col-6 col-md-6">
                        <div class="feature">
                            <div class="icon">
                                <img src="../Public/images/bag.svg" alt="Image" class="imf-fluid">
                            </div>
                            <h3>Easy to products</h3>
                            <p>Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate.
                            </p>
                        </div>
                    </div>

                    <div class="col-6 col-md-6">
                        <div class="feature">
                            <div class="icon">
                                <img src="../Public/images/support.svg" alt="Image" class="imf-fluid">
                            </div>
                            <h3>24/7 Support</h3>
                            <p>Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate.
                            </p>
                        </div>
                    </div>

                    <div class="col-6 col-md-6">
                        <div class="feature">
                            <div class="icon">
                                <img src="../Public/images/return.svg" alt="Image" class="imf-fluid">
                            </div>
                            <h3>Hassle Free Returns</h3>
                            <p>Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate.
                            </p>
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-lg-5">
                <div class="img-wrap">
                    <img src="../Public/images/why-choose-us-img.jpg" alt="Image" class="img-fluid">
                </div>
            </div>

        </div>
    </div>
</div>
<!-- End Why Choose Us Section -->

<!-- Start We Help Section -->
<div class="we-help-section">
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-lg-7 mb-5 mb-lg-0">
                <div class="imgs-grid">
                    <div class="grid grid-1"><img src="../public/images/img-grid-1.jpg" alt="Untree.co"></div>
                    <div class="grid grid-2"><img src="../public/images/img-grid-2.jpg" alt="Untree.co"></div>
                    <div class="grid grid-3"><img src="../public/images/img-grid-3.jpg" alt="Untree.co"></div>
                </div>
            </div>
            <div class="col-lg-5 ps-lg-5">
                <h2 class="section-title mb-4">We Help You Make Modern Interior Design</h2>
                <p>Donec facilisis quam ut purus rutrum lobortis. Donec vitae odio quis nisl dapibus malesuada. Nullam
                    ac aliquet velit. Aliquam vulputate velit imperdiet dolor tempor tristique. Pellentesque habitant
                    morbi tristique senectus et netus et malesuada</p>

                <ul class="list-unstyled custom-list my-4">
                    <li>Donec vitae odio quis nisl dapibus malesuada</li>
                    <li>Donec vitae odio quis nisl dapibus malesuada</li>
                    <li>Donec vitae odio quis nisl dapibus malesuada</li>
                    <li>Donec vitae odio quis nisl dapibus malesuada</li>
                </ul>
                <p><a herf="#" class="btn">Explore</a></p>
            </div>
        </div>
    </div>
</div>
<!-- End We Help Section -->

<!-- Start Popular Product -->

<!-- Start Popular Product -->
<div class="popular-product">
    <div class="container">
        <div class="row">

            <?php
            try {
                // Fetch products from the database
                $sql = "SELECT * FROM products LIMIT 3";
                $stmt = $db->query($sql);
                $products = $stmt->fetchAll();

                foreach ($products as $product) {
                    $name = $product['name'];
                    $description = $product['description'];
                    $image_url = $product['image'];
            ?>

                    <div class="col-12 col-md-6 col-lg-4 mb-4 mb-lg-0">
                        <div class="product-item-sm d-flex">
                            <div class="thumbnail">
                                <img src="../public/images/<?php echo $image_url; ?>" alt="Image" class="img-fluid">
                            </div>
                            <div class="pt-3">
                                <h3><?php echo $name; ?></h3>
                                <p><?php echo $description; ?></p>
                                <p><a href="#">Read More</a></p>
                            </div>
                        </div>
                    </div>

            <?php
                }
            } catch (PDOException $e) {
                // Handle database errors
                echo "Error: " . $e->getMessage();
            }
            ?>

        </div>
    </div>
</div>
<!-- End Popular Product -->




<!-- End Popular Product -->

<!-- Start Testimonial Slider -->
<?php
include '../include/Testimonial.php';
?>
<!-- End Testimonial Slider -->




<!-- Start Blog Section -->
<div class="blog-section">
    <div class="container"> 67U
        <div class="row mb-5">
            <div class="col-md-6">
                <h2 class="section-title">Recent Blog</h2>
            </div>
            <div class="col-md-6 text-start text-md-end">
                <a href="blog.php" class="more">View All Posts</a>
            </div>
        </div>

        <div class="row">

            <?php
            try {
                // Fetch recent blog posts from the database
                $sql = "SELECT * FROM blog_posts ORDER BY post_date DESC LIMIT 3";
                $stmt = $db->query($sql);
                $posts = $stmt->fetchAll();

                foreach ($posts as $post) {
                    $title = $post['title'];
                    $author = $post['author'];
                    $post_date = $post['post_date'];
                    $image_url = $post['image_url'];
            ?>

                    <div class="col-12 col-sm-6 col-md-4 mb-4 mb-md-0">
                        <div class="post-entry">
                            <a href="#" class="post-thumbnail"><img src="../Public/images/<?php echo $image_url; ?>" alt="Image"
                                    class="img-fluid"></a>
                            <div class="post-content-entry">
                                <h3><a href="#"><?php echo $title; ?></a></h3>
                                <div class="meta">
                                    <span>by <a href="#"><?php echo $author; ?></a></span> <span>on <a
                                            href="#"><?php echo $post_date; ?></a></span>
                                </div>
                            </div>
                        </div>
                    </div>

            <?php
                }
            } catch (PDOException $e) {
                // Handle database errors
                echo "Error: " . $e->getMessage();
            }
            ?>

        </div>
    </div>
</div>
<!-- End Blog Section -->

<!--   ////////.........start Footer tob bar................//////-->
<?php include '../include/Footer.php'; ?>
<!--   ////////.........end Footer tob bar................//////-->

<script src="jquery/dist/jquery.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
<script src="js/tiny-slider.js"></script>
<script src="js/custom.js"></script>


</body>

</html>