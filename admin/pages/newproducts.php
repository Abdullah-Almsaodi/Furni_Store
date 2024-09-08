<?php
include('upload/header.php');
require_once 'config.php'; // Database configuration
require_once 'classes/ProductManager.php';
require_once 'classes/Database.php';

$db = new Database();

$productManager = new ProductManager($db);

$products = $productManager->getProducts();



?>
<!-- /. NAV SIDE  -->
<div id="page-wrapper">
    <div id="page-inner">
        <div class="row">
            <div class="col-md-12">
                <h2><i class="fa fa-items"></i>Products</h2>
            </div>
        </div>
        <!-- /. ROW  -->
        <hr />
        <div class="row">
            <div class="col-md-8">
                <!-- Form Elements -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-plus-circle"></i> Add New Porduct
                    </div>
                    <?php


                    $name = $price = $description = $cat_id = $image = "";
                    $errors = [];
                    $successMessage = '';
                    $successM = '';
                    $err = '';


                    if (isset($_POST['submitProduct'])) {


                        $name = $_POST['name'] ?? '';
                        $price = $_POST['price'] ?? '';
                        $description = $_POST['description'] ?? '';
                        $cat_id = $_POST['cat_id'] ?? '';

                        $errors = $productManager->validateProductdata($name, $price, $description, $cat_id);
                        // Validate image
                        if (!empty($_FILES['image']) && !empty($_FILES['image']['name'])) {
                            $imageFileType = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                            $allowedExtensions = array("jpg", "jpeg", "png", "gif");

                            // Check if the uploaded file is a valid image file
                            if (!in_array($imageFileType, $allowedExtensions)) {
                                $errors['imageE'] = "Invalid image format. Only JPG, JPEG, PNG, and GIF files are allowed.";
                            } elseif ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
                                // Check for upload errors
                                $uploadErrors = array(
                                    UPLOAD_ERR_INI_SIZE => 'The uploaded file exceeds the upload_max_filesize directive in php.ini.',
                                    UPLOAD_ERR_FORM_SIZE => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.',
                                    UPLOAD_ERR_PARTIAL => 'The uploaded file was only partially uploaded.',
                                    UPLOAD_ERR_NO_FILE => 'No file was uploaded.',
                                    UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder.',
                                    UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
                                    UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload.',
                                );

                                $errorCode = $_FILES['image']['error'];
                                if (isset($uploadErrors[$errorCode])) {
                                    $errors['imageE'] = $uploadErrors[$errorCode];
                                } else {
                                    $errors['imageE'] = 'Unknown error occurred during file upload.';
                                }
                            }
                        } else {
                            $errors['imageE'] = "Image is required";
                        }


                        if (empty($errors)) {



                            // Upload the image file to the server
                            $targetDirectory = "../uploads";
                            $targetFile = $targetDirectory . basename($_FILES['image']['name']);
                            move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);
                            $image_name = $_FILES['image']['name'];



                            // add prodcuts
                            $result = $productManager->addProduct($name, $description, $price, $cat_id, $image_name);

                            if ($result['success']) {
                                $successMessage = "Product added successfully";
                            } else {
                                $errors = $result['errors'];
                            }


                            // if ($stm->rowCount()) {
                            //     // Check if the insertion was successful
                            //     if ($stm->rowCount() > 0) {
                            //         // Redirect the user to the login page with a success message
                            //         // Set a session variable to indicate successful user update

                            //         $_SESSION['message'] = "<div class='alert alert-success'>One Row   Updated </div>";
                            //         echo "<script>

                            //                             window.open('products.php','_self');
                            //                             </script> ";
                            //     } else {
                            //         echo "<div class='alert alert-danger'>One Row  not Updated </div>";
                            //     }
                            // }



                        }
                    }





                    function test_input($data)
                    {
                        $data = trim($data);
                        $data = stripslashes($data);
                        $data = htmlspecialchars($data);
                        return $data;
                    }

                    ?>


                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <form role="form" method="post" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" name="name" placeholder="Please Enter your Name "
                                            class="form-control" />
                                        <i style="color: red;">
                                            <?php if (isset($errors['name'])) echo  $errors['name']  ?>
                                        </i>
                                    </div>
                                    <div class="form-group">
                                        <label>price</label>
                                        <input type="text" name="price" placeholder="Please Enter your Name "
                                            class="form-control" />
                                        <i style="color: red;">
                                            <?php if (isset($errors['price'])) echo  $errors['price']  ?>
                                        </i>
                                    </div>
                                    <div class="form-group">
                                        <label>description</label>
                                        <textarea placeholder="Please Enter Description" name="description"
                                            class="form-control" cols="30" rows="3"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>images</label>
                                        <input type="file" class="form-control" name="file">
                                        <i style="color: red;">
                                            <?php if (isset($errors['image_error'])) echo  $errors['image_error']  ?>
                                        </i>
                                    </div>

                                    <div class="form-group">
                                        <label>product Type</label>
                                        <select class="form-control" name="cat_id">
                                            <?php

                                            $pro = $productManager->getProductById($product_id);

                                            foreach ($pro as $catRow) {
                                            ?>
                                                <option value=<?php echo $catRow['cat_id'] ?>><?php echo  $row['name'] ?>
                                                </option>
                                            <?php
                                            } ?>
                                        </select>
                                    </div>
                                    <div style="float:right;">
                                        <button type="submit" name="submitProduct" class="btn btn-primary">Add
                                            Product</button>
                                        <button type="reset" class="btn btn-danger">Cancel</button>
                                    </div>

                            </div>
                            </form>

                        </div>

                    </div>
                </div>
            </div>

        </div>
        <hr />

        <div class="row">
            <div class="col-md-12">
                <!-- Advanced Tables -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-task"></i> Products
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Image</th>
                                        <th>Price</th>
                                        <th>Type</th>
                                        <th>action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($products)): ?>

                                        <?php foreach ($products as $product):
                                            $product_id = $product['product_id']; ?>

                                            <tr class="odd gradeX">
                                                <td><?php echo htmlspecialchars($product['name']); ?></td>
                                                <td><?php echo  htmlspecialchars($product['description']); ?></td>
                                                <!--  -->
                                                <td><img src="../images/<?php echo $product['image'] ?>" width="100px"
                                                        class="img-fluid"></td>
                                                <td><?php echo   $product['price']; ?></td>
                                                <td><?php

                                                    $pro = $productManager->getProductById($product_id);

                                                    foreach ($pro as $catRow) {
                                                        echo $catRow['name'];
                                                    }
                                                    ?>
                                                </td>

                                                <td>
                                                    <a href="editproducts.php?action=edit&id=<?php echo $product['product_id']; ?>"
                                                        class='btn btn-success action'>Edit</a>
                                                    <a href="?action=delete&id=<?php echo $user['product_id']; ?>"
                                                        class='delete btn btn-danger '>Delete</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>

                                    <?php endif; ?>


                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
                <!--End Advanced Tables -->

            </div>
            <!-- /. ROW  -->
        </div>
        <!-- /. PAGE INNER  -->
    </div>
    <!-- /. PAGE WRAPPER  -->
</div>
</div>

<!-- /. WRAPPER  -->
<!-- SCRIPTS -AT THE BOTOM TO REDUCE THE LOAD TIME-->
<!-- JQUERY SCRIPTS -->
<script src="assets/js/jquery-1.10.2.js"></script>
<!-- BOOTSTRAP SCRIPTS -->
<script src="assets/js/bootstrap.min.js"></script>
<!-- METISMENU SCRIPTS -->
<script src="assets/js/jquery.metisMenu.js"></script>
<!-- MORRIS CHART SCRIPTS -->
<script src="assets/js/morris/raphael-2.1.0.min.js"></script>
<script src="assets/js/morris/morris.js"></script>
<!-- CUSTOM SCRIPTS -->
<script src="assets/js/custom.js"></script>


</body>

</html>