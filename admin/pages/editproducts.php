<?php
include('upload/header.php');
require('db_connect.php');
// if (isset($_GET['action'], $_GET['id']) && $_GET['action'] == 'edit') {
//     // $id = $_GET['id'];
// }

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
                    $name = $price = $description = "";

                    $product_id = $_GET['id'];

                    // Retrieve the user's information from the database
                    $query = "SELECT * FROM products WHERE product_id = :product_id";
                    $stmt = $db->prepare($query);
                    $stmt->bindParam(':product_id', $product_id);
                    $stmt->execute();

                    if ($stmt->rowCount() > 0) {
                        $user = $stmt->fetch(PDO::FETCH_ASSOC);

                        $name = $user['name'];
                        $price = $user['price'];
                        $description = $user['description'];
                        $errors = array();
                    } else {
                        // Handle the case if the user's information is not found
                        echo "<div class='alert alert-danger'> Products   not Found </div>";
                        exit;
                    }







                    if (isset($_POST['submitProduct'])) {


                        // echo "<div class='alert alert-success'>Row Update</div>";





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
                            $targetDirectory = "../uploads/";
                            $targetFile = $targetDirectory . basename($_FILES['image']['name']);
                            move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);

                            //Updata the user data into the "users" table


                            $query = "UPDATE products SET name = :name,  price = :price, description = :description, image = :image WHERE product_id = :product_id";
                            $stmt = $db->prepare($query);
                            $stmt->execute(array(
                                ':name' => $name,
                                ':price' => $price,
                                ':description' => $description,
                                ':image' => $_FILES['image']['name'],
                                ':product_id' => $product_id
                            ));

                            // Check if the insertion was successful
                            if ($stmt->rowCount() > 0) {
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
                                        <input type="text" name="name" value="<?php echo $name ?>"
                                            placeholder="Please Enter your Name " class="form-control" />
                                        <i style="color: red;">
                                            <?php if (isset($errors['nameE'])) echo  $errors['nameE']  ?>
                                        </i>
                                    </div>
                                    <div class="form-group">
                                        <label>price</label>
                                        <input type="text" name="price" value="<?php echo $price ?>"
                                            placeholder="Please Enter your Name " class="form-control" />
                                        <i style="color: red;">
                                            <?php if (isset($errors['priceE'])) echo  $errors['priceE']  ?>
                                        </i>
                                    </div>
                                    <div class="form-group">
                                        <label>description</label>
                                        <textarea placeholder="Please Enter Description"
                                            value="<?php echo $description ?>" name="description" class="form-control"
                                            cols="30" rows="3"><?php echo $description ?></textarea>
                                        <!-- <?php if (isset($errors['descriptionE'])) echo  $errors['descriptionE']  ?> -->

                                    </div>
                                    <div class="form-group">
                                        <label>images</label>
                                        <input type="file" class="form-control" name="image">
                                        <i style="color: red;">
                                            <?php if (isset($errors['imageE'])) echo  $errors['imageE']  ?>
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
include('upload/footer.php');
?>

<script>
$(document).ready(function() {
    $('.delete').click(function() {
        return confirm('Are You Sure !!');
    });
});
</script>