<?php
include('../templates/header.php');
require_once '../classes/Manager/StoreFacade.php';
$storeFacade = new StoreFacade();

// Initialize variables
$id = $_GET['id']; // تأكد من أن لديك الـ ID من الاستعلام
$name = $price = $description = $cat_id = $image = "";
$errors = [];
$successMessage = '';

// Fetch product details
$product = $storeFacade->findProduct($id);
if (!$product) {
    die("Product not found");
}

// Handle form submission
if (isset($_POST['submitProduct'])) {
    $name = $_POST['name'] ?? '';
    $price = $_POST['price'] ?? '';
    $description = $_POST['description'] ?? '';
    $cat_id = isset($_POST['cat_id']) ? (int)$_POST['cat_id'] : 0;

    // Check if image is uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $image = $_FILES['image']['name'];
        $target_path = "../upload/" . basename($image);

        // Move the uploaded file to the specified directory
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
            // File moved successfully
        } else {
            $errors['image'] = "Failed to upload image.";
        }
    } else {
        // Use existing image if new image is not uploaded
        $image = $product['image'];
    }

    // Call ProductManager to update the product
    $result = $storeFacade->editProduct($id, $name, $description, $price, $cat_id, $image);

    if ($result['success']) {
        $_SESSION['message'] = "Product updated successfully";
        header('Location: products.php');
        exit();
    } else {
        $errors = $result['errors'];
    }
}

// Display all errors if they exist
if (!empty($errors)) {
    foreach ($errors as $error) {
        echo "<div class='error-message'>$error</div>"; // Adjust HTML as needed
    }
}
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
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">

                                <?php
                                $id = $_GET['id'] ?? null;
                                if ($id) {
                                    $product = $storeFacade->findProduct($id);
                                    if ($product) {
                                        $name = $product['name'];
                                        $price = $product['price'];
                                        $image = $product['image'];
                                        $description = $product['description'];
                                        $categoryId = $product['category_id'];
                                    } else {
                                        // Handle case where product is not found
                                        $errors[] = "Product not found.";
                                    }
                                } else {
                                    // Handle case where no product ID is provided
                                    $errors[] = "No product ID provided.";
                                }
                                ?>


                                <form role="form" method="post" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" name="name" value="<?php echo $name ?>"
                                            placeholder="Please Enter your Name " class="form-control" />
                                        <i style="color: red;">
                                            <?php if (isset($errors['name'])) echo  $errors['name']  ?>
                                        </i>
                                    </div>
                                    <div class="form-group">
                                        <label>price</label>
                                        <input type="text" name="price" value="<?php echo $price ?>"
                                            placeholder="Please Enter your Name " class="form-control" />
                                        <i style="color: red;">
                                            <?php if (isset($errors['price'])) echo  $errors['price']  ?>
                                        </i>
                                    </div>
                                    <div class="form-group">
                                        <label>description</label>
                                        <textarea placeholder="Please Enter Description"
                                            value="<?php echo $description ?>" name="description" class="form-control"
                                            cols="30" rows="3"><?php echo $description ?></textarea>
                                        <!-- <?php if (isset($errors['description'])) echo  $errors['description']  ?> -->

                                    </div>
                                    <div class="form-group">
                                        <label>images</label>
                                        <input type="file" class="form-control" name="image">
                                        <i style="color: red;">
                                            <?php if (isset($errors['image'])) echo  $errors['image']  ?>
                                        </i>
                                    </div>



                                    <div class="form-group">
                                        <label>Product Type</label>
                                        <select class="form-control" name="cat_id">
                                            <?php
                                            // Fetch categories
                                            $categories = $storeFacade->listCategories();

                                            if (is_array($categories)) {
                                                foreach ($categories as $category) {
                                                    $categoryId = isset($category['category_id']) ? htmlspecialchars($category['category_id']) : '';
                                                    $categoryName = isset($category['name']) ? htmlspecialchars($category['name']) : 'Unknown Category';
                                            ?>
                                                    <option value="<?php echo $categoryId; ?>"
                                                        <?php if ($cat_id == $categoryId) echo 'selected'; ?>>
                                                        <?php echo $categoryName; ?>
                                                    </option>
                                            <?php
                                                }
                                            } else {
                                                echo '<option value="">No categories available</option>';
                                            }
                                            ?>
                                        </select>

                                        <i style="color: red;">
                                            <?php if (isset($errors['cat_id'])) echo $errors['cat_id']; ?>
                                        </i>
                                    </div>


                                    <div style="float:right;">
                                        <button type="submit" name="submitProduct" class="btn btn-primary">Update
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


        <!-- /. PAGE INNER  -->
    </div>
    <!-- /. PAGE WRAPPER  -->
</div>
</div>

<?php
include('../templates/footer.php');
?>

<script>
    $(document).ready(function() {
        $('.delete').click(function() {
            return confirm('Are You Sure !!');
        });
    });
</script>