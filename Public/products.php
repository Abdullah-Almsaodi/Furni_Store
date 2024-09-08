<!--   ////////.........end Footer tob bar................//////-->
<?php
include 'include/Header.php';
include 'db_connect.php';

try {
    // Retrieve data from the "product" table
    $sql = "SELECT * FROM Products";
    $stmt = $db->query($sql);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
}
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
            <?php

            try {
                // Fetch recent blog posts from the database
                $sql = "SELECT  * FROM products order by sales DESC LIMIT 8";
                $stmt = $db->query($sql);
                $products = $stmt->fetchAll();
                // Loop through the retrieved data and display products
                foreach ($products as $row) {
                    $productName = $row['name'];
                    $productImage = $row['image'];
                    $productPrice = $row['price'];
            ?>
                    <div class="col-12 col-md-4 col-lg-3 mb-5">
                        <a class="product-item" href="#">
                            <img src="images/<?php echo $productImage; ?>" class="img-fluid product-thumbnail">
                            <h3 class="product-title"><?php echo $productName; ?></h3>
                            <strong class="product-price"><?php echo $productPrice; ?></strong>
                            <span class="icon-cross">
                                <img src="images/cross.svg" class="img-fluid">
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
        </div>
    </div>
</div>

<!--   ////////.........start Footer tob bar................//////-->
<?php include 'include\Footer.php'; ?>
<!--   ////////.........end Footer tob bar................//////-->


<script src="js/bootstrap.bundle.min.js"></script>
<script src="js/tiny-slider.js"></script>
<script src="js/custom.js"></script>
</body>