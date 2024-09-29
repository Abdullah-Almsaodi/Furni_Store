<!--   ////////.........start Footer tob bar................//////-->
<?php
include '../include/Header.php';
require_once '../admin/pages/config.php';
require_once '../admin/classes/Database.php';


// Initialize Database
$connInstance = Database::getInstance();
$conn = $connInstance->getInstance()->getConnection();



?>

<!--   ////////.........end Footer tob bar................//////-->

<!-- Start Hero Section -->
<div class="hero">
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-lg-5">
                <div class="intro-excerpt">
                    <h1>Services</h1>
                    <p class="mb-4">Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam
                        vulputate velit imperdiet dolor tempor tristique.</p>
                    <p><a href="" class="btn btn-secondary me-2">products Now</a><a href="#"
                            class="btn btn-white-outline">Explore</a></p>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="hero-img-wrap">
                    <img src="images/couch.png" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Hero Section -->



<!-- Start Why Choose Us Section -->
<div class="why-choose-section">
    <div class="container">


        <div class="row my-5">
            <div class="col-6 col-md-6 col-lg-3 mb-4">
                <div class="feature">
                    <div class="icon">
                        <img src="images/truck.svg" alt="Image" class="imf-fluid">
                    </div>
                    <h3>Fast &amp; Free Shipping</h3>
                    <p>Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate.</p>
                </div>
            </div>

            <div class="col-6 col-md-6 col-lg-3 mb-4">
                <div class="feature">
                    <div class="icon">
                        <img src="images/bag.svg" alt="Image" class="imf-fluid">
                    </div>
                    <h3>Easy to products</h3>
                    <p>Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate.</p>
                </div>
            </div>

            <div class="col-6 col-md-6 col-lg-3 mb-4">
                <div class="feature">
                    <div class="icon">
                        <img src="images/support.svg" alt="Image" class="imf-fluid">
                    </div>
                    <h3>24/7 Support</h3>
                    <p>Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate.</p>
                </div>
            </div>

            <div class="col-6 col-md-6 col-lg-3 mb-4">
                <div class="feature">
                    <div class="icon">
                        <img src="images/return.svg" alt="Image" class="imf-fluid">
                    </div>
                    <h3>Hassle Free Returns</h3>
                    <p>Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate.</p>
                </div>
            </div>

            <div class="col-6 col-md-6 col-lg-3 mb-4">
                <div class="feature">
                    <div class="icon">
                        <img src="images/truck.svg" alt="Image" class="imf-fluid">
                    </div>
                    <h3>Fast &amp; Free Shipping</h3>
                    <p>Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate.</p>
                </div>
            </div>

            <div class="col-6 col-md-6 col-lg-3 mb-4">
                <div class="feature">
                    <div class="icon">
                        <img src="images/bag.svg" alt="Image" class="imf-fluid">
                    </div>
                    <h3>Easy to products</h3>
                    <p>Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate.</p>
                </div>
            </div>

            <div class="col-6 col-md-6 col-lg-3 mb-4">
                <div class="feature">
                    <div class="icon">
                        <img src="images/support.svg" alt="Image" class="imf-fluid">
                    </div>
                    <h3>24/7 Support</h3>
                    <p>Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate.</p>
                </div>
            </div>

            <div class="col-6 col-md-6 col-lg-3 mb-4">
                <div class="feature">
                    <div class="icon">
                        <img src="images/return.svg" alt="Image" class="imf-fluid">
                    </div>
                    <h3>Hassle Free Returns</h3>
                    <p>Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate.</p>
                </div>
            </div>

        </div>

    </div>
</div>
<!-- End Why Choose Us Section -->



<!-- Start Product Section -->
<div class="product-section pt-0">
    <div class="container">
        <div class="row" id="product-items">

            <!-- Start Column 1 -->
            <div class="col-md-12 col-lg-3 mb-5 mb-lg-0">
                <h2 class="mb-4 section-title">Crafted with excellent material.</h2>
                <p class="mb-4">Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate
                    velit imperdiet dolor tempor tristique. </p>
                <p><a href="#" class="btn">Explore</a></p>
            </div>
            <!-- End Column 1 -->


        </div>
    </div>
</div>
<!-- End Product Section -->

<!-- Start Testimonial Slider -->
<?php
include '../include/Testimonial.php';
?>
<!-- End Testimonial Slider -->


<!--   ////////.........start Footer tob bar................//////-->
<?php include '../include/Footer.php'; ?>
<!--   ////////.........end Footer tob bar................//////-->





<script>
$(document).ready(function() {
    // Fetch all product data using AJAX
    $.ajax({
        url: 'http://192.168.1.6/New-Furni/api/v1/product/product', // Replace with your actual API URL
        method: 'GET',
        success: function(data) {
            var productItemsContainer = $('#product-items'); // For Product Section


            // Add products to the Product Section (Limit to 3)
            data.slice(0, 3).forEach(function(product) {
                var productCard = `
                        <div class="col-12 col-md-4 col-lg-3 mb-5 mb-md-0">
                            <a class="product-item" href="cart.php">
                                <img src="../Public/images/${product.image}" class="img-fluid product-thumbnail">
                                <h3 class="product-title">${product.name}</h3>
                                <strong class="product-price">${product.price}</strong>
                                <span class="icon-cross">
                                    <img src="../Public/images/cross.svg" class="img-fluid">
                                </span>
                            </a>
                        </div>
                    `;
                productItemsContainer.append(
                    productCard); // Append each product to the Product Section
            });
        },
        error: function(error) {
            console.log("Error fetching the products", error);
        }
    });
});
</script>




</body>

</html>