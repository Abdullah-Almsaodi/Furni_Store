<?php

include('../templates/header.php');
require_once 'config.php'; // Database configuration
require_once '../classes/Database.php';
require_once '../classes/Manager/CategoryManager.php';
require_once '../classes/Repository/CategoryRepository.php';


// Initialize Database
$dbInstance = Database::getInstance();
$conn = $dbInstance->getInstance()->getConnection();

// Initialize repositories with dependencies
$categortyRepository = new CategoryRepository($conn);
$categoryManager = new CategoryManager($categortyRepository);


?>
<!-- /. NAV SIDE  -->
<div id="page-wrapper">
    <div id="page-inner">
        <div class="row">
            <div class="col-md-12">
                <h2><i class="fa fa-tasks"></i> Categories</h2>

            </div>
        </div>
        <!-- /. ROW  -->
        <hr />
        <div class="row">
            <div class="col-md-8">
                <!-- Form Elements -->

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-plus-circle"></i> Edit Category
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <?php

                            $id = $name = $description = $successMessage  = "";
                            $errors = [];;


                            if (isset($_GET['action'], $_GET['id']) && $_GET['action'] == 'edit') {
                                $id = $_GET['id'];
                                $category = $categoryManager->getCategoryById($id);
                            }






                            // define variables and set to empty values

                            if (isset($_POST['submit'])) {

                                if (isset($_GET['action'], $_GET['id']) && $_GET['action'] == 'edit') {


                                    $name = $_POST['name'] ?? '';
                                    $description = $_POST['description'] ?? '';




                                    $result = $categoryManager->editCategory($id, $name, $description);

                                    if ($result['success']) {
                                        $_SESSION['message'] = "categories  Update successfully";
                                        header('Location: categories.php');
                                    } else {
                                        $errors = $result['errors'];
                                    }
                                }
                            }



                            ?>
                            <div class="col-md-12">


                                <?php if ($successMessage): ?>

                                    <div class='alert alert-success'><?php echo $successMessage; ?></div>
                                <?php endif; ?>

                                <?php if (isset($errors['general'])): ?>
                                    <div class='alert alert-danger'><?php echo $errors['general']; ?>
                                    </div>
                                <?php endif; ?>

                                <form role="form" method="post">
                                    <input type="hidden" name="id" value="<?php echo $id ?>">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" placeholder="Please Enter your Name " class="form-control"
                                            value="<?php echo $category['name']; ?>" name="name" />
                                        <span style="color:red">
                                            <?php if (isset($errors['cname'])): echo $errors['cname'];
                                            endif; ?>
                                        </span>
                                    </div>
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea placeholder="Please Enter Description" class="form-control" rows="3"
                                            cols="30"
                                            name='description'> <?php echo $category['description'];  ?> </textarea>
                                        <span style="color:red">
                                            <?php if (isset($errors['cdescription'])): echo $errors['cdescription'];
                                            endif; ?>
                                        </span>
                                    </div>

                                    <div style="float:right;">
                                        <button type="submit" name="submit" class="btn btn-primary">Edit
                                            Category</button>
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