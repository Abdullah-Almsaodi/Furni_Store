<!--   ////////.........start Footer tob bar................//////-->
<?php include '../include/Header.php'; ?>
<?php include 'db_connect.php'; ?>
<!--   ////////.........end Footer tob bar................//////-->
<!--   ////////.........end Footer tob bar................//////-->

<!-- Start Hero Section -->
<div class="hero">
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-lg-5">
                <div class="intro-excerpt">
                    <h1>About Us</h1>
                    <p class="mb-4">Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam
                        vulputate velit imperdiet dolor tempor tristique.</p>
                    <p><a href="" class="btn btn-secondary me-2">products Now</a><a href="#"
                            class="btn btn-white-outline">Explore</a></p>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="hero-img-wrap">
                    <img src="../public/images/couch.png" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Hero Section -->



<!-- Start Why Choose Us Section -->
<div class="why-choose-section">
    <div class="container">
        <div class="row justify-content-between align-items-center">
            <div class="col-lg-6">
                <h2 class="section-title">Why Choose Us</h2>
                <p>Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate velit
                    imperdiet dolor tempor tristique.</p>

                <div class="row my-5">
                    <div class="col-6 col-md-6">
                        <div class="feature">
                            <div class="icon">
                                <img src="../public/images/truck.svg" alt="Image" class="imf-fluid">
                            </div>
                            <h3>Fast &amp; Free Shipping</h3>
                            <p>Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate.
                            </p>
                        </div>
                    </div>

                    <div class="col-6 col-md-6">
                        <div class="feature">
                            <div class="icon">
                                <img src="../public/images/bag.svg" alt="Image" class="imf-fluid">
                            </div>
                            <h3>Easy to products</h3>
                            <p>Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate.
                            </p>
                        </div>
                    </div>

                    <div class="col-6 col-md-6">
                        <div class="feature">
                            <div class="icon">
                                <img src="../public/images/support.svg" alt="Image" class="imf-fluid">
                            </div>
                            <h3>24/7 Support</h3>
                            <p>Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate.
                            </p>
                        </div>
                    </div>

                    <div class="col-6 col-md-6">
                        <div class="feature">
                            <div class="icon">
                                <img src="../public/images/return.svg" alt="Image" class="imf-fluid">
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
                    <img src="../public/images/why-choose-us-img.jpg" alt="Image" class="img-fluid">
                </div>
            </div>

        </div>
    </div>
</div>
<!-- End Why Choose Us Section -->



<!-- Start Team Section -->
<div class="untree_co-section">
    <div class="container">

        <div class="row mb-5">
            <div class="col-lg-5 mx-auto text-center">
                <h2 class="section-title">Our Team</h2>
            </div>
        </div>

        <div class="row">

            <?php



            // Prepare and execute the SELECT query
            $stmt = $db->query("SELECT * FROM team");
            $teamMembers = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($teamMembers as $member) {
                $name = $member['name'];
                $position = $member['position'];
                $description = $member['description'];
                $image_url = $member['image'];
            ?>
                <!-- Start Column -->
                <div class="col-12 col-md-6 col-lg-3 mb-5 mb-md-0">
                    <img src="../public/images/<?php echo $image_url; ?>" class="img-fluid mb-5">
                    <h3 clas><a href="#"><span class=""><?php echo $name; ?></span></a></h3>
                    <span class="d-block position mb-4"><?php echo $position; ?></span>
                    <p><?php echo $description; ?></p>
                    <p class="mb-0"><a href="#" class="more dark">Learn More <span class="icon-arrow_forward"></span></a>
                    </p>
                </div>
                <!-- End Column -->
            <?php
            }

            ?>

        </div>
    </div>
</div>
<!-- End Team Section -->



<!-- Start Testimonial Slider -->
<?php
include '../include/Testimonial.php';
?>
<!-- End Testimonial Slider -->



<!--   ////////.........start Footer tob bar................//////-->
<?php include '../include/Footer.php'; ?>
<!--   ////////.........end Footer tob bar................//////-->


<script src="../public/js/bootstrap.bundle.min.js"></script>
<script src="../public/js/tiny-slider.js"></script>
<script src="../public/js/custom.js"></script>
</body>

</html>