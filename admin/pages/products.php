﻿<?php
include('../templates/header.php');
require_once '../classes/Manager/StoreFacade.php';

$storeFacade = new StoreFacade();
// Initialize variables
$name = $price = $description = $cat_id = "";
$errors = [];
$successMessage = $successM = $err = '';

// Handle form submission
if (isset($_POST['submitProduct'])) {
    $name = $_POST['name'] ?? '';
    $price = $_POST['price'] ?? '';
    $description = $_POST['description'] ?? '';
    $cat_id = isset($_POST['cat_id']) ? (int)$_POST['cat_id'] : 0;
    $image = $_FILES['image'] ?? null;

    // Use ProductManager to handle validation and addition
    $result = $storeFacade->addProduct($name, $description, $price, $cat_id, $image);

    if ($result['success']) {
        $successMessage = "Product added successfully";
        // Reset form data after success
        $name = $price = $description = $cat_id = '';
    } else {
        $errors = $result['errors']; // Collect all errors returned from addProduct
    }

    // Display all errors if they exist
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<div class='error-message'>$error</div>"; // Adjust HTML as needed
        }
    }
}

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




                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <?php if ($successMessage): ?>

                                    <div class='alert alert-success'><?php echo $successMessage; ?></div>
                                <?php endif; ?>

                                <?php if (isset($errors['general'])): ?>
                                    <div class='alert alert-danger'><?php echo $errors['general']; ?>
                                    </div>
                                <?php endif; ?>


                                <?php
                                if (isset($_SESSION['message'])) : ?>
                                    <div class='alert alert-success'><?php echo $_SESSION['message'] ?></div>

                                <?php unset($_SESSION['message']);
                                endif; ?>
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
                                            class="form-control" cols="30"
                                            rows="3"><?php echo htmlspecialchars($description); ?></textarea>
                                        <i style="color: red;">
                                            <?php if (isset($errors['description'])) echo $errors['description']; ?>
                                        </i>
                                    </div>
                                    <div class="form-group">
                                        <label>Image</label>
                                        <input type="file" class="form-control" name="image">
                                        <i style="color: red;">
                                            <?php if (isset($errors['image'])) echo $errors['image']; ?>
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
                                        <button type="submit" name="submitProduct" class="btn btn-primary">Add
                                            Product</button>
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







                    <?php
                    if (isset($_GET['action'], $_GET['id'])) {
                        $product_id = $_GET['id'];
                        switch ($_GET['action']) {
                            case "delete":

                                $result = $storeFacade->deleteProduct($product_id);
                                if ($result['success']) {
                                    // $successM = "User deleted successfully";
                                    $_SESSION['message'] = "Product deleted successfully";
                                    echo " <script> window.open('products.php','_self');
                                    </script> ";
                                } else {
                                    $errors = $result['errors'];
                                    // $errors = $delete['errors'];
                                }
                                break;

                            default:
                                $errors = $delete['errors'];
                                break;
                        }
                    }

                    ?>
                    <div class="panel-body">




                        <?php if ($successM): ?>

                            <div class='alert alert-success'><?php echo $successM; ?></div>
                        <?php endif; ?>

                        <?php if (isset($errors['general'])): ?>
                            <div class='alert alert-danger'><?php echo $errors['general']; ?>
                            </div>
                        <?php endif; ?>


                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>ID</th>
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
                                    $products = $storeFacade->listProducts(); // Fetch products
                                    if (!empty($products)):
                                        foreach ($products as $product):
                                            $product_id = htmlspecialchars($product['product_id']);
                                    ?>
                                            <tr class="odd gradeX">
                                                <td><?php echo htmlspecialchars($product['product_id']); ?></td>
                                                <td><?php echo htmlspecialchars($product['name']); ?></td>
                                                <td><?php echo htmlspecialchars($product['description']); ?></td>
                                                <td><img src="../upload/<?php echo htmlspecialchars($product['image']); ?>"
                                                        width="100px" class="img-fluid"></td>
                                                <td><?php echo htmlspecialchars($product['price']); ?></td>
                                                <td><?php echo htmlspecialchars($product['category']); ?></td>

                                                <td>
                                                    <a href="editproducts.php?action=edit&id=<?php echo $product_id; ?>"
                                                        class='btn btn-success'>Edit</a>
                                                    <a href="?action=delete&id=<?php echo $product_id; ?>"
                                                        class='delete btn btn-danger'>Delete</a>
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