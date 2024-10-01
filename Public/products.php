<!--   ////////.........end Footer tob bar................//////-->
<?php
include '../include/Header.php';



?>

<!-- Start Hero Section -->
<!-- Start Hero Section -->
<div class="hero">
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-lg-5">
                <div class="intro-excerpt">
                    <h1> <span clsas="d-block"> products Store</span></h1>
                    <!-- <p class="mb-4">Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam
                        vulputate velit imperdiet dolor tempor tristique.</p> -->
                    <p><a href="" class="btn btn-secondary me-2"> Shop Now</a></p>
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
<!-- Hero content -->
</div>
<!-- End Hero Section -->

<div class="untree_co-section product-section before-Footer-section">
    <div class="container">
        <div class="row">
            <div class="row" id="product-items">
                <!-- Products will be dynamically inserted here via AJAX -->
            </div>
        </div>
    </div>
</div>

<!--   ////////.........start Footer tob bar................//////-->
<?php include '../include/Footer.php'; ?>
<!--   ////////.........end Footer tob bar................//////-->



<script src="jquery/dist/jquery.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
<script src="js/tiny-slider.js"></script>
<script src="js/custom.js"></script>


<script>
    const base_url = "<?php echo BASE_URL; ?>";

    $(document).ready(function() {
        // Fetch all product data using AJAX
        $.ajax({
            url: base_url + 'product/product', // Replace with your actual API URL
            method: 'GET',
            success: function(data) {

                var productItemsContainer = $('#product-items'); // For Product Section


                // Add products to the Product Section (Limit to 3)
                data.slice(0, 8).forEach(function(product) {
                    var productCard = `

                     <div class="col-12 col-md-4 col-lg-3 mb-5">
                        <a class="product-item" href="#">
                            <img src="images/${product.image}" class="img-fluid product-thumbnail">
                            <h3 class="product-title">${product.name}</h3>
                            <strong class="product-price">${product.price}</strong>
                            <span class="icon-cross">
                                <img src="images/cross.svg" class="img-fluid">
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