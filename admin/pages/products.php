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
?>

<!-- /. NAV SIDE  -->
<div id="page-wrapper">
    <div id="page-inner">
        <div class="row">
            <div class="col-md-12">
                <h2><i class="fa fa-items"></i> Products</h2>
            </div>
        </div>
        <!-- /. ROW  -->
        <hr />
        <div class="row">
            <div class="col-md-8">
                <!-- Form Elements -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-plus-circle"></i> Add New Product
                    </div>
                    <?php
                    $name = $price = $description = $cat_id = $image = "";
                    $errors = [];
                    $successMessage = '';

                    if (isset($_POST['submitProduct'])) {
                        $name = $_POST['name'] ?? '';
                        $price = $_POST['price'] ?? '';
                        $description = $_POST['description'] ?? '';
                        $cat_id = isset($_POST['cat_id']) ? (int)$_POST['cat_id'] : 0; // Ensure cat_id is an integer

                        // Validate and upload image
                        if (!empty($_FILES['image']) && !empty($_FILES['image']['name'])) {
                            $imageFileType = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                            $allowedExtensions = ["jpg", "jpeg", "png", "gif"];

                            if (!in_array($imageFileType, $allowedExtensions)) {
                                $errors['imageE'] = "Invalid image format. Only JPG, JPEG, PNG, and GIF files are allowed.";
                            } elseif ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
                                $uploadErrors = [
                                    UPLOAD_ERR_INI_SIZE => 'The uploaded file exceeds the upload_max_filesize directive in php.ini.',
                                    UPLOAD_ERR_FORM_SIZE => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.',
                                    UPLOAD_ERR_PARTIAL => 'The uploaded file was only partially uploaded.',
                                    UPLOAD_ERR_NO_FILE => 'No file was uploaded.',
                                    UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder.',
                                    UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
                                    UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload.',
                                ];

                                $errorCode = $_FILES['image']['error'];
                                $errors['imageE'] = $uploadErrors[$errorCode] ?? 'Unknown error occurred during file upload.';
                            }
                        } else {
                            $errors['imageE'] = "Image is required";
                        }

                        if (empty($errors)) {
                            $targetDirectory = "../uploads/";
                            $targetFile = $targetDirectory . basename($_FILES['image']['name']);
                            move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);
                            $image_name = $_FILES['image']['name'];

                            // Add product
                            $result = $productManager->addProduct($name, $price, $description, $cat_id, $image_name);
                            if ($result['success']) {
                                $successMessage = "Product added successfully";
                            } else {
                                $errors = $result['errors'];
                            }
                        }
                    }
                    ?>

                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <form role="form" method="post" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" name="name" placeholder="Please Enter Product Name"
                                            class="form-control" value="<?php echo htmlspecialchars($name); ?>" />
                                        <i style="color: red;">
                                            <?php if (isset($errors['name'])) echo $errors['name']; ?>
                                        </i>
                                    </div>
                                    <div class="form-group">
                                        <label>Price</label>
                                        <input type="text" name="price" placeholder="Please Enter Price"
                                            class="form-control" value="<?php echo htmlspecialchars($price); ?>" />
                                        <i style="color: red;">
                                            <?php if (isset($errors['price'])) echo $errors['price']; ?>
                                        </i>
                                    </div>
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea placeholder="Please Enter Description" name="description"
                                            class="form-control" cols="30" rows="3"><?php echo htmlspecialchars($description); ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Image</label>
                                        <input type="file" class="form-control" name="image">
                                        <i style="color: red;">
                                            <?php if (isset($errors['imageE'])) echo $errors['imageE']; ?>
                                        </i>
                                    </div>
                                    <div class="form-group">
                                        <label>Product Type</label>
                                        <select class="form-control" name="cat_id">
    <?php
    // Fetch categories
    $categories = $categoryManager->getCategories();

    // Ensure $categories is an array
    if (is_array($categories)) {
        foreach ($categories as $category) {
            // Ensure each $category contains 'id' and 'name'
            $categoryId = isset($category['id']) ? htmlspecialchars($category['id']) : '';
            $categoryName = isset($category['name']) ? htmlspecialchars($category['name']) : 'Unknown Category';
    ?>
            <option value="<?php echo $categoryId; ?>">
                <?php echo $categoryName; ?>
            </option>
    <?php
        }
    } else {
        echo '<option value="">No categories available</option>';
    }
    ?>
</select>

                                    </div>
                                    <div style="float:right;">
                                        <button type="submit" name="submitProduct" class="btn btn-primary">Add Product</button>
                                        <button type="reset" class="btn btn-danger">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <?php if (!empty($successMessage)): ?>
                            <div class="alert alert-success"><?php echo $successMessage; ?></div>
                        <?php endif; ?>
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
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $products = $productManager->getProducts(); // Fetch products
                                    if (!empty($products)):
                                        foreach ($products as $product):
                                            $product_id = htmlspecialchars($product['product_id']);
                                    ?>
                                            <tr class="odd gradeX">
                                                <td><?php echo htmlspecialchars($product['name']); ?></td>
                                                <td><?php echo htmlspecialchars($product['description']); ?></td>
                                                <td><img src="../uploads/<?php echo htmlspecialchars($product['image']); ?>" width="100px" class="img-fluid"></td>
                                                <td><?php echo htmlspecialchars($product['price']); ?></td>
                                                <td><?php
                                                // Ensure cat_id is an integer and check if it exists in the product array
                                                $category_id = isset($product['cat_id']) ? (int)$product['cat_id'] : 0;

                                                // Fetch category only if category_id is valid
                                                if ($category_id > 0) {
                                                    $category = $categoryManager->getCategoryById($category_id);

                                                    // Check if the category was found and if 'name' exists
                                                    $category_name = isset($category['name']) ? htmlspecialchars($category['name']) : ' 2';
                                                } else {
                                                    $category_name = 'hh';
                                                }
                                            ?><?php echo $category_name; ?></td>
                                                <td>
                                                    <a href="editproducts.php?action=edit&id=<?php echo $product_id; ?>" class='btn btn-success'>Edit</a>
                                                    <a href="?action=delete&id=<?php echo $product_id; ?>" class='delete btn btn-danger'>Delete</a>
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
        </div>
        <!-- /. ROW  -->
    </div>
    <!-- /. PAGE INNER  -->
</div>
<!-- /. PAGE WRAPPER  -->

<?php include('../templates/footer.php'); ?>

<script>
    $(document).ready(function() {
        $('.delete').click(function() {
            return confirm('Are You Sure?');
        });
    });
</script>
