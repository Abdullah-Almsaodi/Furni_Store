<?php
include('../templates/header.php');
require_once 'config.php'; // Database configuration
require_once '../classes/Database.php';
require_once '../classes/Manager/CategoryManager.php';
require_once '../classes/Repository/CategoryRepository.php';
require_once '../classes/Manager/ProductManager.php';
require_once '../classes/Repository/ProductRepository.php';

// Initialize Database
$dbInstance = Database::getInstance();
$conn = $dbInstance->getInstance()->getConnection();

// Initialize repositories with dependencies
$productRepository = new ProductRepository($conn);
$productManager = new ProductManager($productRepository);

$categoryRepository = new CategoryRepository($conn);
$categoryManager = new CategoryManager($categoryRepository);

if (isset($_GET['action'], $_GET['id']) && $_GET['action'] == 'edit') {
    $id = $_GET['id'];
    $product = $productManager->getProductById($id);
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

                    <?php


                    $name = $price = $description = $cat_id = "";
                    $errors = [];
                    $successMessage = '';

                    if (isset($_POST['submitProduct'])) {
                        $name = $_POST['name'] ?? '';
                        $price = $_POST['price'] ?? '';
                        $description = $_POST['description'] ?? '';
                        $cat_id = isset($_POST['cat_id']) ? (int)$_POST['cat_id'] : 0; // Ensure cat_id is an integer

                        // Validate product data
                        $errors = $productManager->validateProductData($name, $description, $price, $cat_id);
                        // Validate image
                        $imageErrors = $productManager->validateProductImage($_FILES['image']);

                        if (empty($errors)) {
                            // Image upload directory
                            $targetDirectory = "../upload/";
                            // Get the original file name
                            $originalFileName = basename($_FILES['image']['name']);
                            // Set the target file path
                            $targetFile = $targetDirectory . $originalFileName;

                            // Attempt to upload the image
                            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                                $successMessage = "Image uploaded successfully";

                                // Attempt to add the product
                                $result = $productManager->editProduct($id, $name, $description, $price, $cat_id, $originalFileName);
                                if ($result['success']) {
                                    $_SESSION['message'] = "Product Update successfully";
                                    header('Location: products.php');
                                } else {
                                    $errors['general'] = "Failed to add product. Errors: ";
                                    $errors = array_merge($errors, $result['errors']);
                                }
                            } else {
                                $errors['image'] = "Failed to upload image.";
                            }
                        } else {
                            $errors = array_merge($errors, $imageErrors);
                        }
                    }
                    ?>










                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">

                                <?php
                                $name = $product['name'];
                                $price = $product['price'];
                                $image = $product['image'];
                                $description = $product['description'];
                                $categoryId = $product['category_id'];
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
                                            $categories = $categoryManager->getCategories();

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