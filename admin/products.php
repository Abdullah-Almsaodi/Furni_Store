<?php
include('upload/header.php');
require('db_connect.php');
$id = "";
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

        <?php
        if (isset($_SESSION['message'])) {
            echo  $_SESSION['message'] . '</p>';
            unset($_SESSION['message']);
        } ?>
        <hr />
        <div class="row">
            <div class="col-md-8">
                <!-- Form Elements -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-plus-circle"></i> Add New Porduct
                    </div>
                    <?php

                    if (isset($_POST['submitProduct'])) {

                        if (empty($_POST["name"])) {
                            $errors['nameE'] = " Name is required";
                        } else {
                            $name = test_input($_POST["name"]);
                            // check if name only contains letters and whitespace
                            if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
                                $errors['nameE'] = "Only letters and white space allowed";
                            }
                        }



                        if (empty($_POST["price"])) {
                            $errors['priceE'] = "price Number is required";
                        } else {
                            $price = test_input($_POST["price"]);
                            // check if phone only contains numbers
                            if (!is_numeric($price)) {
                                $errors['priceE'] = "Only numeric values allowed";
                            }
                        }

                        if (empty($_POST["cat_id"])) {
                            $errors['cateE'] = " Role is required";
                        } else {
                            $cat_id = test_input($_POST["cat_id"]);
                        }


                        if (empty($_POST["description"])) {
                            $errors['descriptionE'] = "About me is required";
                        } else {
                            $description = test_input($_POST["description"]);
                            // check if about me only contains letters, whitespace, and numbers
                            if (!preg_match("/^[a-zA-Z0-9\s.,'-]*$/", $description)) {
                                $errors['descriptionE'] = "Only letters, numbers, whitespace, commas, apostrophes, and hyphens allowed in the about me field";
                            }
                        }



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


                            $sql = "INSERT INTO products(name,price,description,cat_id,image) VALUES (?,?,?,?,?) ";
                            $stm = $db->prepare($sql);
                            $stm->execute(array($name, $price, $description, $cat_id, $image_name));
                            if ($stm->rowCount()) {
                                // Check if the insertion was successful
                                if ($stm->rowCount() > 0) {
                                    // Redirect the user to the login page with a success message
                                    // Set a session variable to indicate successful user update

                                    $_SESSION['message'] = "<div class='alert alert-success'>One Row   Updated </div>";
                                    echo "<script>

                                                        window.open('products.php','_self');
                                                        </script> ";
                                } else {
                                    echo "<div class='alert alert-danger'>One Row  not Updated </div>";
                                }
                            }
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
                                        <input type="text" name="name" placeholder="Please Enter your Name " class="form-control" />
                                        <i style="color: red;">
                                            <?php if (isset($errors['nameE'])) echo  $errors['nameE']  ?>
                                        </i>
                                    </div>
                                    <div class="form-group">
                                        <label>price</label>
                                        <input type="text" name="price" placeholder="Please Enter your Name " class="form-control" />
                                        <i style="color: red;">
                                            <?php if (isset($errors['priceE'])) echo  $errors['priceE']  ?>
                                        </i>
                                    </div>
                                    <div class="form-group">
                                        <label>description</label>
                                        <textarea placeholder="Please Enter Description" name="description" class="form-control" cols="30" rows="3"></textarea>
                                        <!-- <?php if (isset($errors['descriptionE'])) echo  $errors['descriptionE']  ?> -->

                                    </div>
                                    <div class="form-group">
                                        <label>images</label>
                                        <input type="file" class="form-control" name="image">
                                        <i style="color: red;">
                                            <?php if (isset($errors['imageE'])) echo  $errors['imageE']  ?>
                                        </i>
                                    </div>

                                    <div class="form-group">
                                        <label>product Type</label>
                                        <select class="form-control" name="cat_id">
                                            <option value="" selected disabled>Select Role</option>

                                            <?php
                                            $sql = "select * from categories ";
                                            $stm = $db->prepare($sql);
                                            $stm->execute();
                                            foreach ($stm->fetchAll() as $row) {
                                            ?>
                                                <option value=<?php echo $row['cat_id'] ?>><?php echo  $row['name'] ?>
                                                </option>
                                            <?php
                                            } ?>
                                        </select>
                                        <i style="color: red;">
                                            <?php if (isset($errors['cateE'])) echo  $errors['cateE']  ?>
                                        </i>


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
                    <?php
                    if (isset($_GET['action'], $_GET['id'])) {
                        $id = $_GET['id'];


                        switch ($_GET['action']) {
                            case "delete":
                                $stmt = $db->prepare("delete from products where product_id=:product_id");
                                $stmt->execute(array("product_id" => $id));
                                if ($stmt->rowCount() == 1) {
                                    echo "<div class='alert alert-success'> One Row Deleted</div>";
                                }
                                break;


                            default:
                                echo "ERROR";
                                break;
                        }
                    }
                    ?>
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
                                    <?php
                                    $sql = "select * from products ";
                                    $stm = $db->prepare($sql);
                                    $stm->execute();


                                    if ($stm->rowCount()) {
                                        foreach ($stm->fetchAll() as $row) {
                                            $id = $row['product_id'];
                                    ?>
                                            <tr class="odd gradeX">
                                                <td><?php echo $row['name'];  ?></td>
                                                <td><?php echo   $row['description']; ?></td>
                                                <!--  -->
                                                <td><img src="../images/<?php echo $row['image'] ?>" width="100px" class="img-fluid"></td>
                                                <td><?php echo   $row['price']; ?></td>
                                                <td><?php
                                                    $sql = "select * from products where product_id=:product_id";
                                                    $stm = $db->prepare($sql);
                                                    $stm->execute(array("product_id" => $row['product_id']));
                                                    foreach ($stm->fetchAll() as $catRow) {
                                                        echo $catRow['name'];
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <a href="editproducts.php?action=edit&id=<?php echo $id ?>" class='btn btn-success'>Edit</a>
                                                    <a href="?action=delete&id=<?php echo $id ?>" class='delete btn btn-danger'>Delete</a>

                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    }  ?>

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

<?php
include('upload/footer.php');
?>

<script>
    $(document).ready(function() {
        $('.delete').click(function() {
            return confirm('Are You Sure !!');
        });
    });
</script>