<!-- Start Testimonial Slider -->
<div class="testimonial-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-7 mx-auto text-center">
                <h2 class="section-title">Testimonials</h2>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="testimonial-slider-wrap text-center">

                    <div id="testimonial-nav">
                        <span class="prev" data-controls="prev"><span class="fa fa-chevron-left"></span></span>
                        <span class="next" data-controls="next"><span class="fa fa-chevron-right"></span></span>
                    </div>

                    <div class="testimonial-slider">

                        <?php
                        try {
                            // Fetch testimonials from the database
                            $sql = "SELECT * FROM testimonials";
                            $stmt = $db->query($sql);
                            $testimonials = $stmt->fetchAll();

                            foreach ($testimonials as $testimonial) {
                                $quote = $testimonial['quote'];
                                $author = $testimonial['author'];
                                $position = $testimonial['position'];
                                $image_url = $testimonial['image_url'];
                        ?>

                                <div class="item">
                                    <div class="row justify-content-center">
                                        <div class="col-lg-8 mx-auto">

                                            <div class="testimonial-block text-center">
                                                <blockquote class="mb-5">
                                                    <p>&ldquo;<?php echo $quote; ?>&rdquo;</p>
                                                </blockquote>

                                                <div class="author-info">
                                                    <div class="author-pic">
                                                        <img src="images/<?php echo $image_url; ?>" alt="<?php echo $author; ?>" class="img-fluid">
                                                    </div>
                                                    <h3 class="font-weight-bold"><?php echo $author; ?></h3>
                                                    <span class="position d-block mb-3"><?php echo $position; ?></span>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <!-- END item -->

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
        </div>
    </div>
</div>
<!-- End Testimonial Slider -->